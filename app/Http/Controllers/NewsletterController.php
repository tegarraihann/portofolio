<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'status' => 'active',
                'subscribed_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Terima kasih! Anda berhasil subscribe newsletter.',
        ]);
    }
}
