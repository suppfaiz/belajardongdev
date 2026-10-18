<x-layouts.dashboard title="Dashboard — BelajarYuk">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- 1. Sapaan Header & Hero Banner Section -->
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                Halo, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-sm text-[#747B8C] mt-1">
                Semoga hari ini penuh pembelajaran baru dan menyenangkan!
            </p>
        </div>

        <!-- Hero Banner + Jadwal Efektif Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Hero Banner Card (8 cols) -->
            <div class="lg:col-span-8 bg-gradient-to-br from-[#080F63] via-[#0D1880] to-[#1222A8] rounded-[24px] p-6 sm:p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-soft-lg">
                <!-- Ambient decorative glow -->
                <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-1/4 -bottom-16 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 max-w-md">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-blue-100 mb-4 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Bimbingan Belajar Privat Terbaik
                    </span>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold leading-tight text-white mb-3">
                        Temukan guru terbaik untuk masa depan anak Anda
                    </h2>
                    <p class="text-sm text-blue-100/90 leading-relaxed mb-4">
                        Pilih tutor berdedikasi dan terverifikasi di berbagai mata pelajaran dan jenjang pendidikan.
                    </p>

                    <!-- Quick Search Guru directly inside Dashboard -->
                    <form action="{{ route('parent.teachers.search') }}" method="GET" class="relative max-w-md mb-3">
                        <div class="relative flex items-center">
                            <svg class="w-4 h-4 text-slate-400 absolute left-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" 
                                   name="q" 
                                   placeholder="Cari guru atau mata pelajaran..." 
                                   class="w-full bg-white text-[#171923] placeholder-slate-400 rounded-full pl-11 pr-24 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-white shadow-md">
                            <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-[#080F63] hover:bg-[#050A4F] text-white text-xs font-bold rounded-full transition flex items-center gap-1">
                                <span>Cari</span>
                            </button>
                        </div>
                    </form>

                    <!-- Popular Mapel Shortcut Badges -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="text-xs text-blue-200 font-medium">Pilihan cepat:</span>
                        @foreach($popularSubjects ?? [] as $subj)
                            <a href="{{ route('parent.teachers.search', ['subject' => $subj->slug]) }}" class="text-[11px] bg-white/15 hover:bg-white/25 px-2.5 py-0.5 rounded-full text-white backdrop-blur-xs transition">
                                {{ $subj->name }}
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ route('parent.teachers.search') }}" class="btn-navy bg-white text-[#080F63] hover:bg-slate-100 hover:text-[#050A4F] font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full inline-flex items-center gap-2 shadow-lg transition">
                        <span>Lihat Semua Guru</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

                <!-- Teacher illustration / photo mockup -->
                <div class="hidden sm:block absolute right-6 bottom-0 w-64 lg:w-72 pointer-events-none z-10">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80" 
                         alt="Guru BelajarYuk" 
                         class="w-full h-auto object-cover object-top mask-gradient drop-shadow-2xl">
                </div>
            </div>

            <!-- Jadwal Efektif Card (4 cols) -->
            <div class="lg:col-span-4 bd-card p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-base text-[#171923]">Jadwal Efektif</h3>
                    <a href="{{ route('parent.schedule') }}" class="text-xs font-semibold text-[#080F63] hover:underline">Lihat semua</a>
                </div>

                <div class="space-y-3 flex-1">
                    <div class="p-3.5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] flex items-center justify-between hover:border-slate-300 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                                📐
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#171923]">Matematika - Kelas 7</p>
                                <p class="text-[11px] text-[#747B8C]">3 Hari / 8 Sesi</p>
                            </div>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] flex items-center justify-between hover:border-slate-300 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0">
                                🌍
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#171923]">Bahasa Inggris - Kelas 5</p>
                                <p class="text-[11px] text-[#747B8C]">2 Hari / 6 Sesi</p>
                            </div>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] flex items-center justify-between hover:border-slate-300 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs shrink-0">
                                🔬
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#171923]">IPA - Kelas 8</p>
                                <p class="text-[11px] text-[#747B8C]">4 Hari / 8 Sesi</p>
                            </div>
                        </div>
                        <span class="text-slate-400">&rsaquo;</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Mid Section: Jadwal Les Berikutnya + Kursus Aktif + Progres Belajar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left: Jadwal Les Berikutnya (7 cols) -->
            <div class="lg:col-span-7 bd-card p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                    <div>
                        <h3 class="font-bold text-base text-[#171923]">Jadwal Les Berikutnya</h3>
                        <p class="text-xs text-[#747B8C]">Agenda terdekat sesi belajar anak Anda</p>
                    </div>
                    <a href="{{ route('parent.schedule') }}" class="text-xs font-semibold text-[#080F63] hover:underline">Lihat semua</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingLessons as $lesson)
                        @php
                            $dt = \Carbon\Carbon::parse($lesson->booking_date);
                            $dayName = match($dt->dayOfWeekIso) {
                                1 => 'Sen',
                                2 => 'Sel',
                                3 => 'Rab',
                                4 => 'Kam',
                                5 => 'Jum',
                                6 => 'Sab',
                                7 => 'Min',
                                default => 'Hari'
                            };
                            $badgeClass = match($lesson->status) {
                                'confirmed', 'in_progress', 'completed' => 'badge-status-confirmed',
                                'pending', 'awaiting_payment' => 'badge-status-pending',
                                default => 'badge-status-scheduled'
                            };
                            $badgeLabel = match($lesson->status) {
                                'confirmed' => 'Terkonfirmasi',
                                'pending' => 'Menunggu',
                                'in_progress' => 'Berjalan',
                                'completed' => 'Selesai',
                                default => 'Dijadwalkan'
                            };
                        @endphp
                        <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] hover:border-slate-300 transition flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <!-- Date Badge Box -->
                                <div class="w-14 h-14 rounded-2xl bg-white border border-[#E5E8F0] shadow-xs flex flex-col items-center justify-center shrink-0">
                                    <span class="text-base font-extrabold text-[#080F63] leading-none">{{ $dt->format('d') }}</span>
                                    <span class="text-[11px] font-semibold text-[#747B8C] uppercase mt-0.5 leading-none">{{ $dayName }}</span>
                                </div>
                                
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-[#171923] truncate">
                                        {{ $lesson->subject->name ?? 'Matematika' }} 
                                        @if($lesson->student)
                                            <span class="font-normal text-xs text-[#747B8C]">({{ $lesson->student->name }})</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-[#747B8C] mt-0.5">
                                        {{ substr($lesson->start_time, 0, 5) }} - {{ substr($lesson->end_time, 0, 5) }} • 
                                        Guru: <span class="font-medium text-[#171923]">{{ $lesson->teacher->name }}</span>
                                    </p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Rp {{ number_format($lesson->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                                @if($lesson->status === 'confirmed' || $lesson->status === 'in_progress')
                                    <a href="{{ route('parent.schedule') }}" class="text-xs font-semibold text-[#080F63] hover:underline">
                                        Detail &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <p class="text-sm font-medium">Belum ada jadwal les mendatang.</p>
                            <a href="{{ route('parent.teachers.search') }}" class="text-xs font-bold text-[#080F63] hover:underline mt-2 inline-block">
                                Cari Guru Sekarang &rarr;
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Kursus Aktif + Progres Belajar + Aksi Cepat (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Kursus Aktif Card -->
                <div class="bd-card p-6 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-[#E5E8F0]">
                        <h3 class="font-bold text-base text-[#171923]">Kursus Aktif</h3>
                        <a href="{{ route('parent.bookings') }}" class="text-xs font-semibold text-[#080F63] hover:underline">Lihat semua</a>
                    </div>

                    <div class="space-y-4">
                        @foreach($activeCourses as $course)
                            <div class="p-3 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0]">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold text-[#171923]">{{ $course['subject'] }}</span>
                                    <span class="text-xs font-semibold text-[#747B8C]">{{ $course['completed'] }} dari {{ $course['total'] }} sesi</span>
                                </div>
                                <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                    <div class="bg-[#080F63] h-full rounded-full transition-all duration-500" style="width: {{ $course['progress'] }}%"></div>
                                </div>
                                <p class="text-[11px] text-[#747B8C] mt-1.5">Tutor: <span class="font-medium text-[#171923]">{{ $course['teacher'] }}</span></p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Progres Belajar & Aksi Cepat Card -->
                <div class="bd-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-base text-[#171923]">Progres Belajar</h3>
                        <a href="{{ route('parent.progress') }}" class="text-xs font-semibold text-[#080F63] hover:underline">Lihat rapor</a>
                    </div>

                    <!-- Circular Progress Ring (75%) -->
                    <div class="flex items-center justify-center py-4">
                        <div class="relative w-36 h-36 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <!-- Track Circle -->
                                <circle cx="50" cy="50" r="40" stroke="#E5E8F0" stroke-width="10" fill="transparent" />
                                <!-- Progress Circle -->
                                <circle cx="50" cy="50" r="40" stroke="#080F63" stroke-width="10" 
                                        stroke-dasharray="251.2" 
                                        stroke-dashoffset="{{ 251.2 * (1 - 0.75) }}" 
                                        stroke-linecap="round" 
                                        fill="transparent" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span class="text-2xl font-extrabold text-[#171923]">75%</span>
                                <span class="text-[10px] font-semibold text-[#747B8C] uppercase tracking-wider">Pencapaian</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-blue-50/60 border border-blue-100 text-center mt-2">
                        <p class="text-xs font-medium text-[#080F63] italic">
                            &ldquo;Investasi belajar adalah pendidikan terbaik untuk masa depan anak.&rdquo;
                        </p>
                    </div>

                    <!-- 4 Quick Actions -->
                    <div class="mt-6 pt-4 border-t border-[#E5E8F0]">
                        <p class="text-xs font-bold text-[#747B8C] uppercase tracking-wider mb-3">Aksi Cepat</p>
                        <div class="grid grid-cols-4 gap-2 text-center">
                            <a href="{{ route('parent.teachers.search') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-slate-100 transition group">
                                <div class="w-10 h-10 rounded-full bg-[#080F63] text-white flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-[#171923]">Cari Guru</span>
                            </a>

                            <a href="{{ route('parent.schedule') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-slate-100 transition group">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-[#171923]">Jadwal</span>
                            </a>

                            <a href="{{ route('parent.payments') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-slate-100 transition group">
                                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-[#171923]">Bayar</span>
                            </a>

                            <a href="{{ route('parent.students') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-slate-100 transition group">
                                <div class="w-10 h-10 rounded-full bg-sky-600 text-white flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-[#171923]">Profil Anak</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
