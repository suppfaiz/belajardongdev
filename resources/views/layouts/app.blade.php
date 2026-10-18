<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'BelajarYuk — Temukan Guru Les Terbaik untuk Masa Depan Anak Anda' }}</title>
    <meta name="description" content="Pilih dari ribuan guru berpengalaman di berbagai mata pelajaran dan jenjang, tersedia untuk les Online maupun Tatap Muka.">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-[#F3F5FA] text-[#171923] font-sans antialiased selection:bg-[#080F63] selection:text-white pb-20 md:pb-0">
    <!-- Navbar BelajarYuk with Smooth Animated Blue Underlines -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#E5E8F0] shadow-2xs transition-all" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[72px]">
                <!-- Left: Logo -->
                <div class="flex items-center gap-10">
                    <a href="{{ route('home') }}" class="focus:outline-none transition-transform hover:scale-105">
                        <x-logo size="default" />
                    </a>
                </div>

                <!-- Center: Navigation Links with Animated Blue Underline Indicator -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                    <!-- Cari Guru -->
                    <a href="{{ route('teachers.search') }}" 
                       class="relative py-2 transition-colors duration-200 group {{ request()->routeIs('teachers.search*') ? 'text-[#080F63] font-bold' : 'text-[#344054] hover:text-[#080F63]' }}">
                        <span>Cari Guru</span>
                        <span class="absolute bottom-0 left-0 h-[2.5px] rounded-full bg-[#080F63] transition-all duration-300 ease-out {{ request()->routeIs('teachers.search*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>

                    <!-- Keunggulan -->
                    <a href="{{ route('home') }}#keunggulan" 
                       class="relative py-2 transition-colors duration-200 group text-[#344054] hover:text-[#080F63]">
                        <span>Keunggulan</span>
                        <span class="absolute bottom-0 left-0 h-[2.5px] rounded-full bg-[#080F63] transition-all duration-300 ease-out w-0 group-hover:w-full"></span>
                    </a>

                    <!-- Cara Kerja -->
                    <a href="{{ route('home') }}#cara-kerja" 
                       class="relative py-2 transition-colors duration-200 group text-[#344054] hover:text-[#080F63]">
                        <span>Cara Kerja</span>
                        <span class="absolute bottom-0 left-0 h-[2.5px] rounded-full bg-[#080F63] transition-all duration-300 ease-out w-0 group-hover:w-full"></span>
                    </a>

                    <!-- Testimoni -->
                    <a href="{{ route('home') }}#rekomendasi" 
                       class="relative py-2 transition-colors duration-200 group text-[#344054] hover:text-[#080F63]">
                        <span>Testimoni</span>
                        <span class="absolute bottom-0 left-0 h-[2.5px] rounded-full bg-[#080F63] transition-all duration-300 ease-out w-0 group-hover:w-full"></span>
                    </a>

                    <!-- Jadi Guru -->
                    <a href="{{ route('for-teachers') }}" 
                       class="relative py-2 transition-colors duration-200 group {{ request()->routeIs('for-teachers*') ? 'text-[#080F63] font-bold' : 'text-[#344054] hover:text-[#080F63]' }}">
                        <span>Jadi Guru</span>
                        <span class="absolute bottom-0 left-0 h-[2.5px] rounded-full bg-[#080F63] transition-all duration-300 ease-out {{ request()->routeIs('for-teachers*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                </nav>

                <!-- Right Action: Masuk & Daftar Gratis or Auth User Info + Dashboard + Keluar -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @php
                            $user = Auth::user();
                            $dashboardRoute = match($user->role) {
                                'admin' => route('admin.dashboard'),
                                'teacher' => route('teacher.dashboard'),
                                default => route('parent.dashboard'),
                            };
                            $roleLabel = match($user->role) {
                                'admin' => 'Admin',
                                'teacher' => 'Guru',
                                default => 'Orang Tua',
                            };
                        @endphp
                        <!-- User Avatar Chip -->
                        <div class="flex items-center gap-2 pl-2.5 pr-3 py-1 bg-slate-50 border border-slate-200/80 rounded-full">
                            <img src="{{ $user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                                 class="w-7 h-7 rounded-full object-cover ring-1 ring-[#080F63]/20" 
                                 alt="{{ $user->name }}">
                            <div class="text-left hidden lg:block leading-tight">
                                <span class="text-xs font-bold text-[#111827] block truncate max-w-[110px]">{{ $user->name }}</span>
                                <span class="text-[10px] font-semibold text-[#080F63] block capitalize">{{ $roleLabel }}</span>
                            </div>
                        </div>

                        <!-- Dashboard Button -->
                        <a href="{{ $dashboardRoute }}" class="bg-[#080F63] hover:bg-[#050A4F] text-white font-bold text-xs py-2.5 px-4 rounded-full shadow-md shadow-[#080F63]/25 hover:shadow-lg hover:-translate-y-0.5 transition duration-200 inline-flex items-center gap-1.5">
                            <span>Dashboard</span>
                            <span class="text-sm leading-none">&rarr;</span>
                        </a>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-3.5 py-2 rounded-full border border-slate-300 bg-white text-xs font-bold text-slate-600 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50/60 shadow-2xs transition duration-200 inline-flex items-center gap-1.5 cursor-pointer" 
                                    title="Keluar dari akun">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full border border-slate-300 bg-white text-xs font-bold text-[#080F63] hover:border-[#080F63] hover:bg-blue-50/50 shadow-2xs transition duration-200">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-[#080F63] hover:bg-[#050A4F] text-white text-xs font-bold shadow-md shadow-[#080F63]/25 hover:shadow-lg hover:-translate-y-0.5 transition duration-200">
                            Daftar Gratis
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Hamburger -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Drawer -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden py-4 border-t border-[#E5E8F0] space-y-2 bg-white"
                 style="display: none;">
                <a href="{{ route('teachers.search') }}" class="block px-3 py-2 rounded-xl font-semibold text-sm hover:bg-blue-50 hover:text-[#080F63] transition">Cari Guru</a>
                <a href="{{ route('home') }}#keunggulan" class="block px-3 py-2 rounded-xl font-semibold text-sm hover:bg-blue-50 hover:text-[#080F63] transition">Keunggulan</a>
                <a href="{{ route('home') }}#cara-kerja" class="block px-3 py-2 rounded-xl font-semibold text-sm hover:bg-blue-50 hover:text-[#080F63] transition">Cara Kerja</a>
                <a href="{{ route('home') }}#rekomendasi" class="block px-3 py-2 rounded-xl font-semibold text-sm hover:bg-blue-50 hover:text-[#080F63] transition">Testimoni</a>
                <a href="{{ route('for-teachers') }}" class="block px-3 py-2 rounded-xl font-semibold text-sm hover:bg-blue-50 hover:text-[#080F63] transition">Jadi Guru</a>
                <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                    @auth
                        @php
                            $user = Auth::user();
                            $dashboardRoute = match($user->role) {
                                'admin' => route('admin.dashboard'),
                                'teacher' => route('teacher.dashboard'),
                                default => route('parent.dashboard'),
                            };
                            $roleLabel = match($user->role) {
                                'admin' => 'Admin',
                                'teacher' => 'Guru',
                                default => 'Orang Tua',
                            };
                        @endphp
                        <div class="space-y-2 w-full pt-1">
                            <div class="flex items-center gap-2.5 px-3 py-2 bg-slate-50 border border-slate-200/80 rounded-xl">
                                <img src="{{ $user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                                     class="w-8 h-8 rounded-full object-cover ring-1 ring-[#080F63]/20" 
                                     alt="{{ $user->name }}">
                                <div class="text-left text-xs leading-tight">
                                    <div class="font-bold text-[#111827]">{{ $user->name }}</div>
                                    <div class="text-[11px] font-semibold text-[#080F63]">{{ $roleLabel }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ $dashboardRoute }}" class="flex-1 bg-[#080F63] text-white text-xs py-2.5 rounded-xl text-center font-bold hover:bg-[#050A4F] transition">
                                    Dashboard &rarr;
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full border border-rose-200 text-rose-600 bg-rose-50/50 hover:bg-rose-100 text-xs py-2.5 rounded-xl text-center font-bold transition cursor-pointer flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 border border-slate-300 text-[#080F63] text-xs py-2.5 rounded-xl text-center font-bold hover:bg-slate-50">Masuk</a>
                        <a href="{{ route('register') }}" class="flex-1 bg-[#080F63] text-white text-xs py-2.5 rounded-xl text-center font-bold">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Content Slot -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer BelajarYuk -->
    <footer class="bg-white border-t border-[#E5E8F0] py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-3 md:col-span-1">
                    <x-logo size="default" />
                    <p class="text-xs text-[#747B8C] leading-relaxed">
                        Platform marketplace les privat terpercaya yang menghubungkan orang tua dan murid dengan guru honorer serta sarjana berdedikasi tinggi.
                    </p>
                    <p class="text-xs text-slate-400">© 2026 BelajarYuk. All rights reserved.</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#171923] uppercase tracking-wider mb-3">Eksplorasi</h4>
                    <ul class="space-y-2 text-xs text-[#747B8C]">
                        <li><a href="{{ route('teachers.search') }}" class="hover:text-[#080F63] transition">Cari Guru Les</a></li>
                        <li><a href="{{ route('teachers.search', ['grade' => 'sd']) }}" class="hover:text-[#080F63] transition">Les Privat SD</a></li>
                        <li><a href="{{ route('teachers.search', ['grade' => 'smp']) }}" class="hover:text-[#080F63] transition">Les Privat SMP</a></li>
                        <li><a href="{{ route('teachers.search', ['grade' => 'sma']) }}" class="hover:text-[#080F63] transition">Les Privat SMA & UTBK</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#171923] uppercase tracking-wider mb-3">Untuk Guru</h4>
                    <ul class="space-y-2 text-xs text-[#747B8C]">
                        <li><a href="{{ route('for-teachers') }}" class="hover:text-[#080F63] transition">Cara Menjadi Pengajar</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#080F63] transition">Daftar Sebagai Tutor</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-[#080F63] transition">Standar Verifikasi Dokumen</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#171923] uppercase tracking-wider mb-3">Bantuan & Keamanan</h4>
                    <ul class="space-y-2 text-xs text-[#747B8C]">
                        <li><a href="{{ route('about') }}" class="hover:text-[#080F63] transition">Tentang BelajarYuk</a></li>
                        <li><a href="#" class="hover:text-[#080F63] transition">Garansi Rekening Bersama</a></li>
                        <li><a href="#" class="hover:text-[#080F63] transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-[#080F63] transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation -->
    <x-mobile-bottom-nav />
</body>
</html>
