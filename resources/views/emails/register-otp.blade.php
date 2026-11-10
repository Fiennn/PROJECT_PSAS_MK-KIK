<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi HomeStay Go</title>
</head>
<body style="margin: 0; padding: 0; background-color: #faf9f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1c1917;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #faf9f5; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #e7e5e4; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
                    <!-- Header Hijau Desa Wisata -->
                    <tr>
                        <td style="background-color: #13391b; padding: 32px; text-align: center;">
                            <div style="display: inline-block; background: #ffffff; border-radius: 9999px; padding: 6px 14px; font-size: 11px; font-weight: 700; color: #13391b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                                Platform Resmi Desa Wisata Pokdarwis
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.02em;">
                                HomeStay Go
                            </h1>
                            <p style="margin: 4px 0 0 0; color: #86efac; font-size: 12px; font-weight: 500;">
                                Autentikasi Keamanan Pendaftaran Akun
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="margin: 0 0 12px 0; font-size: 15px; color: #292524;">
                                Halo <strong style="color: #13391b;">{{ $name }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #57534e;">
                                Terima kasih telah mendaftar di <strong>HomeStay Go</strong>. Untuk memastikan keamanan akun dan memverifikasi alamat Gmail Anda (<strong>{{ $email }}</strong>), gunakan kode One-Time Password (OTP) berikut:
                            </p>

                            <!-- Kotak Kode OTP -->
                            <div style="background-color: #f0fdf4; border: 2px dashed #86efac; border-radius: 16px; padding: 24px; text-align: center; margin-bottom: 24px;">
                                <div style="font-size: 11px; font-weight: 700; color: #15803d; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">
                                    Kode Verifikasi OTP Anda
                                </div>
                                <div style="font-size: 36px; font-weight: 800; letter-spacing: 0.25em; color: #13391b; font-family: monospace;">
                                    {{ $otp }}
                                </div>
                                <div style="font-size: 12px; color: #65a30d; margin-top: 8px;">
                                    Berlaku selama 10 menit
                                </div>
                            </div>

                            <p style="margin: 0 0 16px 0; font-size: 13px; line-height: 1.6; color: #78716c;">
                                ⚠️ <strong>Perhatian:</strong> Jangan pernah membagikan kode ini kepada siapa pun, termasuk pihak yang mengatasnamakan pengelola desa atau admin HomeStay Go. Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini dengan aman.
                            </p>

                            <hr style="border: none; border-top: 1px solid #f5f5f4; margin: 24px 0;">

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size: 12px; color: #a8a29e; line-height: 1.5;">
                                        Sistem Reservasi Homestay Resmi Desa Wisata Binaan Pokdarwis Nusantara.<br>
                                        Memajukan pariwisata berkelanjutan dan ekonomi warga desa.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #faf9f5; padding: 18px 28px; text-align: center; border-top: 1px solid #e7e5e4; font-size: 11px; color: #a8a29e;">
                            &copy; {{ date('Y') }} HomeStay Go. Hak cipta dilindungi undang-undang.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
