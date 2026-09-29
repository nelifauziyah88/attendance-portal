<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Undangan {{ $event->name }}</title>
</head>
<body style="margin:0;padding:24px;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;color:#1f2933;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:8px;">
        <tr>
            <td style="padding:32px;">
                <h1 style="margin:0 0 16px;font-size:20px;">Undangan {{ $event->name }}</h1>
                <p style="margin:0 0 12px;">Yth. {{ $participant->name }},</p>
                <p style="margin:0 0 12px;">Anda diundang untuk menghadiri {{ $event->name }}. Mohon konfirmasi kehadiran Anda melalui tautan di bawah ini atau dengan memindai kode QR.</p>
                @if ($event->description)
                    <p style="margin:0 0 12px;">{{ $event->description }}</p>
                @endif
                <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 16px;font-size:14px;">
                    <tr><td style="padding:2px 12px 2px 0;">Tanggal</td><td>{{ $eventDate }}</td></tr>
                    <tr><td style="padding:2px 12px 2px 0;">Waktu</td><td>{{ $eventTime }}</td></tr>
                    <tr><td style="padding:2px 12px 2px 0;">Lokasi</td><td>{{ $event->location }}</td></tr>
                    <tr><td style="padding:2px 12px 2px 0;">BADGE</td><td>{{ $participant->badge_id }}</td></tr>
                    @if ($participant->department)
                        <tr><td style="padding:2px 12px 2px 0;">Departemen</td><td>{{ $participant->department }}</td></tr>
                    @endif
                </table>
                <p style="margin:0 0 24px;">
                    <a href="{{ $invitationUrl }}" style="display:inline-block;padding:12px 20px;background:#0b5cad;color:#ffffff;text-decoration:none;border-radius:6px;">Konfirmasi Kehadiran</a>
                </p>
                <p style="margin:0 0 24px;text-align:center;">
                    <img src="{{ $message->embedData($qrCodePng, 'qr-'.$participant->badge_id.'.png', 'image/png') }}" width="200" height="200" alt="QR Code {{ $participant->badge_id }}">
                </p>
                <p style="margin:0;font-size:12px;color:#6b7280;">Jika tombol tidak berfungsi, buka tautan berikut: {{ $invitationUrl }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
