<x-layouts.dashboard title="Penghasilan & Ledger — Belajar Dong" headerTitle="Penghasilan & Buku Besar (Ledger)" headerSubtitle="Catatan mutasi keuangan transparan, komisi platform, dan rekonsiliasi saldo mengajar Anda">
    <div class="space-y-6 pt-2">
        <!-- 3 Financial Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            <div class="panel-window p-5 space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pendapatan Bruto</span>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">Rp {{ number_format($grossEarnings, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400">Total nilai sesi les yang telah selesai</p>
            </div>

            <div class="panel-window p-5 space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Potongan Komisi (10%)</span>
                <p class="text-2xl font-bold text-rose-600 tabular-nums">- Rp {{ number_format($totalCommissionPaid, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400">Biaya operasional & penampungan escrow</p>
            </div>

            <div class="panel-window p-5 space-y-1 bg-blue-50/30 border-blue-100">
                <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider block">Saldo Tersedia</span>
                <p class="text-2xl font-bold text-blue-600 tabular-nums">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
                <a href="{{ route('teacher.payouts') }}" class="text-[11px] text-blue-600 font-semibold hover:underline inline-flex items-center gap-1 mt-0.5">
                    <span>Ajukan Pencairan ke Rekening</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Ledger Entries Table -->
        <div class="data-table-wrapper">
            <div class="p-4 sm:px-5 border-b border-slate-200/80 bg-white">
                <h3 class="text-sm font-bold text-slate-900">Buku Besar Rekonsiliasi Keuangan (Financial Ledger)</h3>
                <p class="text-xs text-slate-400 mt-0.5">Seluruh mutasi kredit dan debit tercatat secara permanen dan immutable.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode Entri</th>
                            <th scope="col">Jenis Transaksi</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col" class="text-right">Nominal</th>
                            <th scope="col" class="text-right">Saldo Akhir</th>
                            <th scope="col">Waktu Mutasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ledgerEntries as $entry)
                            <tr>
                                <td class="font-mono font-semibold text-slate-900 tabular-nums">
                                    {{ $entry->entry_code }}
                                </td>
                                <td>
                                    @if($entry->direction === 'credit')
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full text-[10px]">
                                            &darr; Kredit (Masuk)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-rose-700 font-semibold bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full text-[10px]">
                                            &uarr; Debit (Keluar)
                                        </span>
                                    @endif
                                </td>
                                <td class="text-slate-600 max-w-xs truncate">{{ $entry->description }}</td>
                                <td class="text-right font-bold tabular-nums {{ $entry->direction === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $entry->direction === 'credit' ? '+' : '-' }} Rp {{ number_format($entry->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-right font-semibold text-slate-900 tabular-nums">
                                    Rp {{ number_format($entry->balance_after, 0, ',', '.') }}
                                </td>
                                <td class="text-slate-500 tabular-nums">{{ $entry->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <x-icons.receipt class="w-8 h-8 text-slate-300" />
                                        <p class="font-medium text-slate-600">Belum ada entri mutasi ledger tercatat.</p>
                                        <p class="text-[11px] text-slate-400">Semua transaksi kredit pembayaran les dan debit pencairan dana akan tampil di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $ledgerEntries->links() }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>
