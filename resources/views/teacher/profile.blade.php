<x-layouts.dashboard title="Profil Saya — BelajarYuk" headerTitle="Profil Guru & Layanan" headerSubtitle="Atur biodata, kualifikasi, tarif les, area jangkauan radius, dan rekening pencairan">
    <div class="max-w-4xl space-y-6 pt-4">

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-lg space-y-1">
                <p class="text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    Terdapat kesalahan — periksa data di bawah:
                </p>
                <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5 pl-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data"
              class="panel-window p-6 sm:p-8 space-y-8 text-xs">
            @csrf
            @method('PUT')

            {{-- Section 1: Profil & Identitas --}}
            <div class="space-y-5">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">1. Data Pribadi & Tampilan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Informasi ini ditampilkan kepada orang tua murid pada profil publik Anda.</p>
                </div>

                {{-- Avatar --}}
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-2xl bg-blue-100 text-blue-700 font-bold text-2xl flex items-center justify-center overflow-hidden border border-blue-200 shrink-0">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <label for="avatar_upload" class="block font-bold text-slate-700 uppercase mb-1.5">Foto Profil Formal</label>
                        <input id="avatar_upload" type="file" name="avatar" accept="image/*"
                            class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Gunakan foto rapi & sopan agar menarik minat orang tua murid.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="teacher_name" class="block font-bold text-slate-700 uppercase mb-1.5">Nama Lengkap & Gelar</label>
                        <input id="teacher_name" type="text" name="name"
                            value="{{ old('name', Auth::user()->name) }}" required
                            class="w-full @error('name') border-rose-400 bg-rose-50/30 @enderror">
                        @error('name')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="teacher_phone" class="block font-bold text-slate-700 uppercase mb-1.5">Nomor WhatsApp Aktif</label>
                        <input id="teacher_phone" type="tel" name="phone"
                            value="{{ old('phone', Auth::user()->phone) }}" required
                            class="w-full @error('phone') border-rose-400 bg-rose-50/30 @enderror">
                        @error('phone')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="teacher_headline" class="block font-bold text-slate-700 uppercase mb-1.5">Headline Singkat</label>
                    <input id="teacher_headline" type="text" name="headline"
                        value="{{ old('headline', $profile->headline) }}" required
                        placeholder="Contoh: Guru Matematika Berpengalaman Lulusan UNJ"
                        class="w-full @error('headline') border-rose-400 bg-rose-50/30 @enderror">
                    @error('headline')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="teacher_bio" class="block font-bold text-slate-700 uppercase mb-1.5">Biografi & Pengalaman Mengajar</label>
                    <textarea id="teacher_bio" name="bio" rows="4" required
                        class="w-full @error('bio') border-rose-400 bg-rose-50/30 @enderror">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Section 2: Kualifikasi & Tarif --}}
            <div class="space-y-5 pt-2 border-t border-slate-100">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">2. Kualifikasi & Tarif Les</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tentukan tarif standar dan sesi mengajar Anda.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="teacher_education" class="block font-bold text-slate-700 uppercase mb-1.5">Riwayat Pendidikan</label>
                        <input id="teacher_education" type="text" name="education"
                            value="{{ old('education', $profile->education) }}" required
                            placeholder="Contoh: S1 Pendidikan Matematika, UNJ"
                            class="w-full @error('education') border-rose-400 bg-rose-50/30 @enderror">
                        @error('education')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="experience_years" class="block font-bold text-slate-700 uppercase mb-1.5">Pengalaman Mengajar (Tahun)</label>
                        <input id="experience_years" type="number" name="experience_years"
                            value="{{ old('experience_years', $profile->experience_years) }}" required
                            min="0" max="50"
                            class="w-full @error('experience_years') border-rose-400 bg-rose-50/30 @enderror">
                        @error('experience_years')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="hourly_rate" class="block font-bold text-slate-700 uppercase mb-1.5">Tarif Standar per Jam (Rupiah)</label>
                        <input id="hourly_rate" type="number" name="hourly_rate"
                            value="{{ old('hourly_rate', $profile->hourly_rate) }}" required
                            step="5000" min="30000"
                            class="w-full font-bold text-blue-600 @error('hourly_rate') border-rose-400 bg-rose-50/30 @enderror">
                        @error('hourly_rate')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="session_duration" class="block font-bold text-slate-700 uppercase mb-1.5">Durasi Sesi Standar</label>
                        <select id="session_duration" name="session_duration_minutes">
                            <option value="60" {{ $profile->session_duration_minutes == 60 ? 'selected' : '' }}>60 Menit (1 Jam)</option>
                            <option value="90" {{ $profile->session_duration_minutes == 90 ? 'selected' : '' }}>90 Menit (1.5 Jam)</option>
                            <option value="120" {{ $profile->session_duration_minutes == 120 ? 'selected' : '' }}>120 Menit (2 Jam)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 3: Area Layanan --}}
            <div class="space-y-5 pt-2 border-t border-slate-100">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">3. Area Layanan Offline & Radius Geografis</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Tentukan lokasi pangkalan mengajar. Sistem akan menghitung jarak ke rumah murid secara otomatis menggunakan koordinat GPS resmi.
                    </p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-2">Hierarki Wilayah Layanan</label>
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

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="service_address" class="block font-bold text-slate-700 uppercase mb-1.5">Alamat Lengkap Titik Awal <span class="text-slate-400 font-normal normal-case">(Privat)</span></label>
                        <input id="service_address" type="text" name="service_address"
                            value="{{ old('service_address', $profile->service_address) }}" required
                            placeholder="Contoh: Jl. Panglima Polim No. 45, Kebayoran Baru"
                            class="w-full @error('service_address') border-rose-400 bg-rose-50/30 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Alamat privat tidak dipublikasikan; hanya untuk kalkulasi jarak presisi.</p>
                        @error('service_address')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="max_travel_distance" class="block font-bold text-slate-700 uppercase mb-1.5">Radius Jangkauan (km)</label>
                        <input id="max_travel_distance" type="number" name="max_travel_distance_km"
                            value="{{ old('max_travel_distance_km', $profile->max_travel_distance_km ?? 10) }}" required
                            min="1" max="50"
                            class="w-full font-bold text-blue-600 @error('max_travel_distance_km') border-rose-400 bg-rose-50/30 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Jarak terjauh yang Anda sanggupi.</p>
                        @error('max_travel_distance_km')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 4: Rekening Bank --}}
            <div class="space-y-5 pt-2 border-t border-slate-100">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">4. Rekening Bank untuk Pencairan Saldo</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pastikan data rekening sesuai buku tabungan untuk menghindari gagal transfer.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="bank_name" class="block font-bold text-slate-700 uppercase mb-1.5">Nama Bank</label>
                        <select id="bank_name" name="bank_name">
                            <option value="BCA" {{ $profile->bank_name === 'BCA' ? 'selected' : '' }}>BCA</option>
                            <option value="Mandiri" {{ $profile->bank_name === 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                            <option value="BRI" {{ $profile->bank_name === 'BRI' ? 'selected' : '' }}>BRI</option>
                            <option value="BNI" {{ $profile->bank_name === 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="BSI" {{ $profile->bank_name === 'BSI' ? 'selected' : '' }}>BSI</option>
                            <option value="CIMB" {{ $profile->bank_name === 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                        </select>
                    </div>
                    <div>
                        <label for="bank_account_number" class="block font-bold text-slate-700 uppercase mb-1.5">Nomor Rekening</label>
                        <input id="bank_account_number" type="text" name="bank_account_number"
                            value="{{ old('bank_account_number', $profile->bank_account_number) }}"
                            placeholder="1234567890"
                            class="w-full font-mono @error('bank_account_number') border-rose-400 bg-rose-50/30 @enderror">
                        @error('bank_account_number')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="bank_account_name" class="block font-bold text-slate-700 uppercase mb-1.5">Atas Nama Rekening</label>
                        <input id="bank_account_name" type="text" name="bank_account_name"
                            value="{{ old('bank_account_name', $profile->bank_account_name) }}"
                            placeholder="Sesuai buku tabungan"
                            class="w-full @error('bank_account_name') border-rose-400 bg-rose-50/30 @enderror">
                        @error('bank_account_name')<p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-4">
                <p class="text-[11px] text-slate-400">Semua bidang wajib bertanda merah diisi dengan benar sebelum menyimpan.</p>
                <button type="submit" class="btn-primary px-8 text-xs font-bold shrink-0">
                    Simpan Seluruh Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
