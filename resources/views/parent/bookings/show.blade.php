<x-layouts.dashboard title="Detail Pemesanan {{ $booking->booking_code }} — Belajar Dong" headerTitle="Detail Pemesanan" headerSubtitle="Informasi lengkap jadwal les privat, status escrow, dan kredensial pengajar">
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ reviewRating: 5, showConfirmModal: false }">
        <!-- Top Breadcrumb & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('parent.bookings') }}" class="btn-white-pill text-xs py-2 px-4 shadow-2xs hover:shadow-xs transition inline-flex items-center gap-2 self-start">
                <x-icons.arrow-left class="w-4 h-4 text-[#080F63]" />
                <span>Kembali ke Daftar Pemesanan</span>
            </a>

            @php
                $statusConfig = match($booking->status) {
                    'pending_teacher' => [
                        'label' => 'Menunggu Konfirmasi Guru',
                        'class' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'dot' => 'bg-amber-500'
                    ],
                    'accepted' => [
                        'label' => 'Menunggu Pembayaran',
                        'class' => 'bg-sky-50 text-sky-800 border-sky-200',
                        'dot' => 'bg-sky-500'
                    ],
                    'confirmed' => [
                        'label' => 'Escrow Aktif (Terkonfirmasi)',
                        'class' => 'bg-blue-50 text-[#080F63] border-blue-200',
                        'dot' => 'bg-blue-600'
                    ],
                    'in_progress' => [
                        'label' => 'Sesi Berlangsung',
                        'class' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                        'dot' => 'bg-indigo-600'
                    ],
                    'completed' => [
                        'label' => 'Selesai & Dicairkan',
                        'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        'dot' => 'bg-emerald-600'
                    ],
                    'rejected' => [
                        'label' => 'Ditolak Guru',
                        'class' => 'bg-rose-50 text-rose-800 border-rose-200',
                        'dot' => 'bg-rose-500'
                    ],
                    'cancelled' => [
                        'label' => 'Dibatalkan',
                        'class' => 'bg-slate-100 text-slate-700 border-slate-300',
                        'dot' => 'bg-slate-500'
                    ],
                    'refunded' => [
                        'label' => 'Dana Dikembalikan (Refund)',
                        'class' => 'bg-purple-50 text-purple-800 border-purple-200',
                        'dot' => 'bg-purple-600'
                    ],
                    default => [
                        'label' => ucfirst($booking->status),
                        'class' => 'bg-slate-100 text-slate-700 border-slate-300',
                        'dot' => 'bg-slate-500'
                    ],
                };
            @endphp

            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#E5E8F0] shadow-2xs text-xs">
                    <span class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">ID Pesanan:</span>
                    <span class="font-mono font-bold text-[#080F63] select-all">{{ $booking->booking_code }}</span>
                </div>

                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold border shadow-2xs {{ $statusConfig['class'] }}">
                    <span class="w-2 h-2 rounded-full {{ $statusConfig['dot'] }} animate-pulse"></span>
                    <span>{{ $statusConfig['label'] }}</span>
                </span>
            </div>
        </div>

        <!-- 1. Garansi Rekening Escrow Belajar Dong (Hero Banner) -->
        <div class="bg-gradient-to-br from-[#080F63] via-[#0D1880] to-[#1222A8] rounded-[24px] p-6 sm:p-8 text-white relative overflow-hidden shadow-soft-lg">
            <!-- Decorative Glow Elements -->
            <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/4 -bottom-16 w-52 h-52 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4 sm:gap-5 max-w-2xl">
                    <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-md text-amber-300 flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
                        <x-icons.shield class="w-7 h-7 text-amber-300" />
                    </div>
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                Garansi Rekening Escrow Belajar Dong
                            </h2>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                Perlindungan 100%
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                            Dana pembayaran Anda diamankan di rekening penampungan resmi Belajar Dong. Saldo baru akan diteruskan ke guru setelah Anda mengonfirmasi bahwa sesi les privat tatap muka telah selesai dilaksanakan di rumah.
                        </p>
                    </div>
                </div>

                <!-- Total Amount Card -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 text-left lg:text-right shrink-0 min-w-[220px]">
                    <span class="text-[11px] font-bold text-blue-200 uppercase tracking-wider block mb-1">Total Biaya Les</span>
                    <div class="text-2xl sm:text-3xl font-black text-amber-300 tabular-nums">
                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 text-xs font-medium text-blue-100 flex items-center lg:justify-end gap-1.5">
                        @if($booking->status === 'accepted')
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Menunggu Pembayaran</span>
                        @elseif($booking->status === 'completed')
                            <x-icons.check class="w-3.5 h-3.5 text-emerald-300" />
                            <span class="text-emerald-300 font-semibold">Transaksi Selesai</span>
                        @else
                            <x-icons.check class="w-3.5 h-3.5 text-emerald-300" />
                            <span class="text-emerald-300 font-semibold">Tersimpan di Escrow</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Tahapan Status Pemesanan (5-Step Visual Stepper) -->
        <div class="bd-card p-6 sm:p-7 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                <div>
                    <h3 class="font-bold text-base text-[#171923]">Tahapan Proses Pemesanan Les</h3>
                    <p class="text-xs text-[#747B8C]">Alur transparan pengamanan sesi belajar dari pengajuan hingga pencairan honor</p>
                </div>
                <span class="text-xs font-semibold text-slate-400 hidden sm:inline">5 Langkah Aman</span>
            </div>

            @php
                $stepIndex = match($booking->status) {
                    'pending_teacher' => 1,
                    'accepted' => 2,
                    'confirmed' => 3,
                    'in_progress' => 4,
                    'completed' => 5,
                    'rejected', 'cancelled', 'refunded' => 0,
                    default => 1,
                };
            @endphp

            @if($stepIndex === 0)
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-base shrink-0">!</span>
                    <div>
                        <p class="font-bold">Pemesanan Tidak Aktif ({{ ucfirst($booking->status) }})</p>
                        <p class="text-rose-600 text-[11px] mt-0.5">Pemesanan ini telah dibatalkan atau ditolak. Jika Anda telah melakukan pembayaran, dana akan diproses kembali sesuai prosedur pengembalian.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    <!-- Step 1 -->
                    @php $isPast1 = $stepIndex > 1; $isCurrent1 = $stepIndex === 1; @endphp
                    <div class="p-3.5 rounded-2xl border transition {{ $isCurrent1 ? 'bg-[#080F63] border-[#080F63] text-white shadow-md' : ($isPast1 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-[#F8FAFC] border-[#E5E8F0] text-slate-400') }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold font-mono {{ $isCurrent1 ? 'text-blue-200' : ($isPast1 ? 'text-emerald-600' : 'text-slate-400') }}">01</span>
                            @if($isPast1)
                                <x-icons.check class="w-4 h-4 text-emerald-600" />
                            @elseif($isCurrent1)
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs font-bold leading-tight {{ $isCurrent1 ? 'text-white' : ($isPast1 ? 'text-emerald-950' : 'text-slate-600') }}">Pengajuan Terkirim</p>
                        <p class="text-[10px] mt-0.5 {{ $isCurrent1 ? 'text-blue-200' : 'text-slate-500' }}">Menunggu konfirmasi</p>
                    </div>

                    <!-- Step 2 -->
                    @php $isPast2 = $stepIndex > 2; $isCurrent2 = $stepIndex === 2; @endphp
                    <div class="p-3.5 rounded-2xl border transition {{ $isCurrent2 ? 'bg-[#080F63] border-[#080F63] text-white shadow-md' : ($isPast2 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-[#F8FAFC] border-[#E5E8F0] text-slate-400') }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold font-mono {{ $isCurrent2 ? 'text-blue-200' : ($isPast2 ? 'text-emerald-600' : 'text-slate-400') }}">02</span>
                            @if($isPast2)
                                <x-icons.check class="w-4 h-4 text-emerald-600" />
                            @elseif($isCurrent2)
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs font-bold leading-tight {{ $isCurrent2 ? 'text-white' : ($isPast2 ? 'text-emerald-950' : 'text-slate-600') }}">Disetujui Guru</p>
                        <p class="text-[10px] mt-0.5 {{ $isCurrent2 ? 'text-blue-200' : 'text-slate-500' }}">Menunggu pembayaran</p>
                    </div>

                    <!-- Step 3 -->
                    @php $isPast3 = $stepIndex > 3; $isCurrent3 = $stepIndex === 3; @endphp
                    <div class="p-3.5 rounded-2xl border transition {{ $isCurrent3 ? 'bg-[#080F63] border-[#080F63] text-white shadow-md' : ($isPast3 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-[#F8FAFC] border-[#E5E8F0] text-slate-400') }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold font-mono {{ $isCurrent3 ? 'text-blue-200' : ($isPast3 ? 'text-emerald-600' : 'text-slate-400') }}">03</span>
                            @if($isPast3)
                                <x-icons.check class="w-4 h-4 text-emerald-600" />
                            @elseif($isCurrent3)
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs font-bold leading-tight {{ $isCurrent3 ? 'text-white' : ($isPast3 ? 'text-emerald-950' : 'text-slate-600') }}">Escrow Aktif</p>
                        <p class="text-[10px] mt-0.5 {{ $isCurrent3 ? 'text-blue-200' : 'text-slate-500' }}">Dana aman di sistem</p>
                    </div>

                    <!-- Step 4 -->
                    @php $isPast4 = $stepIndex > 4; $isCurrent4 = $stepIndex === 4; @endphp
                    <div class="p-3.5 rounded-2xl border transition {{ $isCurrent4 ? 'bg-[#080F63] border-[#080F63] text-white shadow-md' : ($isPast4 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-[#F8FAFC] border-[#E5E8F0] text-slate-400') }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold font-mono {{ $isCurrent4 ? 'text-blue-200' : ($isPast4 ? 'text-emerald-600' : 'text-slate-400') }}">04</span>
                            @if($isPast4)
                                <x-icons.check class="w-4 h-4 text-emerald-600" />
                            @elseif($isCurrent4)
                                <span class="w-2 h-2 rounded-full bg-indigo-300 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs font-bold leading-tight {{ $isCurrent4 ? 'text-white' : ($isPast4 ? 'text-emerald-950' : 'text-slate-600') }}">Sesi Berlangsung</p>
                        <p class="text-[10px] mt-0.5 {{ $isCurrent4 ? 'text-blue-200' : 'text-slate-500' }}">Bimbingan di rumah</p>
                    </div>

                    <!-- Step 5 -->
                    @php $isCurrent5 = $stepIndex >= 5; @endphp
                    <div class="p-3.5 rounded-2xl border transition {{ $isCurrent5 ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-[#F8FAFC] border-[#E5E8F0] text-slate-400' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold font-mono {{ $isCurrent5 ? 'text-emerald-600' : 'text-slate-400' }}">05</span>
                            @if($isCurrent5)
                                <x-icons.check class="w-4 h-4 text-emerald-600" />
                            @endif
                        </div>
                        <p class="text-xs font-bold leading-tight {{ $isCurrent5 ? 'text-emerald-950' : 'text-slate-600' }}">Selesai & Dicairkan</p>
                        <p class="text-[10px] mt-0.5 text-slate-500">Honor diteruskan ke guru</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- 3. Contextual Action Alert Based On Status -->
        @if($booking->status === 'accepted')
            <div class="p-5 sm:p-6 rounded-2xl bg-amber-50/90 border border-amber-300 flex flex-col sm:flex-row sm:items-center justify-between gap-5 shadow-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <h4 class="text-sm font-extrabold text-amber-950">Guru Telah Menyetujui Jadwal Bimbingan Ini!</h4>
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Segera selesaikan pembayaran untuk mengunci jadwal les di kalender guru dan mengaktifkan proteksi rekening escrow Belajar Dong.
                    </p>
                </div>

                <a href="{{ route('bookings.checkout', $booking->id) }}" class="btn-navy bg-amber-600 hover:bg-amber-700 py-3 px-6 text-xs font-bold shadow-md flex items-center justify-center gap-2 shrink-0">
                    <span>Bayar Sekarang (Rp {{ number_format($booking->total_amount, 0, ',', '.') }})</span>
                    <span>&rarr;</span>
                </a>
            </div>
        @elseif($booking->status === 'confirmed' || $booking->status === 'in_progress')
            <div class="p-5 sm:p-6 rounded-2xl bg-blue-50/90 border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-5 shadow-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                        <h4 class="text-sm font-extrabold text-blue-950">Konfirmasi Sesi Les & Pencairan Escrow</h4>
                    </div>
                    <p class="text-xs text-blue-800 leading-relaxed max-w-xl">
                        Apakah guru telah hadir ke rumah dan menyelesaikan sesi bimbingan les privat sesuai jadwal? Klik tombol di samping untuk mencairkan honor ke saldo aktif guru.
                    </p>
                </div>

                <button type="button" @click="showConfirmModal = true" class="btn-navy bg-emerald-600 hover:bg-emerald-700 py-3 px-6 text-xs font-bold shadow-md flex items-center justify-center gap-2 shrink-0">
                    <x-icons.check class="w-4 h-4 text-white" />
                    <span>Konfirmasi Sesi Selesai</span>
                </button>
            </div>

            <!-- Confirmation Modal Dialog -->
            <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div @click.away="showConfirmModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl space-y-5 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
                        <x-icons.check class="w-7 h-7 text-emerald-600" />
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-black text-slate-900">Konfirmasi Penyelesaian Sesi?</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Dengan mengonfirmasi, Anda menyatakan bahwa guru telah menyelesaikan pembelajaran les privat untuk <span class="font-bold text-slate-900">{{ $booking->student->name }}</span>.
                        </p>
                        <p class="text-[11px] text-[#080F63] font-semibold bg-blue-50 p-3 rounded-xl border border-blue-100">
                            Honor sebesar <strong>Rp {{ number_format($booking->teacher_earnings, 0, ',', '.') }}</strong> akan langsung diteruskan ke dompet saldo aktif guru.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showConfirmModal = false" class="btn-white-pill flex-1 py-2.5 text-xs font-bold">
                            Batal
                        </button>
                        <form method="POST" action="{{ route('parent.bookings.confirm', $booking->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="btn-navy bg-emerald-600 hover:bg-emerald-700 w-full py-2.5 text-xs font-bold shadow-md">
                                Ya, Selesai & Cairkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($booking->status === 'completed')
            <div class="p-5 sm:p-6 rounded-2xl bg-emerald-50/90 border border-emerald-300 flex items-center gap-4 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <x-icons.check class="w-6 h-6 text-emerald-700" />
                </div>
                <div class="space-y-0.5">
                    <h4 class="text-sm font-extrabold text-emerald-950">Sesi Pembelajaran Telah Berhasil Selesai</h4>
                    <p class="text-xs text-emerald-800">Honor les telah dicairkan ke saldo guru secara transparan melalui sistem ledger Belajar Dong.</p>
                </div>
            </div>
        @endif

        <!-- 4. Main 2-Column Content Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column: Teacher & Student Details (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Teacher Profile Card -->
                <div class="bd-card p-6 sm:p-7 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                <x-icons.academic class="w-5 h-5 text-[#080F63]" />
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#171923]">Profil & Kredensial Pengajar</h3>
                                <p class="text-xs text-[#747B8C]">Guru yang ditugaskan membimbing anak Anda</p>
                            </div>
                        </div>

                        @php
                            $teacherProfileId = $booking->teacher->teacherProfile?->id ?? 1;
                            $profileUrl = Route::has('parent.teachers.show') 
                                ? route('parent.teachers.show', $teacherProfileId) 
                                : route('teachers.show', $teacherProfileId);
                        @endphp
                        <div class="flex items-center gap-2">
                            <a href="{{ route('parent.messages', ['teacher_id' => $booking->teacher_id]) }}" class="btn-navy py-1 px-3 text-[11px] font-bold shadow-2xs inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                <span>Chat Guru</span>
                            </a>
                            <a href="{{ $profileUrl }}" class="text-xs text-[#080F63] font-bold hover:underline inline-flex items-center gap-1">
                                <span>Profil</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>

                    <!-- Teacher Information Row -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0]">
                        <div class="relative w-16 h-16 rounded-2xl overflow-hidden ring-2 ring-[#080F63]/10 shadow-xs shrink-0">
                            @if($booking->teacher->avatar)
                                <img src="{{ $booking->teacher->avatar }}" alt="{{ $booking->teacher->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-100 text-[#080F63] font-bold text-xl flex items-center justify-center">
                                    {{ substr($booking->teacher->name, 0, 1) }}
                                </div>
                            @endif
                            @if($booking->teacher->teacherProfile?->is_verified)
                                <span class="absolute bottom-0 right-0 w-4 h-4 bg-blue-600 rounded-full ring-2 ring-white flex items-center justify-center text-white text-[9px]" title="Guru Terverifikasi">
                                    ✓
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1.5 min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-sm font-bold text-[#171923] truncate">{{ $booking->teacher->name }}</h4>
                                @if($booking->teacher->teacherProfile?->is_verified)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
                                        Terverifikasi Resmi
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-[#747B8C]">{{ $booking->teacher->teacherProfile?->education ?? 'Pendidikan Guru Profesional' }}</p>
                            <p class="text-xs text-slate-700 font-medium pt-0.5 flex items-center gap-1.5">
                                <x-icons.phone class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                <span>{{ $booking->teacher->phone ?? 'Kontak aman melalui koordinasi sistem Belajar Dong' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Regulatory Compliance Verification Checklist (2x2 Grid) -->
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-2.5">Legalitas & Kepatuhan Terverifikasi</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                            <div class="p-3 rounded-xl bg-white border border-[#E5E8F0] shadow-2xs flex items-center gap-2.5">
                                <x-icons.check class="w-4 h-4 text-blue-600 shrink-0" />
                                <span class="text-slate-800 font-semibold text-[11px]">KTP WNI Sah (UU 24/2013)</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-[#E5E8F0] shadow-2xs flex items-center gap-2.5">
                                <x-icons.check class="w-4 h-4 text-emerald-600 shrink-0" />
                                <span class="text-slate-800 font-semibold text-[11px]">Ijazah S1 PDDikti (UU 14/2005)</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-[#E5E8F0] shadow-2xs flex items-center gap-2.5">
                                <x-icons.check class="w-4 h-4 text-blue-600 shrink-0" />
                                <span class="text-slate-800 font-semibold text-[11px]">SKCK Bebas Pidana</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-[#E5E8F0] shadow-2xs flex items-center gap-2.5">
                                <x-icons.check class="w-4 h-4 text-amber-600 shrink-0" />
                                <span class="text-slate-800 font-semibold text-[11px]">Pakta Ramah Anak (UU 35/2014)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Learning Card -->
                <div class="bd-card p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                <x-icons.user class="w-5 h-5 text-[#080F63]" />
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#171923]">Data Murid & Materi Les</h3>
                                <p class="text-xs text-[#747B8C]">Profil anak yang menerima bimbingan belajar</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-3 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-400 font-bold block text-[10px] uppercase">Nama Murid</span>
                                <span class="text-sm font-extrabold text-[#171923]">{{ $booking->student->name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold block text-[10px] uppercase">Jenjang & Kelas</span>
                                <span class="text-xs font-bold text-[#171923] uppercase">{{ $booking->student->grade_level }} {{ $booking->student->class_grade ? '• ' . $booking->student->class_grade : '' }}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-[#E5E8F0]">
                            <span class="text-slate-400 font-bold block text-[10px] uppercase mb-1">Mata Pelajaran</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-[#080F63] font-bold text-xs border border-blue-200">
                                📚 {{ $booking->subject->name }}
                            </span>
                        </div>

                        @if($booking->notes)
                            <div class="pt-3 border-t border-[#E5E8F0]">
                                <span class="text-slate-400 font-bold block text-[10px] uppercase mb-1">Catatan Khusus Orang Tua</span>
                                <p class="text-slate-700 italic text-[11px] bg-white p-3 rounded-xl border border-[#E5E8F0] leading-relaxed">
                                    “{{ $booking->notes }}”
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Review Section (If Completed) -->
                @if($booking->status === 'completed')
                    <div class="bd-card p-6 sm:p-7 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <x-icons.star class="w-5 h-5 text-amber-500 fill-amber-500" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-[#171923]">Evaluasi & Ulasan Bimbingan</h3>
                                    <p class="text-xs text-[#747B8C]">Apresiasi dan masukan kinerja pengajar</p>
                                </div>
                            </div>
                        </div>

                        @if($booking->review)
                            <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1 text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-icons.star class="w-4 h-4 {{ $i <= $booking->review->rating ? 'fill-amber-400 text-amber-400' : 'text-slate-300' }}" />
                                        @endfor
                                        <span class="text-xs font-black text-amber-900 ml-1.5">{{ number_format($booking->review->rating, 1) }} / 5.0</span>
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ $booking->review->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <p class="text-xs text-slate-700 italic leading-relaxed">
                                    “{{ $booking->review->comment }}”
                                </p>
                            </div>
                        @else
                            <form method="POST" action="{{ route('parent.bookings.review', $booking->id) }}" class="p-5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-4">
                                @csrf
                                <div class="space-y-1">
                                    <h4 class="text-xs font-extrabold text-[#171923]">Bagikan Pengalaman Belajar Anak Anda</h4>
                                    <p class="text-[11px] text-[#747B8C]">Ulasan Anda membantu orang tua lain memilih guru terbaik dan meningkatkan reputasi guru.</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-800">Rating Kualitas Pengajaran:</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1">
                                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                                <button type="button" @click="reviewRating = star" class="p-1 focus:outline-none transition transform hover:scale-110">
                                                    <x-icons.star class="w-6 h-6" x-bind:class="star <= reviewRating ? 'fill-amber-400 text-amber-400' : 'text-slate-300'" />
                                                </button>
                                            </template>
                                        </div>
                                        <input type="hidden" name="rating" x-bind:value="reviewRating">
                                        <span class="text-xs font-black text-slate-700 ml-2" x-text="reviewRating + ' Bintang'"></span>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-800">Ulasan & Masukan:</label>
                                    <textarea name="comment" rows="3" required placeholder="Ceritakan bagaimana guru mengajar, ketepatan waktu, dan pemahaman anak terhadap materi..." class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E8F0] text-xs outline-none focus:border-[#080F63] focus:ring-2 focus:ring-[#080F63]/20 bg-white font-medium"></textarea>
                                </div>

                                <button type="submit" class="btn-navy py-2.5 px-6 text-xs font-bold shadow-sm">
                                    Kirim Ulasan Resmi
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Column: Schedule, Location & Financial Breakdown (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Schedule & Location Card -->
                <div class="bd-card p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#080F63] flex items-center justify-center">
                                <x-icons.calendar class="w-5 h-5 text-[#080F63]" />
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#171923]">Jadwal & Lokasi Les</h3>
                                <p class="text-xs text-[#747B8C]">Waktu dan alamat bimbingan tatap muka</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-3.5 text-xs">
                        <div class="space-y-1.5">
                            <span class="text-slate-400 font-bold block text-[10px] uppercase">Paket & Waktu Pembelajaran</span>
                            <div class="p-3 bg-white rounded-xl border border-[#E5E8F0] space-y-2 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $booking->plan_type === 'six_months' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($booking->plan_type === 'monthly' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200') }}">
                                        {{ $booking->plan_label }}
                                    </span>
                                    <span class="text-xs font-bold text-[#080F63]">
                                        {{ $booking->total_sessions ?? 1 }} Pertemuan
                                    </span>
                                </div>

                                <p class="text-sm font-extrabold text-[#171923] flex items-center gap-2">
                                    <x-icons.calendar class="w-4 h-4 text-[#080F63]" />
                                    <span>Mulai: {{ $booking->booking_date->translatedFormat('l, d F Y') }}</span>
                                </p>
                                <p class="text-[#080F63] font-semibold flex items-center gap-2 pt-0.5 text-xs">
                                    <x-icons.clock class="w-4 h-4 text-blue-500" />
                                    <span>{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB ({{ $booking->session_duration_minutes }} menit/sesi)</span>
                                </p>

                                @if(!empty($booking->schedule_dates) && count($booking->schedule_dates) > 1)
                                    <div class="pt-2 border-t border-slate-100" x-data="{ showAllDates: false }">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Daftar Tanggal Sesi Les:</span>
                                            <button type="button" @click="showAllDates = !showAllDates" class="text-[11px] font-bold text-blue-600 hover:underline">
                                                <span x-text="showAllDates ? 'Tutup Daftar' : 'Lihat Semua ({{ count($booking->schedule_dates) }})'"></span>
                                            </button>
                                        </div>

                                        <div class="flex flex-wrap gap-1.5 max-h-36 overflow-y-auto pr-1">
                                            @foreach($booking->schedule_dates as $idx => $sDate)
                                                @php $parsedSDate = \Carbon\Carbon::parse($sDate); @endphp
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-semibold {{ $idx >= 4 ? '' : '' }}"
                                                      x-show="showAllDates || {{ $idx }} < 4">
                                                    <span>#{{ $idx + 1 }}</span>
                                                    <span>{{ $parsedSDate->translatedFormat('d M') }} ({{ $parsedSDate->translatedFormat('D') }})</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <span class="text-slate-400 font-bold block text-[10px] uppercase">Alamat Rumah Tempat Les</span>
                            <div class="p-3 bg-white rounded-xl border border-[#E5E8F0] shadow-2xs flex items-start gap-2.5">
                                <x-icons.map-pin class="w-4 h-4 text-[#080F63] shrink-0 mt-0.5" />
                                <div class="space-y-1">
                                    <p class="text-slate-800 leading-relaxed font-medium">
                                        {{ $booking->lesson_address }}
                                    </p>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">
                                        ● Tatap Muka di Rumah
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Breakdown & Official Receipt -->
                <div class="bd-card p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <x-icons.receipt class="w-5 h-5 text-emerald-600" />
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#171923]">Rincian Transaksi & Bukti</h3>
                                <p class="text-xs text-[#747B8C]">Transparansi biaya pembayaran</p>
                            </div>
                        </div>

                        @if($booking->payment && $booking->payment->status === 'paid')
                            <span class="text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300 px-2.5 py-0.5 rounded-full">
                                Lunas (Escrow)
                            </span>
                        @endif
                    </div>

                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Paket Bimbingan:</span>
                            <span class="font-bold text-[#171923]">{{ $booking->plan_label }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Pertemuan:</span>
                            <span class="font-bold text-[#171923]">{{ $booking->total_sessions ?? 1 }} Sesi</span>
                        </div>

                        @if(($booking->discount_amount ?? 0) > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal Normal:</span>
                                <span class="line-through text-slate-400">Rp {{ number_format(($booking->session_fee + $booking->discount_amount), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-emerald-700 font-bold">
                                <span>Diskon Paket ({{ $booking->discount_percent }}%):</span>
                                <span>- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Bersih Sesi Pengajar:</span>
                            <span class="font-bold text-[#171923]">Rp {{ number_format($booking->session_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Layanan Platform ({{ $booking->platform_commission_rate }}%):</span>
                            <span>Rp {{ number_format($booking->platform_commission_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Garansi Perlindungan Escrow:</span>
                            <span class="text-emerald-700 font-semibold">Gratis (Termasuk)</span>
                        </div>

                        <div class="flex justify-between items-baseline pt-3 border-t border-[#E5E8F0] font-extrabold text-[#171923]">
                            <span class="text-xs">Total Pembayaran:</span>
                            <span class="text-lg text-[#080F63] font-black tabular-nums">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($booking->payment)
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-2 text-[11px] text-slate-600">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Metode Pembayaran:</span>
                                <span class="font-bold text-[#171923] uppercase bg-white px-2 py-0.5 rounded border border-blue-100">{{ $booking->payment->payment_method ?? 'QRIS Instan' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Kode Transaksi:</span>
                                <span class="font-mono font-bold text-[#080F63] bg-white px-2 py-0.5 rounded border border-blue-100 select-all">{{ $booking->payment->payment_code }}</span>
                            </div>
                            @if($booking->payment->paid_at)
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Waktu Pembayaran:</span>
                                    <span class="font-semibold text-slate-800">{{ $booking->payment->paid_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
