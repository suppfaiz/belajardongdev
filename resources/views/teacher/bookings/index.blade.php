<x-layouts.dashboard title="Pemesanan Les Guru — BelajarYuk" headerTitle="Permintaan & Jadwal Les" headerSubtitle="Kelola permintaan les masuk dan pantau sesi les aktif Anda">
    <div class="space-y-6 pt-2">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100/90 rounded-lg text-xs font-medium overflow-x-auto border border-slate-200/60 max-w-full">
            <a href="{{ route('teacher.bookings') }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ empty($status) ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Semua</a>
            <a href="{{ route('teacher.bookings', ['status' => 'pending_teacher']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'pending_teacher' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Permintaan Masuk</a>
            <a href="{{ route('teacher.bookings', ['status' => 'accepted']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'accepted' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Menunggu Bayar</a>
            <a href="{{ route('teacher.bookings', ['status' => 'confirmed']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'confirmed' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Terkonfirmasi</a>
            <a href="{{ route('teacher.bookings', ['status' => 'completed']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'completed' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Selesai</a>
        </div>

        <div class="space-y-3.5">
            @forelse($bookings as $booking)
                <div class="panel-window p-5 sm:p-6 transition hover:border-slate-300 flex flex-col md:flex-row md:items-center justify-between gap-5">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-700 font-bold text-base flex items-center justify-center shrink-0 border border-blue-200">
                            {{ substr($booking->student->name, 0, 1) }}
                        </div>

                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-semibold text-slate-400 tabular-nums">{{ $booking->booking_code }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $booking->student->name }}</h3>
                                <span class="text-slate-400 text-xs">({{ $booking->parent->name }})</span>
                            </div>

                            <p class="text-xs text-slate-600 font-medium">
                                {{ $booking->subject->name }} &bull; Durasi {{ $booking->session_duration_minutes }} menit
                            </p>

                            <p class="text-[11px] text-blue-600 font-medium flex items-center gap-1.5 tabular-nums">
                                <x-icons.calendar class="w-3.5 h-3.5 text-blue-500" />
                                <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} &bull; {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span>
                            </p>
                            <p class="text-[11px] text-slate-500 truncate flex items-center gap-1.5">
                                <x-icons.map-pin class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                <span>{{ $booking->lesson_address }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="md:text-right shrink-0 flex md:flex-col items-center md:items-end justify-between gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <div>
                            @php
                                $statusBadge = match($booking->status) {
                                    'pending_teacher' => ['label' => 'Perlu Ditanggapi', 'class' => 'bg-amber-50 text-amber-800 border-amber-300 font-semibold px-2.5 py-0.5 rounded-full text-[10px]'],
                                    'accepted' => ['label' => 'Menunggu Pembayaran', 'class' => 'badge-pending'],
                                    'confirmed' => ['label' => 'Jadwal Siap (Lunas)', 'class' => 'badge-active'],
                                    'in_progress' => ['label' => 'Sedang Berlangsung', 'class' => 'badge-active'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'badge-verified'],
                                    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-600 text-[10px] px-2 py-0.5 rounded-full font-semibold border border-rose-200'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded-full font-semibold'],
                                    'refunded' => ['label' => 'Dana Direfund', 'class' => 'bg-purple-50 text-purple-700 text-[10px] px-2 py-0.5 rounded-full font-semibold border border-purple-200'],
                                    default => ['label' => $booking->status, 'class' => 'badge-pending'],
                                };
                            @endphp
                            <span class="{{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                            <p class="text-xs font-semibold text-slate-900 mt-1 tabular-nums">Pendapatan Bersih: <span class="font-bold text-blue-600">Rp {{ number_format($booking->teacher_net_amount, 0, ',', '.') }}</span></p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('teacher.bookings.show', $booking->id) }}" class="btn-secondary btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel-window p-10 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto border border-blue-100">
                        <x-icons.calendar class="w-6 h-6" />
                    </div>
                    <p class="text-sm font-semibold text-slate-800">Tidak ada pemesanan dalam kategori status ini.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layouts.dashboard>
