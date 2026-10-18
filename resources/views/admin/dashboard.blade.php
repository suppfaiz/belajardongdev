<x-layouts.dashboard title="Dashboard Admin — BelajarYuk" headerTitle="Dashboard Admin" headerSubtitle="Ringkasan aktivitas platform marketplace, verifikasi guru, dan monitoring transaksi les">
    <div class="space-y-8 pt-4">
        <!-- Top Admin Banner (Mesh Gradient Luxury Look) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-gradient-to-r from-[#0B0F19] via-slate-900 to-blue-950 rounded-2xl p-6 sm:p-8 text-white border border-slate-800 shadow-xl relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-8 -bottom-8 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold text-blue-300 uppercase tracking-widest">Sistem Marketplace Aktif</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">Pusat Kendali Administrator</h2>
                <p class="text-xs sm:text-sm text-slate-300 font-normal max-w-2xl">Pantau verifikasi dokumen kualifikasi guru, kelancaran transaksi rekening bersama escrow, dan aktivitas les tatap muka.</p>
            </div>
            <div class="relative z-10 flex items-center gap-3 shrink-0">
                <a href="{{ route('admin.teachers') }}" class="btn-primary bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold py-2.5 px-4 shadow-md shadow-blue-500/20 border-0">
                    Antrean Verifikasi Guru →
                </a>
            </div>
        </div>

        <!-- 4 Stat Cards with stat-card-gradient -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Stat 1: Total Pengguna -->
            <div class="stat-card-gradient space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Pengguna</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center ring-1 ring-blue-500/10">
                        <x-icons.user class="w-4 h-4 text-blue-600" />
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight tabular-nums">{{ number_format($totalUsers, 0, ',', '.') }}</p>
                <p class="text-[11px] text-blue-600 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    Orang Tua & Guru Terdaftar
                </p>
            </div>

            <!-- Stat 2: Guru Terverifikasi -->
            <div class="stat-card-gradient space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Guru Terverifikasi</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center ring-1 ring-emerald-500/10">
                        <x-icons.check class="w-4 h-4 text-emerald-600" />
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight tabular-nums">{{ number_format($verifiedTeachers, 0, ',', '.') }}</p>
                <a href="{{ route('admin.teachers') }}" class="text-[11px] text-emerald-700 font-bold hover:underline flex items-center gap-1">
                    <span>Kelola berkas verifikasi</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Stat 3: Total Booking Les -->
            <div class="stat-card-gradient space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Booking Les</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center ring-1 ring-purple-500/10">
                        <x-icons.calendar class="w-4 h-4 text-purple-600" />
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-purple-600 tracking-tight tabular-nums">{{ number_format($totalBookings, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 font-medium">Sesi bimbingan tatap muka</p>
            </div>

            <!-- Stat 4: Komisi Platform -->
            <div class="stat-card-gradient space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Komisi Platform</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center ring-1 ring-amber-500/10">
                        <x-icons.receipt class="w-4 h-4 text-amber-600" />
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight tabular-nums truncate">Rp {{ number_format($totalCommission, 0, ',', '.') }}</p>
                <a href="{{ route('admin.settings') }}" class="text-[11px] text-slate-500 hover:text-blue-600 font-semibold block">
                    Tarif komisi platform 10% →
                </a>
            </div>
        </div>

        <!-- Two Columns: Guru Perlu Verifikasi & Pemesanan Terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Guru Perlu Verifikasi -->
            <div class="lg:col-span-6 panel-window p-6 sm:p-7 space-y-5 bg-white shadow-sm border border-slate-200/80 rounded-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Antrean Verifikasi Guru</h3>
                        <p class="text-xs text-slate-500">Tinjau kesesuaian KTP, Ijazah S1 PDDikti & SKCK</p>
                    </div>
                    <a href="{{ route('admin.teachers') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($pendingTeachers as $t)
                        <div class="p-4 rounded-xl border border-slate-200/80 hover:border-blue-300 transition flex items-center justify-between gap-4 bg-slate-50/50">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-xs">
                                    {{ substr($t->user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $t->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate">{{ $t->education ?? 'Pendidikan Guru' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="badge-pending text-[10px] py-0.5 px-2">
                                            {{ $t->documents->count() }} Dokumen Diunggah
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('admin.teachers.show', $t->id) }}" class="btn-primary text-xs py-2 px-3.5 shrink-0 shadow-xs font-bold">
                                Tinjau Berkas →
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs panel-window border-dashed">
                            Semua berkas guru telah tuntas ditinjau. Tidak ada antrean verifikasi saat ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Pemesanan Terbaru -->
            <div class="lg:col-span-6 panel-window p-6 sm:p-7 space-y-5 bg-white shadow-sm border border-slate-200/80 rounded-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pemesanan Les Terbaru</h3>
                        <p class="text-xs text-slate-500">Monitoring sesi bimbingan dan alur pembayaran escrow</p>
                    </div>
                    <a href="{{ route('admin.bookings') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentBookings as $b)
                        <div class="p-4 rounded-xl border border-slate-200/80 bg-white flex items-center justify-between gap-4 text-xs card-hover">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-slate-500 text-[11px]">{{ $b->booking_code }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-bold text-blue-700 truncate">{{ $b->subject->name }}</span>
                                </div>
                                <p class="text-slate-700 font-medium truncate flex items-center gap-1.5">
                                    <span class="font-bold">{{ $b->parent->name }}</span>
                                    <x-icons.arrow-right class="w-3 h-3 text-slate-400 shrink-0" />
                                    <span>{{ $b->teacher->name }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 flex items-center gap-1">
                                    <x-icons.calendar class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                    <span>{{ $b->booking_date->translatedFormat('d M Y') }} • {{ substr($b->start_time, 0, 5) }} WIB</span>
                                </p>
                            </div>

                            <div class="text-right shrink-0 space-y-1">
                                <p class="font-black text-slate-900 text-sm tabular-nums">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</p>
                                @php
                                    $badge = match($b->status) {
                                        'completed' => 'badge-verified',
                                        'confirmed', 'in_progress' => 'badge-active',
                                        'accepted' => 'badge-warning',
                                        default => 'badge-pending',
                                    };
                                @endphp
                                <span class="{{ $badge }} text-[10px] py-0.5 px-2">{{ ucfirst($b->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs panel-window border-dashed">
                            Belum ada transaksi pemesanan les terbaru.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Optional Bottom Section: Support Tickets -->
        @if($recentTickets->isNotEmpty())
            <div class="panel-window p-6 sm:p-7 space-y-4 bg-white shadow-sm border border-slate-200/80 rounded-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tiket Bantuan Terbuka</h3>
                        <p class="text-xs text-slate-500">Pertanyaan dan keluhan pengguna yang membutuhkan respons admin</p>
                    </div>
                    <a href="{{ route('admin.tickets') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Buka Pusat Bantuan →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($recentTickets as $ticket)
                        <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-bold text-slate-400 text-[10px]">{{ $ticket->ticket_code }}</span>
                                <span class="badge-pending text-[10px]">{{ ucfirst($ticket->priority) }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 truncate">{{ $ticket->subject }}</h4>
                            <p class="text-[11px] text-slate-500">Dari: {{ $ticket->user->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
