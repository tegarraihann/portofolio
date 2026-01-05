<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'status' => 'active',
                'subscribed_at' => now(),
            ]
        );

        if ($subscriber->wasRecentlyCreated) {
            try {
                Mail::to($subscriber->email)->send(
                    new NewsletterWelcome(
                        'Selamat datang di newsletter',
                        url('/'),
                        'Lihat Portfolio'
                    )
                );
            } catch (\Throwable $e) {
                Log::warning('Newsletter welcome email failed.', [
                    'subscriber_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Terima kasih! Anda berhasil subscribe newsletter.',
        ]);
    }
}
