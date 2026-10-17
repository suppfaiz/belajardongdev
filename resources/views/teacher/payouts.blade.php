<x-layouts.dashboard title="Pencairan Dana — Belajar Dong" headerTitle="Pencairan Dana (Payout)" headerSubtitle="Ajukan pencairan saldo mengajar langsung ke rekening bank Anda">
    <div class="space-y-6 pt-2">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left: Request Payout Form -->
            <div class="lg:col-span-5 panel-window p-5 sm:p-6 space-y-4 text-xs">
                <div class="p-4 rounded-lg bg-blue-50/70 border border-blue-200 text-center space-y-1">
                    <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider block">Saldo Tersedia untuk Ditarik</span>
                    <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 tabular-nums">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-blue-700">Minimal pencairan Rp 50.000</p>
                </div>

                <form method="POST" action="{{ route('teacher.payouts.request') }}" class="space-y-3.5">
                    @csrf

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nominal yang Ingin Ditarik (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" min="50000" max="{{ $availableBalance }}" value="{{ old('amount', min(100000, $availableBalance)) }}" required class="w-full px-3 py-2 h-9 border border-slate-200 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs font-bold text-slate-900 tabular-nums">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Bank Tujuan <span class="text-rose-500">*</span></label>
                        <select name="bank_name" required class="w-full px-3 py-2 h-9 border border-slate-200 rounded-lg outline-none focus:border-blue-500 bg-white text-xs font-medium">
                            <option value="BCA" {{ $profile->bank_name === 'BCA' ? 'selected' : '' }}>BCA</option>
                            <option value="Mandiri" {{ $profile->bank_name === 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                            <option value="BRI" {{ $profile->bank_name === 'BRI' ? 'selected' : '' }}>BRI</option>
                            <option value="BNI" {{ $profile->bank_name === 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="BSI" {{ $profile->bank_name === 'BSI' ? 'selected' : '' }}>BSI</option>
                            <option value="CIMB" {{ $profile->bank_name === 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nomor Rekening Bank <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $profile->bank_account_number) }}" required placeholder="Contoh: 1234567890" class="w-full px-3 py-2 h-9 border border-slate-200 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs font-mono font-semibold tabular-nums">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Pemilik Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $profile->bank_account_name) }}" required placeholder="Harus sesuai dengan nama di KTP" class="w-full px-3 py-2 h-9 border border-slate-200 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-xs font-medium">
                    </div>

                    <button type="submit" @if($availableBalance < 50000) disabled @endif class="w-full btn-primary py-2.5 text-xs font-semibold justify-center shadow-xs disabled:opacity-50 disabled:cursor-not-allowed">
                        Ajukan Pencairan Dana
                    </button>
                </form>
            </div>

            <!-- Right: Payout History -->
            <div class="lg:col-span-7 panel-window p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Pengajuan Pencairan</h3>
                        <p class="text-[11px] text-slate-400">Status verifikasi dan transfer ke rekening bank</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">{{ $payouts->total() }} Pengajuan</span>
                </div>

                <div class="space-y-3">
                    @forelse($payouts as $po)
                        <div class="p-3.5 rounded-lg border border-slate-200/80 bg-slate-50/50 flex items-center justify-between gap-4 text-xs transition hover:border-slate-300">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-semibold text-slate-900 tabular-nums">{{ $po->payout_code }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span class="font-semibold text-slate-700 tabular-nums">{{ $po->bank_name }} ({{ $po->bank_account_number }})</span>
                                </div>
                                <p class="text-[11px] text-slate-500">a.n. {{ $po->bank_account_name }}</p>
                                <p class="text-[10px] text-slate-400 tabular-nums">Diajukan: {{ $po->requested_at->format('d M Y, H:i') }} WIB</p>
                            </div>

                            <div class="text-right shrink-0 space-y-1">
                                <p class="text-sm font-bold text-blue-600 tabular-nums">Rp {{ number_format($po->amount, 0, ',', '.') }}</p>
                                @if($po->status === 'completed')
                                    <span class="badge-verified text-[10px]">Telah Ditransfer</span>
                                @elseif($po->status === 'requested')
                                    <span class="badge-pending text-[10px]">Menunggu Verifikasi</span>
                                @elseif($po->status === 'processing')
                                    <span class="badge-active text-[10px]">Diproses Transfer</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400 text-xs">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <x-icons.receipt class="w-8 h-8 text-slate-300" />
                                <p class="font-medium text-slate-600">Belum ada pengajuan pencairan dana.</p>
                                <p class="text-[11px] text-slate-400">Setelah saldo minimum Rp 50.000 tercapai, Anda dapat mengajukan withdraw.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="pt-2">
                    {{ $payouts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
