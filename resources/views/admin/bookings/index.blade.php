<x-layouts.dashboard title="Monitoring Booking — BelajarYuk" headerTitle="Monitoring Booking Marketplace" headerSubtitle="Pantau seluruh pemesanan les tatap muka di seluruh Indonesia">
    <div class="space-y-5 pt-2">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100/90 rounded-lg text-xs font-medium overflow-x-auto border border-slate-200/60 max-w-full">
            <a href="{{ route('admin.bookings') }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ empty($status) ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Semua</a>
            <a href="{{ route('admin.bookings', ['status' => 'pending_teacher']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'pending_teacher' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Menunggu Guru</a>
            <a href="{{ route('admin.bookings', ['status' => 'accepted']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'accepted' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Menunggu Bayar</a>
            <a href="{{ route('admin.bookings', ['status' => 'confirmed']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'confirmed' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Terkonfirmasi</a>
            <a href="{{ route('admin.bookings', ['status' => 'completed']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'completed' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Selesai</a>
        </div>

        <!-- Bookings Table -->
        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode Booking</th>
                            <th scope="col">Orang Tua & Murid</th>
                            <th scope="col">Guru & Pelajaran</th>
                            <th scope="col">Jadwal Les</th>
                            <th scope="col" class="text-right">Nilai Transaksi</th>
                            <th scope="col" class="text-right">Komisi (10%)</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                            <tr>
                                <td class="font-mono font-semibold text-slate-900 tabular-nums">
                                    {{ $b->booking_code }}
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $b->parent->name }}</p>
                                    <p class="text-slate-400 text-[11px]">Murid: {{ $b->student->name }}</p>
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $b->teacher->name }}</p>
                                    <p class="text-slate-400 text-[11px]">{{ $b->subject->name }}</p>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-800 tabular-nums">{{ $b->booking_date->format('d M Y') }}</p>
                                    <p class="text-[11px] text-blue-600 font-medium tabular-nums">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</p>
                                </td>
                                <td class="text-right font-semibold text-slate-900 tabular-nums">
                                    Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-emerald-600 tabular-nums">
                                    Rp {{ number_format($b->platform_commission_amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $statusBadge = match($b->status) {
                                            'completed' => 'badge-verified',
                                            'confirmed' => 'badge-active',
                                            'accepted' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'pending_teacher' => 'badge-pending',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                        $statusLabel = match($b->status) {
                                            'completed' => 'Selesai',
                                            'confirmed' => 'Terkonfirmasi',
                                            'accepted' => 'Menunggu Bayar',
                                            'pending_teacher' => 'Menunggu Guru',
                                            default => ucfirst(str_replace('_', ' ', $b->status)),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <x-icons.receipt class="w-8 h-8 text-slate-300" />
                                        <p class="font-medium text-slate-600">Tidak ada data pemesanan les.</p>
                                        <p class="text-[11px] text-slate-400">Belum ada booking dengan filter status ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>
