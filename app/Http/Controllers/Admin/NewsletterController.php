<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterDigest;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
            'articles' => Article::where('status', 'published')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
                ->map(function (Article $article) {
                    $excerptSource = $article->excerpt ?: $article->content;

                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'excerpt' => $excerptSource ? Str::limit(strip_tags($excerptSource), 120) : null,
                        'date' => $article->created_at?->format('d M Y'),
                        'thumbnail_url' => $article->thumbnail_url,
                    ];
                })
                ->values(),
            'projects' => Project::where('is_active', true)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
                ->map(function (Project $project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->title,
                        'excerpt' => $project->description
                            ? Str::limit(strip_tags($project->description), 120)
                            : null,
                        'date' => $project->created_at?->format('d M Y'),
                        'thumbnail_url' => $project->thumbnail_url,
                    ];
                })
                ->values(),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_type' => ['required', 'in:article,project'],
            'item_id' => ['required', 'integer'],
            'scope' => ['nullable', 'in:all,selected'],
            'subscriber_ids' => ['required_if:scope,selected', 'array'],
            'subscriber_ids.*' => ['integer', 'exists:newsletter_subscribers,id'],
        ]);

        $scope = $data['scope'] ?? 'all';
        $itemType = $data['item_type'];
        $itemId = $data['item_id'];
        $item = null;
        $subject = null;
        $typeLabel = null;

        if ($itemType === 'article') {
            $article = Article::where('status', 'published')->find($itemId);
            if (! $article) {
                return redirect()
                    ->route('admin.newsletter.index')
                    ->with('status', 'Artikel tidak ditemukan atau belum dipublikasikan.');
            }

            $excerptSource = $article->excerpt ?: $article->content;

            $item = [
                'title' => $article->title,
                'excerpt' => $excerptSource ? Str::limit(strip_tags($excerptSource), 160) : null,
                'url' => route('articles.show', $article->slug),
                'date' => $article->created_at?->format('d M Y'),
                'thumbnail_url' => $article->thumbnail_url,
            ];
            $subject = 'Artikel Terbaru: ' . Str::limit($article->title, 120);
            $typeLabel = 'Artikel';
        }

        if ($itemType === 'project') {
            $project = Project::where('is_active', true)->find($itemId);
            if (! $project) {
                return redirect()
                    ->route('admin.newsletter.index')
                    ->with('status', 'Project tidak ditemukan atau belum aktif.');
            }

            $url = $project->live_demo_url ?: $project->github_url ?: url('/#projects');

            $item = [
                'title' => $project->title,
                'excerpt' => $project->description
                    ? Str::limit(strip_tags($project->description), 160)
                    : null,
                'url' => $url,
                'date' => $project->created_at?->format('d M Y'),
                'thumbnail_url' => $project->thumbnail_url,
            ];
            $subject = 'Project Terbaru: ' . Str::limit($project->title, 120);
            $typeLabel = 'Project';
        }

        $sent = 0;

        $query = NewsletterSubscriber::where('status', 'active');

        if ($scope === 'selected') {
            $query->whereIn('id', $data['subscriber_ids']);
        }

        $query->select(['id', 'email'])
            ->chunkById(200, function ($subscribers) use (&$sent, $subject, $item, $typeLabel) {
                foreach ($subscribers as $subscriber) {
                    Mail::to($subscriber->email)->send(new NewsletterDigest(
                        $subject,
                        $item,
                        $typeLabel
                    ));
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
