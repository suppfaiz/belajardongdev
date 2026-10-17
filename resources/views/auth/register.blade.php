<x-layouts.app title="Daftar Akun Baru — Belajar Dong">
    <div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#F3F5FA] relative overflow-hidden" x-data="{ 
        role: '{{ old('role', 'parent') }}',
        showPassword: false
    }">
        <!-- Ambient decorative blur in Deep Navy & Blue -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#080F63]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-xl w-full space-y-6 relative z-10">
            <div class="p-8 sm:p-10 bg-white shadow-xl space-y-6 border border-[#E5E8F0] rounded-2xl">
                <!-- Brand & Header -->
                <div class="text-center space-y-2">
                    <a href="{{ route('home') }}" class="inline-block transform hover:scale-105 transition">
                        <x-logo size="lg" />
                    </a>
                    <h2 class="text-2xl font-extrabold text-[#111827] tracking-tight pt-2">Bergabung di Belajar Dong</h2>
                    <p class="text-xs text-[#64748B] font-normal">Pilih jenis akun yang sesuai dengan kebutuhan Anda untuk memulai.</p>
                </div>

                <form method="POST" action="{{ route('register', [], false) }}" class="space-y-5">
                    @csrf

                    <!-- Role Selection Cards -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider">Daftar Sebagai</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Option: Orang Tua -->
                            <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer transition text-left" :class="role === 'parent' ? 'border-[#080F63] bg-blue-50/60 ring-2 ring-[#080F63]/20' : 'border-[#E2E8F0] hover:border-slate-300 bg-white'">
                                <input type="radio" name="role" value="parent" x-model="role" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#111827] flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full" :class="role === 'parent' ? 'bg-[#080F63] ring-2 ring-blue-200' : 'bg-slate-300'"></span>
                                        Orang Tua Murid
                                    </span>
                                    <span class="text-lg">👨‍👩‍👧</span>
                                </div>
                                <span class="text-[11px] text-[#64748B] mt-2 leading-relaxed">Mencari guru les privat tatap muka atau online terbaik untuk anak di rumah.</span>
                            </label>

                            <!-- Option: Guru Les -->
                            <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer transition text-left" :class="role === 'teacher' ? 'border-[#080F63] bg-blue-50/60 ring-2 ring-[#080F63]/20' : 'border-[#E2E8F0] hover:border-slate-300 bg-white'">
                                <input type="radio" name="role" value="teacher" x-model="role" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#111827] flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full" :class="role === 'teacher' ? 'bg-[#080F63] ring-2 ring-blue-200' : 'bg-slate-300'"></span>
                                        Guru Les Privat
                                    </span>
                                    <span class="text-lg">👩‍🏫</span>
                                </div>
                                <span class="text-[11px] text-[#64748B] mt-2 leading-relaxed">Mengajar privat, atur tarif mandiri & dapatkan penghasilan fleksibel.</span>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-xs text-[#EF4444] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Nama Lengkap Sesuai KTP</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso, S.Pd." class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] @error('name') border-[#EF4444] bg-[#FEE2E2]/30 @enderror">
                        @error('name')
                            <p class="text-xs text-[#EF4444] mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Alamat Email</label>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="nama@email.com" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] @error('email') border-[#EF4444] bg-[#FEE2E2]/30 @enderror">
                            @error('email')
                                <p class="text-xs text-[#EF4444] mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif</label>
                            <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}" placeholder="081234567890" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] @error('phone') border-[#EF4444] bg-[#FEE2E2]/30 @enderror">
                            @error('phone')
                                <p class="text-xs text-[#EF4444] mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password & Confirmation Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-bold text-[#111827] uppercase tracking-wider">Kata Sandi</label>
                                <button type="button" @click="showPassword = !showPassword" class="text-[11px] text-[#080F63] font-bold focus:outline-none cursor-pointer">
                                    <span x-text="showPassword ? 'Sembunyikan' : 'Lihat'"></span>
                                </button>
                            </div>
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required placeholder="Minimal 8 karakter" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] @error('password') border-[#EF4444] bg-[#FEE2E2]/30 @enderror">
                            @error('password')
                                <p class="text-xs text-[#EF4444] mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Ulangi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" :type="showPassword ? 'text' : 'password'" required placeholder="Ketik ulang sandi" class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63]">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#080F63] hover:bg-[#050A4F] text-white font-bold py-3 rounded-xl text-sm shadow-md transition cursor-pointer">
                            Daftar Akun Sekarang →
                        </button>
                    </div>
                </form>

                <div class="text-center text-xs text-slate-500 pt-3 border-t border-[#F0F0F0]">
                    Sudah memiliki akun Belajar Dong?
                    <a href="{{ route('login') }}" class="font-bold text-[#080F63] hover:underline ml-1">Masuk di sini</a>
                </div>
            </div>

            <!-- Trust Badge -->
            <div class="text-center text-[11px] text-slate-500 flex items-center justify-center gap-3">
                <span class="flex items-center gap-1 font-semibold">
                    <x-icons.shield class="w-3.5 h-3.5 text-emerald-600" />
                    Garansi Rekening Escrow
                </span>
                <span>•</span>
                <span class="flex items-center gap-1 font-semibold">
                    <x-icons.check class="w-3.5 h-3.5 text-[#080F63]" />
                    Verifikasi Dokumen Resmi
                </span>
            </div>
        </div>
    </div>
</x-layouts.app>
