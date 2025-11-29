<?php

namespace App\Http\Middleware;

use App\Models\Article;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrackArticleView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!Config::get('article_view.enabled', true)) {
            return $response;
        }

        // Jalankan hanya pada route show artikel dengan binding model
        $article = $request->route('articleModel');
        if (!$article instanceof Article) {
            return $response;
        }

        // Bot/healthcheck skip
        $userAgent = (string) $request->userAgent();
        if ($this->isBot($userAgent)) {
            return $response;
        }

        $ip = $request->ip();
        if (!$ip) {
            return $response;
        }

        $ipHash = $this->hashIp($ip);
        $cookieName = Config::get('article_view.cookie_name', 'visit_token');
        $sessionToken = $request->cookie($cookieName) ?: Str::uuid()->toString();
        $now = now();

        // Hindari duplikat per hari per artikel per sesi
        $alreadyLogged = DB::table('article_views')
            ->whereDate('viewed_at', $now->toDateString())
            ->where('article_id', $article->id)
            ->where('session_token', $sessionToken)
            ->exists();

        if (!$alreadyLogged) {
            DB::table('article_views')->insert([
                'article_id'     => $article->id,
                'ip_hash'        => $ipHash,
                'session_token'  => $sessionToken,
                'referrer'       => $request->headers->get('referer'),
                'user_agent'     => $userAgent,
                'viewed_at'      => $now,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // Set cookie sesi view
        Cookie::queue(
            Cookie::make(
                $cookieName,
                $sessionToken,
                Config::get('article_view.cookie_minutes', 60 * 24),
                '/',
                Config::get('session.domain'),
                Config::get('session.secure', false),
                false,
                false,
                Config::get('session.same_site', 'lax')
            )
        );

        return $response;
    }

    private function isBot(string $userAgent): bool
    {
        $ua = mb_strtolower($userAgent);
        $bots = [
            'bot', 'crawler', 'spider', 'crawl', 'slurp', 'bingpreview',
            'facebookexternalhit', 'linkedinbot', 'embedly', 'quora link preview',
            'showyoubot', 'outbrain', 'pinterest', 'postman', 'insomnia', 'curl', 'wget',
        ];

        foreach ($bots as $bot) {
            if (str_contains($ua, $bot)) {
                return true;
            }
        }

        return false;
    }

    private function hashIp(string $ip): string
    {
        $salt = Config::get('article_view.hash_salt', Config::get('app.key', ''));

        // Truncate IPv4 untuk privasi ringan
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';
            $ip = implode('.', $parts);
        }

        return hash('sha256', $ip . $salt);
    }
}
