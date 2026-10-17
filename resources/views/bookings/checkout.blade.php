<x-layouts.app title="Pembayaran Les Tatap Muka — Belajar Dong">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ selectedMethod: 'qris' }">
        <!-- Breadcrumb -->
        <nav class="flex text-xs font-semibold text-slate-400 gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('teachers.search') }}" class="hover:text-blue-600 transition">Cari Guru</a>
            <span>/</span>
            <a href="{{ route('teachers.show', $booking->teacher->teacherProfile->id) }}" class="hover:text-blue-600 transition">{{ $booking->teacher->name }}</a>
            <span>/</span>
            <span class="text-slate-800">Checkout Pembayaran</span>
        </nav>

        <!-- Stepper Visualizer -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 card-shadow">
            <div class="flex items-center justify-between max-w-3xl mx-auto text-xs">
                <!-- Step 1 -->
                <div class="flex items-center gap-2.5 text-emerald-600 font-bold">
                    <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 shadow-xs">
                        <x-icons.check class="w-4 h-4 text-emerald-600" />
                    </span>
                    <span class="hidden sm:inline">1. Pilih Guru & Waktu</span>
                    <span class="sm:hidden">1. Guru</span>
                </div>
                <div class="h-0.5 flex-1 mx-2 sm:mx-4 bg-emerald-300"></div>

                <!-- Step 2 -->
                <div class="flex items-center gap-2.5 text-emerald-600 font-bold">
                    <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 shadow-xs">
                        <x-icons.check class="w-4 h-4 text-emerald-600" />
                    </span>
                    <span class="hidden sm:inline">2. Data Rumah & Murid</span>
                    <span class="sm:hidden">2. Alamat</span>
                </div>
                <div class="h-0.5 flex-1 mx-2 sm:mx-4 bg-blue-600"></div>

                <!-- Step 3 (Active) -->
                <div class="flex items-center gap-2.5 text-blue-600 font-bold">
                    <span class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs ring-4 ring-blue-100">
                        3
                    </span>
                    <span class="hidden sm:inline">3. Pembayaran Escrow</span>
                    <span class="sm:hidden">3. Bayar</span>
                </div>
                <div class="h-0.5 flex-1 mx-2 sm:mx-4 bg-slate-200"></div>

                <!-- Step 4 -->
                <div class="flex items-center gap-2.5 text-slate-400 font-semibold">
                    <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                        4
                    </span>
                    <span class="hidden sm:inline">4. Les Dikonfirmasi</span>
                    <span class="sm:hidden">4. Selesai</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Order Summary & Lesson Specs -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Teacher & Lesson Card -->
                <div class="panel-window p-6 sm:p-7 space-y-5">
                    <div class="panel-window-header">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold text-xs">Offline Les</span>
                            <h2 class="text-base font-extrabold text-slate-900">Rincian Pertemuan Les Privat</h2>
                        </div>
                        <span class="text-xs font-mono font-bold text-blue-600 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                            {{ $booking->booking_code }}
                        </span>
                    </div>

                    <!-- Teacher info banner -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50/70 border border-slate-200">
                        <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 font-black text-xl flex items-center justify-center overflow-hidden border border-blue-200 shrink-0 shadow-xs">
                            @if($booking->teacher->avatar)
                                <img src="{{ $booking->teacher->avatar }}" alt="{{ $booking->teacher->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($booking->teacher->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-slate-900 truncate">{{ $booking->teacher->name }}</h3>
                                @if($booking->teacher->teacherProfile?->is_verified)
                                    <span class="badge-verified text-[10px] py-0.5 px-2">Terverifikasi</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">{{ $booking->subject->name }} • {{ $booking->teacher->teacherProfile?->education ?? 'Pendidikan Guru' }}</p>
                            <p class="text-xs text-blue-600 font-bold mt-1">Rp {{ number_format($booking->hourly_rate_snapshot, 0, ',', '.') }} / jam</p>
                        </div>
                    </div>

                    <!-- Data Grid Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1">
                            <span class="form-label text-[10px]">Murid / Siswa</span>
                            <p class="font-bold text-slate-900 text-sm">{{ $booking->student->name }}</p>
                            <p class="text-slate-500 uppercase text-[11px] font-semibold">{{ $booking->student->grade_level }} {{ $booking->student->class_grade ? '• ' . $booking->student->class_grade : '' }}</p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1">
                            <span class="form-label text-[10px]">Waktu & Jadwal Pertemuan</span>
                            <p class="font-bold text-slate-900 text-sm">{{ $booking->booking_date->translatedFormat('l, d F Y') }}</p>
                            <p class="text-blue-600 font-bold text-[11px]">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB ({{ $booking->session_duration_minutes }} menit)</p>
                        </div>
                    </div>

                    <!-- Location Box -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="form-label text-[10px]">Lokasi Rumah (Alamat Les Tatap Muka)</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 flex items-center gap-1">
                                <x-icons.shield class="w-3 h-3 text-emerald-600" />
                                Terproteksi Privasi
                            </span>
                        </div>
                        <p class="text-xs text-slate-800 leading-relaxed font-medium">{{ $booking->lesson_address }}</p>
                        @if($booking->notes)
                            <div class="pt-1.5 border-t border-slate-100 text-[11px] text-slate-500 italic">
                                Catatan: “{{ $booking->notes }}”
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Escrow Security Assurance Card -->
                <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white space-y-3 shadow-md">
                    <div class="flex items-center gap-2.5 text-blue-400 font-bold text-sm">
                        <div class="w-7 h-7 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <x-icons.shield class="w-4 h-4 text-blue-400" />
                        </div>
                        <span>Jaminan Keamanan Escrow Belajar Dong</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Dana pembayaran Anda **tidak langsung diberikan ke guru**. Dana disimpan aman dalam rekening penampung resmi (escrow) platform dan baru diteruskan ke guru setelah Anda mengonfirmasi bahwa sesi les tatap muka telah terlaksana dengan baik di rumah Anda.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 text-[11px] text-blue-200">
                        <span class="flex items-center gap-1.5">
                            <x-icons.check class="w-3.5 h-3.5 text-emerald-400" />
                            100% Garansi Uang Kembali jika Guru Berhalangan
                        </span>
                        <span class="flex items-center gap-1.5">
                            <x-icons.check class="w-3.5 h-3.5 text-emerald-400" />
                            Verifikasi KTP & SKCK Asli Pendidik
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Price Breakdown & Multi-Channel Payment -->
            <div class="lg:col-span-5 space-y-6">
                <div class="panel-window p-6 sm:p-7 space-y-5">
                    <div class="panel-window-header">
                        <h3 class="text-base font-extrabold text-slate-900">Ringkasan Biaya</h3>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                            Harga Transparan
                        </span>
                    </div>

                    <!-- Cost Breakdown List -->
                    <div class="space-y-3 text-xs text-slate-600 border-b border-slate-100 pb-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Paket Bimbingan</span>
                            <span class="font-bold text-slate-900">{{ $booking->plan_label }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Jumlah Pertemuan</span>
                            <span class="font-bold text-[#080F63]">{{ $booking->total_sessions ?? 1 }} Sesi</span>
                        </div>

                        @if(($booking->discount_amount ?? 0) > 0)
                            <div class="flex items-center justify-between text-slate-500">
                                <span>Subtotal Normal</span>
                                <span class="line-through text-slate-400">Rp {{ number_format(($booking->session_fee + $booking->discount_amount), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-emerald-700 font-bold">
                                <span>Diskon Paket ({{ $booking->discount_percent }}%)</span>
                                <span>- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="font-medium">Biaya Sesi Pengajar</span>
                            <span class="font-bold text-slate-900 tabular-nums">Rp {{ number_format($booking->session_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-500">
                            <span>Biaya Operasional & Komisi Guru</span>
                            <span class="text-slate-400 font-medium">Termasuk ({{ $booking->platform_commission_rate }}%)</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-500">
                            <span>Biaya Proteksi Escrow & Asuransi</span>
                            <span class="text-emerald-600 font-bold">Gratis (Rp 0)</span>
                        </div>
                    </div>

                    <!-- Total Price Box -->
                    <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200/80 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-blue-900 uppercase tracking-wider block">Total Tagihan Pembayaran</span>
                            <span class="text-[10px] text-blue-600 font-medium">Sudah termasuk seluruh pajak & biaya</span>
                        </div>
                        <span class="text-2xl font-black text-blue-600 tracking-tight tabular-nums">
                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Payment Method Selection Form -->
                    <form method="POST" action="{{ route('bookings.process-checkout', $booking->id) }}" class="space-y-4 pt-1">
                        @csrf
                        <div class="flex items-center justify-between">
                            <label class="form-label">Pilih Metode Pembayaran</label>
                            <span class="text-[11px] font-bold text-blue-600">Verifikasi Otomatis</span>
                        </div>

                        <div class="space-y-2.5">
                            <!-- Channel 1: QRIS Instan (Paling Direkomendasikan) -->
                            <label class="flex items-start justify-between p-3.5 rounded-2xl border cursor-pointer transition hover:border-blue-400" :class="selectedMethod === 'qris' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 bg-white'">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="payment_method" value="qris" x-model="selectedMethod" class="mt-1 text-blue-600">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs font-bold text-slate-900">QRIS Instan (Semua Pembayaran)</p>
                                            <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Paling Cepat</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">BCA Mobile, Livin' Mandiri, BRImo, BNI, GoPay, OVO, ShopeePay, Dana</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 shrink-0 font-black text-[10px]">
                                    QR
                                </div>
                            </label>

                            <!-- Channel 2: Virtual Account Bank Mandiri / BCA / BRI / BNI -->
                            <label class="flex items-start justify-between p-3.5 rounded-2xl border cursor-pointer transition hover:border-blue-400" :class="selectedMethod === 'virtual_account' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 bg-white'">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="payment_method" value="virtual_account" x-model="selectedMethod" class="mt-1 text-blue-600">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Virtual Account Otomatis (24 Jam)</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Bank Mandiri, BCA, BRI, BNI, Permata Bank</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 font-bold text-[10px]">
                                    VA
                                </div>
                            </label>

                            <!-- Channel 3: E-Wallet (GoPay / DANA) -->
                            <label class="flex items-start justify-between p-3.5 rounded-2xl border cursor-pointer transition hover:border-blue-400" :class="selectedMethod === 'gopay' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 bg-white'">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="payment_method" value="gopay" x-model="selectedMethod" class="mt-1 text-blue-600">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">E-Wallet GoPay / DANA</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Langsung diarahkan ke aplikasi e-wallet Anda</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#080F63] flex items-center justify-center shrink-0 font-bold text-[10px]">
                                    APP
                                </div>
                            </label>

                            <!-- Channel 4: Transfer Bank Manual -->
                            <label class="flex items-start justify-between p-3.5 rounded-2xl border cursor-pointer transition hover:border-blue-400" :class="selectedMethod === 'bank_transfer' ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-500/20 shadow-xs' : 'border-slate-200 bg-white'">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="selectedMethod" class="mt-1 text-blue-600">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Transfer Bank Manual</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Rekening Giro Resmi Belajar Dong Indonesia</p>
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 font-bold text-[10px]">
                                    TF
                                </div>
                            </label>
                        </div>

                        <!-- Cancellation & Refund Policy Accordion -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1.5 leading-relaxed" x-data="{ expanded: false }">
                            <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                                <p class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <x-icons.shield class="w-4 h-4 text-blue-600" />
                                    <span>Ketentuan Pembatalan & Pengembalian Dana</span>
                                </p>
                                <span class="text-blue-600 font-bold" x-text="expanded ? '▲ Sembunyikan' : '▼ Lihat Ketentuan'"></span>
                            </div>
                            <div x-show="expanded" x-transition class="pt-2 border-t border-slate-200 space-y-1 text-slate-500">
                                <p>• Pembatalan oleh orang tua minimal 6 jam sebelum sesi: dana dikembalikan 100% utuh.</p>
                                <p>• Pembatalan mendadak kurang dari 6 jam: dikenakan kompensasi waktu perjalanan guru sebesar 25%.</p>
                                <p>• Jika guru berhalangan hadir atau terlambat lebih dari 30 menit tanpa konfirmasi: dana 100% otomatis dikembalikan.</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full btn-primary py-4 text-sm font-bold shadow-md justify-center flex items-center gap-2">
                            <span>Lanjut ke Instruksi Pembayaran</span>
                            <x-icons.arrow-right class="w-4 h-4" />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
