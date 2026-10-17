@props([
    'cities' => collect(),
    'selectedCityId' => null,
    'selectedDistrictId' => null,
    'selectedVillageId' => null,
    'cityField' => 'city_id',
    'districtField' => 'district_id',
    'villageField' => 'village_id',
    'cityNameField' => null,
    'districtNameField' => null,
    'villageNameField' => null,
    'latField' => 'lat',
    'lonField' => 'lon',
    'initialLat' => null,
    'initialLon' => null,
    'showLabels' => true,
    'labels' => [
        'city' => 'Kota / Kabupaten',
        'district' => 'Kecamatan',
        'village' => 'Kelurahan / Desa',
    ],
    'required' => false,
    'class' => 'grid grid-cols-1 sm:grid-cols-3 gap-4',
])

<div 
    x-data="{
        cityId: '{{ $selectedCityId ?? '' }}',
        districtId: '{{ $selectedDistrictId ?? '' }}',
        villageId: '{{ $selectedVillageId ?? '' }}',
        cityName: '',
        districtName: '',
        villageName: '',
        latitude: '{{ $initialLat ?? '' }}',
        longitude: '{{ $initialLon ?? '' }}',
        districts: [],
        villages: [],
        loadingDistricts: false,
        loadingVillages: false,

        async init() {
            if (this.cityId) {
                await this.loadDistricts(this.cityId, false);
                if (this.districtId) {
                    await this.loadVillages(this.districtId, false);
                }
            }
        },

        async onCityChange(e) {
            const select = e.target;
            const selectedOpt = select.options[select.selectedIndex];
            this.cityId = select.value;
            this.cityName = selectedOpt ? selectedOpt.dataset.name || '' : '';
            this.districtId = '';
            this.districtName = '';
            this.villageId = '';
            this.villageName = '';
            this.villages = [];

            if (selectedOpt && selectedOpt.dataset.lat) {
                this.latitude = selectedOpt.dataset.lat;
                this.longitude = selectedOpt.dataset.lon;
            }

            if (this.cityId) {
                await this.loadDistricts(this.cityId, true);
            } else {
                this.districts = [];
            }
        },

        async onDistrictChange(e) {
            const select = e.target;
            const selectedOpt = select.options[select.selectedIndex];
            this.districtId = select.value;
            this.districtName = selectedOpt ? selectedOpt.dataset.name || '' : '';
            this.villageId = '';
            this.villageName = '';

            if (selectedOpt && selectedOpt.dataset.lat) {
                this.latitude = selectedOpt.dataset.lat;
                this.longitude = selectedOpt.dataset.lon;
            }

            if (this.districtId) {
                await this.loadVillages(this.districtId, true);
            } else {
                this.villages = [];
            }
        },

        onVillageChange(e) {
            const select = e.target;
            const selectedOpt = select.options[select.selectedIndex];
            this.villageId = select.value;
            this.villageName = selectedOpt ? selectedOpt.dataset.name || '' : '';

            if (selectedOpt && selectedOpt.dataset.lat) {
                this.latitude = selectedOpt.dataset.lat;
                this.longitude = selectedOpt.dataset.lon;
            }
        },

        async loadDistricts(cityId, resetChildren = true) {
            this.loadingDistricts = true;
            try {
                const res = await fetch(`/api/regions/districts?city_id=${cityId}`);
                if (res.ok) {
                    this.districts = await res.json();
                }
            } catch (err) {
                console.error('Gagal memuat daftar kecamatan:', err);
            } finally {
                this.loadingDistricts = false;
            }
        },

        async loadVillages(districtId, resetChildren = true) {
            this.loadingVillages = true;
            try {
                const res = await fetch(`/api/regions/villages?district_id=${districtId}`);
                if (res.ok) {
                    this.villages = await res.json();
                }
            } catch (err) {
                console.error('Gagal memuat daftar kelurahan:', err);
            } finally {
                this.loadingVillages = false;
            }
        }
    }"
    class="{{ $class }}"
