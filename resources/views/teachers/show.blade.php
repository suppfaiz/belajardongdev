@php
    $isDashboard = $isDashboard ?? false;
    $layoutComponent = $isDashboard ? 'layouts.dashboard' : 'layouts.app';
    $backUrl = $isDashboard && Route::has('parent.teachers.search') ? route('parent.teachers.search') : route('teachers.search');
@endphp

<x-dynamic-component :component="$layoutComponent" title="{{ $teacherProfile->user->name }} — Profil Guru Belajar Dong">
    <div class="{{ $isDashboard ? 'space-y-6 max-w-7xl mx-auto' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6' }}" x-data="{ activeTab: 'tentang' }">
        <!-- Back Navigation Button -->
        <div>
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#080F63] hover:underline bg-white border border-[#E5E8F0] px-3.5 py-1.5 rounded-full shadow-2xs">
                <span>&larr;</span>
                <span>Kembali ke Pencarian Guru</span>
            </a>
        </div>

        <!-- 1. Header Profil Guru Card -->
        <div class="bd-card p-6 sm:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <!-- Avatar & Core Info -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                    <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden ring-4 ring-[#080F63]/10 shadow-sm shrink-0">
                        <img src="{{ $teacherProfile->user->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80' }}" 
                             alt="{{ $teacherProfile->user->name }}" 
                             class="w-full h-full object-cover">
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-[#171923]">{{ $teacherProfile->user->name }}</h1>
                            @if($teacherProfile->is_verified)
                                <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>

                        <!-- Subjects chips -->
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5">
                            @foreach($teacherProfile->subjects as $subj)
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-[#080F63] text-xs font-semibold">
                                    {{ $subj->name }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Rating & Price -->
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs pt-1">
                            <span class="text-amber-500 font-bold flex items-center gap-1">
                                ★ {{ number_format($teacherProfile->rating_cache, 1) }}
                                <span class="text-slate-400 font-normal">({{ $teacherProfile->reviews_count_cache }} ulasan)</span>
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="text-base font-extrabold text-[#080F63]">
                                Rp {{ number_format($teacherProfile->hourly_rate, 0, ',', '.') }}/jam
                            </span>
                        </div>

                        <!-- Education & Location -->
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs text-[#747B8C] pt-0.5">
                            <span>🎓 {{ $teacherProfile->education ?? 'Sarjana Pendidikan' }}</span>
                            <span>•</span>
                            <span>📍 {{ $teacherProfile->service_city ?? 'Jakarta Selatan' }}, Indonesia</span>
                        </div>
                    </div>
                </div>

                <!-- Right Action Buttons: "Chat Guru" + "Pesan Guru" + Wishlist -->
                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-center sm:justify-end flex-wrap sm:flex-nowrap">
                    <a href="{{ route('parent.messages', ['teacher_id' => $teacherProfile->user_id]) }}" 
                       class="px-5 py-3 rounded-xl border border-[#080F63] text-[#080F63] hover:bg-blue-50/70 font-bold text-sm transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Chat Guru</span>
                    </a>

                    <a href="{{ route('bookings.create', $teacherProfile->id) }}" class="btn-navy px-8 py-3 text-sm font-bold shadow-md flex-1 sm:flex-none justify-center">
                        Pesan Guru
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Menu Tabulasi Konten (Tentang, Mata Pelajaran, Jadwal, Ulasan) -->
        <div class="flex items-center gap-2 border-b border-[#E5E8F0] pb-2 overflow-x-auto">
            <button type="button" 
                    @click="activeTab = 'tentang'" 
                    :class="activeTab === 'tentang' ? 'border-[#080F63] text-[#080F63] font-bold' : 'border-transparent text-[#747B8C] hover:text-[#171923] font-medium'"
                    class="py-2.5 px-5 text-sm border-b-2 transition whitespace-nowrap">
                Tentang
            </button>

            <button type="button" 
                    @click="activeTab = 'mapel'" 
                    :class="activeTab === 'mapel' ? 'border-[#080F63] text-[#080F63] font-bold' : 'border-transparent text-[#747B8C] hover:text-[#171923] font-medium'"
                    class="py-2.5 px-5 text-sm border-b-2 transition whitespace-nowrap">
                Mata Pelajaran
            </button>

            <button type="button" 
                    @click="activeTab = 'jadwal'" 
                    :class="activeTab === 'jadwal' ? 'border-[#080F63] text-[#080F63] font-bold' : 'border-transparent text-[#747B8C] hover:text-[#171923] font-medium'"
                    class="py-2.5 px-5 text-sm border-b-2 transition whitespace-nowrap">
                Jadwal
            </button>

            <button type="button" 
                    @click="activeTab = 'ulasan'" 
                    :class="activeTab === 'ulasan' ? 'border-[#080F63] text-[#080F63] font-bold' : 'border-transparent text-[#747B8C] hover:text-[#171923] font-medium'"
                    class="py-2.5 px-5 text-sm border-b-2 transition whitespace-nowrap">
                Ulasan ({{ $teacherProfile->reviews_count_cache }})
            </button>
        </div>

        <!-- 3. Layout Informasi Dua Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Kolom Kiri: Tentang Saya & Metode Mengajar (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Tab 1: Tentang Saya -->
                <div class="bd-card p-6 space-y-4" x-show="activeTab === 'tentang' || activeTab === 'mapel'">
                    <h3 class="font-bold text-base text-[#171923]">Tentang Saya</h3>
                    <div class="text-sm text-[#747B8C] leading-relaxed whitespace-pre-line space-y-3">
                        <p>{{ $teacherProfile->bio ?? 'Saya adalah guru privat berpengalaman yang berkomitmen mendampingi siswa memahami materi pelajaran dari dasar hingga mampu menyelesaikan soal ujian dengan percaya diri.' }}</p>
                    </div>

                    <div class="pt-4 border-t border-[#E5E8F0] grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-[#747B8C] block">Pengalaman Mengajar</span>
                            <span class="text-sm font-bold text-[#171923]">{{ $teacherProfile->experience_years }} Tahun</span>
                        </div>
                        <div>
                            <span class="text-xs text-[#747B8C] block">Almamater / Gelar</span>
                            <span class="text-sm font-bold text-[#171923]">{{ $teacherProfile->education ?? 'S1 Pendidikan' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Metode Mengajar Card -->
                <div class="bd-card p-6 space-y-4">
                    <h3 class="font-bold text-base text-[#171923]">Metode Mengajar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">
                                💻
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-[#171923]">Online</h4>
                                <p class="text-xs text-[#747B8C]">Via Zoom / Google Meet</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-base">
                                🏠
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-[#171923]">Tatap Muka</h4>
                                <p class="text-xs text-[#747B8C]">Guru datang ke rumah murid</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Jadwal Ketersediaan -->
                <div class="bd-card p-6 space-y-4" x-show="activeTab === 'jadwal'">
                    <h3 class="font-bold text-base text-[#171923]">Jadwal Ketersediaan Mengajar</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @forelse($teacherProfile->availabilitySlots as $slot)
                            @php
                                $dayStr = match($slot->day_of_week) {
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    7 => 'Minggu',
                                    default => 'Hari'
                                };
                            @endphp
                            <div class="p-3 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] text-center">
                                <span class="text-xs font-bold text-[#080F63] block">{{ $dayStr }}</span>
                                <span class="text-xs text-[#747B8C] mt-0.5 block">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 col-span-full">Jadwal fleksibel (Senin - Sabtu).</p>
                        @endforelse
                    </div>
                </div>

                <!-- Tab Ulasan Orang Tua -->
                <div class="bd-card p-6 space-y-4" x-show="activeTab === 'ulasan'">
                    <h3 class="font-bold text-base text-[#171923]">Ulasan Orang Tua</h3>
                    <div class="space-y-3">
                        @forelse($teacherProfile->reviews as $rev)
                            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0]">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold text-[#171923]">{{ $rev->parent->name }}</span>
                                    <span class="text-xs font-bold text-amber-500">★ {{ $rev->rating }}.0</span>
                                </div>
                                <p class="text-xs text-[#747B8C] leading-relaxed">{{ $rev->comment }}</p>
                            </div>
                        @empty
                            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0]">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold text-[#171923]">Ibu Ratna (Orang Tua Murid Kelas 7)</span>
                                    <span class="text-xs font-bold text-amber-500">★ 5.0</span>
                                </div>
                                <p class="text-xs text-[#747B8C] leading-relaxed">&ldquo;Guru sangat sabar dalam menjelaskan rumus aljabar. Anak saya sekarang lebih percaya diri saat ujian sekolah!&rdquo;</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Mata Pelajaran, Jenjang, Lokasi (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Card Mata Pelajaran -->
                <div class="bd-card p-6 space-y-4">
                    <h3 class="font-bold text-base text-[#171923]">Mata Pelajaran</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($teacherProfile->subjects as $subj)
                            <span class="px-3.5 py-1.5 rounded-full bg-blue-50 text-[#080F63] border border-blue-100 text-xs font-semibold">
                                {{ $subj->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Card Jenjang -->
                <div class="bd-card p-6 space-y-4">
                    <h3 class="font-bold text-base text-[#171923]">Jenjang yang Diajar</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3.5 py-1.5 rounded-full bg-slate-100 text-[#171923] text-xs font-semibold">SD (Kelas 1 - 6)</span>
                        <span class="px-3.5 py-1.5 rounded-full bg-slate-100 text-[#171923] text-xs font-semibold">SMP (Kelas 7 - 9)</span>
                        <span class="px-3.5 py-1.5 rounded-full bg-slate-100 text-[#171923] text-xs font-semibold">SMA (Kelas 10 - 12)</span>
                    </div>
                </div>

                <!-- Card Lokasi Mengajar -->
                <div class="bd-card p-6 space-y-4">
                    <h3 class="font-bold text-base text-[#171923]">Lokasi Mengajar</h3>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0">
                            📍
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#171923]">{{ $teacherProfile->service_city ?? 'Jakarta Selatan' }}, DKI Jakarta</p>
                            <p class="text-[11px] text-[#747B8C] mt-0.5">Maksimal radius perjalanan: {{ $teacherProfile->max_travel_distance_km ?? 10 }} km</p>
                        </div>
                    </div>
                </div>

                <!-- Statutory Credentials & Compliance Card -->
                <div class="bd-card p-6 space-y-3.5">
                    <h3 class="font-bold text-sm text-[#171923] flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Kredensial & Kepatuhan Regulasi Resmi</span>
                    </h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>KTP WNI Sah</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Ijazah S1/D-IV Terverifikasi</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Bebas Catatan Pidana (SKCK)</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Pakta Integritas Ramah Anak</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 pt-2 border-t border-slate-100 leading-relaxed">
                        Tunduk dan patuh pada UU No. 27/2022 tentang Pelindungan Data Pribadi untuk menjamin privasi anak & keluarga.
                    </p>
                </div>

                <!-- CTA Card Booking -->
                <div class="p-6 rounded-[24px] bg-[#080F63] text-white space-y-4 shadow-soft">
                    <h4 class="font-bold text-base">Siap Memulai Belajar?</h4>
                    <p class="text-xs text-blue-100 leading-relaxed">
                        Pesan sesi les privat dengan {{ $teacherProfile->user->name }} sekarang dan tentukan jadwal yang pas untuk anak Anda.
                    </p>
                    <a href="{{ route('bookings.create', $teacherProfile->id) }}" class="w-full btn-navy bg-white text-[#080F63] hover:bg-slate-100 justify-center py-3 text-sm font-bold shadow-md">
                        Pesan Guru Sekarang &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
