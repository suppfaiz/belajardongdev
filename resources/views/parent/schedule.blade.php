<x-layouts.dashboard title="Jadwal Saya — BelajarYuk">
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ viewMode: 'minggu' }">
        <!-- Header Page Title & Subtitle -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                    Jadwal Saya
                </h1>
                <p class="text-sm text-[#747B8C] mt-1">
                    Kalender waktu belajar les privat anak agar tidak bentrok
                </p>
            </div>

            <!-- View Switcher & Date Range -->
            <div class="flex items-center gap-3">
                <!-- Hari / Minggu / Bulan Buttons -->
                <div class="p-1 rounded-full bg-slate-200/80 flex items-center">
                    <button type="button" 
                            @click="viewMode = 'hari'"
                            :class="viewMode === 'hari' ? 'bg-[#080F63] text-white font-bold shadow-xs' : 'text-[#747B8C] font-semibold hover:text-[#171923]'"
                            class="px-3.5 py-1.5 rounded-full text-xs transition">
                        Hari
                    </button>
                    <button type="button" 
                            @click="viewMode = 'minggu'"
                            :class="viewMode === 'minggu' ? 'bg-[#080F63] text-white font-bold shadow-xs' : 'text-[#747B8C] font-semibold hover:text-[#171923]'"
                            class="px-3.5 py-1.5 rounded-full text-xs transition">
                        Minggu
                    </button>
                    <button type="button" 
                            @click="viewMode = 'bulan'"
                            :class="viewMode === 'bulan' ? 'bg-[#080F63] text-white font-bold shadow-xs' : 'text-[#747B8C] font-semibold hover:text-[#171923]'"
                            class="px-3.5 py-1.5 rounded-full text-xs transition">
                        Bulan
                    </button>
                </div>

                <!-- Date Range Navigator -->
                <div class="flex items-center gap-2 bg-white border border-[#E5E8F0] px-3.5 py-1.5 rounded-full text-xs font-bold text-[#171923] shadow-xs">
                    <span>11 - 17 Oktober 2026</span>
                    <div class="flex items-center gap-1 text-slate-400">
                        <button type="button" class="hover:text-[#080F63]">&lsaquo;</button>
                        <button type="button" class="hover:text-[#080F63]">&rsaquo;</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Column Layout: Time-Grid Calendar (Left) + Agenda Hari Ini (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left: Weekly Time-Grid (8 cols) -->
            <div class="lg:col-span-8 bd-card p-6 overflow-x-auto">
                <!-- Day Headers (Sen 12, Sel 13, Rab 14, Kam 15, Jum 16, Sab 17) -->
                <div class="grid grid-cols-7 gap-2 pb-4 border-b border-[#E5E8F0] text-center min-w-[500px]">
                    <div class="text-xs font-bold text-slate-400">Jam</div>
                    <div class="p-2 rounded-xl {{ now()->isMonday() ? 'bg-[#080F63] text-white' : 'text-[#171923]' }}">
                        <span class="text-xs block font-semibold">Sen</span>
                        <span class="text-base font-extrabold">12</span>
                    </div>
                    <div class="p-2 rounded-xl {{ now()->isTuesday() ? 'bg-[#080F63] text-white' : 'text-[#171923]' }}">
                        <span class="text-xs block font-semibold">Sel</span>
                        <span class="text-base font-extrabold">13</span>
                    </div>
                    <div class="p-2 rounded-xl {{ now()->isWednesday() ? 'bg-[#080F63] text-white' : 'text-[#171923]' }}">
                        <span class="text-xs block font-semibold">Rab</span>
                        <span class="text-base font-extrabold">14</span>
                    </div>
                    <div class="p-2 rounded-xl {{ now()->isThursday() ? 'bg-[#080F63] text-white' : 'text-[#171923]' }}">
                        <span class="text-xs block font-semibold">Kam</span>
                        <span class="text-base font-extrabold">15</span>
                    </div>
                    <div class="p-2 rounded-xl {{ now()->isFriday() ? 'bg-[#080F63] text-white' : 'text-[#171923]' }}">
                        <span class="text-xs block font-semibold">Jum</span>
                        <span class="text-base font-extrabold">16</span>
                    </div>
                    <div class="p-2 rounded-xl {{ now()->isSaturday() ? 'bg-[#080F63] text-white' : 'text-[#171923]' }}">
                        <span class="text-xs block font-semibold">Sab</span>
                        <span class="text-base font-extrabold">17</span>
                    </div>
                </div>

                <!-- Time Grid Rows (07:00 - 15:00) -->
                <div class="divide-y divide-slate-100 min-w-[500px] text-xs">
                    <!-- 08:00 Row -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-center">
                        <span class="text-slate-400 font-medium">08:00</span>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <!-- 09:00 Row (Math in Blue on Monday) -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-start relative">
                        <span class="text-slate-400 font-medium">09:00</span>
                        <!-- Matematika - Biru Muda Block -->
                        <div class="col-span-1 p-2.5 rounded-xl event-math shadow-xs">
                            <span class="font-extrabold block">Matematika</span>
                            <span class="text-[10px] opacity-80 block">09:00 - 10:30</span>
                            <span class="text-[10px] font-semibold mt-1 block">Siti Nurhaliza</span>
                        </div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <!-- 10:00 Row -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-center">
                        <span class="text-slate-400 font-medium">10:00</span>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <!-- 11:00 Row (English in Green on Wednesday) -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-start">
                        <span class="text-slate-400 font-medium">11:00</span>
                        <div></div>
                        <div></div>
                        <!-- Bahasa Inggris - Hijau Muda Block -->
                        <div class="col-span-1 p-2.5 rounded-xl event-english shadow-xs">
                            <span class="font-extrabold block">B. Inggris</span>
                            <span class="text-[10px] opacity-80 block">11:00 - 12:30</span>
                            <span class="text-[10px] font-semibold mt-1 block">Ahmad Rizky</span>
                        </div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <!-- 13:00 Row -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-center">
                        <span class="text-slate-400 font-medium">13:00</span>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <!-- 14:00 Row (Science on Thursday) -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-start">
                        <span class="text-slate-400 font-medium">14:00</span>
                        <div></div>
                        <div></div>
                        <div></div>
                        <!-- IPA - Sky Blue Block -->
                        <div class="col-span-1 p-2.5 rounded-xl event-science shadow-xs">
                            <span class="font-extrabold block">IPA Terpadu</span>
                            <span class="text-[10px] opacity-80 block">14:00 - 15:30</span>
                            <span class="text-[10px] font-semibold mt-1 block">Rina Amelia</span>
                        </div>
                        <div></div>
                        <div></div>
                    </div>

                    <!-- 15:00 Row -->
                    <div class="grid grid-cols-7 gap-2 py-3 items-center">
                        <span class="text-slate-400 font-medium">15:00</span>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>

            <!-- Right: Panel Agenda Hari Ini (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bd-card p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                        <h3 class="font-bold text-base text-[#171923]">Agenda Hari Ini</h3>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                            {{ now()->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <!-- Sesi 1: Matematika (Online with Join Meeting) -->
                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#080F63]">Matematika - Kelas 7</span>
                            <span class="badge-status badge-status-confirmed text-[10px]">Online</span>
                        </div>
                        <div class="text-xs text-[#747B8C]">
                            <p>Tutor: <span class="font-bold text-[#171923]">Siti Nurhaliza</span></p>
                            <p class="mt-0.5">Waktu: <span class="font-bold text-[#171923]">10:00 - 11:30</span></p>
                            <p class="mt-0.5">Murid: <span class="text-[#171923]">Rian Pratama</span></p>
                        </div>
                        <!-- Action: Join Meeting -->
                        <div class="pt-2 flex items-center justify-between gap-2">
                            <a href="https://meet.google.com" target="_blank" class="btn-navy py-2 px-4 text-xs font-bold flex-1 justify-center">
                                Join Meeting &rarr;
                            </a>
                            <a href="{{ route('parent.bookings') }}" class="btn-white-pill py-2 px-3 text-xs font-semibold">
                                Detail
                            </a>
                        </div>
                    </div>

                    <!-- Sesi 2: Bahasa Inggris (Tatap Muka) -->
                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-700">Bahasa Inggris - Kelas 5</span>
                            <span class="badge-status badge-status-scheduled text-[10px]">Tatap Muka</span>
                        </div>
                        <div class="text-xs text-[#747B8C]">
                            <p>Tutor: <span class="font-bold text-[#171923]">Ahmad Rizky</span></p>
                            <p class="mt-0.5">Waktu: <span class="font-bold text-[#171923]">14:00 - 15:30</span></p>
                            <p class="mt-0.5">Lokasi: <span class="text-[#171923]">Rumah Murid</span></p>
                        </div>
                        <div class="pt-2 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Guru dalam perjalanan</span>
                            <a href="{{ route('parent.bookings') }}" class="text-xs font-bold text-[#080F63] hover:underline">
                                Detail &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Sesi 3: IPA -->
                    <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-700">IPA - Kelas 8</span>
                            <span class="badge-status badge-status-scheduled text-[10px]">Dijadwalkan</span>
                        </div>
                        <div class="text-xs text-[#747B8C]">
                            <p>Tutor: <span class="font-bold text-[#171923]">Rina Amelia</span> • 16:00 - 17:30</p>
                        </div>
                    </div>

                    <!-- Summary Card: Total Hari Ini: 3 Sesi -->
                    <div class="p-4 rounded-2xl bg-[#080F63] text-white flex items-center justify-between shadow-soft">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                                📅
                            </div>
                            <div>
                                <span class="text-[11px] text-blue-200 uppercase font-semibold block">Rekap Harian</span>
                                <span class="text-base font-extrabold text-white">Total Hari Ini: 3 sesi</span>
                            </div>
                        </div>
                        <span class="text-xs font-bold bg-white/20 px-2.5 py-1 rounded-full text-white">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
