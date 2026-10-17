<x-layouts.dashboard title="Pencairan Dana — Belajar Dong" headerTitle="Antrean Pencairan Dana (Payouts)" headerSubtitle="Verifikasi dan selesaikan pencairan saldo mengajar para guru ke rekening bank resmi">
    <div class="space-y-5 pt-2">
        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode Payout</th>
                            <th scope="col">Nama Guru</th>
                            <th scope="col">Rekening Tujuan</th>
                            <th scope="col" class="text-right">Nominal</th>
                            <th scope="col">Status</th>
                            <th scope="col">Diajukan</th>
                            <th scope="col" class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $po)
                            <tr>
                                <td class="font-mono font-semibold text-slate-900 tabular-nums">
                                    {{ $po->payout_code }}
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $po->teacherProfile->user->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ $po->teacherProfile->user->phone ?? '—' }}</p>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-900 tabular-nums">{{ $po->bank_name }} - {{ $po->bank_account_number }}</p>
                                    <p class="text-slate-400 text-[11px]">a.n. {{ $po->bank_account_name }}</p>
                                </td>
                                <td class="text-right font-bold text-blue-600 tabular-nums">
                                    Rp {{ number_format($po->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($po->status === 'completed')
                                        <span class="badge-verified text-[10px]">Telah Ditransfer</span>
                                    @elseif($po->status === 'requested')
                                        <span class="badge-pending text-[10px]">Perlu Transfer</span>
                                    @elseif($po->status === 'processing')
                                        <span class="badge-active text-[10px]">Diproses</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 uppercase">{{ $po->status }}</span>
                                    @endif
                                </td>
                                <td class="text-slate-500 tabular-nums">{{ $po->requested_at->format('d M Y, H:i') }} WIB</td>
                                <td class="text-right">
                                    @if($po->status === 'requested')
                                        <form method="POST" action="{{ route('admin.payouts.process', $po->id) }}" onsubmit="return confirm('Konfirmasi bahwa dana telah berhasil ditransfer ke rekening guru? Mutasi debit akan dicatat ke ledger.')">
                                            @csrf
                                            <button type="submit" class="btn-primary btn-sm bg-emerald-600 hover:bg-emerald-700 text-white gap-1.5 ml-auto">
                                                <x-icons.check class="w-3.5 h-3.5" />
                                                <span>Proses Payout</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">Telah selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <x-icons.receipt class="w-8 h-8 text-slate-300" />
                                        <p class="font-medium text-slate-600">Belum ada antrean permohonan pencairan dana.</p>
                                        <p class="text-[11px] text-slate-400">Permohonan withdraw dari guru akan ditampilkan di antrean ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $payouts->links() }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>
