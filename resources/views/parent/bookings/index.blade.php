<x-layouts.dashboard title="Pesanan Les — BelajarYuk">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                    Pesanan Les
                </h1>
                <p class="text-sm text-[#747B8C] mt-1">
                    Lacak status pesanan bimbingan les anak dari pemesanan awal hingga selesai
                </p>
            </div>
            <a href="{{ route('teachers.search') }}" class="btn-navy text-xs py-2.5 px-5 shadow-md self-start sm:self-auto">
                + Pesan Guru Baru
            </a>
        </div>

        <!-- Filter Status Tabs -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-200/70 rounded-full text-xs font-semibold overflow-x-auto max-w-full">
            <a href="{{ route('parent.bookings') }}" 
               class="px-4 py-2 rounded-full transition whitespace-nowrap {{ empty($status) ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-[#747B8C] hover:text-[#171923]' }}">
                Semua
            </a>
            <a href="{{ route('parent.bookings', ['status' => 'pending_teacher']) }}" 
               class="px-4 py-2 rounded-full transition whitespace-nowrap flex items-center gap-1.5 {{ $status === 'pending_teacher' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-[#747B8C] hover:text-[#171923]' }}">
                <span>Menunggu Konfirmasi</span>
            </a>
            <a href="{{ route('parent.bookings', ['status' => 'accepted']) }}" 
               class="px-4 py-2 rounded-full transition whitespace-nowrap flex items-center gap-1.5 {{ $status === 'accepted' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-[#747B8C] hover:text-[#171923]' }}">
                <span>Menunggu Pembayaran</span>
            </a>
            <a href="{{ route('parent.bookings', ['status' => 'confirmed']) }}" 
               class="px-4 py-2 rounded-full transition whitespace-nowrap flex items-center gap-1.5 {{ $status === 'confirmed' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-[#747B8C] hover:text-[#171923]' }}">
                <span>Sedang Berjalan</span>
            </a>
            <a href="{{ route('parent.bookings', ['status' => 'completed']) }}" 
               class="px-4 py-2 rounded-full transition whitespace-nowrap flex items-center gap-1.5 {{ $status === 'completed' ? 'bg-[#080F63] text-white shadow-xs font-bold' : 'text-[#747B8C] hover:text-[#171923]' }}">
                <span>Selesai</span>
            </a>
        </div>

        <!-- Bookings List -->
        <div class="space-y-4">
            @forelse($bookings as $booking)
                <div class="bd-card p-6 transition duration-200 hover:-translate-y-0.5">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <!-- Left Details -->
                        <div class="flex items-start gap-4 sm:gap-5 min-w-0">
                            <div class="relative shrink-0">
                                <img class="w-16 h-16 rounded-full object-cover ring-2 ring-[#080F63]/10 shadow-xs" 
                                     src="{{ $booking->teacher->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80' }}" 
                                     alt="{{ $booking->teacher->name }}">
                                @if($booking->teacher->teacherProfile?->is_verified)
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center ring-2 ring-white">
                                        ✓
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-slate-400">#{{ $booking->booking_code }}</span>
                                    <span class="text-slate-300">•</span>
                                    <h3 class="text-base font-extrabold text-[#171923] truncate">{{ $booking->teacher->name }}</h3>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#080F63] font-bold">
                                        {{ $booking->subject->name }}
                                    </span>
                                    <span class="text-slate-400">untuk</span>
                                    <span class="font-bold text-[#171923]">
                                        {{ $booking->student->name }} ({{ strtoupper($booking->student->grade_level) }})
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 pt-1 text-xs text-[#747B8C]">
                                    <span>📅 {{ $booking->booking_date->translatedFormat('l, d F Y') }}</span>
                                    <span>•</span>
                                    <span>⏰ {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Price & Status Actions -->
                        <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-3 pt-4 lg:pt-0 border-t lg:border-t-0 border-[#E5E8F0]">
                            <div class="text-left lg:text-right">
                                <span class="text-xs text-[#747B8C] block">Total Biaya</span>
                                <span class="text-lg font-extrabold text-[#080F63]">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                @php
                                    $bClass = match($booking->status) {
                                        'confirmed', 'in_progress', 'completed' => 'badge-status-confirmed',
                                        'pending', 'accepted' => 'badge-status-pending',
                                        default => 'badge-status-scheduled'
                                    };
                                    $bLabel = match($booking->status) {
                                        'pending' => 'Menunggu Guru',
                                        'accepted' => 'Menunggu Pembayaran',
                                        'confirmed' => 'Terkonfirmasi (Escrow)',
                                        'in_progress' => 'Sedang Berjalan',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => $booking->status
                                    };
                                @endphp
                                <span class="badge-status {{ $bClass }}">
                                    {{ $bLabel }}
                                </span>

                                <a href="{{ route('parent.bookings.show', $booking->id) }}" class="btn-navy py-1.5 px-3.5 text-xs font-semibold">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bd-card p-12 text-center text-slate-400 space-y-2">
                    <p class="text-base font-bold text-[#171923]">Belum ada pesanan pada status ini.</p>
                    <p class="text-xs text-[#747B8C]">Cari dan pesan guru les privat terverifikasi sekarang juga.</p>
                    <a href="{{ route('teachers.search') }}" class="btn-navy text-xs mt-3 inline-flex">
                        Cari Guru &rarr;
                    </a>
                </div>
            @endforelse
        </div>

        @if(method_exists($bookings, 'links'))
            <div class="pt-4 flex justify-center">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
