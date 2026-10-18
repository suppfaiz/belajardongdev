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

        @php
            $advancedFiltersCount = collect(['grade', 'max_price', 'rating', 'method'])->filter(fn($k) => request()->filled($k))->count() + (request('sort') && !in_array(request('sort'), ['relevance', 'distance']) ? 1 : 0);
        @endphp

        <!-- Search Keyword & Responsive Filter System -->
        <form id="searchFilterForm" 
              action="{{ $searchAction }}" 
              method="GET" 
              class="bg-white rounded-2xl border border-[#E5E8F0] p-3.5 sm:p-5 shadow-xs space-y-3"
              x-data="{ 
                  filterModalOpen: false,
                  mobileGrade: '{{ request('grade', '') }}',
                  mobilePrice: '{{ request('max_price', '') }}',
                  mobileRating: '{{ request('rating', '') }}',
                  mobileMethod: '{{ request('method', '') }}',
                  mobileSort: '{{ request('sort', 'relevance') }}',
                  applyFilters() {
                      document.getElementById('filter-grade').value = this.mobileGrade;
                      document.getElementById('filter-price').value = this.mobilePrice;
                      document.getElementById('filter-rating').value = this.mobileRating;
                      document.getElementById('filter-method').value = this.mobileMethod;
                      document.getElementById('filter-sort').value = this.mobileSort;
                      this.filterModalOpen = false;
                      document.getElementById('searchFilterForm').submit();
                  },
                  resetFilters() {
                      window.location.href = '{{ $searchAction }}';
                  }
              }">
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

            <!-- Mobile Toolbar (Phone Screens): Quick Subject + "Filter Lebih Lanjut" Button -->
            <div class="flex md:hidden items-center gap-2 pt-0.5">
                <!-- 1. Quick Mapel Picker -->
                <div class="relative flex-1 min-w-0">
                    <select onchange="document.getElementById('filter-subject').value = this.value; this.form.submit();"
                            class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-xs font-semibold text-[#080F63] rounded-full py-2.5 pl-3.5 pr-8 appearance-none truncate">
                        <option value="">Semua Mapel</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->slug }}" {{ request('subject') == $subj->slug ? 'selected' : '' }}>
                                {{ $subj->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- 2. "Filter Lebih Lanjut" Modal Trigger Button -->
                <button type="button" 
                        @click="filterModalOpen = true" 
                        class="flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-full border text-xs font-bold transition shrink-0 shadow-2xs {{ $advancedFiltersCount > 0 ? 'bg-[#080F63] text-white border-[#080F63]' : 'bg-white border-[#E2E8F0] text-[#080F63] hover:bg-slate-50' }}">
                    <svg class="w-3.5 h-3.5 {{ $advancedFiltersCount > 0 ? 'text-white' : 'text-[#080F63]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter</span>
                    @if($advancedFiltersCount > 0)
                        <span class="w-4 h-4 rounded-full bg-amber-400 text-slate-900 text-[10px] flex items-center justify-center font-black">
                            {{ $advancedFiltersCount }}
                        </span>
                    @endif
                </button>
            </div>

            <!-- Active Filter Badges (Easily clearable on mobile and desktop) -->
            @if(request()->hasAny(['subject', 'grade', 'max_price', 'rating', 'method', 'q', 'sort']))
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-1 pb-0.5">
                    <span class="text-[11px] font-semibold text-slate-400 shrink-0">Filter:</span>

                    @if(request('q'))
                        <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 border border-blue-200 text-[#080F63] px-2.5 py-1 rounded-full shrink-0">
                            <span>"{{ Str::limit(request('q'), 14) }}"</span>
                            <span class="hover:text-red-600 font-bold ml-0.5">✕</span>
                        </a>
                    @endif

                    @if(request('subject'))
                        @php $activeSubjName = $subjects->firstWhere('slug', request('subject'))?->name ?? request('subject'); @endphp
                        <a href="{{ request()->fullUrlWithQuery(['subject' => null]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 border border-blue-200 text-[#080F63] px-2.5 py-1 rounded-full shrink-0">
                            <span>Mapel: {{ $activeSubjName }}</span>
                            <span class="hover:text-red-600 font-bold ml-0.5">✕</span>
                        </a>
                    @endif

                    @if(request('grade'))
                        @php 
                            $gradeLabels = ['sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA', 'ujian' => 'UTBK'];
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery(['grade' => null]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 border border-blue-200 text-[#080F63] px-2.5 py-1 rounded-full shrink-0">
                            <span>Jenjang: {{ $gradeLabels[request('grade')] ?? request('grade') }}</span>
                            <span class="hover:text-red-600 font-bold ml-0.5">✕</span>
                        </a>
                    @endif

                    @if(request('max_price'))
                        <a href="{{ request()->fullUrlWithQuery(['max_price' => null]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 border border-blue-200 text-[#080F63] px-2.5 py-1 rounded-full shrink-0">
                            <span>≤ Rp {{ number_format(request('max_price'), 0, ',', '.') }}</span>
                            <span class="hover:text-red-600 font-bold ml-0.5">✕</span>
                        </a>
                    @endif

                    @if(request('rating'))
                        <a href="{{ request()->fullUrlWithQuery(['rating' => null]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 border border-blue-200 text-[#080F63] px-2.5 py-1 rounded-full shrink-0">
                            <span>★ {{ request('rating') }}+</span>
                            <span class="hover:text-red-600 font-bold ml-0.5">✕</span>
                        </a>
                    @endif

                    @if(request('method'))
                        <a href="{{ request()->fullUrlWithQuery(['method' => null]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 border border-blue-200 text-[#080F63] px-2.5 py-1 rounded-full shrink-0">
                            <span>{{ request('method') === 'online' ? 'Online' : 'Tatap Muka' }}</span>
                            <span class="hover:text-red-600 font-bold ml-0.5">✕</span>
                        </a>
                    @endif

                    <a href="{{ $searchAction }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-600 hover:text-rose-800 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-full shrink-0">
                        <span>Reset Semua</span>
                    </a>
                </div>
            @endif

            <!-- Desktop Full Filter Bar (Visible on md and above) -->
            <div class="hidden md:flex items-center justify-between gap-2.5 overflow-x-auto no-scrollbar py-1">
                <div class="flex items-center gap-2 shrink-0 flex-wrap">
                    <!-- 1. Mata Pelajaran -->
                    <div class="relative shrink-0">
                        <select name="subject" id="filter-subject" onchange="this.form.submit()" 
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
                        <select name="grade" id="filter-grade" onchange="this.form.submit()" 
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
                        <select name="max_price" id="filter-price" onchange="this.form.submit()" 
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
                        <select name="rating" id="filter-rating" onchange="this.form.submit()" 
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
                        <select name="method" id="filter-method" onchange="this.form.submit()" 
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
                    <select name="sort" id="filter-sort" onchange="this.form.submit()" 
                            class="btn-white-pill text-xs py-2 px-3 pr-7 appearance-none bg-slate-50 cursor-pointer border-[#E5E8F0] shrink-0 font-medium">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevansi</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                    <svg class="w-3.5 h-3.5 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>

            <!-- Mobile "Filter Lebih Lanjut" Bottom Sheet Modal -->
            <div x-show="filterModalOpen" 
                 class="fixed inset-0 z-50 md:hidden flex flex-col justify-end" 
                 style="display: none;" 
                 role="dialog" 
                 aria-modal="true">
                <!-- Backdrop Overlay -->
                <div x-show="filterModalOpen"
                     x-transition:enter="transition-opacity ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="filterModalOpen = false"
                     class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"></div>

                <!-- Modal Panel (Bottom Sheet) -->
                <div x-show="filterModalOpen"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-y-0"
                     x-transition:leave-end="translate-y-full"
                     class="relative bg-white rounded-t-3xl shadow-2xl max-h-[85vh] flex flex-col z-10 overflow-hidden">
                    
                    <!-- Pull Indicator & Header -->
                    <div class="px-5 pt-3 pb-3 border-b border-[#E5E8F0] shrink-0">
                        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-3"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-extrabold text-[#111827]">Filter & Urutkan Guru</h2>
                                <p class="text-xs text-slate-500">Sesuaikan kriteria pencarian les privat</p>
                            </div>
                            <button type="button" @click="filterModalOpen = false" class="p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Scrollable Filter Content -->
                    <div class="flex-1 overflow-y-auto p-5 space-y-6">
                        <!-- 1. Jenjang Pendidikan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                                Jenjang Pendidikan
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        @click="mobileGrade = ''" 
                                        :class="mobileGrade === '' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Semua Jenjang
                                </button>
                                <button type="button" 
                                        @click="mobileGrade = 'sd'" 
                                        :class="mobileGrade === 'sd' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    SD (Kelas 1-6)
                                </button>
                                <button type="button" 
                                        @click="mobileGrade = 'smp'" 
                                        :class="mobileGrade === 'smp' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    SMP (Kelas 7-9)
                                </button>
                                <button type="button" 
                                        @click="mobileGrade = 'sma'" 
                                        :class="mobileGrade === 'sma' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    SMA (Kelas 10-12)
                                </button>
                                <button type="button" 
                                        @click="mobileGrade = 'ujian'" 
                                        :class="mobileGrade === 'ujian' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="col-span-2 px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Persiapan UTBK / SNBT
                                </button>
                            </div>
                        </div>

                        <!-- 2. Rentang Harga per Jam -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                                Tarif Maksimal per Jam
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        @click="mobilePrice = ''" 
                                        :class="mobilePrice === '' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Semua Tarif
                                </button>
                                <button type="button" 
                                        @click="mobilePrice = '100000'" 
                                        :class="mobilePrice === '100000' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    &le; Rp 100.000
                                </button>
                                <button type="button" 
                                        @click="mobilePrice = '150000'" 
                                        :class="mobilePrice === '150000' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    &le; Rp 150.000
                                </button>
                                <button type="button" 
                                        @click="mobilePrice = '200000'" 
                                        :class="mobilePrice === '200000' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    &le; Rp 200.000
                                </button>
                            </div>
                        </div>

                        <!-- 3. Rating Minimal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                                Rating Guru Minimal
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        @click="mobileRating = ''" 
                                        :class="mobileRating === '' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Semua Rating
                                </button>
                                <button type="button" 
                                        @click="mobileRating = '4.8'" 
                                        :class="mobileRating === '4.8' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    ★ 4.8 ke atas
                                </button>
                                <button type="button" 
                                        @click="mobileRating = '4.5'" 
                                        :class="mobileRating === '4.5' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    ★ 4.5 ke atas
                                </button>
                                <button type="button" 
                                        @click="mobileRating = '4.0'" 
                                        :class="mobileRating === '4.0' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    ★ 4.0 ke atas
                                </button>
                            </div>
                        </div>

                        <!-- 4. Metode Les -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                                Metode Belajar
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" 
                                        @click="mobileMethod = ''" 
                                        :class="mobileMethod === '' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-2 py-2.5 rounded-xl border text-xs text-center transition">
                                    Semua
                                </button>
                                <button type="button" 
                                        @click="mobileMethod = 'tatap_muka'" 
                                        :class="mobileMethod === 'tatap_muka' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-2 py-2.5 rounded-xl border text-xs text-center transition">
                                    Tatap Muka
                                </button>
                                <button type="button" 
                                        @click="mobileMethod = 'online'" 
                                        :class="mobileMethod === 'online' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-2 py-2.5 rounded-xl border text-xs text-center transition">
                                    Online
                                </button>
                            </div>
                        </div>

                        <!-- 5. Urutan Hasil -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                                Urutkan Berdasarkan
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        @click="mobileSort = 'relevance'" 
                                        :class="mobileSort === 'relevance' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Relevansi
                                </button>
                                <button type="button" 
                                        @click="mobileSort = 'rating'" 
                                        :class="mobileSort === 'rating' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Rating Tertinggi
                                </button>
                                <button type="button" 
                                        @click="mobileSort = 'price_low'" 
                                        :class="mobileSort === 'price_low' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Harga Terendah
                                </button>
                                <button type="button" 
                                        @click="mobileSort = 'price_high'" 
                                        :class="mobileSort === 'price_high' ? 'border-[#080F63] bg-blue-50/80 text-[#080F63] font-bold ring-1 ring-[#080F63]' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                        class="px-3 py-2.5 rounded-xl border text-xs text-center transition">
                                    Harga Tertinggi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Footer Buttons with Safe Margin -->
                    <div class="p-4 bg-slate-50 border-t border-[#E5E8F0] flex items-center gap-3 shrink-0 pb-6">
                        <button type="button" 
                                @click="resetFilters()" 
                                class="w-1/3 py-3 px-4 rounded-full border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition text-center cursor-pointer">
                            Reset
                        </button>
                        <button type="button" 
                                @click="applyFilters()" 
                                class="w-2/3 py-3 px-4 rounded-full bg-[#080F63] hover:bg-[#050A4F] text-xs font-bold text-white transition text-center shadow-sm cursor-pointer">
                            Terapkan Filter
                        </button>
                    </div>
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
