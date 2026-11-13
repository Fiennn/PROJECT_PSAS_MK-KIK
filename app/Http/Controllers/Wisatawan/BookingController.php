<?php

namespace App\Http\Controllers\Wisatawan;

use App\Enums\StatusBooking;
use App\Enums\StatusKamar;
use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wisatawan\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Kamar;
use App\Services\BookingService;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $wisatawan = $request->user()->wisatawan()->firstOrFail();

        $status = $request->query('status');
        $allowedStatuses = array_column(StatusBooking::cases(), 'value');

        $bookings = Booking::query()
            ->where('id_wisatawan', $wisatawan->id_wisatawan)
            ->when(
                $status && in_array($status, $allowedStatuses, true),
                fn ($query) => $query->where('status', $status)
            )
            ->with(['kamar.homestay', 'paket', 'pembayaran', 'ulasan'])
            ->orderByDesc('id_booking')
            ->paginate(8)
            ->withQueryString();

        return view('wisatawan.booking.index', compact('bookings', 'status'));
    }

    public function create(Kamar $kamar): View
    {
        $kamar->load(['homestay.paket', 'homestay.fasilitas']);

        abort_unless(
            $kamar->status === StatusKamar::Tersedia
            && $kamar->homestay->status_verifikasi === StatusVerifikasi::Disetujui,
            404
        );

        $selectedPackages = collect(old('paket', []))
            ->filter(fn ($quantity, $packageId) => is_numeric($packageId) && (int) $quantity > 0)
            ->mapWithKeys(fn ($quantity, $packageId) => [(string) $packageId => (int) $quantity])
            ->all();

        return view('wisatawan.booking.create', compact('kamar', 'selectedPackages'));
    }

    public function store(StoreBookingRequest $request, BookingService $bookingService): RedirectResponse
    {
        $validated = $request->validated();

        $kamar = Kamar::findOrFail($validated['id_kamar']);
        $wisatawan = $request->user()->wisatawan()->firstOrFail();

        $booking = $bookingService->create($wisatawan, $kamar, $validated);

        return redirect()
            ->route('wisatawan.booking.show', $booking)
            ->with('success', 'Pemesanan berhasil dibuat. Silakan unggah bukti pembayaran.');
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load([
            'wisatawan',
            'kamar.homestay.pemilik',
            'paket',
            'pembayaran' => fn ($query) => $query->orderByDesc('id_pembayaran'),
            'ulasan',
        ]);

        return view('wisatawan.booking.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $booking->update(['status' => StatusBooking::Dibatalkan->value]);

        return redirect()
            ->route('wisatawan.booking.show', $booking)
            ->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function ticket(Booking $booking, TicketService $ticketService): View|RedirectResponse
    {
        $this->authorize('view', $booking);

        if (! in_array($booking->status->value, [StatusBooking::Dikonfirmasi->value, StatusBooking::Selesai->value], true)) {
            return redirect()
                ->route('wisatawan.booking.show', $booking)
                ->with('error', 'E-Tiket resmi Pokdarwis baru dapat diterbitkan setelah pembayaran diverifikasi dan reservasi berstatus Dikonfirmasi.');
        }

        $booking->load([
            'wisatawan.user',
            'kamar.homestay.pemilik.user',
            'paket',
            'pembayaran' => fn ($query) => $query->where('status_verifikasi', 'terverifikasi')->orderByDesc('id_pembayaran'),
        ]);

        $ticketCode = $ticketService->formatTicketCode($booking);
        $qrPayload = $ticketService->generateVerificationToken($booking);
        $qrSvg = $ticketService->generateQrCodeSvg($qrPayload, 170);

        return view('wisatawan.booking.ticket', compact('booking', 'ticketCode', 'qrPayload', 'qrSvg'));
    }
}

