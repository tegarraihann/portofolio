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
                                    {{ $typeLabel }} terbaru untuk Anda
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:0 24px 16px;">
                                <div style="font-size:14px; color:#1f2937; line-height:1.6;">
                                    {{ $typeLabel }} terbaru dari {{ config('app.name') }}.
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:0 24px 24px;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                    @if (!empty($item['thumbnail_url']))
                                        <tr>
                                            <td>
                                                <img
                                                    src="{{ $item['thumbnail_url'] }}"
                                                    alt="{{ $item['title'] }}"
                                                    style="display:block; width:100%; height:auto; max-height:280px; object-fit:cover;"
                                                >
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="padding:18px;">
                                            <div style="font-size:16px; font-weight:700; color:#0f172a;">
                                                {{ $item['title'] }}
                                            </div>
                                            @if (!empty($item['date']))
                                                <div style="margin-top:6px; font-size:12px; color:#94a3b8;">
                                                    {{ $item['date'] }}
                                                </div>
                                            @endif
                                            @if (!empty($item['excerpt']))
                                                <div style="margin-top:10px; font-size:13px; line-height:1.6; color:#475569;">
                                                    {{ $item['excerpt'] }}
                                                </div>
                                            @endif
                                            <div style="margin-top:16px;">
                                                <a
                                                    href="{{ $item['url'] }}"
                                                    style="display:inline-block; background:#4f46e5; color:#ffffff; text-decoration:none; font-size:13px; font-weight:700; padding:10px 16px; border-radius:8px;"
                                                >
                                                    Baca Selengkapnya
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:24px 24px 32px; color:#94a3b8; font-size:12px; line-height:1.5;">
                                Anda menerima email ini karena berlangganan newsletter.
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
