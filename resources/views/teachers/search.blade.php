@php
    $isDashboard = $isDashboard ?? false;
    $layoutComponent = $isDashboard ? 'layouts.dashboard' : 'layouts.app';
    $searchAction = $isDashboard && Route::has('parent.teachers.search') ? route('parent.teachers.search') : route('teachers.search');
    $showTeacherRoute = fn($t) => $isDashboard && Route::has('parent.teachers.show') ? route('parent.teachers.show', $t) : route('teachers.show', $t);
@endphp

<x-dynamic-component :component="$layoutComponent" title="Cari & Temukan Guru Les Terbaik — Belajar Dong">
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

        <!-- 5 Dropdown Filter Bar + Sort -->
        <form action="{{ $searchAction }}" method="GET" class="bg-white rounded-2xl border border-[#E5E8F0] p-4 sm:p-5 shadow-xs space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <!-- Filter Left: 5 Dropdown Buttons -->
                <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[280px]">
                    <!-- 1. Mata Pelajaran -->
                    <div class="relative">
                        <select name="subject" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs sm:text-sm py-2 px-3.5 pr-8 appearance-none bg-white cursor-pointer focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63]">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->slug }}" {{ request('subject') == $subj->slug ? 'selected' : '' }}>
                                    {{ $subj->name }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 2. Jenjang -->
                    <div class="relative">
                        <select name="grade" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs sm:text-sm py-2 px-3.5 pr-8 appearance-none bg-white cursor-pointer focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63]">
                            <option value="">Semua Jenjang</option>
                            <option value="sd" {{ request('grade') == 'sd' ? 'selected' : '' }}>SD (Kelas 1-6)</option>
                            <option value="smp" {{ request('grade') == 'smp' ? 'selected' : '' }}>SMP (Kelas 7-9)</option>
                            <option value="sma" {{ request('grade') == 'sma' ? 'selected' : '' }}>SMA (Kelas 10-12)</option>
                            <option value="ujian" {{ request('grade') == 'ujian' ? 'selected' : '' }}>Persiapan UTBK</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 3. Harga -->
                    <div class="relative">
                        <select name="max_price" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs sm:text-sm py-2 px-3.5 pr-8 appearance-none bg-white cursor-pointer focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63]">
                            <option value="">Semua Harga</option>
                            <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>&le; Rp 100.000/jam</option>
                            <option value="150000" {{ request('max_price') == '150000' ? 'selected' : '' }}>&le; Rp 150.000/jam</option>
                            <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>&le; Rp 200.000/jam</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 4. Rating -->
                    <div class="relative">
                        <select name="rating" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs sm:text-sm py-2 px-3.5 pr-8 appearance-none bg-white cursor-pointer focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63]">
                            <option value="">Semua Rating</option>
                            <option value="4.8" {{ request('rating') == '4.8' ? 'selected' : '' }}>★ 4.8 Ke Atas</option>
                            <option value="4.5" {{ request('rating') == '4.5' ? 'selected' : '' }}>★ 4.5 Ke Atas</option>
                            <option value="4.0" {{ request('rating') == '4.0' ? 'selected' : '' }}>★ 4.0 Ke Atas</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    <!-- 5. Metode Les -->
                    <div class="relative">
                        <select name="method" onchange="this.form.submit()" 
                                class="btn-white-pill text-xs sm:text-sm py-2 px-3.5 pr-8 appearance-none bg-white cursor-pointer focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63]">
                            <option value="">Semua Metode</option>
                            <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="tatap_muka" {{ request('method') == 'tatap_muka' ? 'selected' : '' }}>Tatap Muka</option>
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>

                    @if(request()->hasAny(['subject', 'grade', 'max_price', 'rating', 'method', 'q', 'sort']))
                        <a href="{{ $searchAction }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline px-2 flex items-center gap-1">
                            <span>✕</span>
                            <span>Reset Filter</span>
                        </a>
                    @endif
                </div>

                <!-- Filter Right: Sort by Relevansi -->
                <div class="relative shrink-0">
                    <select name="sort" onchange="this.form.submit()" 
                            class="btn-white-pill text-xs sm:text-sm py-2 px-3.5 pr-8 appearance-none bg-slate-50 cursor-pointer border-[#E5E8F0]">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Urutkan: Relevansi</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                    <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </form>

        <!-- 3-Column Grid of Tutor Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($teachers as $teacher)
                <div class="bg-white rounded-2xl border border-[#E5E8F0] p-6 flex flex-col justify-between shadow-xs hover:shadow-lg hover:-translate-y-1 transition duration-200 relative group">
                    <div>
                        <!-- Top Avatar & Name Info -->
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

                                <!-- Star Rating & Review Count -->
                                <div class="flex items-center justify-center gap-1.5 mt-1 text-xs">
                                    <span class="text-amber-500 font-bold">★ {{ number_format($teacher->rating_cache, 1) }}</span>
                                    <span class="text-slate-400">({{ $teacher->reviews_count_cache }} ulasan)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Price Tag -->
                        <div class="text-center py-2 mb-3 bg-slate-50/70 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 block leading-tight">Tarif Bimbingan</span>
                            <span class="text-base sm:text-lg font-black text-[#080F63]">
                                Rp {{ number_format($teacher->hourly_rate, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500">/jam</span>
                            </span>
                        </div>

                        <!-- Method Tags: Online & Tatap Muka -->
                        <div class="flex items-center justify-center gap-2 mb-5">
                            <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ● Online
                            </span>
                            <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                ● Tatap Muka
                            </span>
                        </div>
                    </div>

                    <!-- Full-Width "Lihat Profil" Button -->
                    <div>
                        <a href="{{ $showTeacherRoute($teacher) }}" 
                           class="w-full bg-[#080F63] hover:bg-[#050A4F] text-white text-xs sm:text-sm py-2.5 px-4 rounded-xl text-center font-bold shadow-md shadow-[#080F63]/20 hover:shadow-lg transition block">
                            Lihat Profil Guru &rarr;
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
