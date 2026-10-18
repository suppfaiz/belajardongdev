@php
    $isDashboard = $isDashboard ?? false;
    $layoutComponent = $isDashboard ? 'layouts.dashboard' : 'layouts.app';
    $searchAction = $isDashboard && Route::has('parent.teachers.search') ? route('parent.teachers.search') : route('teachers.search');
    $showTeacherRoute = fn($t) => $isDashboard && Route::has('parent.teachers.show') ? route('parent.teachers.show', $t) : route('teachers.show', $t);
@endphp

<x-dynamic-component :component="$layoutComponent" title="Cari & Temukan Guru Les Terbaik — BelajarYuk">
    <div class="{{ $isDashboard ? 'space-y-6 max-w-7xl mx-auto' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8' }}">
        <!-- Header Page Title & Subtitle -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                    Temukan Guru Les Terbaik
                </h1>
                <p class="text-xs sm:text-sm text-[#64748B] mt-1">
                    Pilih guru les privat terverifikasi di berbagai mata pelajaran dan jenjang, tersedia Online & Tatap Muka.
                </p>
            </div>
            
            <div class="text-xs text-slate-500 font-semibold bg-white border border-[#E5E8F0] px-4 py-2 rounded-full shadow-2xs">
                Ditemukan <span class="text-[#080F63] font-bold">{{ $teachers->total() }}</span> guru terverifikasi
            </div>
        </div>

        <!-- Search Keyword & Horizontal Mobile Filter Chips -->
        <form action="{{ $searchAction }}" method="GET" class="bg-white rounded-2xl border border-[#E5E8F0] p-3.5 sm:p-5 shadow-xs space-y-3">
            <!-- Search Keyword Bar -->
            <div class="relative w-full">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Cari nama guru, mata pelajaran, atau topik belajar..." 
                       class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-full pl-11 pr-24 py-2.5 text-xs sm:text-sm text-[#171923] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] transition shadow-2xs">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-[#080F63] hover:bg-[#050A4F] text-white text-xs font-bold rounded-full transition flex items-center gap-1 cursor-pointer">
                    <span>Cari</span>
                </button>
            </div>

            <!-- Horizontal Swipeable Filter Chips on Mobile / Flex-wrap on Desktop -->
            <div class="flex items-center justify-between gap-2.5 overflow-x-auto no-scrollbar py-1">
                <div class="flex items-center gap-2 shrink-0 md:flex-wrap">
                    <!-- 1. Mata Pelajaran -->
                    <div class="relative shrink-0">
                        <select name="subject" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none cursor-pointer shrink-0 transition {{ request('subject') ? 'border-[#080F63] bg-blue-50/80 font-bold text-[#080F63] ring-1 ring-[#080F63]/30' : 'bg-white' }}">
                            <option value="">Semua Mapel</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->slug }}" {{ request('subject') == $subj->slug ? 'selected' : '' }}>
                                    {{ $subj->name }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none {{ request('subject') ? 'text-[#080F63]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 2. Jenjang -->
                    <div class="relative shrink-0">
                        <select name="grade" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none cursor-pointer shrink-0 transition {{ request('grade') ? 'border-[#080F63] bg-blue-50/80 font-bold text-[#080F63] ring-1 ring-[#080F63]/30' : 'bg-white' }}">
                            <option value="">Semua Jenjang</option>
                            <option value="sd" {{ request('grade') == 'sd' ? 'selected' : '' }}>SD (Kelas 1-6)</option>
                            <option value="smp" {{ request('grade') == 'smp' ? 'selected' : '' }}>SMP (Kelas 7-9)</option>
                            <option value="sma" {{ request('grade') == 'sma' ? 'selected' : '' }}>SMA (Kelas 10-12)</option>
                            <option value="ujian" {{ request('grade') == 'ujian' ? 'selected' : '' }}>Persiapan UTBK</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none {{ request('grade') ? 'text-[#080F63]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 3. Harga -->
                    <div class="relative shrink-0">
                        <select name="max_price" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none cursor-pointer shrink-0 transition {{ request('max_price') ? 'border-[#080F63] bg-blue-50/80 font-bold text-[#080F63] ring-1 ring-[#080F63]/30' : 'bg-white' }}">
                            <option value="">Semua Harga</option>
                            <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>&le; 100rb/jam</option>
                            <option value="150000" {{ request('max_price') == '150000' ? 'selected' : '' }}>&le; 150rb/jam</option>
                            <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>&le; 200rb/jam</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none {{ request('max_price') ? 'text-[#080F63]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 4. Rating -->
                    <div class="relative shrink-0">
                        <select name="rating" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none cursor-pointer shrink-0 transition {{ request('rating') ? 'border-[#080F63] bg-blue-50/80 font-bold text-[#080F63] ring-1 ring-[#080F63]/30' : 'bg-white' }}">
                            <option value="">Semua Rating</option>
                            <option value="4.8" {{ request('rating') == '4.8' ? 'selected' : '' }}>★ 4.8+</option>
                            <option value="4.5" {{ request('rating') == '4.5' ? 'selected' : '' }}>★ 4.5+</option>
                            <option value="4.0" {{ request('rating') == '4.0' ? 'selected' : '' }}>★ 4.0+</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none {{ request('rating') ? 'text-[#080F63]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 5. Metode Les -->
                    <div class="relative shrink-0">
                        <select name="method" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none cursor-pointer shrink-0 transition {{ request('method') ? 'border-[#080F63] bg-blue-50/80 font-bold text-[#080F63] ring-1 ring-[#080F63]/30' : 'bg-white' }}">
                            <option value="">Semua Metode</option>
                            <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="tatap_muka" {{ request('method') == 'tatap_muka' ? 'selected' : '' }}>Tatap Muka</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none {{ request('method') ? 'text-[#080F63]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    @if(request()->hasAny(['subject', 'grade', 'max_price', 'rating', 'method', 'q', 'sort']))
                        <a href="{{ $searchAction }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline px-2.5 py-1.5 rounded-full bg-rose-50 border border-rose-200 flex items-center gap-1 shrink-0">
                            <span>✕</span>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>

                <!-- Filter Right: Sort by Relevansi -->
                <div class="relative shrink-0">
                    <select name="sort" onchange="this.form.submit()" 
                            class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none bg-slate-50 cursor-pointer border-[#E5E8F0] shrink-0 font-medium">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevansi</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                    <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </form>

        <!-- 2-Column Mobile Grid, 3-Column Desktop Grid of Tutor Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
            @forelse($teachers as $teacher)
                <div class="bg-white rounded-2xl border border-[#E5E8F0] p-3 sm:p-6 flex flex-col justify-between shadow-xs hover:shadow-lg hover:-translate-y-1 transition duration-200 relative group">
                    <div>
                        <!-- Top Avatar & Name Info -->
                        <div class="flex flex-col items-center text-center mb-2.5 sm:mb-4">
                            <div class="relative mb-2 sm:mb-3">
                                <img class="w-14 h-14 sm:w-20 sm:h-20 rounded-full object-cover ring-2 sm:ring-4 ring-[#080F63]/10 shadow-sm" 
                                     src="{{ $teacher->user->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80' }}" 
                                     alt="{{ $teacher->user->name }}">
                                @if($teacher->is_verified)
                                    <span class="absolute bottom-0 right-0 w-4 h-4 sm:w-5 sm:h-5 bg-blue-600 rounded-full ring-1.5 sm:ring-2 ring-white flex items-center justify-center text-white text-[8px] sm:text-[10px]" title="Guru Terverifikasi">
                                        ✓
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-1 w-full min-w-0">
                                <h3 class="font-bold text-xs sm:text-base text-[#111827] group-hover:text-[#080F63] transition-colors truncate">
                                    {{ $teacher->user->name }}
                                </h3>

                                <div class="flex items-center justify-center gap-1 sm:gap-1.5 flex-wrap">
                                    <span class="px-2 sm:px-2.5 py-0.5 rounded-full bg-blue-50 text-[#080F63] text-[9px] sm:text-[11px] font-bold truncate max-w-full">
                                        {{ $teacher->subjects->first()->name ?? 'Bimbingan Belajar' }}
                                    </span>
                                    @if($teacher->service_city)
                                        <span class="text-[9px] sm:text-[11px] text-[#64748B] truncate hidden sm:inline">📍 {{ $teacher->service_city }}</span>
                                    @endif
                                </div>

                                <p class="text-[10px] sm:text-xs text-[#64748B] line-clamp-1 pt-0.5">
                                    {{ $teacher->education ?? 'Pendidikan Guru Profesional' }}
                                </p>

                                <!-- Star Rating & Review Count -->
                                <div class="flex items-center justify-center gap-1 mt-0.5 sm:mt-1 text-[10px] sm:text-xs">
                                    <span class="text-amber-500 font-bold">★ {{ number_format($teacher->rating_cache, 1) }}</span>
                                    <span class="text-slate-400">({{ $teacher->reviews_count_cache }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Price Tag -->
                        <div class="text-center py-1.5 sm:py-2 mb-2 sm:mb-3 bg-slate-50/70 rounded-xl border border-slate-100">
                            <span class="text-[9px] sm:text-xs text-slate-500 block leading-tight">Tarif Bimbingan</span>
                            <span class="text-xs sm:text-lg font-black text-[#080F63]">
                                Rp {{ number_format($teacher->hourly_rate, 0, ',', '.') }}<span class="text-[9px] sm:text-xs font-normal text-slate-500">/jam</span>
                            </span>
                        </div>

                        <!-- Method Tags: Online & Tatap Muka -->
                        <div class="flex items-center justify-center gap-1 sm:gap-2 mb-3 sm:mb-5">
                            <span class="text-[8px] sm:text-[10px] font-semibold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ● Online
                            </span>
                            <span class="text-[8px] sm:text-[10px] font-semibold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                ● Tatap Muka
                            </span>
                        </div>
                    </div>

                    <!-- Full-Width "Lihat Profil" Button -->
                    <div>
                        <a href="{{ $showTeacherRoute($teacher) }}" 
                           class="w-full bg-[#080F63] hover:bg-[#050A4F] text-white text-[11px] sm:text-sm py-2 sm:py-2.5 px-2 sm:px-4 rounded-xl text-center font-bold shadow-xs hover:shadow-lg transition block">
                            <span class="sm:hidden">Lihat Profil &rarr;</span>
                            <span class="hidden sm:inline">Lihat Profil Guru &rarr;</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-[#E5E8F0] p-12 text-center text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-blue-50 text-[#080F63] flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-base font-bold text-[#111827]">Tidak ada guru yang cocok dengan kriteria pencarian.</p>
                    <p class="text-xs text-[#747B8C] mt-1">Coba sesuaikan mata pelajaran, rentang tarif, atau hapus filter untuk melihat semua pengajar.</p>
                    <a href="{{ $searchAction }}" class="btn-navy mt-4 inline-flex text-xs py-2 px-5">
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse
        </div>

        @if(method_exists($teachers, 'links'))
            <div class="pt-4 flex justify-center">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</x-dynamic-component>
