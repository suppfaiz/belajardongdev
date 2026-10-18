<x-layouts.dashboard title="Pembayaran — BelajarYuk">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                    Pembayaran & Tagihan
                </h1>
                <p class="text-sm text-[#747B8C] mt-1">
                    Kelola tagihan les, pilihan metode pembayaran, dan bukti invoice transaksi
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Sistem Rekening Bersama (Escrow Aman)
            </div>
        </div>

        <!-- 1. Kartu Tagihan Aktif (Jika ada booking pending bayar) -->
        <div class="bd-card p-6 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border-amber-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-bold text-xl shadow-xs shrink-0">
                        ⚡
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-base text-[#171923]">Tagihan Menunggu Pembayaran</h3>
                            <span class="badge-status badge-status-pending text-[10px]">Batas Waktu: 23 Jam Lagi</span>
                        </div>
                        <p class="text-xs text-[#747B8C] mt-0.5">
                            Paket Les Matematika (Rian Pratama) • Guru: Siti Nurhaliza
                        </p>
                        <p class="text-base font-extrabold text-[#080F63] mt-1">
                            Rp 150.000 <span class="text-xs font-normal text-slate-500">(Virtual Account BCA: 8077700001234)</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('parent.bookings') }}" class="btn-navy py-2.5 px-6 text-xs font-bold shadow-md">
                        Bayar Sekarang &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Pilihan Metode Pembayaran Terintegrasi -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bd-card p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#080F63] font-bold text-sm flex items-center justify-center">
                    🏦
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#171923]">Virtual Account</h4>
                    <p class="text-[11px] text-[#747B8C]">BCA, Mandiri, BRI, BNI</p>
                </div>
            </div>

            <div class="bd-card p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-sm flex items-center justify-center">
                    📱
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#171923]">E-Wallet / QRIS</h4>
                    <p class="text-[11px] text-[#747B8C]">GoPay, OVO, Dana, ShopeePay</p>
                </div>
            </div>

            <div class="bd-card p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#080F63] font-bold text-sm flex items-center justify-center">
                    💳
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#171923]">Kartu Debit / Kredit</h4>
                    <p class="text-[11px] text-[#747B8C]">Visa & Mastercard</p>
                </div>
            </div>
        </div>

        <!-- 3. Tabel Riwayat Transaksi -->
        <div class="bd-card overflow-hidden">
            <div class="p-5 border-b border-[#E5E8F0] flex items-center justify-between">
                <h3 class="font-bold text-base text-[#171923]">Riwayat Transaksi</h3>
                <span class="text-xs text-[#747B8C]">Semua pembayaran tercatat transparan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] border-b border-[#E5E8F0] text-[#747B8C] font-semibold uppercase text-[11px]">
                            <th class="py-3 px-5">Kode Invoice</th>
                            <th class="py-3 px-5">Guru & Mapel</th>
                            <th class="py-3 px-5">Metode</th>
                            <th class="py-3 px-5 text-right">Nominal</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5">Tanggal</th>
                            <th class="py-3 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $pay)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-5 font-mono font-bold text-[#171923]">
                                    {{ $pay->payment_code }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <p class="font-bold text-[#171923]">{{ $pay->booking->teacher?->name }}</p>
                                    <p class="text-[#747B8C] text-[11px]">{{ $pay->booking->subject?->name }}</p>
                                </td>
                                <td class="py-3.5 px-5 capitalize font-medium text-slate-600">
                                    {{ str_replace('_', ' ', $pay->payment_method) }}
                                </td>
                                <td class="py-3.5 px-5 text-right font-extrabold text-[#080F63]">
                                    Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-5">
                                    @if($pay->status === 'paid')
                                        <span class="badge-status badge-status-confirmed text-[10px]">Lunas</span>
                                    @elseif($pay->status === 'pending')
                                        <span class="badge-status badge-status-pending text-[10px]">Menunggu</span>
                                    @else
                                        <span class="badge-status badge-status-scheduled text-[10px]">{{ $pay->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-400 whitespace-nowrap">
                                    {{ $pay->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <button type="button" 
                                            onclick="alert('Kwitansi PDF transaksi {{ $pay->payment_code }} siap diunduh!')"
                                            class="btn-white-pill text-[11px] py-1 px-3">
                                        Unduh Kwitansi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Belum ada transaksi pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($payments, 'hasPages') && $payments->hasPages())
                <div class="p-4 border-t border-[#E5E8F0]">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
