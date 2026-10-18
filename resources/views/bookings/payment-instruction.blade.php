<x-layouts.app title="Instruksi Pembayaran — BelajarYuk">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6" x-data="{
        copied: false,
        activeGuide: 'm-banking',
        minutes: 14,
        seconds: 59,
        init() {
            setInterval(() => {
                if (this.seconds > 0) {
                    this.seconds--;
                } else if (this.minutes > 0) {
                    this.minutes--;
                    this.seconds = 59;
                }
            }, 1000);
        },
        triggerConfetti() {
            if (window.confetti) {
                window.confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }
        }
    }">
        @php
            $payment = $booking->payment;
        @endphp

        <!-- Top Status Banner -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                <span>Menunggu Pembayaran Escrow</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Selesaikan Pembayaran Les</h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
                Dana Anda akan diamankan di escrow platform sampai sesi les tatap muka selesai dilaksanakan oleh guru.
            </p>
        </div>

        <!-- Main Payment Box -->
        <div class="panel-window p-6 sm:p-8 space-y-6">
            <!-- Amount & Countdown Bar -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-blue-900 to-indigo-950 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                <div class="text-center sm:text-left space-y-1">
                    <span class="text-xs font-bold text-blue-200 uppercase tracking-wider block">Total yang Harus Dibayar</span>
                    <p class="text-3xl sm:text-4xl font-black text-white tracking-tight tabular-nums">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-blue-200/80">Kode Pesanan: <span class="font-mono font-bold text-white">{{ $booking->booking_code }}</span></p>
                </div>

                <div class="bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/20 text-center shrink-0">
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">Sisa Waktu Pembayaran</span>
                    <div class="text-xl font-black text-amber-300 font-mono tracking-wider tabular-nums pt-0.5">
                        <span x-text="String(minutes).padStart(2, '0')"></span>:<span x-text="String(seconds).padStart(2, '0')"></span>
                    </div>
                    <span class="text-[10px] text-blue-200">Batas: {{ $payment->expires_at?->translatedFormat('H:i') ?? '15 Menit' }} WIB</span>
                </div>
            </div>

            <!-- PAYMENT CHANNEL CONTENT: QRIS vs VIRTUAL ACCOUNT -->
            @if($payment->payment_method === 'qris' || $payment->qr_url)
                <!-- QRIS Frame Presentation -->
                <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 text-center space-y-4">
                    <div class="flex items-center justify-center gap-3">
                        <span class="px-3 py-1 rounded-lg bg-rose-600 text-white font-black text-xs tracking-wider">QRIS</span>
                        <span class="text-xs font-bold text-slate-700">Standar Pembayaran Nasional (GPN)</span>
                    </div>

                    <!-- Realistic QR Frame -->
                    <div class="relative w-64 h-64 mx-auto p-4 bg-white border-2 border-slate-300 rounded-3xl shadow-sm flex flex-col items-center justify-center space-y-2">
                        @if($payment->qr_url)
                            <img src="{{ $payment->qr_url }}" alt="QRIS BelajarYuk" class="w-full h-full object-contain">
                        @else
                            <!-- Styled SVG QR Code demonstration if dynamic URL -->
                            <div class="w-full h-full bg-slate-900 rounded-2xl flex flex-col items-center justify-center text-white p-4 space-y-2">
                                <svg class="w-28 h-28 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-5 5h2v3h-2v-3zm3 0h2v3h-2v-3zm3 0h2v3h-2v-3zm-5-3h3v2h-3v-2zm3 0h3v2h-3v-2z" />
                                </svg>
                                <span class="text-[10px] font-bold tracking-widest text-emerald-400">QRIS READY</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-800">Merchant: BELAJARYUK OFFICIAL (ESCROW PT)</p>
                        <p class="text-[11px] text-slate-500">NMID: ID1020261013009 • Berlaku untuk seluruh M-Banking & E-Wallet</p>
                    </div>

                    <!-- E-wallet & Mobile Banking Badges -->
                    <div class="flex flex-wrap items-center justify-center gap-2 pt-1 text-[11px] font-bold text-slate-600">
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">BCA Mobile</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">Livin' Mandiri</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">BRImo</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">BNI Mobile</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">GoPay</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">OVO</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">ShopeePay</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 shadow-xs">DANA</span>
                    </div>
                </div>
            @else
                <!-- Virtual Account Frame -->
                <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="form-label text-[10px]">Nomor Virtual Account Bank</span>
                            <h3 class="text-sm font-extrabold text-slate-900">Bank Mandiri / BCA / Permata Escrow</h3>
                        </div>
                        <span class="badge-active text-[11px] py-1 px-3">Verifikasi Otomatis</span>
                    </div>

                    <!-- VA Input with Copy Button -->
                    <div class="flex items-center gap-2.5">
                        <input type="text" readonly value="{{ $payment->va_number ?? '88019' . str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}" class="form-input font-mono text-lg font-black text-slate-900 tracking-wider bg-white">
                        <button type="button" @click="navigator.clipboard.writeText('{{ $payment->va_number ?? '88019' . str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}'); copied = true; setTimeout(() => copied = false, 2500)" class="btn-primary py-3 px-5 text-xs font-bold shrink-0 flex items-center gap-1.5 shadow-sm">
                            <span x-show="!copied">Salin No. VA</span>
                            <span x-show="copied" class="text-emerald-300">✓ Tersalin!</span>
                        </button>
                    </div>

                    <p class="text-[11px] text-slate-500">
                        Transfer tepat sesuai nominal <span class="font-bold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span> agar sistem otomatis mendeteksi transaksi tanpa upload bukti transfer.
                    </p>
                </div>
            @endif

            <!-- Expandable Payment Guide Accordion -->
            <div class="space-y-3 pt-2">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900">Panduan Pembayaran Langkah Demi Langkah</h4>

                <div class="flex gap-2 border-b border-slate-200 pb-2 text-xs">
                    <button type="button" @click="activeGuide = 'm-banking'" :class="activeGuide === 'm-banking' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-slate-500 font-medium'" class="pb-2 px-1 transition">
                        Mobile Banking (Aplikasi)
                    </button>
                    <button type="button" @click="activeGuide = 'atm'" :class="activeGuide === 'atm' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-slate-500 font-medium'" class="pb-2 px-1 transition">
                        Mesin ATM
                    </button>
                    <button type="button" @click="activeGuide = 'e-wallet'" :class="activeGuide === 'e-wallet' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-slate-500 font-medium'" class="pb-2 px-1 transition">
                        E-Wallet (GoPay/OVO/ShopeePay)
                    </button>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200 text-xs text-slate-600 leading-relaxed">
                    <div x-show="activeGuide === 'm-banking'" class="space-y-1.5">
                        <p>1. Buka aplikasi Mobile Banking (Livin' Mandiri, BCA Mobile, BRImo, atau BNI Mobile).</p>
                        <p>2. Pilih menu <strong>Bayar / Transfer</strong> &gt; <strong>Virtual Account / QRIS</strong>.</p>
                        <p>3. Masukkan nomor Virtual Account atau scan QR Code di atas.</p>
                        <p>4. Konfirmasi nama merchant <strong>BELAJARYUK</strong> dan nominal pembayaran.</p>
                        <p>5. Masukkan PIN Anda. Notifikasi konfirmasi akan terbit dalam hitungan detik.</p>
                    </div>

                    <div x-show="activeGuide === 'atm'" class="space-y-1.5">
                        <p>1. Masukkan kartu ATM dan PIN Anda di mesin ATM terdekat.</p>
                        <p>2. Pilih menu <strong>Transaksi Lainnya</strong> &gt; <strong>Pembayaran</strong> &gt; <strong>Lainnya</strong> &gt; <strong>Virtual Account</strong>.</p>
                        <p>3. Ketikkan nomor Virtual Account BelajarYuk.</p>
                        <p>4. Periksa kecocokan data pesanan di layar ATM dan selesaikan transaksi.</p>
                    </div>

                    <div x-show="activeGuide === 'e-wallet'" class="space-y-1.5">
                        <p>1. Buka aplikasi GoPay, OVO, ShopeePay, atau DANA.</p>
                        <p>2. Klik tombol <strong>Scan / Bayar</strong> di menu utama.</p>
                        <p>3. Arahkan kamera ponsel Anda ke QRIS di atas.</p>
                        <p>4. Masukkan PIN e-wallet untuk menyelesaikan pembayaran secara instan.</p>
                    </div>
                </div>
            </div>

            <!-- Sandbox Testing Simulator Box -->
            <div class="p-5 sm:p-6 rounded-3xl bg-emerald-50 border border-emerald-200/90 text-emerald-950 space-y-3.5 shadow-xs">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-900">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping"></span>
                        <span>Sandbox Simulator (Lingkungan Uji Coba)</span>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full">
                        Instant Webhook
                    </span>
                </div>
                <p class="text-xs text-emerald-800 leading-relaxed">
                    Uji coba alur transaksi secara nyata! Klik tombol di bawah untuk mensimulasikan notifikasi sukses dari sistem payment gateway, mengaktifkan status escrow, dan mencatat transaksi ke buku kas (ledger) otomatis.
                </p>
                <form method="POST" action="{{ route('bookings.simulate-pay', $booking->id) }}" @submit="triggerConfetti()">
                    @csrf
                    <button type="submit" class="w-full btn-primary bg-emerald-600 hover:bg-emerald-700 py-3.5 text-xs justify-center font-bold shadow-md flex items-center gap-2">
                        <x-icons.bolt class="w-4 h-4 text-white" />
                        <span>Simulasikan Pembayaran Berhasil (Konfirmasi Instan & Rilis Escrow)</span>
                    </button>
                </form>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('parent.bookings.show', $booking->id) }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center justify-center gap-1.5">
                    <span>← Kembali ke Riwayat Pemesanan</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
