<x-layouts.dashboard title="Transaksi & Pembayaran — BelajarYuk" headerTitle="Transaksi & Pembayaran Escrow" headerSubtitle="Daftar pembayaran dari orang tua melalui gateway dan status rekening escrow">
    <div class="space-y-5 pt-2">
        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode Bayar</th>
                            <th scope="col">Kode Booking</th>
                            <th scope="col">Orang Tua</th>
                            <th scope="col">Metode Bayar</th>
                            <th scope="col" class="text-right">Nominal</th>
                            <th scope="col">Status</th>
                            <th scope="col">Waktu Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $pay)
                            <tr>
                                <td class="font-mono font-semibold text-slate-900 tabular-nums">
                                    {{ $pay->payment_code }}
                                </td>
                                <td class="font-mono font-medium text-blue-600 tabular-nums">
                                    {{ $pay->booking->booking_code }}
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $pay->booking->parent?->name }}</p>
                                    <p class="text-[11px] text-slate-400">Guru: {{ $pay->booking->teacher?->name }}</p>
                                </td>
                                <td class="capitalize font-medium text-slate-700">
                                    {{ str_replace('_', ' ', $pay->payment_method) }}
                                </td>
                                <td class="text-right font-bold text-slate-900 tabular-nums">
                                    Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($pay->status === 'paid')
                                        <span class="badge-verified text-[10px]">Lunas</span>
                                    @elseif($pay->status === 'pending')
                                        <span class="badge-pending text-[10px]">Menunggu</span>
                                    @elseif($pay->status === 'refunded')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">Refunded</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200 uppercase">{{ $pay->status }}</span>
                                    @endif
                                </td>
                                <td class="text-slate-500 tabular-nums">{{ $pay->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <x-icons.receipt class="w-8 h-8 text-slate-300" />
                                        <p class="font-medium text-slate-600">Belum ada transaksi pembayaran.</p>
                                        <p class="text-[11px] text-slate-400">Semua riwayat pembayaran escrow dari orang tua akan tercatat di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>
