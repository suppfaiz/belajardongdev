<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} — BelajarYuk</title>

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
<body class="h-screen overflow-hidden bg-[#F3F5FA] text-[#171923] font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="h-full flex overflow-hidden">
        <!-- Off-Canvas Drawer (Mobile) -->
        <div x-show="sidebarOpen" 
             class="fixed inset-0 z-50 lg:hidden flex" 
             style="display: none;" 
             role="dialog" 
             aria-modal="true">
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs"
                 @click="sidebarOpen = false"></div>

            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex flex-col w-72 max-w-[85vw] bg-[#050A4F] z-50 text-slate-200 h-full">
                <div class="h-20 shrink-0 flex items-center justify-between px-6 border-b border-white/10">
                    <a href="{{ route('home') }}" class="focus:outline-none">
                        <x-logo :white="true" />
                    </a>
                    <button type="button" @click="sidebarOpen = false" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition">
                        <span class="sr-only">Tutup menu</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto min-h-0 px-4 py-5 space-y-1 sidebar-scroll">
                    @include('layouts.partials.dashboard-nav')
                </div>

                <div class="shrink-0 p-4 border-t border-white/10 flex items-center justify-between bg-[#04083F]">
                    @auth
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400 capitalize truncate">{{ Auth::user()->role === 'parent' ? 'Orang Tua' : (Auth::user()->role === 'teacher' ? 'Guru' : 'Admin') }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Keluar" class="p-2 text-slate-300 hover:text-red-400 rounded-lg hover:bg-white/10 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-navy text-xs py-2 px-4 w-full text-center">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Desktop Persistent Left Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#050A4F] shrink-0 text-slate-200 h-full">
            <!-- Brand Logo Area -->
            <div class="h-20 shrink-0 flex items-center px-6 border-b border-white/10">
                <a href="{{ route('home') }}" class="focus:outline-none">
                    <x-logo :white="true" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto min-h-0 px-4 py-6 space-y-1 sidebar-scroll">
                @include('layouts.partials.dashboard-nav')
            </div>

            <!-- Sidebar Logout Footer -->
            <div class="shrink-0 p-4 border-t border-white/10 bg-[#04083F]">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 font-medium text-sm transition">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-navy text-xs py-2 px-4 w-full text-center">Masuk</a>
                @endauth
            </div>
        </aside>

        <!-- Main Column Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#F3F5FA] h-full overflow-hidden">
            <!-- Topbar Header -->
            <header class="h-20 bg-white border-b border-[#E5E8F0] shrink-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-xs">
                <!-- Left: Hamburger (Mobile) + Global Search Bar -->
                <div class="flex items-center gap-4 flex-1 max-w-2xl">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 rounded-xl hover:bg-slate-100 transition" aria-label="Buka menu navigasi">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <form action="{{ Route::has('parent.teachers.search') && Auth::user()?->role === 'parent' ? route('parent.teachers.search') : route('teachers.search', ['from' => 'dashboard']) }}" method="GET" class="relative w-full max-w-lg">
                        <div class="relative flex items-center">
                            <svg class="w-4 h-4 text-slate-400 absolute left-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" 
                                   name="q" 
                                   value="{{ request('q') }}"
                                   placeholder="Cari guru, mata pelajaran, atau kebutuhan belajar..." 
                                   class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-full pl-11 pr-4 py-2.5 text-sm text-[#171923] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#080F63]/20 focus:border-[#080F63] transition">
                        </div>
                    </form>
                </div>

                <!-- Right: Notifications + User Profile -->
                <div class="flex items-center gap-4">
                    @auth
                        <!-- Notification Bell with Red Badge -->
                        <div class="relative">
                            <button type="button" class="p-2.5 text-slate-600 hover:text-[#080F63] rounded-full bg-[#F3F5FA] hover:bg-slate-100 transition relative">
                                <span class="sr-only">Notifikasi</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                            </button>
                        </div>

                        <!-- User Profile Chip -->
                        @php
                            $profileRoute = match(Auth::user()->role) {
                                'admin' => route('admin.dashboard'),
                                'teacher' => route('teacher.profile'),
                                default => route('parent.profile'),
                            };
                            $roleLabel = match(Auth::user()->role) {
                                'admin' => 'Admin',
                                'teacher' => 'Guru',
                                default => 'Orang Tua',
                            };
                        @endphp
                        <a href="{{ $profileRoute }}" class="flex items-center gap-3 pl-2 pr-1 py-1 rounded-full hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-[#080F63]/20" 
                                 src="{{ Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80' }}" 
                                 alt="{{ Auth::user()->name }}">
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-bold text-[#171923] leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-slate-500 leading-tight">{{ $roleLabel }}</p>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-navy text-xs py-2 px-5">Masuk</a>
                    @endauth
                </div>
            </header>

            <!-- Page Main Content Slot -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto pb-20 lg:pb-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-xs">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between text-sm shadow-xs">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @php
        $isTeacher = auth()->check() && auth()->user()->role === 'teacher';
        $homeRoute = $isTeacher ? route('teacher.dashboard') : route('parent.dashboard');
        $homeActive = $isTeacher ? request()->routeIs('teacher.dashboard') : request()->routeIs('parent.dashboard');
        $scheduleRoute = $isTeacher ? route('teacher.schedules') : route('parent.schedule');
        $scheduleActive = $isTeacher ? request()->routeIs('teacher.schedules*') : request()->routeIs('parent.schedule*');
        $messagesRoute = $isTeacher ? route('teacher.messages') : route('parent.messages');
        $messagesActive = $isTeacher ? request()->routeIs('teacher.messages*') : request()->routeIs('parent.messages*');
        $profileRoute = $isTeacher ? route('teacher.profile') : route('parent.profile');
        $profileActive = $isTeacher ? request()->routeIs('teacher.profile*') : request()->routeIs('parent.profile*');
    @endphp

    <!-- Bottom Navigation Bar (Mobile View - 4 Tabs: Beranda, Jadwal, Pesan, Akun) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-[#E5E8F0] shadow-lg flex items-center justify-around py-2 px-3">
        <a href="{{ $homeRoute }}" class="flex flex-col items-center gap-1 py-1 px-3 text-xs font-semibold {{ $homeActive ? 'text-[#080F63]' : 'text-slate-400' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Beranda</span>
        </a>

        <a href="{{ $scheduleRoute }}" class="flex flex-col items-center gap-1 py-1 px-3 text-xs font-semibold {{ $scheduleActive ? 'text-[#080F63]' : 'text-slate-400' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Jadwal</span>
        </a>

        <a href="{{ $messagesRoute }}" class="flex flex-col items-center gap-1 py-1 px-3 text-xs font-semibold {{ $messagesActive ? 'text-[#080F63]' : 'text-slate-400' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>Pesan</span>
        </a>

        <a href="{{ $profileRoute }}" class="flex flex-col items-center gap-1 py-1 px-3 text-xs font-semibold {{ $profileActive ? 'text-[#080F63]' : 'text-slate-400' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Akun</span>
        </a>
    </nav>
</body>
</html>
