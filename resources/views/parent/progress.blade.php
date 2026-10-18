<x-layouts.dashboard title="Progres Belajar — BelajarYuk">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                    Progres Belajar
                </h1>
                <p class="text-sm text-[#747B8C] mt-1">
                    Evaluasi perkembangan akademik, nilai latihan, dan catatan evaluasi guru
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-[#080F63] bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                    Semester Ganjil 2026/2027
                </span>
            </div>
        </div>

        <!-- 3 Highlight Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bd-card p-5">
                <span class="text-xs font-bold text-[#747B8C] uppercase tracking-wider block mb-1">Rata-rata Nilai</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-[#080F63]">85.3</span>
                    <span class="text-xs font-bold text-emerald-600">+4.2% dari bulan lalu</span>
                </div>
            </div>

            <div class="bd-card p-5">
                <span class="text-xs font-bold text-[#747B8C] uppercase tracking-wider block mb-1">Tingkat Kehadiran</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-emerald-600">95%</span>
                    <span class="text-xs text-[#747B8C]">19 dari 20 sesi</span>
                </div>
            </div>

            <div class="bd-card p-5">
                <span class="text-xs font-bold text-[#747B8C] uppercase tracking-wider block mb-1">Total Jam Belajar</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-[#171923]">32 Jam</span>
                    <span class="text-xs text-[#747B8C]">Bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Detail Rapor Evaluasi Per Mata Pelajaran -->
        <div class="bd-card p-6 space-y-6">
            <h3 class="font-bold text-lg text-[#171923] pb-3 border-b border-[#E5E8F0]">
                Rapor Evaluasi Mata Pelajaran
            </h3>

            <div class="space-y-6">
                @foreach($subjectsProgress as $prog)
                    <div class="p-5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#080F63] text-white flex items-center justify-center font-bold text-sm">
                                    {{ substr($prog['subject'], 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-base text-[#171923]">{{ $prog['subject'] }}</h4>
                                    <p class="text-xs text-[#747B8C]">Guru Pembimbing: <span class="font-semibold text-[#171923]">{{ $prog['teacher'] }}</span></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <span class="text-[11px] text-[#747B8C] block">Skor Rata-rata</span>
                                    <span class="text-base font-extrabold text-[#080F63]">{{ $prog['score'] }} / 100</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[11px] text-[#747B8C] block">Kehadiran</span>
                                    <span class="text-base font-extrabold text-emerald-600">{{ $prog['attendance'] }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div>
                            <div class="flex items-center justify-between text-xs text-[#747B8C] mb-1.5 font-semibold">
                                <span>Target Kurikulum</span>
                                <span>{{ $prog['progress'] }}% Selesai</span>
                            </div>
                            <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-[#080F63] h-full rounded-full transition-all duration-500" style="width: {{ $prog['progress'] }}%"></div>
                            </div>
                        </div>

                        <!-- Teacher Written Feedback -->
                        <div class="p-3.5 rounded-xl bg-white border border-[#E5E8F0]">
                            <span class="text-[11px] font-bold text-[#080F63] uppercase tracking-wider block mb-1">Catatan Guru:</span>
                            <p class="text-xs text-[#171923] leading-relaxed italic">
                                &ldquo;{{ $prog['feedback'] }}&rdquo;
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.dashboard>
