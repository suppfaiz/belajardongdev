<x-layouts.dashboard title="Pemesanan {{ $booking->booking_code }} — BelajarYuk" headerTitle="Detail Jadwal Les" headerSubtitle="Informasi murid dan alamat tatap muka les privat">
    <div class="max-w-4xl space-y-6 pt-4">
        <a href="{{ route('teacher.bookings') }}" class="text-xs text-blue-600 font-semibold hover:underline inline-flex items-center gap-1">
            ← Kembali ke Daftar Pemesanan
        </a>

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-mono font-bold text-slate-400">KODE PEMESANAN</span>
                    <h2 class="text-2xl font-extrabold text-slate-900">{{ $booking->booking_code }}</h2>
                </div>
                <div class="text-left sm:text-right">
                    @php
                        $statusBadge = match($booking->status) {
                            'pending_teacher' => ['label' => 'Permintaan Baru (Perlu Tindakan)', 'class' => 'bg-amber-100 text-amber-800 border border-amber-300 font-bold px-3 py-1 rounded-full text-xs'],
                            'accepted' => ['label' => 'Diterima (Menunggu Pembayaran)', 'class' => 'badge-pending text-xs py-1 px-3'],
                            'confirmed' => ['label' => 'Jadwal Siap (Lunas)', 'class' => 'badge-active text-xs py-1 px-3'],
                            'in_progress' => ['label' => 'Sedang Berlangsung', 'class' => 'badge-active text-xs py-1 px-3'],
                            'completed' => ['label' => 'Selesai', 'class' => 'badge-verified text-xs py-1 px-3'],
                            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-600 text-xs px-3 py-1 rounded font-bold'],
                            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-slate-100 text-slate-500 text-xs px-3 py-1 rounded font-bold'],
                            default => ['label' => $booking->status, 'class' => 'badge-pending text-xs py-1 px-3'],
                        };
                    @endphp
                    <span class="{{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                    <p class="text-xs text-slate-400 mt-1">Diajukan: {{ $booking->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            <!-- Actions if Pending Request -->
            @if($booking->status === 'pending_teacher')
                <div class="p-6 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs" x-data="{ rejectModal: false }">
                    <div class="space-y-1">
                        <h4 class="font-bold text-amber-950">Konfirmasi Ketersediaan Jadwal Anda</h4>
                        <p class="text-amber-800">Apakah Anda bersedia mengajar pada jadwal dan lokasi yang diminta orang tua murid?</p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <button type="button" @click="rejectModal = true" class="btn-secondary text-rose-600 hover:bg-rose-50 border-rose-200 text-xs py-2 px-4 font-bold">
                            Tolak Permintaan
                        </button>
                        <form method="POST" action="{{ route('teacher.bookings.accept', $booking->id) }}">
                            @csrf
                            <button type="submit" class="btn-primary py-2 px-5 text-xs font-bold flex items-center gap-1.5">
                                <x-icons.check class="w-3.5 h-3.5 text-white" />
                                <span>Terima Permintaan</span>
                            </button>
                        </form>
                    </div>

                    <!-- Reject Modal -->
                    <div x-show="rejectModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
                        <div @click.away="rejectModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 text-left">
                            <h4 class="text-sm font-bold text-slate-900">Alasan Penolakan Permintaan</h4>
                            <form method="POST" action="{{ route('teacher.bookings.reject', $booking->id) }}" class="space-y-3">
                                @csrf
                                <textarea name="rejection_reason" required rows="3" placeholder="Sebutkan alasan penolakan..." class="w-full p-3 border rounded-xl text-xs outline-none focus:border-rose-500"></textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="rejectModal = false" class="btn-secondary py-1.5 px-3 text-xs">Batal</button>
                                    <button type="submit" class="btn-primary bg-rose-600 hover:bg-rose-700 py-1.5 px-4 text-xs font-bold">Kirim Penolakan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'confirmed' || $booking->status === 'in_progress')
                <div class="p-6 rounded-2xl bg-blue-50 border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                    <div class="space-y-1">
                        <h4 class="font-bold text-blue-950">Sesi Les Telah Siap Dilaksanakan</h4>
                        <p class="text-blue-800">Datang ke alamat les sesuai jam yang disepakati. Tekan tombol berikut saat Anda telah hadir di lokasi.</p>
                    </div>
                    <form method="POST" action="{{ route('teacher.bookings.confirm-session', $booking->id) }}">
                        @csrf
                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700 py-2.5 px-5 text-xs font-bold shrink-0 flex items-center gap-1.5">
                            <x-icons.check class="w-3.5 h-3.5 text-white" />
                            <span>Konfirmasi Hadir di Rumah Murid</span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Grid Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <!-- Data Murid & Orang Tua -->
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Data Murid & Orang Tua</span>
                        <h4 class="text-sm font-bold text-slate-900">{{ $booking->student->name }}</h4>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-1 border-t border-slate-200/80">
                            <p class="text-slate-500">Orang Tua: <span class="font-semibold text-slate-800">{{ $booking->parent->name }}</span> ({{ $booking->parent->phone }})</p>
                            <a href="{{ route('teacher.messages', ['parent_id' => $booking->parent_id]) }}" class="btn-navy py-1 px-3 text-[11px] font-bold shadow-2xs inline-flex items-center gap-1.5 self-start sm:self-auto">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                <span>Chat Orang Tua</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Alamat Lengkap Les Offline (Tatap Muka)</span>
                        <p class="text-slate-800 font-semibold leading-relaxed flex items-start gap-1.5">
                            <x-icons.map-pin class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" />
                            <span>{{ $booking->lesson_address }}</span>
                        </p>
                        @if($booking->notes)
                            <p class="text-slate-600 italic pt-1 border-t border-slate-200">Catatan dari Orang Tua: “{{ $booking->notes }}”</p>
                        @endif
                    </div>
                </div>

                <!-- Waktu & Estimasi Pendapatan -->
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Paket & Jadwal Sesi</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-[#080F63]">
                                {{ $booking->plan_label }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <x-icons.calendar class="w-4 h-4 text-slate-400" />
                            <span>Mulai: {{ $booking->booking_date->translatedFormat('l, d F Y') }}</span>
                        </p>
                        <p class="text-blue-600 font-semibold">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB ({{ $booking->session_duration_minutes }} menit/sesi)</p>
                        <p class="text-slate-500">Mata Pelajaran: <span class="font-bold text-slate-800">{{ $booking->subject->name }}</span> ({{ $booking->total_sessions ?? 1 }} Pertemuan)</p>

                        @if(!empty($booking->schedule_dates) && count($booking->schedule_dates) > 1)
                            <div class="pt-2 border-t border-slate-200" x-data="{ openDates: false }">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Jadwal Sesi Rutin:</span>
                                    <button type="button" @click="openDates = !openDates" class="text-[10px] text-blue-600 font-bold hover:underline">
                                        <span x-text="openDates ? 'Sembunyikan' : 'Buka Semua ({{ count($booking->schedule_dates) }})'"></span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-1 mt-1 max-h-28 overflow-y-auto" x-show="openDates">
                                    @foreach($booking->schedule_dates as $idx => $sDate)
                                        <span class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] text-slate-700">
                                            #{{ $idx + 1 }}: {{ \Carbon\Carbon::parse($sDate)->translatedFormat('d M') }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Pendapatan Guru (Snapshot)</span>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Sesi Bruto ({{ $booking->total_sessions ?? 1 }} sesi):</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($booking->session_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Potongan Komisi Platform ({{ $booking->platform_commission_rate }}%):</span>
                            <span class="text-rose-600">- Rp {{ number_format($booking->platform_commission_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-blue-600 pt-1 border-t border-slate-200 text-sm">
                            <span>Pendapatan Bersih Anda:</span>
                            <span>Rp {{ number_format($booking->teacher_net_amount, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-400 italic">Pendapatan bersih akan otomatis masuk ke Saldo Tersedia Anda saat sesi les diselesaikan.</p>
                    </div>
                </div>
            </div>

            <!-- Review Feedback if any -->
            @if($booking->review)
                <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-amber-950">Ulasan & Rating dari Orang Tua:</span>
                        <div class="flex items-center gap-0.5 text-amber-400">
                            @for($i = 0; $i < $booking->review->rating; $i++)
                                <x-icons.star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                            @endfor
                        </div>
                    </div>
                    <p class="text-slate-700 italic">“{{ $booking->review->comment }}”</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
