<x-layouts.dashboard title="Pilih Jadwal & Paket Pemesanan — Belajar Dong">
    <div class="max-w-4xl mx-auto space-y-6" x-data="{
        step: 2,
        planType: 'daily',
        selectedDays: [1, 4],
        daysList: [
            { id: 1, name: 'Senin', short: 'Sen' },
            { id: 2, name: 'Selasa', short: 'Sel' },
            { id: 3, name: 'Rabu', short: 'Rab' },
            { id: 4, name: 'Kamis', short: 'Kam' },
            { id: 5, name: 'Jumat', short: 'Jum' },
            { id: 6, name: 'Sabtu', short: 'Sab' },
            { id: 0, name: 'Minggu', short: 'Min' }
        ],
        hourlyRate: {{ (int) $teacherProfile->hourly_rate }},
        sessionDuration: 90,
        selectedDate: '{{ now()->addDay()->format('Y-m-d') }}',
        selectedTime: '10:00',
        endTime: '11:30',
        selectedSlotLabel: '10:00 - 11:30 WIB',
        selectedStudentId: '{{ $students->first()?->id }}',
        selectedSubjectId: '{{ $teacherProfile->subjects->first()?->id }}',
        timeSlots: [
            { start: '08:00', end: '09:30', label: '08:00 - 09:30 WIB' },
            { start: '10:00', end: '11:30', label: '10:00 - 11:30 WIB' },
            { start: '13:00', end: '14:30', label: '13:00 - 14:30 WIB' },
            { start: '15:30', end: '17:00', label: '15:30 - 17:00 WIB' },
            { start: '17:00', end: '18:30', label: '17:00 - 18:30 WIB' },
            { start: '19:00', end: '20:30', label: '19:00 - 20:30 WIB' }
        ],
        activeDay: {{ now()->addDay()->format('j') }},
        currentMonthName: '{{ now()->translatedFormat('F Y') }}',

        toggleDay(dayId) {
            const index = this.selectedDays.indexOf(dayId);
            if (index > -1) {
                if (this.selectedDays.length > 1) {
                    this.selectedDays.splice(index, 1);
                }
            } else {
                this.selectedDays.push(dayId);
                this.selectedDays.sort((a, b) => a - b);
            }
        },

        isDaySelected(dayId) {
            return this.selectedDays.includes(dayId);
        },

        sessionsPerWeek() {
            if (this.planType === 'daily') return 1;
            return Math.max(1, this.selectedDays.length);
        },

        totalSessions() {
            if (this.planType === 'daily') return 1;
            if (this.planType === 'monthly') return this.sessionsPerWeek() * 4;
            if (this.planType === 'six_months') return this.sessionsPerWeek() * 24;
            return 1;
        },

        singleSessionFee() {
            return Math.round((this.hourlyRate * this.sessionDuration) / 60);
        },

        subtotal() {
            return this.singleSessionFee() * this.totalSessions();
        },

        discountPercent() {
            if (this.planType === 'monthly') return 5;
            if (this.planType === 'six_months') return 15;
            return 0;
        },

        discountAmount() {
            return Math.round((this.subtotal() * this.discountPercent()) / 100);
        },

        totalPrice() {
            return Math.max(0, this.subtotal() - this.discountAmount());
        },

        formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        },

        selectDay(day) {
            this.activeDay = day;
            const pad = (n) => n < 10 ? '0' + n : n;
            this.selectedDate = '{{ now()->format('Y-m') }}-' + pad(day);
        },

        selectSlot(slot) {
            this.selectedTime = slot.start;
            this.endTime = slot.end;
            this.selectedSlotLabel = slot.label;
        }
    }">
        <!-- 1. Stepper 4 Tahap -->
        <div class="bd-card p-6">
            <div class="flex items-center justify-between max-w-2xl mx-auto relative">
                <!-- Connecting Line -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 z-0"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-[#080F63] z-0 transition-all duration-300" style="width: 33%;"></div>

                <!-- Step 1: Pilih Guru (Completed) -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full bg-[#080F63] text-white flex items-center justify-center font-bold text-xs ring-4 ring-white">
                        ✓
                    </div>
                    <span class="text-xs font-semibold text-[#080F63] mt-1.5">Pilih Guru</span>
                </div>

                <!-- Step 2: Paket & Jadwal (Active) -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full bg-[#080F63] text-white flex items-center justify-center font-bold text-xs ring-4 ring-white shadow-md">
                        2
                    </div>
                    <span class="text-xs font-bold text-[#080F63] mt-1.5">Paket & Jadwal</span>
                </div>

                <!-- Step 3: Detail Alamat -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs ring-4 ring-white">
                        3
                    </div>
                    <span class="text-xs font-medium text-slate-400 mt-1.5">Detail</span>
                </div>

                <!-- Step 4: Pembayaran Escrow -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs ring-4 ring-white">
                        4
                    </div>
                    <span class="text-xs font-medium text-slate-400 mt-1.5">Pembayaran</span>
                </div>
            </div>
        </div>

        <!-- 2. Main Booking Wizard Form Container -->
        <form method="POST" action="{{ route('bookings.store', $teacherProfile->id) }}" class="space-y-6">
            @csrf

            <!-- Hidden Inputs driven by Alpine -->
            <input type="hidden" name="plan_type" :value="planType">
            <template x-for="day in selectedDays" :key="day">
                <input type="hidden" name="selected_days[]" :value="day">
            </template>
            <input type="hidden" name="booking_date" :value="selectedDate">
            <input type="hidden" name="start_time" :value="selectedTime">
            <input type="hidden" name="student_id" :value="selectedStudentId">
            <input type="hidden" name="subject_id" :value="selectedSubjectId">
            <input type="hidden" name="session_duration_minutes" :value="sessionDuration">

            <div class="bd-card p-6 sm:p-8 space-y-7">
                <!-- Section Header with Teacher Profile Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#E5E8F0]">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-[#171923] tracking-tight">Pilih Paket & Waktu Les</h2>
                        <p class="text-xs text-[#747B8C] mt-1">Tentukan durasi paket pembelajaran dan hari bimbingan yang pas untuk anak Anda</p>
                    </div>

                    <!-- Teacher Chip Badge -->
                    <div class="flex items-center gap-3 p-2.5 pr-4 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] shrink-0">
                        <img class="w-11 h-11 rounded-xl object-cover ring-2 ring-[#080F63]/10" 
                             src="{{ $teacherProfile->user->avatar ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80' }}" 
                             alt="{{ $teacherProfile->user->name }}">
                        <div class="text-left">
                            <span class="text-xs font-bold text-[#171923] block">{{ $teacherProfile->user->name }}</span>
                            <span class="text-[11px] text-[#080F63] font-black">Rp {{ number_format($teacherProfile->hourly_rate, 0, ',', '.') }}/jam</span>
                        </div>
                    </div>
                </div>

                <!-- 1. PILIHAN PAKET BELAJAR (Harian, Bulanan, 6 Bulanan) -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-black text-[#171923] uppercase tracking-wider">
                            1. Pilih Paket Bimbingan Belajar
                        </label>
                        <span class="text-xs font-semibold text-blue-600" x-text="planType === 'daily' ? 'Pilihan Fleksibel' : (planType === 'monthly' ? 'Diskon 5%' : 'Diskon Spesial 15%')"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Option A: Harian (Per Sesi) -->
                        <div @click="planType = 'daily'"
                             :class="planType === 'daily' ? 'border-[#080F63] bg-blue-50/50 shadow-md ring-2 ring-[#080F63]/20' : 'border-[#E5E8F0] bg-white hover:border-slate-300'"
                             class="p-5 rounded-2xl border-2 transition cursor-pointer flex flex-col justify-between relative group">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        Per Sesi
                                    </span>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                         :class="planType === 'daily' ? 'border-[#080F63] bg-[#080F63] text-white' : 'border-slate-300'">
                                        <span x-show="planType === 'daily'" class="text-[10px] font-bold">✓</span>
                                    </div>
                                </div>
                                <h3 class="text-base font-black text-[#171923]">Harian (1 Sesi)</h3>
                                <p class="text-xs text-[#747B8C] leading-relaxed">
                                    Fleksibel untuk uji coba atau persiapan ujian mendadak. 1 sesi belajar tatap muka di rumah.
                                </p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100">
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">Tarif:</span>
                                <span class="text-base font-black text-[#080F63]" x-text="formatRupiah(singleSessionFee())"></span>
                            </div>
                        </div>

                        <!-- Option B: Paket 1 Bulan -->
                        <div @click="planType = 'monthly'"
                             :class="planType === 'monthly' ? 'border-[#080F63] bg-blue-50/50 shadow-md ring-2 ring-[#080F63]/20' : 'border-[#E5E8F0] bg-white hover:border-slate-300'"
                             class="p-5 rounded-2xl border-2 transition cursor-pointer flex flex-col justify-between relative group">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        ★ Populer • Hemat 5%
                                    </span>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                         :class="planType === 'monthly' ? 'border-[#080F63] bg-[#080F63] text-white' : 'border-slate-300'">
                                        <span x-show="planType === 'monthly'" class="text-[10px] font-bold">✓</span>
                                    </div>
                                </div>
                                <h3 class="text-base font-black text-[#171923]">Paket 1 Bulan</h3>
                                <p class="text-xs text-[#747B8C] leading-relaxed">
                                    Komitmen 4 minggu berturut-turut. Pilih hari les rutin dalam seminggu sesuai kenyamanan anak.
                                </p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100">
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">Mulai Dari (4 Minggu):</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-base font-black text-[#080F63]" x-text="formatRupiah(totalPrice())"></span>
                                    <span class="text-[10px] text-emerald-600 font-bold" x-show="planType === 'monthly'">Hemat 5%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Option C: Paket 6 Bulan (1 Semester) -->
                        <div @click="planType = 'six_months'"
                             :class="planType === 'six_months' ? 'border-[#080F63] bg-blue-50/50 shadow-md ring-2 ring-[#080F63]/20' : 'border-[#E5E8F0] bg-white hover:border-slate-300'"
                             class="p-5 rounded-2xl border-2 transition cursor-pointer flex flex-col justify-between relative group">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Paling Hemat • Diskon 15%
                                    </span>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                         :class="planType === 'six_months' ? 'border-[#080F63] bg-[#080F63] text-white' : 'border-slate-300'">
                                        <span x-show="planType === 'six_months'" class="text-[10px] font-bold">✓</span>
                                    </div>
                                </div>
                                <h3 class="text-base font-black text-[#171923]">Paket 6 Bulan (Semester)</h3>
                                <p class="text-xs text-[#747B8C] leading-relaxed">
                                    Bimbingan intensif 24 minggu untuk peningkatan nilai rapor dan prestasi akademik maksimal.
                                </p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100">
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">Total Hemat (24 Minggu):</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-base font-black text-[#080F63]" x-text="formatRupiah(totalPrice())"></span>
                                    <span class="text-[10px] text-emerald-600 font-bold" x-show="planType === 'six_months'">Hemat 15%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. PILIHAN HARI LES RUTIN (Jika Memilih Paket Bulanan / 6 Bulanan) -->
                <div x-show="planType !== 'daily'" x-transition class="p-5 sm:p-6 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-sm font-extrabold text-[#171923] flex items-center gap-2">
                                <span>📅 Tentukan Hari Les Rutin di Setiap Minggu</span>
                                <span class="text-xs font-normal text-slate-500">(Bisa pilih hari apa saja)</span>
                            </h3>
                            <p class="text-xs text-[#747B8C] mt-0.5">
                                Klik untuk memilih 1 hari atau beberapa hari sekaligus dalam seminggu
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-[#080F63] bg-white px-3 py-1 rounded-full border border-[#E5E8F0] shadow-2xs">
                                <span x-text="sessionsPerWeek()"></span> Hari / Minggu • <span x-text="totalSessions()"></span> Total Sesi
                            </span>
                        </div>
                    </div>

                    <!-- 7 Days Interactive Pills -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2">
                        <template x-for="day in daysList" :key="day.id">
                            <button type="button" 
                                    @click="toggleDay(day.id)"
                                    :class="isDaySelected(day.id) ? 'bg-[#080F63] text-white font-bold border-[#080F63] shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border-[#E5E8F0]'"
                                    class="py-3 px-2 rounded-xl border text-xs text-center transition flex flex-col items-center gap-1">
                                <span class="text-sm" x-text="isDaySelected(day.id) ? '✓' : '○'"></span>
                                <span class="font-bold" x-text="day.name"></span>
                            </button>
                        </template>
                    </div>

                    <p class="text-[11px] text-blue-700 bg-blue-50 p-3 rounded-xl border border-blue-100 leading-relaxed">
                        💡 <strong>Jadwal Otomatis:</strong> Sesi les akan dijadwalkan secara rutin pada hari-hari yang Anda pilih di atas selama <span x-text="planType === 'monthly' ? '4 minggu (1 bulan)' : '24 minggu (6 bulan)'"></span> mulai dari tanggal pertemuan pertama.
                    </p>
                </div>

                <!-- 3. Profil Murid & Mata Pelajaran Selector -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-[#171923] uppercase tracking-wider mb-2">Profil Anak (Murid)</label>
                        <select x-model="selectedStudentId" class="w-full bg-white border border-[#E5E8F0] rounded-xl py-3 px-4 text-xs font-bold text-[#171923] outline-none focus:border-[#080F63] focus:ring-2 focus:ring-[#080F63]/20 shadow-2xs cursor-pointer">
                            @foreach($students as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ strtoupper($st->grade_level) }} - {{ $st->class_grade }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#171923] uppercase tracking-wider mb-2">Mata Pelajaran</label>
                        <select x-model="selectedSubjectId" class="w-full bg-white border border-[#E5E8F0] rounded-xl py-3 px-4 text-xs font-bold text-[#171923] outline-none focus:border-[#080F63] focus:ring-2 focus:ring-[#080F63]/20 shadow-2xs cursor-pointer">
                            @foreach($teacherProfile->subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 4. Tanggal Mulai Les (Pertemuan Pertama) & Jam Les -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                    <!-- Sisi Kiri: Tanggal Mulai Les (7 cols) -->
                    <div class="md:col-span-7 p-5 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-black text-[#171923] uppercase tracking-wider">
                                    <span x-text="planType === 'daily' ? 'Tanggal Les' : 'Tanggal Mulai Sesi Pertama'"></span>
                                </h3>
                                <p class="text-[11px] text-[#747B8C]" x-text="currentMonthName"></p>
                            </div>
                            <span class="text-xs font-bold text-[#080F63] bg-white px-2.5 py-1 rounded-full border border-[#E5E8F0]" x-text="selectedDate"></span>
                        </div>

                        <!-- Days of week -->
                        <div class="grid grid-cols-7 text-center text-xs font-bold text-[#747B8C] mb-1">
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                            <span>Min</span>
                        </div>

                        <!-- Days Grid -->
                        <div class="grid grid-cols-7 gap-1.5 text-center">
                            @for($i = 1; $i <= 31; $i++)
                                <button type="button" 
                                        @click="selectDay({{ $i }})"
                                        :class="activeDay === {{ $i }} ? 'bg-[#080F63] text-white font-black shadow-md' : 'text-[#171923] hover:bg-slate-200/70 font-semibold'"
                                        class="h-9 w-9 mx-auto rounded-full text-xs flex items-center justify-center transition">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>

                    <!-- Sisi Kanan: Jam Mulai & Durasi (5 cols) -->
                    <div class="md:col-span-5 space-y-4">
                        <div>
                            <label class="block text-xs font-black text-[#171923] uppercase tracking-wider mb-2">Durasi Setiap Sesi</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="sessionDuration = 60"
                                        :class="sessionDuration === 60 ? 'bg-[#080F63] text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-50 border-[#E5E8F0]'"
                                        class="py-2 rounded-xl border text-xs text-center transition">
                                    60 Menit
                                </button>
                                <button type="button" @click="sessionDuration = 90"
                                        :class="sessionDuration === 90 ? 'bg-[#080F63] text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-50 border-[#E5E8F0]'"
                                        class="py-2 rounded-xl border text-xs text-center transition">
                                    90 Menit
                                </button>
                                <button type="button" @click="sessionDuration = 120"
                                        :class="sessionDuration === 120 ? 'bg-[#080F63] text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-50 border-[#E5E8F0]'"
                                        class="py-2 rounded-xl border text-xs text-center transition">
                                    120 Menit
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-[#171923] uppercase tracking-wider mb-2">Jam Mulai Les</label>
                            <div class="space-y-2">
                                <template x-for="slot in timeSlots" :key="slot.start">
                                    <button type="button" 
                                            @click="selectSlot(slot)"
                                            :class="selectedTime === slot.start ? 'bg-[#080F63] text-white font-black border-[#080F63] shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-50 border-[#E5E8F0]'"
                                            class="w-full py-2.5 px-4 rounded-xl border text-xs font-semibold transition flex items-center justify-between">
                                        <span x-text="slot.label"></span>
                                        <span x-show="selectedTime === slot.start" class="text-white text-xs">✓</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Detail Alamat Rumah & Catatan Orang Tua -->
                <div class="p-5 sm:p-6 rounded-2xl bg-[#F8FAFC] border border-[#E5E8F0] space-y-4">
                    <h3 class="text-xs font-black text-[#171923] uppercase tracking-wider">Detail Lokasi Les Tatap Muka di Rumah</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Rumah Tempat Les:</label>
                            <textarea name="lesson_address" rows="2" required placeholder="Jl. Melati No. 12, RT 01/RW 02, Kebayoran Baru, Jakarta Selatan (Patokan dekat masjid)..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:border-[#080F63] focus:ring-2 focus:ring-[#080F63]/20 bg-white font-medium">{{ $students->first()?->address }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Khusus untuk Guru (Opsional):</label>
                            <input type="text" name="notes" placeholder="Contoh: Fokus pada latihan rumus cepat aljabar dan persiapan ulangan harian..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:border-[#080F63] focus:ring-2 focus:ring-[#080F63]/20 bg-white font-medium">
                        </div>
                    </div>
                </div>

                <!-- 6. Ringkasan Transparansi Biaya & Kalkulator Hemat -->
                <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-slate-900 via-[#080F63] to-[#0D1880] text-white shadow-soft-lg space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-white/15">
                        <div class="flex items-center gap-2.5">
                            <x-icons.receipt class="w-5 h-5 text-amber-300" />
                            <h4 class="text-sm font-extrabold text-white">Ringkasan Biaya Bimbingan Les</h4>
                        </div>
                        <span class="text-xs font-bold text-blue-200" x-text="planType === 'daily' ? 'Paket Harian' : (planType === 'monthly' ? 'Paket 1 Bulan (4 Minggu)' : 'Paket 6 Bulan (24 Minggu)')"></span>
                    </div>

                    <div class="space-y-2 text-xs text-blue-100">
                        <div class="flex justify-between">
                            <span>Tarif Per Sesi (<span x-text="sessionDuration + ' menit'"></span>):</span>
                            <span class="font-bold text-white" x-text="formatRupiah(singleSessionFee())"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jumlah Pertemuan Les:</span>
                            <span class="font-bold text-white" x-text="totalSessions() + ' Sesi'"></span>
                        </div>
                        <div class="flex justify-between" x-show="planType !== 'daily'">
                            <span>Subtotal Normal:</span>
                            <span class="text-slate-300 line-through" x-text="formatRupiah(subtotal())"></span>
                        </div>
                        <div class="flex justify-between text-emerald-300 font-bold" x-show="discountPercent() > 0">
                            <span>Diskon Paket (<span x-text="discountPercent() + '%'"></span>):</span>
                            <span>- <span x-text="formatRupiah(discountAmount())"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Garansi Rekening Escrow Belajar Dong:</span>
                            <span class="text-emerald-300 font-semibold">Gratis (Perlindungan 100%)</span>
                        </div>

                        <div class="flex justify-between items-baseline pt-3 border-t border-white/20 font-extrabold text-white">
                            <span class="text-xs">Total Pembayaran:</span>
                            <span class="text-xl sm:text-2xl text-amber-300 font-black tabular-nums" x-text="formatRupiah(totalPrice())"></span>
                        </div>
                    </div>
                </div>

                <!-- 7. Tombol Aksi Bawah -->
                <div class="pt-4 border-t border-[#E5E8F0] flex items-center justify-between">
                    <a href="{{ route('teachers.show', $teacherProfile->id) }}" class="btn-white-pill px-6 py-2.5 text-xs font-bold">
                        &larr; Kembali ke Profil Guru
                    </a>

                    <button type="submit" class="btn-navy px-8 py-3 text-xs font-bold shadow-md flex items-center gap-2">
                        <span>Lanjut ke Konfirmasi</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
