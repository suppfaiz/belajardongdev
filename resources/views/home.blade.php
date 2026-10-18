<x-layouts.app title="BelajarYuk — Temukan Guru Terbaik untuk Masa Depan Anak Anda">
    <div class="space-y-16 lg:space-y-20 pb-16">
        <!-- ==========================================================================
             PANEL 1: HERO SECTION & QUICK SEARCH & STAT STRIP
             ========================================================================== -->
        <section class="relative pt-4 sm:pt-10 overflow-hidden overflow-x-clip">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    <!-- Left Hero Content (7 cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="space-y-3">
                            <h1 class="text-3xl sm:text-4xl lg:text-[46px] font-extrabold text-[#111827] tracking-tight leading-[1.18]">
                                Temukan Guru Terbaik untuk<br class="hidden sm:inline">
                                Masa Depan Anak Anda
                            </h1>
                            <p class="text-sm sm:text-base text-[#64748B] leading-relaxed max-w-lg">
                                Pilih dari ribuan guru berpengalaman di berbagai mata pelajaran dan jenjang, tersedia untuk les Online maupun Tatap Muka.
                            </p>

                            <!-- Compact Social Proof Badge on Mobile -->
                            <div class="flex items-center gap-3 pt-1 lg:hidden">
                                <div class="flex -space-x-2 shrink-0">
                                    <img class="w-8 h-8 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=80&q=80" alt="Guru">
                                    <img class="w-8 h-8 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80" alt="Guru">
                                    <img class="w-8 h-8 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=80&q=80" alt="Guru">
                                </div>
                                <div class="text-left">
                                    <div class="font-extrabold text-xs text-[#111827] flex items-center gap-1 leading-tight">
                                        <span>4.9 / 5.0</span>
                                        <span class="text-amber-500">★★★★★</span>
                                    </div>
                                    <span class="text-[10px] text-[#64748B]">Dipercaya 1.000+ Orang Tua Murid</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pencarian Cepat Container -->
                        <div class="space-y-2.5 pt-1">
                            <h3 class="text-sm font-bold text-[#111827]">
                                Pencarian Cepat
                            </h3>

                            <form action="{{ route('teachers.search') }}" method="GET" class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                    <!-- 1. Mata Pelajaran -->
                                    <div class="relative">
                                        <select name="subject" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-semibold text-slate-700 py-3 px-3.5 pr-8 appearance-none focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] cursor-pointer shadow-2xs">
                                            <option value="">Mata Pelajaran</option>
                                            @foreach($subjects as $subj)
                                                <option value="{{ $subj->slug }}">{{ $subj->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>

                                    <!-- 2. Jenjang -->
                                    <div class="relative">
                                        <select name="grade" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-semibold text-slate-700 py-3 px-3.5 pr-8 appearance-none focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] cursor-pointer shadow-2xs">
                                            <option value="">Jenjang</option>
                                            <option value="sd">SD (Kelas 1-6)</option>
                                            <option value="smp">SMP (Kelas 7-9)</option>
                                            <option value="sma">SMA (Kelas 10-12)</option>
                                            <option value="ujian">Persiapan UTBK</option>
                                        </select>
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>

                                    <!-- 3. Metode Les -->
                                    <div class="relative">
                                        <select name="method" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-semibold text-slate-700 py-3 px-3.5 pr-8 appearance-none focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] cursor-pointer shadow-2xs">
                                            <option value="">Metode Les (Online/Tatap Muka)</option>
                                            <option value="online">Online</option>
                                            <option value="tatap_muka">Tatap Muka</option>
                                        </select>
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Big Navy Search Button -->
                                <button type="submit" class="w-full bg-[#080F63] hover:bg-[#050A4F] text-white font-bold text-xs sm:text-sm py-3 px-6 rounded-xl shadow-md shadow-[#080F63]/20 flex items-center justify-center gap-2 transition duration-150 cursor-pointer">
                                    <span>Cari Guru Sekarang</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Hero Teacher Photo with Floating Badges (Desktop Only, hidden on Mobile) -->
                    <div class="hidden lg:flex lg:col-span-5 relative justify-center items-center">
                        <div class="relative w-full max-w-[340px] sm:max-w-[420px] h-[340px] sm:h-[460px] flex items-end justify-center">
                            <!-- Soft Sky Blue Backdrop Organic Curve (as seen in design) -->
                            <div class="absolute inset-x-3 sm:inset-x-4 top-3 sm:top-4 bottom-0 bg-gradient-to-b from-[#E0EDFF] via-[#EAF2FF] to-[#DBEAFE] rounded-[48px] sm:rounded-[64px] -z-10 shadow-xs"></div>

                            <!-- Teacher Photo Cutout / Standing Portrait -->
                            <div class="relative z-10 w-full h-[310px] sm:h-[430px] flex items-end justify-center overflow-hidden rounded-b-[48px] sm:rounded-b-[64px]">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80" 
                                     alt="Guru BelajarYuk" 
                                     class="w-full h-full object-cover object-top filter contrast-[1.03]">
                            </div>

                            <!-- Floating Badge 1: Document Icon (Top Left) -->
                            <div class="absolute top-6 left-0 sm:top-12 sm:-left-4 z-20 bg-white/95 backdrop-blur-md rounded-2xl p-2 sm:p-2.5 shadow-md border border-slate-100 flex items-center justify-center">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Floating Badge 2: Rating 4.9 ★ (Top Right) -->
                            <div class="absolute top-6 right-0 sm:top-8 sm:right-0 z-20 bg-white/95 backdrop-blur-md rounded-2xl py-1.5 px-3 sm:py-2 sm:px-3.5 shadow-md border border-slate-100 flex items-center gap-2">
                                <div class="text-left">
                                    <span class="text-[9px] text-[#64748B] font-bold block uppercase tracking-wider">Rating</span>
                                    <span class="text-xs font-black text-[#111827] flex items-center gap-1">
                                        4.9 <span class="text-amber-500 text-sm leading-none">★</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Floating Badge 3: Progres Belajar 75% (Bottom Right) -->
                            <div class="absolute bottom-6 right-0 sm:bottom-10 sm:-right-4 z-20 bg-white/95 backdrop-blur-md rounded-2xl p-2.5 sm:p-3 shadow-md border border-slate-100 min-w-[130px] sm:min-w-[145px] space-y-1 sm:space-y-1.5">
                                <div class="flex items-center justify-between text-[10px] sm:text-[11px] font-bold text-[#111827]">
                                    <span>Progres Belajar</span>
                                    <span class="text-[#080F63]">75%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-[#080F63] h-full rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Trust Stats Banner (Strip Card) -->
                <div class="mt-6 sm:mt-12 bg-white rounded-2xl border border-[#E5E8F0] p-3 sm:p-5 shadow-xs">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-0 divide-y-0 sm:divide-y sm:divide-y-0 md:divide-x divide-slate-100 text-center">
                        <div class="p-2 sm:p-3 rounded-xl sm:rounded-none bg-slate-50/60 sm:bg-transparent flex items-center justify-start sm:justify-center gap-2.5 sm:gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <span class="text-xs sm:text-base font-extrabold text-[#111827] block leading-tight">1.000+</span>
                                <span class="text-[11px] sm:text-xs text-[#64748B] font-medium leading-tight">Guru Terverifikasi</span>
                            </div>
                        </div>

                        <div class="p-2 sm:p-3 rounded-xl sm:rounded-none bg-slate-50/60 sm:bg-transparent flex items-center justify-start sm:justify-center gap-2.5 sm:gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <span class="text-xs sm:text-base font-extrabold text-[#111827] block leading-tight">4.9 ★</span>
                                <span class="text-[11px] sm:text-xs text-[#64748B] font-medium leading-tight">Rating Rata-Rata</span>
                            </div>
                        </div>

                        <div class="p-2 sm:p-3 rounded-xl sm:rounded-none bg-slate-50/60 sm:bg-transparent flex items-center justify-start sm:justify-center gap-2.5 sm:gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <span class="text-xs sm:text-base font-extrabold text-[#111827] block leading-tight">Fleksibel</span>
                                <span class="text-[11px] sm:text-xs text-[#64748B] font-medium leading-tight">Online & Tatap Muka</span>
                            </div>
                        </div>

                        <!-- Stat 4: Pantau Progres Real-Time -->
                        <div class="p-2 sm:p-3 rounded-xl sm:rounded-none bg-slate-50/60 sm:bg-transparent flex items-center justify-start sm:justify-center gap-2.5 sm:gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <span class="text-xs sm:text-base font-extrabold text-[#111827] block leading-tight">Pantau Progres</span>
                                <span class="text-[11px] sm:text-xs text-[#64748B] font-medium leading-tight">Real-Time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
             PANEL 2: REKOMENDASI GURU TERBAIK (FULL-WIDTH 3-COLUMN CARDS)
             ========================================================================== -->
        <section id="rekomendasi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ activeGrade: 'all' }">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                        Rekomendasi Guru Terbaik
                    </h2>
                    <p class="text-xs sm:text-sm text-[#64748B] mt-0.5">
                        Pilihan guru les privat berprestasi dan terverifikasi yang siap membimbing belajar anak
                    </p>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-3 overflow-x-auto no-scrollbar py-1">
                    <!-- Filter Pills -->
                    <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-full border border-slate-200/80 shrink-0">
                        <button type="button" 
                                @click="activeGrade = 'all'"
                                :class="activeGrade === 'all' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-[#080F63] font-medium'"
                                class="px-3.5 py-1 rounded-full text-xs transition cursor-pointer">
                            Semua
                        </button>
                        <button type="button" 
                                @click="activeGrade = 'sd'"
                                :class="activeGrade === 'sd' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-[#080F63] font-medium'"
                                class="px-3.5 py-1 rounded-full text-xs transition cursor-pointer">
                            SD
                        </button>
                        <button type="button" 
                                @click="activeGrade = 'smp'"
                                :class="activeGrade === 'smp' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-[#080F63] font-medium'"
                                class="px-3.5 py-1 rounded-full text-xs transition cursor-pointer">
                            SMP
                        </button>
                        <button type="button" 
                                @click="activeGrade = 'sma'"
                                :class="activeGrade === 'sma' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-[#080F63] font-medium'"
                                class="px-3.5 py-1 rounded-full text-xs transition cursor-pointer">
                            SMA
                        </button>
                    </div>

                    <!-- Direct link to Full Catalogue -->
                    <a href="{{ route('teachers.search') }}" class="text-xs sm:text-sm font-bold text-[#080F63] hover:text-[#050A4F] inline-flex items-center gap-1 group shrink-0">
                        <span>Lihat Semua Guru</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Full-Width 3-Column Teacher Cards (Symmetrical, spacious grid) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendedTeachers->take(6) as $teacher)
                    <div class="bg-white rounded-2xl border border-[#E5E8F0] p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:-translate-y-1 transition duration-200 relative group">
                        <!-- Top Avatar, Verified Badge, & Details -->
                        <div>
                            <!-- Header Avatar + Status Ring -->
                            <div class="flex flex-col items-center text-center mb-4">
                                <div class="relative mb-3">
                                    <img class="w-20 h-20 rounded-full object-cover ring-4 ring-[#080F63]/10 shadow-sm" 
                                         src="{{ $teacher->user->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80' }}" 
                                         alt="{{ $teacher->user->name }}">
                                    @if($teacher->is_verified)
                                        <span class="absolute bottom-0 right-0 w-5 h-5 bg-blue-600 rounded-full ring-2 ring-white flex items-center justify-center text-white text-[10px]" title="Guru Terverifikasi">
                                            ✓
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <h3 class="font-bold text-base text-[#111827] group-hover:text-[#080F63] transition-colors">
                                        {{ $teacher->user->name }}
                                    </h3>
                                    
                                    <!-- Primary Subject Chip -->
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#080F63] text-[11px] font-bold">
                                            {{ $teacher->subjects->first()->name ?? 'Bimbingan Belajar' }}
                                        </span>
                                        @if($teacher->service_city)
                                            <span class="text-[11px] text-[#64748B]">📍 {{ $teacher->service_city }}</span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-[#64748B] line-clamp-1 pt-0.5">
                                        {{ $teacher->education ?? 'Pendidikan Guru Profesional' }}
                                    </p>

                                    <!-- Rating & Reviews -->
                                    <div class="flex items-center justify-center gap-1 text-xs pt-1">
                                        <span class="text-amber-500 font-bold">★ {{ number_format($teacher->rating_cache, 1) }}</span>
                                        <span class="text-slate-400">({{ $teacher->reviews_count_cache }} ulasan)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Hourly Rate Display -->
                            <div class="text-center py-2 mb-3 bg-slate-50/70 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 block leading-tight">Tarif Bimbingan</span>
                                <span class="text-base sm:text-lg font-black text-[#080F63]">
                                    Rp {{ number_format($teacher->hourly_rate, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500">/jam</span>
                                </span>
                            </div>

                            <!-- Method Pills -->
                            <div class="flex items-center justify-center gap-2 mb-5">
                                <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ● Online
                                </span>
                                <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    ● Tatap Muka
                                </span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div>
                            <a href="{{ route('teachers.show', $teacher) }}" 
                               class="w-full bg-[#080F63] hover:bg-[#050A4F] text-white text-xs sm:text-sm py-2.5 px-4 rounded-xl text-center font-bold shadow-md shadow-[#080F63]/20 hover:shadow-lg transition block">
                                Lihat Profil Guru &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- ==========================================================================
             PANEL 3: 4 LANGKAH MUDAH PESAN GURU (WITH CONNECTING FLOW & SVGS)
             ========================================================================== -->
        <section id="cara-kerja" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="text-center max-w-xl mx-auto">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                    4 Langkah Mudah Pesan Guru
                </h2>
                <p class="text-xs sm:text-sm text-[#64748B] mt-1">
                    Alur pemesanan les privat yang praktis, aman, dan tanpa repot
                </p>
            </div>

            <!-- Clean 4-Step Card with Arrow Connectors -->
            <div class="bg-white rounded-3xl border border-[#E5E8F0] p-6 sm:p-10 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative items-start">
                    
                    <!-- Step 1: Pilih Guru -->
                    <div class="text-center space-y-3 relative flex flex-col items-center">
                        <!-- Step 1 SVG Illustration -->
                        <div class="w-24 h-24 flex items-center justify-center relative">
                            <svg class="w-20 h-20" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="40" r="36" fill="#EFF6FF" />
                                <!-- Female Student Figure with Tablet -->
                                <circle cx="34" cy="28" r="9" fill="#080F63" />
                                <path d="M22 52C22 43.1634 27.1634 39 34 39C40.8366 39 46 43.1634 46 52V54H22V52Z" fill="#1E40AF" />
                                <!-- Tablet Device -->
                                <rect x="36" y="38" width="18" height="22" rx="3" fill="#FFFFFF" stroke="#080F63" stroke-width="2" />
                                <circle cx="45" cy="44" r="3" fill="#38BDF8" />
                                <rect x="40" y="50" width="10" height="2" rx="1" fill="#94A3B8" />
                                <rect x="42" y="54" width="6" height="2" rx="1" fill="#94A3B8" />
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">1. Pilih Guru</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed max-w-[220px]">
                            Cari & bandingkan profil, kualifikasi, serta tarif pengajar.
                        </p>
                        <!-- Connector Arrow Desktop -->
                        <div class="hidden lg:block absolute -right-4 top-12 text-slate-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </div>
                    </div>

                    <!-- Step 2: Atur Jadwal -->
                    <div class="text-center space-y-3 relative flex flex-col items-center">
                        <!-- Step 2 SVG Illustration -->
                        <div class="w-24 h-24 flex items-center justify-center relative">
                            <svg class="w-20 h-20" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="40" r="36" fill="#EFF6FF" />
                                <!-- Calendar Card -->
                                <rect x="22" y="24" width="36" height="34" rx="6" fill="#FFFFFF" stroke="#080F63" stroke-width="2" />
                                <path d="M22 34H58" stroke="#080F63" stroke-width="2" />
                                <rect x="28" y="20" width="3" height="6" rx="1.5" fill="#080F63" />
                                <rect x="49" y="20" width="3" height="6" rx="1.5" fill="#080F63" />
                                <!-- Calendar Grid Dots -->
                                <circle cx="30" cy="41" r="2" fill="#38BDF8" />
                                <circle cx="40" cy="41" r="2" fill="#38BDF8" />
                                <circle cx="50" cy="41" r="2" fill="#38BDF8" />
                                <circle cx="30" cy="49" r="2" fill="#38BDF8" />
                                <!-- Clock Badge -->
                                <circle cx="48" cy="48" r="10" fill="#080F63" stroke="#FFFFFF" stroke-width="2" />
                                <path d="M48 43V48L51 51" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">2. Atur Jadwal</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed max-w-[220px]">
                            Pilih hari dan jam les yang paling cocok untuk anak.
                        </p>
                        <!-- Connector Arrow Desktop -->
                        <div class="hidden lg:block absolute -right-4 top-12 text-slate-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </div>
                    </div>

                    <!-- Step 3: Pembayaran Aman -->
                    <div class="text-center space-y-3 relative flex flex-col items-center">
                        <!-- Step 3 SVG Illustration -->
                        <div class="w-24 h-24 flex items-center justify-center relative">
                            <svg class="w-20 h-20" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="40" r="36" fill="#EFF6FF" />
                                <!-- Credit Card -->
                                <rect x="20" y="26" width="40" height="28" rx="5" fill="#38BDF8" stroke="#080F63" stroke-width="2" />
                                <path d="M20 34H60" stroke="#080F63" stroke-width="2" />
                                <rect x="25" y="42" width="6" height="4" rx="1" fill="#FFFFFF" />
                                <!-- Golden Padlock Badge -->
                                <rect x="44" y="42" width="14" height="12" rx="2.5" fill="#F59E0B" stroke="#080F63" stroke-width="1.8" />
                                <path d="M47 42V39C47 36.7909 48.7909 35 51 35C53.2091 35 55 36.7909 55 39V42" stroke="#080F63" stroke-width="1.8" stroke-linecap="round" />
                                <circle cx="51" cy="48" r="1.5" fill="#FFFFFF" />
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">3. Pembayaran Aman</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed max-w-[220px]">
                            Bayar mudah dengan berbagai pilihan metode pembayaran.
                        </p>
                        <!-- Connector Arrow Desktop -->
                        <div class="hidden lg:block absolute -right-4 top-12 text-slate-300">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </div>
                    </div>

                    <!-- Step 4: Pantau Progres -->
                    <div class="text-center space-y-3 relative flex flex-col items-center">
                        <!-- Step 4 SVG Illustration -->
                        <div class="w-24 h-24 flex items-center justify-center relative">
                            <svg class="w-20 h-20" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="40" cy="40" r="36" fill="#EFF6FF" />
                                <!-- Laptop Display -->
                                <rect x="22" y="24" width="36" height="24" rx="3" fill="#FFFFFF" stroke="#080F63" stroke-width="2" />
                                <path d="M18 48H62C63.1046 48 64 48.8954 64 50V51C64 51.5523 63.5523 52 63 52H17C16.4477 52 16 51.5523 16 51V50C16 48.8954 16.8954 48 18 48Z" fill="#080F63" />
                                <!-- Rising Chart inside Screen -->
                                <rect x="27" y="38" width="4" height="6" rx="1" fill="#38BDF8" />
                                <rect x="33" y="34" width="4" height="10" rx="1" fill="#38BDF8" />
                                <rect x="39" y="30" width="4" height="14" rx="1" fill="#080F63" />
                                <rect x="45" y="27" width="4" height="17" rx="1" fill="#10B981" />
                                <!-- Circular Check Badge -->
                                <circle cx="26" cy="46" r="8" fill="#10B981" stroke="#FFFFFF" stroke-width="2" />
                                <path d="M23 46L25 48L29 44" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">4. Pantau Progres</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed max-w-[220px]">
                            Ikuti sesi belajar dan lihat laporan perkembangan anak langsung dari dashboard.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- ==========================================================================
             PANEL 4: KENAPA BELAJARYUK? & CALL TO ACTION BANNER
             ========================================================================== -->
        <section id="keunggulan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-xl mx-auto">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                    Kenapa BelajarYuk?
                </h2>
                <p class="text-xs sm:text-sm text-[#64748B] mt-1">
                    Solusi bimbingan belajar privat terlengkap yang dirancang untuk kenyamanan keluarga
                </p>
            </div>

            <!-- Two Columns: Left Dashboard/Mobile Mockup Preview + Right 3 Key Features -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <!-- Left Mockup Preview (6 cols) -->
                <div class="lg:col-span-6 relative flex justify-center">
                    <div class="relative w-full max-w-lg bg-slate-100/80 rounded-3xl p-3 sm:p-5 border border-slate-200/90 shadow-soft">
                        <!-- Desktop Dashboard Mockup Window -->
                        <div class="rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-white">
                            <!-- Window Top Bar -->
                            <div class="h-6 bg-[#050A4F] flex items-center px-3 gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span class="text-[9px] text-slate-300 ml-2 font-mono">belajaryuk.id/parent/dashboard</span>
                            </div>
                            
                            <!-- Internal Dashboard Grid Preview -->
                            <div class="grid grid-cols-12 bg-[#F3F5FA] min-h-[220px]">
                                <!-- Left Mini Sidebar -->
                                <div class="col-span-3 bg-[#050A4F] p-2 space-y-2 text-white">
                                    <div class="h-4 w-12 bg-white/20 rounded"></div>
                                    <div class="space-y-1 pt-1">
                                        <div class="h-3 w-full bg-white/30 rounded-md"></div>
                                        <div class="h-3 w-4/5 bg-white/10 rounded-md"></div>
                                        <div class="h-3 w-3/4 bg-white/10 rounded-md"></div>
                                        <div class="h-3 w-4/5 bg-white/10 rounded-md"></div>
                                    </div>
                                </div>
                                
                                <!-- Right Main Content Preview -->
                                <div class="col-span-9 p-3 space-y-2">
                                    <!-- Greeting Bar -->
                                    <div class="bg-white rounded-xl p-2.5 border border-slate-200 flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] font-extrabold text-[#111827] block">Halo, Andi Pratama 👋</span>
                                            <span class="text-[8px] text-slate-400">Pantau perkembangan anak Anda hari ini</span>
                                        </div>
                                        <span class="bg-emerald-50 text-emerald-600 text-[8px] font-bold px-1.5 py-0.5 rounded-full">Aktif</span>
                                    </div>
                                    
                                    <!-- Lesson Preview Cards -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="bg-white rounded-xl p-2 border border-slate-200 space-y-1">
                                            <span class="text-[8px] text-slate-400 font-bold block">Matematika SD</span>
                                            <span class="text-[10px] font-extrabold text-[#080F63] block">Siti Nurhaliza</span>
                                            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                                                <div class="bg-[#080F63] h-full" style="width: 75%"></div>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-xl p-2 border border-slate-200 space-y-1">
                                            <span class="text-[8px] text-slate-400 font-bold block">Jadwal Les Berikutnya</span>
                                            <span class="text-[10px] font-extrabold text-emerald-600 block">Kamis, 15:30 WIB</span>
                                            <span class="text-[8px] text-slate-500 block">Tatap Muka di Rumah</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Device Floating Mockup (Overlapping right) -->
                        <div class="absolute -bottom-5 -right-2 sm:-right-4 w-36 sm:w-44 rounded-2xl bg-white p-2 border-2 border-slate-800 shadow-xl z-20">
                            <div class="h-1.5 w-10 bg-slate-800 rounded-full mx-auto mb-2"></div>
                            <div class="space-y-1.5 text-[9px]">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-[#080F63] font-bold flex items-center justify-between">
                                    <span>BelajarYuk App</span>
                                    <span class="text-[7px]">⚡</span>
                                </div>
                                <div class="p-1.5 rounded-lg bg-slate-50 border border-slate-100">
                                    <span class="text-[8px] text-slate-400 block">Les Hari Ini</span>
                                    <span class="font-bold text-slate-800">16:00 · Fisika SMA</span>
                                </div>
                                <div class="h-6 rounded-lg bg-[#080F63] text-white flex items-center justify-center font-bold text-[8px]">
                                    Pesan Guru Baru
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right 3 Value Propositions (6 cols) -->
                <div class="lg:col-span-6 space-y-3.5">
                    <!-- Feature 1: Satu Akun untuk Semua Anak -->
                    <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E8F0] shadow-2xs hover:shadow-xs transition">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">Satu Akun untuk Semua Anak</h3>
                            <p class="text-xs text-[#64748B] leading-relaxed">
                                Pilih dari ribuan guru berpengalaman di berbagai pelajaran dan jenjang, cukup untuk 1 akun orang tua.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2: Pantau Jadwal Anti-Bentrok -->
                    <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E8F0] shadow-2xs hover:shadow-xs transition">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">Pantau Jadwal Anti-Bentrok</h3>
                            <p class="text-xs text-[#64748B] leading-relaxed">
                                Pantau jadwal les anak secara real-time, bebas bentrok untuk les online maupun tatap muka.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3: Laporan Evaluasi dari Guru -->
                    <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E8F0] shadow-2xs hover:shadow-xs transition">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-sm sm:text-base font-extrabold text-[#111827]">Laporan Evaluasi dari Guru</h3>
                            <p class="text-xs text-[#64748B] leading-relaxed">
                                Laporan evaluasi dari guru memberikan saran dan catatan perkembangan akademik anak secara berkala.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Call to Action Banner Card -->
            <div class="rounded-[28px] bg-[#050A4F] p-8 sm:p-12 text-white relative overflow-hidden shadow-xl">
                <!-- Ambient Subtle Glow -->
                <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center relative z-10">
                    <!-- Left CTA Text & Button (8 cols) -->
                    <div class="md:col-span-8 space-y-4">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                            Mulai Petualangan Belajar<br class="hidden sm:inline">
                            Anak Anda Hari Ini!
                        </h2>
                        <p class="text-xs sm:text-sm text-blue-100/90 max-w-lg leading-relaxed">
                            Daftarkan anak Anda sekarang dan temukan guru les privat terbaik dengan metode belajar yang menyenangkan.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('register') }}" class="bg-white hover:bg-slate-100 text-[#050A4F] font-extrabold text-xs sm:text-sm py-3 px-8 rounded-lg shadow-md transition inline-flex items-center gap-2 cursor-pointer">
                                <span>Daftar Sekarang</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right App Badges (4 cols) -->
                    <div class="md:col-span-4 flex flex-col items-center md:items-end gap-3 text-center md:text-right">
                        <div class="flex items-center gap-2.5">
                            <!-- App Store Badge -->
                            <div class="px-4 py-2 rounded-xl bg-black/80 hover:bg-black border border-white/20 text-white flex items-center gap-2.5 transition cursor-pointer">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.89c.65-.8 1.1-1.92.97-3.04-1 .04-2.17.67-2.85 1.47-.6.69-1.12 1.83-.98 2.93 1.11.09 2.22-.56 2.86-1.36z"/>
                                </svg>
                                <div class="text-left">
                                    <span class="text-[8px] uppercase tracking-wider block leading-tight text-slate-300">Download on</span>
                                    <span class="text-xs font-bold leading-none">App Store</span>
                                </div>
                            </div>

                            <!-- Google Play Badge -->
                            <div class="px-4 py-2 rounded-xl bg-black/80 hover:bg-black border border-white/20 text-white flex items-center gap-2.5 transition cursor-pointer">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M3.609 1.814L13.793 12 3.61 22.186c-.365-.365-.61-.88-.61-1.464V3.278c0-.584.245-1.099.61-1.464zM15.207 13.414l2.48 2.48-11.83 6.83 9.35-9.31zm0-2.828L5.857 1.276l11.83 6.83-2.48 2.48zm1.414 1.414l2.76-1.594c.82-.473.82-1.247 0-1.72l-2.76-1.594-1.414 1.414 1.414 1.414z"/>
                                </svg>
                                <div class="text-left">
                                    <span class="text-[8px] uppercase tracking-wider block leading-tight text-slate-300">GET IT ON</span>
                                    <span class="text-xs font-bold leading-none">Google Play</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-[11px] text-blue-200/80">Akses nyaman dari smartphone & laptop</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
