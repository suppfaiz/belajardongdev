<x-layouts.dashboard title="Milestone Verifikasi Guru — Belajar Dong" headerTitle="Tahapan Milestone Verifikasi Guru" headerSubtitle="Standar legalitas lengkap sesuai UU No. 14/2005 (Guru & Dosen), UU No. 35/2014 (Perlindungan Anak), dan UU PDP No. 27/2022">
    <div class="space-y-8 pt-4" x-data="{ currentMilestone: {{ $activeMilestone ?? 1 }} }">

        <!-- Top Overview & Verification Banner -->
        @if($profile->is_verified)
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-950 rounded-3xl p-6 sm:p-7 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 shadow-xs">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <x-icons.check class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-extrabold text-emerald-950">Profil Guru Telah Terverifikasi Penuh</h2>
                            <span class="badge-verified text-[11px] py-0.5 px-3">Lolos 100% Uji Regulasi</span>
                        </div>
                        <p class="text-xs text-emerald-700 mt-1 max-w-2xl leading-relaxed">
                            Seluruh milestone identitas kependudukan, ijazah akademik S1, SKCK kepolisian, dan pakta integritas perlindungan anak Anda telah divalidasi sah oleh tim verifikator Belajar Dong.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <span class="px-3 py-1.5 rounded-xl bg-white text-emerald-800 border border-emerald-200 text-xs font-bold shadow-xs">
                        KTP WNI Sah
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white text-emerald-800 border border-emerald-200 text-xs font-bold shadow-xs">
                        Ijazah S1 Valid
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white text-emerald-800 border border-emerald-200 text-xs font-bold shadow-xs">
                        SKCK Aktif
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white text-emerald-800 border border-emerald-200 text-xs font-bold shadow-xs">
                        Pakta Ramah Anak
                    </span>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-3xl p-6 sm:p-7 shadow-sm border border-blue-800/60 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-semibold">
                        <x-icons.shield class="w-3.5 h-3.5 text-blue-400" />
                        <span>Kepatuhan Standar Peraturan Perundang-undangan RI</span>
                    </div>
                    <h2 class="text-lg font-extrabold text-white">Lengkapi 4 Milestone Verifikasi Tenaga Pendidik</h2>
                    <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                        Untuk memberikan rasa aman maksimal bagi orang tua dan murid dalam les tatap muka di rumah, lengkapi data identitas, ijazah, SKCK, serta pakta perlindungan anak secara bertahap di bawah.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 min-w-[220px] shrink-0 text-center space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-blue-200">
                        <span>Kelengkapan Milestone</span>
                        <span>{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full h-3 bg-white/20 rounded-full overflow-hidden p-0.5">
                        <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-300 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <p class="text-[11px] text-blue-200/80">
                        @if($progressPercent == 100)
                            Seluruh data siap ditinjau admin
                        @else
                            {{ 4 - (int)round($progressPercent / 25) }} milestone tersisa
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <!-- Milestone Stepper Navigation Bar -->
        <div class="bg-white rounded-3xl p-3 sm:p-4 border border-slate-200 card-shadow">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                <!-- Milestone 1 Button -->
                <button type="button" @click="currentMilestone = 1" class="p-3 sm:p-3.5 rounded-2xl text-left transition flex items-center gap-3 border" :class="currentMilestone === 1 ? 'border-blue-600 bg-blue-50/50 shadow-xs ring-2 ring-blue-500/20' : 'border-transparent hover:bg-slate-50'">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold shrink-0 text-xs" :class="{{ $profile->isMilestone1Complete() ? 'true' : 'false' }} ? 'bg-emerald-100 text-emerald-700' : (currentMilestone === 1 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600')">
                        @if($profile->isMilestone1Complete())
                            <x-icons.check class="w-4 h-4 text-emerald-700" />
                        @else
                            1
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="block font-bold truncate" :class="currentMilestone === 1 ? 'text-blue-900' : 'text-slate-800'">Milestone 1</span>
                        <span class="text-[11px] text-slate-400 truncate block">KTP & Identitas</span>
                    </div>
                </button>

                <!-- Milestone 2 Button -->
                <button type="button" @click="currentMilestone = 2" class="p-3 sm:p-3.5 rounded-2xl text-left transition flex items-center gap-3 border" :class="currentMilestone === 2 ? 'border-blue-600 bg-blue-50/50 shadow-xs ring-2 ring-blue-500/20' : 'border-transparent hover:bg-slate-50'">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold shrink-0 text-xs" :class="{{ $profile->isMilestone2Complete() ? 'true' : 'false' }} ? 'bg-emerald-100 text-emerald-700' : (currentMilestone === 2 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600')">
                        @if($profile->isMilestone2Complete())
                            <x-icons.check class="w-4 h-4 text-emerald-700" />
                        @else
                            2
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="block font-bold truncate" :class="currentMilestone === 2 ? 'text-blue-900' : 'text-slate-800'">Milestone 2</span>
                        <span class="text-[11px] text-slate-400 truncate block">Ijazah & SK Guru</span>
                    </div>
                </button>

                <!-- Milestone 3 Button -->
                <button type="button" @click="currentMilestone = 3" class="p-3 sm:p-3.5 rounded-2xl text-left transition flex items-center gap-3 border" :class="currentMilestone === 3 ? 'border-blue-600 bg-blue-50/50 shadow-xs ring-2 ring-blue-500/20' : 'border-transparent hover:bg-slate-50'">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold shrink-0 text-xs" :class="{{ $profile->isMilestone3Complete() ? 'true' : 'false' }} ? 'bg-emerald-100 text-emerald-700' : (currentMilestone === 3 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600')">
                        @if($profile->isMilestone3Complete())
                            <x-icons.check class="w-4 h-4 text-emerald-700" />
                        @else
                            3
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="block font-bold truncate" :class="currentMilestone === 3 ? 'text-blue-900' : 'text-slate-800'">Milestone 3</span>
                        <span class="text-[11px] text-slate-400 truncate block">SKCK Kepolisian</span>
                    </div>
                </button>

                <!-- Milestone 4 Button -->
                <button type="button" @click="currentMilestone = 4" class="p-3 sm:p-3.5 rounded-2xl text-left transition flex items-center gap-3 border" :class="currentMilestone === 4 ? 'border-blue-600 bg-blue-50/50 shadow-xs ring-2 ring-blue-500/20' : 'border-transparent hover:bg-slate-50'">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold shrink-0 text-xs" :class="{{ $profile->isMilestone4Complete() ? 'true' : 'false' }} ? 'bg-emerald-100 text-emerald-700' : (currentMilestone === 4 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600')">
                        @if($profile->isMilestone4Complete())
                            <x-icons.check class="w-4 h-4 text-emerald-700" />
                        @else
                            4
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="block font-bold truncate" :class="currentMilestone === 4 ? 'text-blue-900' : 'text-slate-800'">Milestone 4</span>
                        <span class="text-[11px] text-slate-400 truncate block">Pakta Perlindungan</span>
                    </div>
                </button>

                <!-- Milestone 5 Button -->
                <button type="button" @click="currentMilestone = 5" class="p-3 sm:p-3.5 rounded-2xl text-left transition flex items-center gap-3 border col-span-2 md:col-span-1" :class="currentMilestone === 5 ? 'border-blue-600 bg-blue-50/50 shadow-xs ring-2 ring-blue-500/20' : 'border-transparent hover:bg-slate-50'">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold shrink-0 text-xs" :class="{{ $profile->is_verified ? 'true' : 'false' }} ? 'bg-emerald-500 text-white' : (currentMilestone === 5 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600')">
                        @if($profile->is_verified)
                            <x-icons.check class="w-4 h-4 text-white" />
                        @else
                            5
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="block font-bold truncate" :class="currentMilestone === 5 ? 'text-blue-900' : 'text-slate-800'">Status Akhir</span>
                        <span class="text-[11px] text-slate-400 truncate block">Tinjauan Admin</span>
                    </div>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left 8 Cols: Active Milestone Form -->
            <div class="lg:col-span-8 space-y-6">

                <!-- PANEL 1: MILESTONE 1 (KTP WNI & DATA DIRI) -->
                <div x-show="currentMilestone === 1" x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-xs">Milestone 1</span>
                                <h3 class="text-base font-extrabold text-slate-900">Identitas Kependudukan & KTP WNI</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Sesuai UU No. 24/2013 tentang Administrasi Kependudukan (Validasi WNI & Usia Dewasa)</p>
                        </div>
                        @if($profile->isMilestone1Complete())
                            <span class="badge-verified text-[11px] py-1 px-2.5">Selesai Diisi</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('teacher.documents.milestone1') }}" enctype="multipart/form-data" class="space-y-5 text-xs">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Nomor Induk Kependudukan (NIK 16 Digit) <span class="text-rose-500">*</span></label>
                                <input type="text" name="nik" value="{{ old('nik', $profile->nik) }}" required maxlength="16" placeholder="Contoh: 317101xxxxxxxxxx" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 font-mono text-sm">
                                <p class="text-[11px] text-slate-400 mt-1">Wajib 16 digit angka sesuai fisik e-KTP Anda.</p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span></label>
                                <input type="text" name="full_name_ktp" value="{{ old('full_name_ktp', $profile->full_name_ktp ?? $profile->user->name) }}" required placeholder="Contoh: Budi Santoso, S.Pd." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Tempat Lahir <span class="text-rose-500">*</span></label>
                                <input type="text" name="birth_place" value="{{ old('birth_place', $profile->birth_place) }}" required placeholder="Contoh: Jakarta" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input type="date" name="birth_date" value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <select name="gender" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm bg-white font-semibold">
                                    <option value="male" {{ old('gender', $profile->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Alamat Domisili Lengkap Sesuai e-KTP <span class="text-rose-500">*</span></label>
                            <textarea name="ktp_address" rows="2" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos..." class="w-full p-3 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">{{ old('ktp_address', $profile->ktp_address ?? $profile->service_address) }}</textarea>
                        </div>

                        <!-- Document File Uploads -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <label class="block font-bold text-slate-900">Unggah Foto / Scan e-KTP Asli</label>
                                <p class="text-[11px] text-slate-500">Foto jelas, tidak buram, seluruh informasi terbaca (PDF, JPG, PNG).</p>
                                <input type="file" name="ktp_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                @php $ktpDoc = $documents->where('document_type', 'ktp')->first(); @endphp
                                @if($ktpDoc)
                                    <div class="pt-1 flex items-center justify-between text-[11px] text-emerald-700 font-semibold">
                                        <span>✓ Berkas e-KTP sudah terunggah</span>
                                        <a href="{{ route('documents.download', $ktpDoc->id) }}" class="text-blue-600 hover:underline">Lihat Berkas</a>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <label class="block font-bold text-slate-900">Pasfoto / Swafoto Wajah Guru</label>
                                <p class="text-[11px] text-slate-500">Foto wajah jelas menghadap depan untuk verifikasi pencocokan identitas fisik.</p>
                                <input type="file" name="selfie_ktp_file" accept=".jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                @php $selfieDoc = $documents->where('document_type', 'selfie_ktp')->first(); @endphp
                                @if($selfieDoc)
                                    <div class="pt-1 flex items-center justify-between text-[11px] text-emerald-700 font-semibold">
                                        <span>✓ Swafoto sudah terunggah</span>
                                        <a href="{{ route('documents.download', $selfieDoc->id) }}" class="text-blue-600 hover:underline">Lihat Berkas</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                            <span class="text-slate-400 text-[11px]">Tersimpan privat & terenkripsi (UU PDP No. 27/2022)</span>
                            <button type="submit" class="btn-primary py-3 px-6 text-xs font-bold shadow-sm inline-flex items-center gap-2">
                                <span>Simpan &amp; Lanjut ke Milestone 2</span>
                                <span>→</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANEL 2: MILESTONE 2 (KUALIFIKASI AKADEMIK & SK GURU) -->
                <div x-show="currentMilestone === 2" x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-xs">Milestone 2</span>
                                <h3 class="text-base font-extrabold text-slate-900">Kualifikasi Akademik &amp; Legalitas Guru</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Sesuai UU No. 14/2005 tentang Guru &amp; Dosen (Kualifikasi S1/D-IV Terakreditasi PDDikti &amp; SK Mengajar)</p>
                        </div>
                        @if($profile->isMilestone2Complete())
                            <span class="badge-verified text-[11px] py-1 px-2.5">Selesai Diisi</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('teacher.documents.milestone2') }}" enctype="multipart/form-data" class="space-y-5 text-xs">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Jenjang Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                                <select name="degree_level" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm bg-white font-semibold">
                                    <option value="S1" {{ old('degree_level', $profile->degree_level) === 'S1' ? 'selected' : '' }}>Sarjana (S-1) — Standar UU</option>
                                    <option value="D4" {{ old('degree_level', $profile->degree_level) === 'D4' ? 'selected' : '' }}>Diploma Empat (D-IV) — Standar UU</option>
                                    <option value="S2" {{ old('degree_level', $profile->degree_level) === 'S2' ? 'selected' : '' }}>Magister (S-2)</option>
                                    <option value="S3" {{ old('degree_level', $profile->degree_level) === 'S3' ? 'selected' : '' }}>Doktor (S-3)</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block font-bold text-slate-700 uppercase mb-1">Nama Perguruan Tinggi / Universitas <span class="text-rose-500">*</span></label>
                                <input type="text" name="university_name" value="{{ old('university_name', $profile->university_name) }}" required placeholder="Contoh: Universitas Negeri Jakarta (UNJ) / Universitas Indonesia" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Program Studi / Jurusan <span class="text-rose-500">*</span></label>
                                <input type="text" name="major" value="{{ old('major', $profile->major) }}" required placeholder="Contoh: Pendidikan Matematika / Fisika" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Nomor Registrasi Ijazah <span class="text-rose-500">*</span></label>
                                <input type="text" name="diploma_number" value="{{ old('diploma_number', $profile->diploma_number) }}" required placeholder="Contoh: 12345/UNJ-S1/2022" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 font-mono text-sm">
                                <p class="text-[11px] text-slate-400 mt-1">Dicocokkan pada PDDikti Kemendikbud.</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase mb-1">Tahun Lulus <span class="text-rose-500">*</span></label>
                                    <input type="number" name="graduation_year" value="{{ old('graduation_year', $profile->graduation_year ?? 2022) }}" required min="1970" max="{{ date('Y') }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase mb-1">IPK (Skala 4.0)</label>
                                    <input type="number" step="0.01" name="gpa" value="{{ old('gpa', $profile->gpa) }}" placeholder="3.75" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Teaching Status & School Placement -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                            <span class="font-bold text-slate-900 block text-xs uppercase tracking-wider">Status Mengajar &amp; Legalitas Satuan Pendidikan</span>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Status Pengajar Saat Ini <span class="text-rose-500">*</span></label>
                                    <select name="teaching_status" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm bg-white font-semibold">
                                        <option value="Guru Honorer Sekolah Negeri" {{ old('teaching_status', $profile->teaching_status) === 'Guru Honorer Sekolah Negeri' ? 'selected' : '' }}>Guru Honorer Sekolah Negeri</option>
                                        <option value="Guru Sekolah Swasta / Yayasan" {{ old('teaching_status', $profile->teaching_status) === 'Guru Sekolah Swasta / Yayasan' ? 'selected' : '' }}>Guru Sekolah Swasta / Yayasan</option>
                                        <option value="Pengajar Lembaga Bimbingan Belajar" {{ old('teaching_status', $profile->teaching_status) === 'Pengajar Lembaga Bimbingan Belajar' ? 'selected' : '' }}>Pengajar Lembaga Bimbel</option>
                                        <option value="Guru Les Privat Profesional" {{ old('teaching_status', $profile->teaching_status) === 'Guru Les Privat Profesional' ? 'selected' : '' }}>Guru Les Privat Mandiri</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Nama Sekolah / Satuan Pendidikan</label>
                                    <input type="text" name="school_origin" value="{{ old('school_origin', $profile->school_origin) }}" placeholder="Contoh: SMP Negeri 1 Jakarta / SMA Al-Azhar" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Nomor SK Pengangkatan Guru / Yayasan</label>
                                    <input type="text" name="sk_guru_number" value="{{ old('sk_guru_number', $profile->sk_guru_number) }}" placeholder="Contoh: 421.2/089/SMPN-01/2024" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 font-mono text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Upload Ijazah & SK Guru -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <label class="block font-bold text-slate-900">Berkas Scan Ijazah S1/D-IV Asli / Legalisir</label>
                                <p class="text-[11px] text-slate-500">Scan berwarna halaman depan ijazah (PDF, JPG, PNG).</p>
                                <input type="file" name="ijazah_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                @php $ijazahDoc = $documents->where('document_type', 'ijazah')->first(); @endphp
                                @if($ijazahDoc)
                                    <div class="pt-1 flex items-center justify-between text-[11px] text-emerald-700 font-semibold">
                                        <span>✓ Berkas Ijazah sudah terunggah</span>
                                        <a href="{{ route('documents.download', $ijazahDoc->id) }}" class="text-blue-600 hover:underline">Lihat Berkas</a>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <label class="block font-bold text-slate-900">Berkas SK Pengangkatan Guru / Sertifikat Mengajar</label>
                                <p class="text-[11px] text-slate-500">Surat penugasan resmi dari kepala sekolah/lembaga pendidikan.</p>
                                <input type="file" name="sk_guru_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                @php $skDoc = $documents->where('document_type', 'sk_guru')->first(); @endphp
                                @if($skDoc)
                                    <div class="pt-1 flex items-center justify-between text-[11px] text-emerald-700 font-semibold">
                                        <span>✓ Berkas SK Guru sudah terunggah</span>
                                        <a href="{{ route('documents.download', $skDoc->id) }}" class="text-blue-600 hover:underline">Lihat Berkas</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                            <button type="button" @click="currentMilestone = 1" class="btn-secondary py-2.5 px-4 text-xs font-semibold">
                                ← Kembali ke Milestone 1
                            </button>
                            <button type="submit" class="btn-primary py-3 px-6 text-xs font-bold shadow-sm inline-flex items-center gap-2">
                                <span>Simpan &amp; Lanjut ke Milestone 3</span>
                                <span>→</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANEL 3: MILESTONE 3 (SKCK KEPOLISIAN) -->
                <div x-show="currentMilestone === 3" x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-xs">Milestone 3</span>
                                <h3 class="text-base font-extrabold text-slate-900">Catatan Kepolisian &amp; SKCK Aktif</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Sesuai UU No. 35/2014 &amp; Perkapolri No. 18/2014 (Bebas Catatan Pidana Demi Keamanan Murid)</p>
                        </div>
                        @if($profile->isMilestone3Complete())
                            <span class="badge-verified text-[11px] py-1 px-2.5">Selesai Diisi</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('teacher.documents.milestone3') }}" enctype="multipart/form-data" class="space-y-5 text-xs">
                        @csrf

                        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 text-amber-950 space-y-1">
                            <span class="font-bold flex items-center gap-1.5">
                                <x-icons.shield class="w-4 h-4 text-amber-600" />
                                Standar Wajib Les Offline Datang ke Rumah Murid
                            </span>
                            <p class="text-[11px] text-amber-900 leading-relaxed">
                                SKCK diterbitkan oleh POLRI untuk membuktikan bahwa tenaga pendidik tidak memiliki riwayat kejahatan terhadap anak, tindak asusila, atau tindak pidana lainnya. Masa berlaku aktif adalah 6 bulan sejak tanggal penerbitan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Nomor Registrasi SKCK <span class="text-rose-500">*</span></label>
                                <input type="text" name="skck_number" value="{{ old('skck_number', $profile->skck_number) }}" required placeholder="Contoh: SKCK/YANMIN/VI/2024/POLRES" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 font-mono text-sm">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Kantor Kepolisian Penerbit <span class="text-rose-500">*</span></label>
                                <input type="text" name="skck_issuer" value="{{ old('skck_issuer', $profile->skck_issuer) }}" required placeholder="Contoh: Polres Metro Jakarta Selatan" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Masa Berlaku Berakhir Pada <span class="text-rose-500">*</span></label>
                                <input type="date" name="skck_valid_until" value="{{ old('skck_valid_until', $profile->skck_valid_until?->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                                <p class="text-[11px] text-slate-400 mt-1">Harus masih aktif (belum kedaluwarsa).</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                            <label class="block font-bold text-slate-900">Unggah Berkas Scan SKCK Resmi dari Kepolisian (PDF, JPG, PNG)</label>
                            <p class="text-[11px] text-slate-500">Scan seluruh halaman SKCK dengan stempel basah atau barcode legalisir resmi.</p>
                            <input type="file" name="skck_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            @php $skckDoc = $documents->where('document_type', 'skck')->first(); @endphp
                            @if($skckDoc)
                                <div class="pt-1 flex items-center justify-between text-[11px] text-emerald-700 font-semibold">
                                    <span>✓ Berkas SKCK resmi sudah terunggah</span>
                                    <a href="{{ route('documents.download', $skckDoc->id) }}" class="text-blue-600 hover:underline">Lihat Berkas</a>
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                            <button type="button" @click="currentMilestone = 2" class="btn-secondary py-2.5 px-4 text-xs font-semibold">
                                ← Kembali ke Milestone 2
                            </button>
                            <button type="submit" class="btn-primary py-3 px-6 text-xs font-bold shadow-sm inline-flex items-center gap-2">
                                <span>Simpan &amp; Lanjut ke Milestone 4</span>
                                <span>→</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANEL 4: MILESTONE 4 (PAKTA PERLINDUNGAN ANAK) -->
                <div x-show="currentMilestone === 4" x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-xs">Milestone 4</span>
                                <h3 class="text-base font-extrabold text-slate-900">Pakta Integritas Perlindungan Anak &amp; Kode Etik Mengajar</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Sesuai UU No. 35/2014 jo UU No. 23/2002 tentang Perlindungan Anak</p>
                        </div>
                        @if($profile->isMilestone4Complete())
                            <span class="badge-verified text-[11px] py-1 px-2.5">Telah Ditandatangani</span>
                        @endif
                    </div>

                    <!-- 4 Safeguards Breakdown -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                <x-icons.check class="w-4 h-4 text-emerald-600 shrink-0" /> 1. Larangan Segala Bentuk Kekerasan
                            </h4>
                            <p class="text-slate-600 text-[11px] leading-relaxed pl-6">
                                Menjamin perlakuan yang bermartabat terhadap murid, bebas dari kekerasan fisik, verbal, intimidasi, hukuman yang merendahkan, maupun perundungan.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                <x-icons.check class="w-4 h-4 text-emerald-600 shrink-0" /> 2. Pencegahan Pelecehan Seksual
                            </h4>
                            <p class="text-slate-600 text-[11px] leading-relaxed pl-6">
                                Menjaga batas kontak fisik yang wajar, profesional, dan pantas. Menolak serta mencegah segala bentuk perbuatan asusila terhadap anak didik.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                <x-icons.check class="w-4 h-4 text-emerald-600 shrink-0" /> 3. Transparansi Ruang Belajar di Rumah
                            </h4>
                            <p class="text-slate-600 text-[11px] leading-relaxed pl-6">
                                Sesi les offline harus dilakukan di ruang belajar yang dapat dilihat dan dipantau sewaktu-waktu oleh orang tua/wali murid, tanpa pintu terkunci secara tertutup rapat.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                <x-icons.check class="w-4 h-4 text-emerald-600 shrink-0" /> 4. Pelindungan Privasi &amp; Data Murid
                            </h4>
                            <p class="text-slate-600 text-[11px] leading-relaxed pl-6">
                                Dilarang merekam atau menyebarluaskan foto murid, informasi tempat tinggal, atau percakapan pribadi ke media sosial tanpa persetujuan orang tua (UU PDP).
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('teacher.documents.milestone4') }}" class="space-y-4 pt-3 border-t border-slate-100 text-xs">
                        @csrf

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Nama Lengkap Penandatangan Digital <span class="text-rose-500">*</span></label>
                            <input type="text" name="child_protection_signature_name" value="{{ old('child_protection_signature_name', $profile->child_protection_signature_name ?? $profile->full_name_ktp ?? $profile->user->name) }}" required placeholder="Ketik nama lengkap Anda sebagai tanda tangan sah" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm font-semibold">
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                            <input type="checkbox" name="agree_child_protection" value="1" {{ $profile->child_protection_pledged_at ? 'checked' : '' }} required class="mt-1 w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <span class="text-xs text-slate-700 leading-relaxed">
                                Saya telah membaca, memahami, dan berjanji secara sadar untuk mematuhi seluruh butir <strong>Pakta Integritas Perlindungan Anak &amp; Kode Etik Mengajar Tatap Muka</strong> di atas. Saya bersedia dituntut sesuai ketentuan perundang-undangan RI yang berlaku apabila terbukti melanggar komitmen perlindungan anak.
                            </span>
                        </label>

                        <div class="pt-2 flex items-center justify-between">
                            <button type="button" @click="currentMilestone = 3" class="btn-secondary py-2.5 px-4 text-xs font-semibold">
                                ← Kembali ke Milestone 3
                            </button>
                            <button type="submit" class="btn-primary py-3 px-7 text-xs font-bold shadow-sm inline-flex items-center gap-2">
                                <x-icons.check class="w-4 h-4 text-white" />
                                <span>Tandatangani Pakta &amp; Ajukan Verifikasi Penuh</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANEL 5: MILESTONE 5 (STATUS REVIEW & KEPUTUSAN VERIFIKATOR) -->
                <div x-show="currentMilestone === 5" x-transition class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-xs">Milestone 5</span>
                                <h3 class="text-base font-extrabold text-slate-900">Status Peninjauan Dokumen &amp; Penerbitan Akun</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Peninjauan langsung oleh tim verifikator Belajar Dong</p>
                        </div>
                    </div>

                    @if($profile->is_verified)
                        <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <x-icons.check class="w-6 h-6 text-emerald-600" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-emerald-950">Profil Guru Aktif &amp; Terverifikasi Sah</h4>
                                    <p class="text-xs text-emerald-700">Profil Anda telah memenuhi seluruh kualifikasi hukum dan dapat dipesan oleh orang tua murid.</p>
                                </div>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('teachers.show', $profile->id) }}" target="_blank" class="btn-primary bg-emerald-600 hover:bg-emerald-700 text-xs py-2 px-4 font-bold shadow-sm inline-flex items-center gap-1.5">
                                    <span>Lihat Profil Publik Anda</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        </div>
                    @elseif($profile->verification_status === 'rejected')
                        <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-950 space-y-2">
                            <h4 class="font-bold text-sm text-rose-900">Perlu Perbaikan Dokumen</h4>
                            <p class="text-rose-800">Catatan dari tim verifikator:</p>
                            <p class="p-3 bg-white rounded-xl border border-rose-200 font-mono text-rose-900">{{ $profile->rejection_reason }}</p>
                            <p class="text-slate-500 pt-1">Silakan perbaiki data pada milestone terkait dan simpan ulang.</p>
                        </div>
                    @else
                        <div class="p-6 rounded-2xl bg-amber-50 border border-amber-200 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <x-icons.clock class="w-6 h-6 text-amber-600" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-amber-950">Berkas Sedang Ditinjau Tim Verifikator</h4>
                                    <p class="text-xs text-amber-800">Estimasi waktu peninjauan maksimal 1x24 jam kerja. Anda akan menerima notifikasi status setelah disetujui.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Review of Uploaded Data Summary -->
                    <div class="space-y-3 pt-2 text-xs">
                        <h4 class="font-bold text-slate-900 uppercase tracking-wider">Ringkasan Data Milestone Anda</h4>
                        <div class="divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-slate-50/50 overflow-hidden">
                            <div class="p-3.5 flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-slate-900">1. Identitas KTP WNI</span>
                                    <p class="text-slate-500 text-[11px]">{{ $profile->nik ? 'NIK: ' . $profile->nik . ' (' . $profile->full_name_ktp . ')' : 'Belum lengkap' }}</p>
                                </div>
                                <button type="button" @click="currentMilestone = 1" class="text-blue-600 font-bold hover:underline">Edit</button>
                            </div>

                            <div class="p-3.5 flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-slate-900">2. Akademik &amp; Ijazah S1</span>
                                    <p class="text-slate-500 text-[11px]">{{ $profile->university_name ? $profile->degree_level . ' ' . $profile->major . ' — ' . $profile->university_name : 'Belum lengkap' }}</p>
                                </div>
                                <button type="button" @click="currentMilestone = 2" class="text-blue-600 font-bold hover:underline">Edit</button>
                            </div>

                            <div class="p-3.5 flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-slate-900">3. SKCK Kepolisian</span>
                                    <p class="text-slate-500 text-[11px]">{{ $profile->skck_number ? 'No: ' . $profile->skck_number . ' (' . $profile->skck_issuer . ')' : 'Belum lengkap' }}</p>
                                </div>
                                <button type="button" @click="currentMilestone = 3" class="text-blue-600 font-bold hover:underline">Edit</button>
                            </div>

                            <div class="p-3.5 flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-slate-900">4. Pakta Integritas Perlindungan Anak</span>
                                    <p class="text-slate-500 text-[11px]">{{ $profile->child_protection_pledged_at ? 'Ditandatangani secara sah pada ' . $profile->child_protection_pledged_at->format('d M Y, H:i') . ' WIB' : 'Belum ditandatangani' }}</p>
                                </div>
                                <button type="button" @click="currentMilestone = 4" class="text-blue-600 font-bold hover:underline">Edit</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right 4 Cols: Milestone Summary & Uploaded Documents Library -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Checklist Widget -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 card-shadow space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Tahapan Verifikasi</h3>

                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-2xl flex items-center justify-between {{ $profile->isMilestone1Complete() ? 'bg-emerald-50 text-emerald-950 border border-emerald-200' : 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                            <div class="flex items-center gap-2.5">
                                @if($profile->isMilestone1Complete())
                                    <x-icons.check class="w-4 h-4 text-emerald-600" />
                                @else
                                    <span class="w-4 h-4 rounded-full border border-slate-300 inline-block"></span>
                                @endif
                                <span class="font-semibold">Milestone 1: KTP WNI</span>
                            </div>
                            <button type="button" @click="currentMilestone = 1" class="text-[11px] font-bold text-blue-600 hover:underline">Buka</button>
                        </div>

                        <div class="p-3 rounded-2xl flex items-center justify-between {{ $profile->isMilestone2Complete() ? 'bg-emerald-50 text-emerald-950 border border-emerald-200' : 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                            <div class="flex items-center gap-2.5">
                                @if($profile->isMilestone2Complete())
                                    <x-icons.check class="w-4 h-4 text-emerald-600" />
                                @else
                                    <span class="w-4 h-4 rounded-full border border-slate-300 inline-block"></span>
                                @endif
                                <span class="font-semibold">Milestone 2: Ijazah &amp; SK</span>
                            </div>
                            <button type="button" @click="currentMilestone = 2" class="text-[11px] font-bold text-blue-600 hover:underline">Buka</button>
                        </div>

                        <div class="p-3 rounded-2xl flex items-center justify-between {{ $profile->isMilestone3Complete() ? 'bg-emerald-50 text-emerald-950 border border-emerald-200' : 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                            <div class="flex items-center gap-2.5">
                                @if($profile->isMilestone3Complete())
                                    <x-icons.check class="w-4 h-4 text-emerald-600" />
                                @else
                                    <span class="w-4 h-4 rounded-full border border-slate-300 inline-block"></span>
                                @endif
                                <span class="font-semibold">Milestone 3: SKCK POLRI</span>
                            </div>
                            <button type="button" @click="currentMilestone = 3" class="text-[11px] font-bold text-blue-600 hover:underline">Buka</button>
                        </div>

                        <div class="p-3 rounded-2xl flex items-center justify-between {{ $profile->isMilestone4Complete() ? 'bg-emerald-50 text-emerald-950 border border-emerald-200' : 'bg-slate-50 text-slate-700 border border-slate-200' }}">
                            <div class="flex items-center gap-2.5">
                                @if($profile->isMilestone4Complete())
                                    <x-icons.check class="w-4 h-4 text-emerald-600" />
                                @else
                                    <span class="w-4 h-4 rounded-full border border-slate-300 inline-block"></span>
                                @endif
                                <span class="font-semibold">Milestone 4: Pakta Integritas</span>
                            </div>
                            <button type="button" @click="currentMilestone = 4" class="text-[11px] font-bold text-blue-600 hover:underline">Buka</button>
                        </div>
                    </div>
                </div>

                <!-- Document Files List -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 card-shadow space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Berkas Terlampir</h3>
                        <span class="text-xs font-bold text-slate-400">{{ $documents->count() }} Berkas</span>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        @forelse($documents as $doc)
                            <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 block truncate uppercase text-[11px]">
                                        @if($doc->document_type === 'ktp') KTP WNI
                                        @elseif($doc->document_type === 'selfie_ktp') Swafoto Wajah
                                        @elseif($doc->document_type === 'ijazah') Ijazah S1/D4
                                        @elseif($doc->document_type === 'sk_guru') SK Guru Honorer
                                        @elseif($doc->document_type === 'skck') SKCK POLRI
                                        @elseif($doc->document_type === 'transkrip') Transkrip Nilai
                                        @else {{ strtoupper($doc->document_type) }}
                                        @endif
                                    </span>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $doc->original_filename }}</p>
                                </div>
                                <a href="{{ route('documents.download', $doc->id) }}" class="text-blue-600 font-bold hover:underline shrink-0 text-[11px]">
                                    Unduh ⤓
                                </a>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400 text-center py-4">Belum ada berkas terunggah.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Legal Notice Box -->
                <div class="p-5 rounded-3xl bg-slate-900 text-white space-y-2 text-xs">
                    <div class="flex items-center gap-2 text-blue-400 font-bold">
                        <x-icons.shield class="w-4 h-4 text-blue-400" />
                        <span>Kerahasiaan Data (UU PDP)</span>
                    </div>
                    <p class="text-[11px] text-slate-300 leading-relaxed">
                        Seluruh berkas scan fisik disimpan dalam sistem privat terenkripsi. Publik hanya dapat melihat lencana kepatuhan resmi tanpa membuka data nomor identitas pribadi Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
