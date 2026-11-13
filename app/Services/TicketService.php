<?php

namespace App\Services;

use App\Models\Booking;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TicketService
{
    /**
     * Format official Pokdarwis ticket code.
     * Example: HSG-00042
     */
    public function formatTicketCode(Booking $booking): string
    {
        return 'HSG-'.str_pad((string) $booking->id_booking, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate secure verification token for QR code.
     */
    public function generateVerificationToken(Booking $booking): string
    {
        $hash = substr(hash_hmac('sha256', 'hsg_ticket_'.$booking->id_booking.'_'.$booking->tgl_booking, config('app.key') ?: 'secret'), 0, 8);
        return strtoupper($this->formatTicketCode($booking).'-'.$hash);
    }

    /**
     * Generate standalone SVG QR Code string.
     */
    public function generateQrCodeSvg(string $content, int $size = 200): string
    {
        try {
            $renderer = new ImageRenderer(
                new RendererStyle($size, 1),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            return $writer->writeString($content);
        } catch (\Throwable $e) {
            // Fallback SVG if BaconQrCode encounters rendering glitch
            $escaped = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200' width='{$size}' height='{$size}'>
                <rect width='200' height='200' fill='#f1f5f9'/>
                <rect x='10' y='10' width='180' height='180' fill='none' stroke='#0f172a' stroke-width='4'/>
                <text x='100' y='95' text-anchor='middle' font-family='sans-serif' font-size='12' fill='#0f172a' font-weight='bold'>KODE TIKET</text>
                <text x='100' y='115' text-anchor='middle' font-family='sans-serif' font-size='11' fill='#ea580c' font-weight='bold'>{$escaped}</text>
                <text x='100' y='145' text-anchor='middle' font-family='sans-serif' font-size='9' fill='#64748b'>Terverifikasi Pokdarwis</text>
            </svg>";
        }
    }

    /**
     * Parse code input and find booking.
     */
    public function parseCode(string $input): ?int
    {
        $trimmed = strtoupper(trim($input));
        // Matches HSG-00042 or HSG-00042-ABCD1234 or plain number 42
        if (preg_match('/(?:HSG-)?(\d+)/', $trimmed, $matches)) {
            return (int) $matches[1];
        }

        return is_numeric($trimmed) ? (int) $trimmed : null;
    }
}
