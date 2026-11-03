import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('bookingForm', (config = {}) => ({
    checkin: config.checkin ?? '',
    checkout: config.checkout ?? '',
    jumlahTamu: Number(config.tamu ?? 1),
    hargaKamar: Number(config.harga ?? 0),
    paket: config.paket ?? {},
    paketHarga: config.paketHarga ?? {},

    get malam() {
        if (! this.checkin || ! this.checkout) {
            return 0;
        }

        const start = new Date(this.checkin);
        const end = new Date(this.checkout);
        const diff = Math.round((end - start) / 86400000);

        return Number.isFinite(diff) && diff > 0 ? diff : 0;
    },

    get totalKamar() {
        return this.malam * this.hargaKamar;
    },

    get totalPaket() {
        return Object.entries(this.paket).reduce((total, [id, quantity]) => {
            return total + (Number(this.paketHarga[id] ?? 0) * Math.max(0, Number(quantity) || 0));
        }, 0);
    },

    get grandTotal() {
        return this.totalKamar + this.totalPaket;
    },

    formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(Number(value) || 0));
    },
}));

Alpine.start();
