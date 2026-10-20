<x-layouts.dashboard title="Profil Saya — BelajarYuk" headerTitle="Profil Guru & Layanan" headerSubtitle="Atur biodata, kualifikasi, tarif les, area jangkauan radius, dan rekening pencairan">
    <div class="max-w-5xl mx-auto space-y-6 pt-2 pb-16">

        {{-- 1. Hero Header Banner (Luxury Navy Gradient) --}}
        <div class="bg-gradient-to-br from-[#050A4F] via-[#080F63] to-[#1222A8] rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-soft-lg">
            {{-- Decorative glow circles --}}
            <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/4 -bottom-16 w-52 h-52 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-blue-100 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Pengaturan Profil Pengajar
                        </span>

                        @if($profile->is_verified)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-bold">
                                <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Terverifikasi Resmi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-200 border border-amber-400/30 text-[11px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Verifikasi: {{ $profile->getMilestoneProgressPercent() }}% Selesai
                            </span>
                        @endif
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-white">
                        Profil & Kredensial Mengajar
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                        Kelola data pribadi yang ditampilkan kepada orang tua murid, atur tarif standar mengajar, tetapkan koordinat GPS jangkauan les offline, dan rekening bank pencairan dana.
                    </p>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 shrink-0">
                    <a href="{{ route('teachers.show', $profile->id) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-md transition shadow-xs">
                        <svg class="w-4 h-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Lihat Profil Publik</span>
                    </a>

                    <a href="{{ route('teacher.documents') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-[#050A4F] hover:bg-blue-50 text-xs font-bold shadow-md transition">
                        <svg class="w-4 h-4 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Milestone Verifikasi</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Error Alerts --}}
        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 p-4 sm:p-5 rounded-2xl space-y-2 shadow-xs">
                <p class="text-xs sm:text-sm font-bold flex items-center gap-2 text-rose-800">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    Terdapat kesalahan pengisian data — mohon periksa daftar di bawah:
                </p>
                <ul class="list-disc list-inside text-xs text-rose-700 space-y-1 pl-4 sm:pl-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Container --}}
        <form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- SECTION 1: DATA PRIBADI & TAMPILAN PUBLIK --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-soft p-5 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-[#080F63] flex items-center justify-center font-bold text-sm shrink-0">
                        01
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Data Pribadi & Tampilan Publik</h2>
                        <p class="text-xs text-slate-500">Informasi ini akan ditampilkan kepada calon wali murid pada kartu dan halaman profil Anda.</p>
                    </div>
                </div>

                {{-- Photo Uploader with Alpine.js Preview --}}
                <div x-data="{
                    photoPreview: '{{ Auth::user()->avatar }}',
                    handleFileChange(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (event) => { this.photoPreview = event.target.result; };
                            reader.readAsDataURL(file);
                        }
                    }
                }" class="p-4 sm:p-5 rounded-2xl bg-[#F8FAFC] border border-slate-200/80 flex flex-col sm:flex-row items-center gap-5">
                    
                    {{-- Avatar Display --}}
                    <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-white shadow-md flex items-center justify-center shrink-0">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-full h-full flex items-center justify-center bg-[#080F63] text-white font-extrabold text-3xl">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </template>
                    </div>

                    {{-- Upload Controls & Advice --}}
                    <div class="space-y-2 flex-1 text-center sm:text-left">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <label for="avatar_upload"
                                   class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-[#080F63] hover:text-[#080F63] shadow-xs transition">
                                <svg class="w-4 h-4 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Pilih Foto Profil Baru</span>
                            </label>
                            <input id="avatar_upload" type="file" name="avatar" accept="image/*" class="sr-only" @change="handleFileChange($event)">
                            <span class="text-[11px] text-slate-500 font-medium">Format: JPG, PNG, atau WebP (Maks. 2MB)</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            💡 <strong>Tips Foto Profesional:</strong> Gunakan foto portrait formal, wajah menghadap kamera dengan senyum ramah dan pencahayaan terang untuk meningkatkan rasa percaya orang tua hingga 85%.
                        </p>
                        @error('avatar')<p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Name & WhatsApp Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="teacher_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="teacher_name" type="text" name="name"
                                   value="{{ old('name', Auth::user()->name) }}" required
                                   placeholder="Contoh: Budi Santoso, S.Pd."
                                   class="w-full pl-10 @error('name') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        @error('name')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="teacher_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nomor WhatsApp Aktif <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input id="teacher_phone" type="tel" name="phone"
                                   value="{{ old('phone', Auth::user()->phone) }}" required
                                   placeholder="Contoh: 081234567890"
                                   class="w-full pl-10 @error('phone') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Digunakan untuk notifikasi jadwal sesi les & komunikasi wali murid.</p>
                        @error('phone')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Headline --}}
                <div>
                    <label for="teacher_headline" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Headline Singkat Profil <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <input id="teacher_headline" type="text" name="headline"
                               value="{{ old('headline', $profile->headline) }}" required
                               placeholder="Contoh: Guru Matematika & Fisika Berpengalaman Lulusan UNJ | Siap UTBK & Olimpiade"
                               class="w-full pl-10 @error('headline') border-rose-400 bg-rose-50/30 @enderror">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Slogan atau ringkasan 1 baris yang langsung menarik perhatian calon murid pada hasil pencarian.</p>
                    @error('headline')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                {{-- Bio --}}
                <div>
                    <label for="teacher_bio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Biografi & Metode Pengajaran <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="teacher_bio" name="bio" rows="5" required
                              placeholder="Ceritakan latar belakang mengajar Anda, pendekatan bimbingan ramah anak yang Anda gunakan, serta bagaimana Anda membantu siswa memahami materi pelajaran yang sulit..."
                              class="w-full @error('bio') border-rose-400 bg-rose-50/30 @enderror">{{ old('bio', $profile->bio) }}</textarea>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1">
                        <span>Jelaskan keunggulan metode belajar Anda secara jelas dan mendalam.</span>
                        <span>Maks. 2000 karakter</span>
                    </div>
                    @error('bio')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- SECTION 2: KUALIFIKASI & TARIF LES --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-soft p-5 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm shrink-0">
                        02
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Kualifikasi Akademik & Tarif Les</h2>
                        <p class="text-xs text-slate-500">Tentukan kualifikasi ijazah, pengalaman, serta patokan harga per jam bimbingan belajar.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="teacher_education" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Riwayat Pendidikan Terakhir <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </div>
                            <input id="teacher_education" type="text" name="education"
                                   value="{{ old('education', $profile->education) }}" required
                                   placeholder="Contoh: S1 Pendidikan Matematika, UNJ"
                                   class="w-full pl-10 @error('education') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        @error('education')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="experience_years" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pengalaman Mengajar (Tahun) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="experience_years" type="number" name="experience_years"
                                   value="{{ old('experience_years', $profile->experience_years) }}" required
                                   min="0" max="50"
                                   class="w-full pr-16 font-bold text-slate-800 @error('experience_years') border-rose-400 bg-rose-50/30 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                                Tahun
                            </div>
                        </div>
                        @error('experience_years')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label for="hourly_rate" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tarif Standar per Jam (Rupiah) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-[#080F63]">
                                Rp
                            </div>
                            <input id="hourly_rate" type="number" name="hourly_rate"
                                   value="{{ old('hourly_rate', $profile->hourly_rate) }}" required
                                   step="5000" min="30000" max="1000000"
                                   class="w-full pl-11 font-bold text-[#080F63] text-base @error('hourly_rate') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Tarif minimal Rp 30.000 / jam. Total biaya dihitung otomatis proporsional durasi sesi.</p>
                        @error('hourly_rate')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="session_duration" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Durasi Sesi Standar <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="session_duration" name="session_duration_minutes"
                                    class="w-full font-semibold text-slate-800 cursor-pointer">
                                <option value="60" {{ (int)old('session_duration_minutes', $profile->session_duration_minutes) === 60 ? 'selected' : '' }}>
                                    ⏱️ 60 Menit (1 Jam Efektif)
                                </option>
                                <option value="90" {{ (int)old('session_duration_minutes', $profile->session_duration_minutes) === 90 ? 'selected' : '' }}>
                                    ⭐ 90 Menit (1.5 Jam — Paling Disukai)
                                </option>
                                <option value="120" {{ (int)old('session_duration_minutes', $profile->session_duration_minutes) === 120 ? 'selected' : '' }}>
                                    📚 120 Menit (2 Jam Intensif)
                                </option>
                            </select>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Durasi default saat orang tua memilih slot di kalender mengajar Anda.</p>
                        @error('session_duration_minutes')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 3: AREA LAYANAN OFFLINE & RADIUS GPS --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-soft p-5 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                        03
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Area Layanan Offline & Radius GPS</h2>
                        <p class="text-xs text-slate-500">Pusat pangkalan mengajar Anda untuk kalkulasi jarak radius presisi ke rumah murid.</p>
                    </div>
                </div>

                {{-- Location Hierarchy Cascade --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Pilih Kota, Kecamatan, & Kelurahan Pangkalan <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200 font-semibold">
                            Koordinat GPS Resmi
                        </span>
                    </div>

                    <x-location-cascade
                        :cities="$cities"
                        :selectedCityId="$profile->city_id"
                        :selectedDistrictId="$profile->district_id"
                        :selectedVillageId="$profile->village_id"
                        :initialLat="$profile->latitude"
                        :initialLon="$profile->longitude"
                        latField="latitude"
                        lonField="longitude"
                        cityField="city_id"
                        districtField="district_id"
                        villageField="village_id"
                        cityNameField="service_city"
                        districtNameField="service_district"
                        villageNameField="service_village"
                        :showLabels="true"
                    />
                </div>

                {{-- Address & Radius --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-2">
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label for="service_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Alamat Lengkap Titik Awal <span class="text-rose-500">*</span>
                            </label>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                                🔒 Privat / Rahasia
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input id="service_address" type="text" name="service_address"
                                   value="{{ old('service_address', $profile->service_address) }}" required
                                   placeholder="Contoh: Jl. Panglima Polim No. 45, Kebayoran Baru"
                                   class="w-full pl-10 @error('service_address') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Alamat spesifik ini tidak pernah dibagikan ke publik; hanya sebagai titik pusat kalkulasi jarak GPS ke rumah murid.</p>
                        @error('service_address')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="max_travel_distance" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Radius Maksimal (km) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="max_travel_distance" type="number" name="max_travel_distance_km"
                                   value="{{ old('max_travel_distance_km', $profile->max_travel_distance_km ?? 10) }}" required
                                   min="1" max="50"
                                   class="w-full pr-14 font-bold text-[#080F63] text-base @error('max_travel_distance_km') border-rose-400 bg-rose-50/30 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                                KM
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Jarak tempuh terjauh yang Anda sanggupi.</p>
                        @error('max_travel_distance_km')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 4: REKENING BANK PENCAIRAN SALDO --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-soft p-5 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">
                        04
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Rekening Bank untuk Pencairan Saldo (Payout)</h2>
                        <p class="text-xs text-slate-500">Honor mengajar yang berhasil diselesaikan akan ditransfer ke rekening bank ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="bank_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Bank Mitra
                        </label>
                        <select id="bank_name" name="bank_name" class="w-full font-semibold text-slate-800 cursor-pointer">
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA" {{ old('bank_name', $profile->bank_name) === 'BCA' ? 'selected' : '' }}>BCA (Bank Central Asia)</option>
                            <option value="Mandiri" {{ old('bank_name', $profile->bank_name) === 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                            <option value="BRI" {{ old('bank_name', $profile->bank_name) === 'BRI' ? 'selected' : '' }}>BRI (Bank Rakyat Indonesia)</option>
                            <option value="BNI" {{ old('bank_name', $profile->bank_name) === 'BNI' ? 'selected' : '' }}>BNI (Bank Negara Indonesia)</option>
                            <option value="BSI" {{ old('bank_name', $profile->bank_name) === 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia)</option>
                            <option value="CIMB" {{ old('bank_name', $profile->bank_name) === 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                            <option value="Permata" {{ old('bank_name', $profile->bank_name) === 'Permata' ? 'selected' : '' }}>Permata Bank</option>
                        </select>
                        @error('bank_name')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="bank_account_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nomor Rekening
                        </label>
                        <div class="relative">
                            <input id="bank_account_number" type="text" name="bank_account_number"
                                   value="{{ old('bank_account_number', $profile->bank_account_number) }}"
                                   placeholder="Contoh: 1234567890"
                                   class="w-full font-mono text-slate-800 tracking-wider @error('bank_account_number') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        @error('bank_account_number')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="bank_account_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Atas Nama Rekening
                        </label>
                        <input id="bank_account_name" type="text" name="bank_account_name"
                               value="{{ old('bank_account_name', $profile->bank_account_name) }}"
                               placeholder="Sesuai buku tabungan"
                               class="w-full @error('bank_account_name') border-rose-400 bg-rose-50/30 @enderror">
                        @error('bank_account_name')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/70 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        <strong>Perhatian:</strong> Pastikan ejaan nama pada rekening bank sama persis dengan nama resmi Anda di buku tabungan untuk menjamin kelancaran transfer saldo dari rekening penampung (escrow) BelajarYuk.
                    </p>
                </div>
            </div>

            {{-- STICKY SAVE ACTION BAR --}}
            <div class="sticky bottom-4 z-30 bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-soft-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2.5 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Perubahan akan langsung diperbarui ke sistem dan profil publik Anda.</span>
                </div>

                <div class="flex items-center gap-3 justify-end shrink-0">
                    <a href="{{ route('teacher.dashboard') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                        Batal
                    </a>

                    <button type="submit"
                            class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-[#080F63] hover:bg-[#050A4F] text-white text-xs font-bold shadow-md shadow-blue-900/20 hover:shadow-lg transition">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Seluruh Perubahan</span>
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-layouts.dashboard>
