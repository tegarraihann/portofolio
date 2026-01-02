<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterController extends Controller
{
    public function index(Request $request): Response
    {
        $query = NewsletterSubscriber::query()->latest();

        if ($search = $request->get('q')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $subscribers = $query->paginate(15)->through(function (NewsletterSubscriber $subscriber) {
            return [
                'id' => $subscriber->id,
                'email' => $subscriber->email,
                'status' => $subscriber->status,
                'subscribed_at' => $subscriber->subscribed_at?->toDateTimeString(),
                'created_at' => $subscriber->created_at?->toDateTimeString(),
            ];
        });

        return Inertia::render('Admin/Newsletter/Index', [
            'subscribers' => $subscribers,
            'filters' => [
                'q' => $request->get('q'),
                'status' => $request->get('status'),
            ],
            'stats' => [
                'total' => NewsletterSubscriber::count(),
                'active' => NewsletterSubscriber::where('status', 'active')->count(),
                'inactive' => NewsletterSubscriber::where('status', '!=', 'active')->count(),
            ],
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
        ]);

        $sent = 0;

        NewsletterSubscriber::where('status', 'active')
            ->select(['id', 'email'])
            ->chunkById(200, function ($subscribers) use (&$sent, $data) {
                foreach ($subscribers as $subscriber) {
                    Mail::raw($data['content'], function ($message) use ($subscriber, $data) {
                        $message->to($subscriber->email)
                            ->subject($data['subject']);
                    });
                    $sent++;
                }
            });

        $message = $sent > 0
            ? "Newsletter terkirim ke {$sent} subscriber."
            : 'Tidak ada subscriber aktif untuk dikirimi.';

        return redirect()
            ->route('admin.newsletter.index')
            ->with('status', $message);
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return redirect()
            ->route('admin.newsletter.index')
            ->with('status', 'Subscriber berhasil dihapus.');
    }
}
