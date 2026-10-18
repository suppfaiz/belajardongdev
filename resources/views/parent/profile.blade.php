<x-layouts.dashboard title="Pengaturan Akun — BelajarYuk">
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                Pengaturan Akun
            </h1>
            <p class="text-sm text-[#747B8C] mt-1">
                Kelola data profil orang tua, preferensi notifikasi, dan keamanan akun Anda
            </p>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left: Form Profil Orang Tua (7 cols) -->
            <form method="POST" action="{{ route('parent.profile.update') }}" class="lg:col-span-7 bd-card p-6 sm:p-8 space-y-5">
                @csrf
                @method('PUT')

                <div class="pb-3 border-b border-[#E5E8F0]">
                    <h3 class="font-bold text-base text-[#171923]">Informasi Pribadi Orang Tua</h3>
                    <p class="text-xs text-[#747B8C] mt-0.5">Digunakan untuk koordinasi sesi belajar anak dengan guru</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" disabled value="{{ $user->email }}"
                           class="w-full bg-slate-100 border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-slate-400 cursor-not-allowed">
                    <p class="text-[11px] text-slate-400 mt-1">Email adalah identitas masuk utama dan tidak dapat diubah.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1.5">Nomor WhatsApp / Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '081234567890') }}" required
                           class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                    <p class="text-[11px] text-[#747B8C] mt-1">Pengingat jadwal les otomatis akan dikirim ke nomor ini.</p>
                </div>

                <div class="pt-4 border-t border-[#E5E8F0] flex justify-end">
                    <button type="submit" class="btn-navy px-6 py-2.5 text-xs font-bold shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <!-- Right: Notifikasi & Keamanan (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Notifikasi Card -->
                <div class="bd-card p-6 space-y-4">
                    <h3 class="font-bold text-base text-[#171923]">Preferensi Notifikasi</h3>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-3 rounded-xl bg-[#F8FAFC] border border-[#E5E8F0] cursor-pointer">
                            <div>
                                <span class="text-xs font-bold text-[#171923] block">Pengingat WhatsApp</span>
                                <span class="text-[11px] text-[#747B8C]">Kirim 1 jam sebelum sesi les dimulai</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 text-[#080F63] rounded-sm focus:ring-[#080F63]">
                        </label>

                        <label class="flex items-center justify-between p-3 rounded-xl bg-[#F8FAFC] border border-[#E5E8F0] cursor-pointer">
                            <div>
                                <span class="text-xs font-bold text-[#171923] block">Notifikasi Email</span>
                                <span class="text-[11px] text-[#747B8C]">Kuitansi pembayaran & laporan evaluasi</span>
                            </div>
                            <input type="checkbox" checked class="w-4 h-4 text-[#080F63] rounded-sm focus:ring-[#080F63]">
                        </label>
                    </div>
                </div>

                <!-- Keamanan Card -->
                <div class="bd-card p-6 space-y-4">
                    <h3 class="font-bold text-base text-[#171923]">Keamanan Akun</h3>
                    <p class="text-xs text-[#747B8C]">Perbarui kata sandi secara berkala untuk menjaga keamanan data anak Anda.</p>
                    <button type="button" 
                            onclick="alert('Tautan ubah kata sandi telah dikirimkan ke email terdaftar Anda!')"
                            class="btn-white-pill w-full py-2.5 text-xs font-bold justify-center">
                        Ubah Kata Sandi &rarr;
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
