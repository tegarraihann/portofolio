<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | {{ config('app.name') }}</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #38bdf8;
            --accent-2: #a855f7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 25% 25%, rgba(56, 189, 248, 0.14), transparent 36%),
                        radial-gradient(circle at 80% 10%, rgba(168, 85, 247, 0.18), transparent 32%),
                        var(--bg);
            font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
        }
        .card {
            width: min(560px, 90vw);
            background: linear-gradient(145deg, rgba(17, 24, 39, 0.92), rgba(15, 23, 42, 0.92));
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 18px;
            padding: 36px;
            box-shadow: 0 20px 70px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
        }
        .card::after {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.15), rgba(168, 85, 247, 0.08));
            mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000);
            mask-composite: exclude;
            pointer-events: none;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.12);
            color: var(--text);
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .badge span:last-child {
            color: var(--muted);
            font-weight: 600;
        }
        h1 {
            font-size: clamp(36px, 6vw, 52px);
            margin: 16px 0 8px;
            letter-spacing: -0.03em;
        }
        p {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.6;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        a.button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: var(--text);
            transition: transform 160ms ease, background 160ms ease, border-color 160ms ease, color 160ms ease;
        }
        a.button.primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-color: rgba(255, 255, 255, 0.12);
            color: #0b1021;
        }
        a.button:hover {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.14);
        }
        .hint {
            margin-top: 18px;
            font-size: 13px;
            color: var(--muted);
        }
        @media (max-width: 540px) {
            .card { padding: 28px; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="card" aria-labelledby="title">
        <div class="badge">
            <span>404</span>
            <span>Halaman tidak ditemukan</span>
        </div>
        <h1 id="title">Ups, halaman ini tidak tersedia.</h1>
        <p>URL yang Anda buka tidak ditemukan atau menu belum aktif. Silakan kembali ke beranda atau jelajahi konten lain.</p>
        <div class="actions">
            <a class="button primary" href="{{ url('/') }}">Kembali ke Beranda</a>
            <a class="button" href="{{ route('articles.index') }}">Lihat Artikel</a>
        </div>
        <div class="hint">Jika ini seharusnya ada, coba muat ulang atau hubungi admin.</div>
    </main>
</body>
</html>
