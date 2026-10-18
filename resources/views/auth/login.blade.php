<x-layouts.app title="Masuk ke Akun — BelajarYuk">
    <div class="min-h-[85vh] flex items-center justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8 bg-[#F3F5FA] relative overflow-hidden" x-data="{ showPassword: false }">
        <!-- Ambient decorative blur in Deep Navy & Blue -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#080F63]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full space-y-6 relative z-10">
            <!-- Main Login Panel -->
            <div class="p-5 sm:p-10 bg-white shadow-xl space-y-6 border border-[#E5E8F0] rounded-2xl">
                <!-- Brand & Title Header -->
                <div class="text-center space-y-2">
                    <a href="{{ route('home') }}" class="inline-block transform hover:scale-105 transition">
                        <x-logo size="lg" />
                    </a>
                    <h2 class="text-2xl font-extrabold text-[#111827] tracking-tight pt-2">Masuk ke Akun Anda</h2>
                    <p class="text-xs text-[#64748B] font-normal">Silakan masukkan email dan kata sandi untuk melanjutkan.</p>
                </div>

                @if(session('error'))
                    <div class="bg-[#FEE2E2] border border-[#FECACA] text-[#991B1B] rounded-2xl p-3.5 text-xs font-semibold flex items-center gap-2.5">
                        <x-icons.x-mark class="w-4 h-4 text-[#EF4444] shrink-0" />
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-[#DCFCE7] border border-[#BBF7D0] text-[#166534] rounded-2xl p-3.5 text-xs font-semibold flex items-center gap-2.5">
                        <x-icons.check class="w-4 h-4 text-[#22C55E] shrink-0" />
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login', [], false) }}" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                value="{{ old('email') }}" 
                                placeholder="nama@email.com" 
                                class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] @error('email') border-[#EF4444] bg-[#FEE2E2]/30 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-[#EF4444] font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-[#111827] uppercase tracking-wider">Kata Sandi</label>
                            <span class="text-[11px] text-[#080F63] font-bold hover:underline cursor-pointer">Lupa kata sandi?</span>
                        </div>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                :type="showPassword ? 'text' : 'password'" 
                                autocomplete="current-password" 
                                required 
                                value="" 
                                placeholder="••••••••" 
                                class="w-full bg-white border border-[#E2E8F0] rounded-xl text-xs font-medium text-slate-800 py-3 px-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] @error('password') border-[#EF4444] bg-[#FEE2E2]/30 @enderror"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan password"
                            >
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4 text-[#080F63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-[#EF4444] font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" checked class="h-4 w-4 text-[#080F63] focus:ring-[#080F63] border-slate-300 rounded">
                        <label for="remember" class="ml-2 block text-xs font-semibold text-slate-600 cursor-pointer">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#080F63] hover:bg-[#050A4F] text-white font-bold py-3 rounded-xl text-sm shadow-md transition cursor-pointer">
                            Masuk Sekarang →
                        </button>
                    </div>
                </form>

                <!-- Footer Sign up prompt -->
                <div class="pt-4 border-t border-[#F0F0F0] text-center text-xs text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-[#080F63] hover:underline">
                        Daftar sebagai Orang Tua atau Guru
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
