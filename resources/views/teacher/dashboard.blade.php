<x-layouts.dashboard title="Dashboard Guru — Belajar Dong" headerTitle="Dashboard Guru" headerSubtitle="Semangat mengajar dan raih masa depan cerah anak bangsa">
    <div class="space-y-6 max-w-7xl mx-auto pt-2">
        <!-- 1. Top Greeting Banner (Mesh Deep Navy Luxury Look) -->
        <div class="bg-gradient-to-br from-[#080F63] via-[#0D1880] to-[#1222A8] rounded-[24px] p-6 sm:p-8 text-white relative overflow-hidden shadow-soft-lg">
            <!-- Decorative Glow Elements -->
            <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/4 -bottom-16 w-52 h-52 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-blue-100 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Portal Resmi Guru Privat
                        </span>

                        @if($profile->is_verified)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold">
                                <x-icons.check class="w-3.5 h-3.5 text-emerald-300" />
                                Terverifikasi Resmi (UU 14/2005)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-200 border border-amber-400/30 text-[11px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Verifikasi Berkas Diperlukan
                            </span>
                        @endif
                    </div>

                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-white">
                        Halo, {{ Auth::user()->name }} 👋
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                        {{ $profile->headline ?? 'Semangat mengajar dan membimbing siswa meraih prestasi terbaiknya hari ini.' }}
                    </p>
                </div>

                <!-- Right Action Buttons & Quick Rating -->
                <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end gap-3 shrink-0">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs">
                        <span class="text-amber-300 font-bold">★ {{ number_format($profile->rating_cache ?? 5.0, 1) }}</span>
                        <span class="text-blue-200">({{ $profile->reviews_count_cache ?? 0 }} ulasan murid)</span>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('teacher.payouts') }}" class="btn-white-pill text-xs font-bold py-2.5 px-4 shadow-sm hover:shadow-md transition inline-flex items-center gap-1.5">
                            <x-icons.wallet class="w-4 h-4 text-[#080F63]" />
                            <span>Tarik Saldo</span>
                            <span>&rarr;</span>
                        </a>

                        <a href="{{ route('teachers.show', $profile->id) }}" target="_blank" class="px-3.5 py-2.5 rounded-full bg-white/15 hover:bg-white/25 text-white text-xs font-semibold backdrop-blur-xs transition border border-white/20">
                            Lihat Profil Publik
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Verification Alert (if not verified) -->
        @if(! $profile->is_verified)
            <div class="p-5 sm:p-6 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 flex flex-col md:flex-row md:items-center justify-between gap-5 shadow-xs">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <x-icons.shield class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-extrabold text-amber-950">Profil Guru Belum Terverifikasi Lengkap</h3>
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Uji Regulasi</span>
                        </div>
                        <p class="text-xs text-amber-800 leading-relaxed max-w-2xl">
                            Lengkapi verifikasi KTP WNI, Ijazah S1 PDDikti, SKCK Kepolisian, dan Pakta Ramah Anak agar profil Anda muncul di halaman pencarian orang tua murid.
                        </p>
                    </div>
                </div>

                <a href="{{ route('teacher.documents') }}" class="btn-navy bg-amber-600 hover:bg-amber-700 py-2.5 px-5 text-xs font-bold shadow-sm shrink-0 flex items-center gap-2 self-start md:self-auto">
                    <span>Lengkapi Milestone Verifikasi</span>
                    <span>&rarr;</span>
                </a>
            </div>
        @endif

        <!-- 3. Empat Stat Cards Modern (bd-card) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Stat 1: Saldo Tersedia -->
            <div class="bd-card p-5 sm:p-6 space-y-3 hover:border-slate-300 transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Saldo Tersedia</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                        <x-icons.wallet class="w-5 h-5 text-[#080F63]" />
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-black text-[#080F63] tracking-tight tabular-nums">
                        Rp {{ number_format($availableBalance, 0, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-[#747B8C] mt-0.5">Siap dicairkan ke rekening bank</p>
                </div>
                <div class="pt-2 border-t border-[#E5E8F0]">
                    <a href="{{ route('teacher.payouts') }}" class="text-xs font-bold text-[#080F63] hover:underline inline-flex items-center gap-1">
                        <span>Tarik Dana</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Stat 2: Escrow Aktif -->
            <div class="bd-card p-5 sm:p-6 space-y-3 hover:border-slate-300 transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Escrow Aktif</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <x-icons.shield class="w-5 h-5 text-amber-600" />
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-black text-amber-600 tracking-tight tabular-nums">
                        Rp {{ number_format($inProcessAmount, 0, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-[#747B8C] mt-0.5">Diamankan hingga sesi selesai</p>
                </div>
                <div class="pt-2 border-t border-[#E5E8F0]">
                    <a href="{{ route('teacher.bookings') }}" class="text-xs font-bold text-amber-700 hover:underline inline-flex items-center gap-1">
                        <span>Pantau Sesi Les</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Stat 3: Total Penghasilan -->
            <div class="bd-card p-5 sm:p-6 space-y-3 hover:border-slate-300 transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block">Total Pendapatan</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <x-icons.receipt class="w-5 h-5 text-emerald-600" />
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-black text-emerald-700 tracking-tight tabular-nums">
                        Rp {{ number_format($grossEarnings, 0, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-[#747B8C] mt-0.5">Akumulasi seluruh sesi selesai</p>
                </div>
                <div class="pt-2 border-t border-[#E5E8F0]">
                    <a href="{{ route('teacher.earnings') }}" class="text-xs font-bold text-emerald-700 hover:underline inline-flex items-center gap-1">
                        <span>Lihat Buku Besar</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Stat 4: Sesi Les & Rating -->
            <div class="bd-card p-5 sm:p-6 space-y-3 hover:border-slate-300 transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sesi Selesai</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                        <x-icons.academic class="w-5 h-5 text-[#080F63]" />
                    </div>
                </div>
                <div>
                    <div class="text-2xl font-black text-[#171923] tracking-tight tabular-nums">
                        {{ $completedLessonsCount ?? 0 }} Sesi
                    </div>
                    <p class="text-[11px] text-[#747B8C] mt-0.5">Rating pengajaran: ★ {{ number_format($profile->rating_cache ?? 5.0, 1) }}</p>
                </div>
                <div class="pt-2 border-t border-[#E5E8F0]">
                    <a href="{{ route('teacher.reviews') }}" class="text-xs font-bold text-[#080F63] hover:underline inline-flex items-center gap-1">
                        <span>Ulasan Murid</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Two-Column Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column: Permintaan Les Masuk & Aksi Cepat (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Permintaan Les Baru -->
                <div class="bd-card p-6 sm:p-7 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                <x-icons.calendar class="w-5 h-5 text-[#080F63]" />
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#171923]">Permintaan Les Masuk</h3>
                                <p class="text-xs text-[#747B8C]">Pemesanan bimbingan les privat yang menunggu konfirmasi Anda</p>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pendingRequests->count() > 0 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ $pendingRequests->count() }} Permintaan
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($pendingRequests as $req)
                            <div class="p-5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] hover:border-slate-300 transition space-y-4 shadow-2xs">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#080F63] to-[#1222A8] text-white font-black text-sm flex items-center justify-center shrink-0 shadow-xs">
                                            {{ substr($req->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-extrabold text-[#171923]">{{ $req->student->name }}</h4>
                                            <p class="text-xs text-[#747B8C]">
                                                📚 {{ $req->subject->name }} &bull; <span class="uppercase font-semibold text-slate-700">{{ $req->student->class_grade ?? $req->student->grade_level }}</span>
                                            </p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">
                                                Orang Tua: <span class="text-slate-600 font-medium">{{ $req->parent->name }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <span class="text-[10px] text-slate-400 font-bold block uppercase">Honor Sesi:</span>
                                        <span class="text-sm sm:text-base font-black text-[#080F63] tabular-nums">
                                            Rp {{ number_format($req->session_fee, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Schedule & Location Breakdown Box -->
                                <div class="text-xs bg-white p-3.5 rounded-xl border border-[#E5E8F0] space-y-2">
                                    <div class="flex items-center gap-2 text-slate-800 font-semibold tabular-nums">
                                        <x-icons.calendar class="w-4 h-4 text-[#080F63] shrink-0" />
                                        <span>{{ $req->booking_date->translatedFormat('l, d F Y') }}</span>
                                        <span class="text-slate-300">•</span>
                                        <x-icons.clock class="w-4 h-4 text-blue-500 shrink-0" />
                                        <span>{{ substr($req->start_time, 0, 5) }} - {{ substr($req->end_time, 0, 5) }} WIB ({{ $req->session_duration_minutes ?? 90 }} mnt)</span>
                                    </div>

                                    <div class="flex items-start gap-2 text-[11px] text-slate-600">
                                        <x-icons.map-pin class="w-4 h-4 text-[#080F63] shrink-0 mt-0.5" />
                                        <span class="leading-relaxed">Alamat Les: {{ $req->lesson_address }}</span>
                                    </div>

                                    @if($req->notes)
                                        <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-700 italic">
                                            “{{ $req->notes }}”
                                        </div>
                                    @endif
                                </div>

                                <!-- Accept or Reject Action Buttons -->
                                <div class="flex items-center justify-end gap-2.5 pt-1" x-data="{ rejectModal: false }">
                                    <button type="button" @click="rejectModal = true" class="btn-white-pill text-xs py-2 px-4 text-rose-600 hover:bg-rose-50 border-rose-200">
                                        Tolak Permintaan
                                    </button>

                                    <form method="POST" action="{{ route('teacher.bookings.accept', $req->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-navy bg-emerald-600 hover:bg-emerald-700 text-xs py-2 px-5 font-bold shadow-xs flex items-center gap-1.5">
                                            <x-icons.check class="w-4 h-4 text-white" />
                                            <span>Terima Permintaan</span>
                                        </button>
                                    </form>

                                    <!-- Rejection Modal Dialog -->
                                    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                                        <div @click.away="rejectModal = false" class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full border border-slate-200 shadow-2xl space-y-4 text-left">
                                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                                <h4 class="text-base font-extrabold text-slate-900">Alasan Penolakan Permintaan</h4>
                                                <button type="button" @click="rejectModal = false" class="p-1 text-slate-400 hover:text-slate-600 rounded-md">
                                                    <x-icons.x-mark class="w-5 h-5" />
                                                </button>
                                            </div>

                                            <p class="text-xs text-slate-600">
                                                Mohon sertakan alasan agar orang tua murid memahami kendala Anda (misal: bentrok jadwal atau di luar jangkauan rute).
                                            </p>

                                            <form method="POST" action="{{ route('teacher.bookings.reject', $req->id) }}" class="space-y-4">
                                                @csrf
                                                <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Jadwal bentrok dengan agenda mengajar les privat lain..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:border-[#080F63] focus:ring-2 focus:ring-[#080F63]/20 bg-white font-medium"></textarea>

                                                <div class="flex justify-end gap-2.5 pt-1">
                                                    <button type="button" @click="rejectModal = false" class="btn-white-pill text-xs py-2 px-4">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="btn-navy bg-rose-600 hover:bg-rose-700 text-xs py-2 px-4 font-bold shadow-sm">
                                                        Konfirmasi Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 rounded-2xl bg-[#F8FAFC] border border-dashed border-[#E5E8F0] space-y-2">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#080F63] flex items-center justify-center mx-auto">
                                    <x-icons.calendar class="w-6 h-6 text-[#080F63]" />
                                </div>
                                <h4 class="text-xs font-bold text-slate-700">Tidak ada permintaan les yang menunggu</h4>
                                <p class="text-[11px] text-slate-400 max-w-sm mx-auto">
                                    Permintaan les baru dari orang tua murid yang memesan bimbingan privat akan muncul di bagian ini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Aksi Cepat Guru (4 Quick Cards) -->
                <div class="bd-card p-6 sm:p-7 space-y-4">
                    <div class="pb-2 border-b border-[#E5E8F0]">
                        <h3 class="text-sm font-extrabold text-[#171923]">Pintasan Menu Guru</h3>
                        <p class="text-xs text-[#747B8C]">Akses cepat pengaturan layanan dan aktivitas mengajar</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <a href="{{ route('teacher.schedules') }}" class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-[#F8FAFC] hover:bg-blue-50/70 border border-[#E5E8F0] hover:border-blue-200 transition group">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#080F63] group-hover:bg-[#080F63] group-hover:text-white flex items-center justify-center transition shadow-2xs">
                                <x-icons.clock class="w-5 h-5" />
                            </div>
                            <span class="text-xs font-bold text-[#171923]">Jadwal Mengajar</span>
                        </a>

                        <a href="{{ route('teacher.subjects') }}" class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-[#F8FAFC] hover:bg-blue-50/70 border border-[#E5E8F0] hover:border-blue-200 transition group">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#080F63] group-hover:bg-[#080F63] group-hover:text-white flex items-center justify-center transition shadow-2xs">
                                <x-icons.book class="w-5 h-5" />
                            </div>
                            <span class="text-xs font-bold text-[#171923]">Mata Pelajaran</span>
                        </a>

                        <a href="{{ route('teacher.reviews') }}" class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-[#F8FAFC] hover:bg-blue-50/70 border border-[#E5E8F0] hover:border-blue-200 transition group">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white flex items-center justify-center transition shadow-2xs">
                                <x-icons.star class="w-5 h-5" />
                            </div>
                            <span class="text-xs font-bold text-[#171923]">Ulasan Murid</span>
                        </a>

                        <a href="{{ route('teacher.payouts') }}" class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-[#F8FAFC] hover:bg-blue-50/70 border border-[#E5E8F0] hover:border-blue-200 transition group">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition shadow-2xs">
                                <x-icons.bank class="w-5 h-5" />
                            </div>
                            <span class="text-xs font-bold text-[#171923]">Tarik Dana</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Jadwal Hari Ini & Kepatuhan Legalitas (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Jadwal Mengajar Hari Ini -->
                <div class="bd-card p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                <x-icons.clock class="w-5 h-5 text-[#080F63]" />
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#171923]">Jadwal Hari Ini</h3>
                                <p class="text-xs text-[#747B8C]">{{ today()->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>

                        <a href="{{ route('teacher.schedules') }}" class="text-xs font-bold text-[#080F63] hover:underline">
                            Lihat Kalender
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($todayLessons as $today)
                            <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200/80 space-y-3 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-[#080F63] tabular-nums">
                                        {{ substr($today->start_time, 0, 5) }} - {{ substr($today->end_time, 0, 5) }} WIB
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-[#080F63]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#080F63] animate-pulse"></span>
                                        {{ ucfirst($today->status) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-extrabold text-[#171923]">
                                        {{ $today->subject->name }} &bull; {{ $today->student->name }}
                                    </h4>
                                    <p class="text-xs text-slate-600 truncate flex items-center gap-1.5 mt-1">
                                        <x-icons.map-pin class="w-3.5 h-3.5 text-[#080F63] shrink-0" />
                                        <span>{{ $today->lesson_address }}</span>
                                    </p>
                                </div>

                                @if($today->status === 'confirmed')
                                    <form method="POST" action="{{ route('teacher.bookings.confirm-session', $today->id) }}" class="pt-1">
                                        @csrf
                                        <button type="submit" class="w-full btn-navy bg-emerald-600 hover:bg-emerald-700 py-2.5 text-xs font-bold shadow-xs justify-center flex items-center gap-2">
                                            <x-icons.check class="w-4 h-4 text-white" />
                                            <span>Konfirmasi Hadir di Rumah Murid</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-7 rounded-2xl bg-[#F8FAFC] border border-dashed border-[#E5E8F0] space-y-1.5">
                                <p class="text-xs font-bold text-slate-700">Tidak ada jadwal mengajar hari ini.</p>
                                <p class="text-[11px] text-slate-400">Gunakan waktu luang untuk memperbarui ketersediaan slot jam.</p>
                            </div>
                        @endforelse

                        <!-- Upcoming Next Lessons (if any) -->
                        @if(!empty($upcomingLessons) && $upcomingLessons->count() > 0)
                            <div class="pt-3 border-t border-[#E5E8F0]">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-2">Jadwal Mendatang Terdekat:</span>
                                <div class="space-y-2">
                                    @foreach($upcomingLessons->take(3) as $nextLesson)
                                        <div class="p-3 rounded-xl bg-white border border-[#E5E8F0] shadow-2xs flex items-center justify-between text-xs">
                                            <div>
                                                <p class="font-bold text-[#171923]">{{ $nextLesson->subject->name }} • {{ $nextLesson->student->name }}</p>
                                                <p class="text-[11px] text-[#747B8C]">{{ $nextLesson->booking_date->translatedFormat('d M') }}, {{ substr($nextLesson->start_time, 0, 5) }} WIB</p>
                                            </div>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                                {{ ucfirst($nextLesson->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Profil & Kepatuhan Legalitas Guru -->
                <div class="bd-card p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                <x-icons.shield class="w-5 h-5 text-[#080F63]" />
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#171923]">Legalitas & Kredensial</h3>
                                <p class="text-xs text-[#747B8C]">Standar kepatuhan resmi UU Guru & UU Anak</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-3 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Tarif Les Saat Ini:</span>
                            <span class="font-extrabold text-[#080F63]">Rp {{ number_format($profile->hourly_rate, 0, ',', '.') }}/jam</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Jangkauan Maksimal:</span>
                            <span class="font-bold text-slate-800">{{ $profile->max_travel_distance_km ?? 10 }} km radius</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Rekening Bank:</span>
                            <span class="font-bold text-slate-800">{{ $profile->bank_name ?? 'BCA' }} - {{ $profile->bank_account_number ? '••••' . substr($profile->bank_account_number, -4) : 'Belum diatur' }}</span>
                        </div>

                        <div class="pt-2 border-t border-[#E5E8F0] space-y-1.5 text-[11px]">
                            <div class="flex items-center gap-2 text-emerald-800 font-semibold">
                                <x-icons.check class="w-3.5 h-3.5 text-emerald-600" />
                                <span>KTP WNI & Ijazah S1 Terverifikasi</span>
                            </div>
                            <div class="flex items-center gap-2 text-emerald-800 font-semibold">
                                <x-icons.check class="w-3.5 h-3.5 text-emerald-600" />
                                <span>SKCK & Pakta Ramah Anak Sah</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('teacher.profile') }}" class="btn-white-pill text-xs font-bold py-2.5 px-4 w-full justify-center flex items-center gap-1.5 shadow-2xs hover:shadow-xs transition">
                        <span>Edit Profil & Layanan Tarif</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
