<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} Newsletter</title>
    </head>
    <body style="margin:0; padding:0; background:#f5f7fb; color:#0f172a; font-family:Arial, Helvetica, sans-serif;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7fb; padding:24px 12px;">
            <tr>
                <td align="center">
                    <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                        <tr>
                            <td style="padding:24px 24px 8px;">
                                <div style="font-size:20px; font-weight:700; color:#0f172a;">{{ config('app.name') }}</div>
                                <div style="font-size:13px; color:#64748b; margin-top:6px;">
                                    Selamat datang & Selamat Menikmati Informasi Terbaru dari Kami!
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 24px 16px;">
                                <div style="font-size:14px; color:#1f2937; line-height:1.7;">
                                    Terima kasih sudah berlangganan newsletter {{ config('app.name') }}.
                                    Mulai sekarang kamu akan menerima ringkasan artikel dan project terbaru langsung ke email ini.
                                    Jika kamu tidak merasa mendaftar, abaikan email ini.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 24px 24px;">
                                <a href="{{ $ctaUrl }}"
                                   style="display:inline-block; padding:10px 18px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:8px; font-size:14px; font-weight:600;">
                                    {{ $ctaLabel }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 24px 32px; color:#94a3b8; font-size:12px; line-height:1.5;">
                                Kamu menerima email ini karena berlangganan newsletter.
                                <div style="margin-top:8px;">
                                    <a href="{{ url('/') }}" style="color:#64748b; text-decoration:none;">{{ url('/') }}</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
