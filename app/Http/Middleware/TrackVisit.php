<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TrackVisit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!Config::get('visit.enabled', true)) {
            return $response;
        }

        // Pastikan tabel tersedia sebelum insert
        if (!Schema::hasTable('visits')) {
            return $response;
        }

        // Hanya track request GET ke halaman publik
        if (!$request->isMethod('get')) {
            return $response;
        }

        // Skip admin area, assets, health checks
        if ($request->is('admin*') || $request->is('build*') || $request->is('storage*') || $request->is('up')) {
            return $response;
        }

        $userAgent = (string) $request->userAgent();
        if ($this->isBot($userAgent)) {
            return $response;
        }

        $ip = $request->ip();
        if (!$ip) {
            return $response;
        }

        $ipHash = $this->hashIp($ip);
        $sessionToken = $request->cookie(Config::get('visit.cookie_name', 'visit_token')) ?: Str::uuid()->toString();
        $now = now();

        // Hindari duplikasi di hari yang sama untuk sesi & url yang sama
        $alreadyLogged = DB::table('visits')
            ->whereDate('visited_at', $now->toDateString())
            ->where('session_token', $sessionToken)
            ->where('url', $request->fullUrl())
            ->exists();

        if (!$alreadyLogged) {
            DB::table('visits')->insert([
                'url'            => $request->fullUrl(),
                'referrer'       => $request->headers->get('referer'),
                'user_agent'     => $userAgent,
                'ip_hash'        => $ipHash,
                'session_token'  => $sessionToken,
                'user_id'        => Auth::id(),
                'visited_at'     => $now,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // Set cookie sesi kunjungan
        Cookie::queue(
            Cookie::make(
                Config::get('visit.cookie_name', 'visit_token'),
                $sessionToken,
                Config::get('visit.cookie_lifetime_minutes', 60 * 24),
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
        $salt = Config::get('visit.hash_salt', Config::get('app.key', ''));

        // Truncate IPv4 untuk privasi ringan
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';
            $ip = implode('.', $parts);
        }

        return hash('sha256', $ip . $salt);
    }
}
