<x-layouts.dashboard title="Pengaturan Platform — BelajarYuk" headerTitle="Pengaturan Platform" headerSubtitle="Atur persentase komisi platform, batas penarikan, dan kebijakan sistem">
    <div class="max-w-2xl space-y-6 pt-4">

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-lg space-y-1">
                <p class="text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    Terdapat kesalahan validasi:
                </p>
                <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5 pl-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="panel-window p-6 sm:p-8 space-y-6 text-xs">
            @csrf

            <div class="space-y-1 pb-4 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Persentase Komisi Platform</h3>
                <p class="text-slate-500 text-[11px]">Komisi dipotong otomatis di tingkat backend dari total biaya sesi les saat pemesanan dibuat.</p>
            </div>

            <div class="space-y-3">
                <label for="commission_rate" class="block font-bold text-slate-700 uppercase">Nilai Komisi (%)</label>
                <div class="flex items-center gap-3">
                    <input id="commission_rate" type="number" step="0.5" name="commission_rate_percent"
                        min="0" max="50"
                        value="{{ old('commission_rate_percent', $commissionRate) }}"
                        required
                        class="w-36 text-lg font-extrabold text-blue-600 text-center @error('commission_rate_percent') border-rose-400 bg-rose-50/30 @enderror">
                    <span class="text-base font-bold text-slate-700">%</span>
                </div>
                @error('commission_rate_percent')
                    <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-slate-400">Contoh standar: 10.0% (Tarif Rp 100.000 = Platform Rp 10.000, Guru Rp 90.000).</p>
            </div>

            {{-- Simulation Box --}}
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 space-y-2">
                <span class="font-bold block text-xs">Simulasi Perhitungan Real-Time:</span>
                <div class="text-[11px] text-blue-800 space-y-1.5">
                    <div class="flex justify-between">
                        <span>Tarif Sesi Guru:</span>
                        <span class="font-bold">Rp 100.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Potongan Platform ({{ $commissionRate }}%):</span>
                        <span class="font-bold">Rp {{ number_format(100000 * ((float)$commissionRate / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-extrabold border-t border-blue-200 pt-1.5 text-blue-950">
                        <span>Pendapatan Bersih Guru:</span>
                        <span>Rp {{ number_format(100000 - (100000 * ((float)$commissionRate / 100)), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-primary px-8 text-xs font-bold">
                    Perbarui Pengaturan Komisi
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