>
    <!-- Hidden Form Fields -->
    <input type="hidden" name="{{ $latField }}" :value="latitude">
    <input type="hidden" name="{{ $lonField }}" :value="longitude">
    @if($cityNameField)
        <input type="hidden" name="{{ $cityNameField }}" :value="cityName">
    @endif
    @if($districtNameField)
        <input type="hidden" name="{{ $districtNameField }}" :value="districtName">
    @endif
    @if($villageNameField)
        <input type="hidden" name="{{ $villageNameField }}" :value="villageName">
    @endif

    <!-- Dropdown 1: Kota / Kabupaten -->
    <div class="relative">
        @if($showLabels)
            <label class="block text-xs font-bold text-[#171717] uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>{{ $labels['city'] }}</span>
                <span class="text-[10px] font-bold text-[#080F63] bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Tahap 1</span>
            </label>
        @endif
        <div class="relative">
            <select 
                name="{{ $cityField }}" 
                x-model="cityId" 
                @change="onCityChange($event)"
                @if($required) required @endif
                class="w-full h-11 px-4 rounded-xl border-[1.5px] border-[#D4D4D4] bg-white text-sm font-semibold text-[#171717] focus:border-[#080F63] focus:ring-3 focus:ring-[#080F63]/15 outline-none transition cursor-pointer appearance-none pr-9 shadow-2xs"
            >
                <option value="">-- Pilih Kota / Kabupaten --</option>
                @foreach($cities as $c)
                    <option 
                        value="{{ $c->id }}" 
                        data-name="{{ $c->name }}"
                        data-lat="{{ $c->latitude }}" 
                        data-lon="{{ $c->longitude }}"
                        {{ (string)$selectedCityId === (string)$c->id ? 'selected' : '' }}
                    >
                        {{ $c->type }} {{ $c->name }} ({{ $c->province?->name ?? 'Indonesia' }})
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#080F63]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Dropdown 2: Kecamatan -->
    <div class="relative">
        @if($showLabels)
            <label class="block text-xs font-bold text-[#171717] uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>{{ $labels['district'] }}</span>
                <span class="text-[10px] font-bold text-[#080F63] bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Tahap 2</span>
            </label>
        @endif
        <div class="relative">
            <select 
                name="{{ $districtField }}" 
                x-model="districtId" 
                @change="onDistrictChange($event)"
                :disabled="!cityId || loadingDistricts"
                class="w-full h-11 px-4 rounded-xl border-[1.5px] border-[#D4D4D4] bg-white text-sm font-semibold text-[#171717] focus:border-[#080F63] focus:ring-3 focus:ring-[#080F63]/15 outline-none transition cursor-pointer appearance-none pr-9 shadow-2xs disabled:bg-[#F5F5F5] disabled:text-[#A3A3A3] disabled:cursor-not-allowed"
            >
                <option value="" x-text="loadingDistricts ? 'Memuat daftar kecamatan...' : (!cityId ? '-- Pilih Kota Terlebih Dahulu --' : '-- Semua / Pilih Kecamatan --')"></option>
                <template x-for="dist in districts" :key="dist.id">
                    <option 
                        :value="dist.id" 
                        :data-name="dist.name"
                        :data-lat="dist.latitude" 
                        :data-lon="dist.longitude"
                        :selected="dist.id == districtId"
                        x-text="'Kec. ' + dist.name"
                    ></option>
                </template>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#080F63]">
                <template x-if="loadingDistricts">
                    <svg class="animate-spin w-4 h-4 text-[#080F63]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </template>
                <template x-if="!loadingDistricts">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                    </svg>
                </template>
            </div>
        </div>
    </div>

    <!-- Dropdown 3: Kelurahan / Desa -->
    <div class="relative">
        @if($showLabels)
            <label class="block text-xs font-bold text-[#171717] uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>{{ $labels['village'] }}</span>
                <span class="text-[10px] font-bold text-[#080F63] bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Tahap 3</span>
            </label>
        @endif
        <div class="relative">
            <select 
                name="{{ $villageField }}" 
                x-model="villageId" 
                @change="onVillageChange($event)"
                :disabled="!districtId || loadingVillages"
                class="w-full h-11 px-4 rounded-xl border-[1.5px] border-[#D4D4D4] bg-white text-sm font-semibold text-[#171717] focus:border-[#080F63] focus:ring-3 focus:ring-[#080F63]/15 outline-none transition cursor-pointer appearance-none pr-9 shadow-2xs disabled:bg-[#F5F5F5] disabled:text-[#A3A3A3] disabled:cursor-not-allowed"
            >
                <option value="" x-text="loadingVillages ? 'Memuat daftar kelurahan...' : (!districtId ? '-- Pilih Kecamatan Terlebih Dahulu --' : '-- Semua / Pilih Kelurahan --')"></option>
                <template x-for="vill in villages" :key="vill.id">
                    <option 
                        :value="vill.id" 
                        :data-name="vill.name"
                        :data-lat="vill.latitude" 
                        :data-lon="vill.longitude"
                        :selected="vill.id == villageId"
                        x-text="'Kel. ' + vill.name + (vill.postal_code ? ' (' + vill.postal_code + ')' : '')"
                    ></option>
                </template>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#080F63]">
                <template x-if="loadingVillages">
                    <svg class="animate-spin w-4 h-4 text-[#080F63]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </template>
                <template x-if="!loadingVillages">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                    </svg>
                </template>
            </div>
        </div>
    </div>

    <!-- Active Selected Location Breadcrumb Feedback -->
    <template x-if="cityName || districtName || villageName">
        <div class="col-span-full flex flex-wrap items-center gap-1.5 text-xs text-[#080F63] bg-blue-50 px-4 py-2.5 rounded-full border border-blue-200 shadow-xs">
            <x-icons.map-pin class="w-4 h-4 text-[#080F63] shrink-0" />
            <span class="font-bold">Wilayah Terpilih:</span>
            <span class="font-extrabold text-[#050A4F]" x-text="[cityName, districtName ? 'Kec. ' + districtName : '', villageName ? 'Kel. ' + villageName : ''].filter(Boolean).join(' → ')"></span>
        </div>
    </template>
</div>
