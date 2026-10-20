@php
    $user = Auth::user();
    $role = $user?->role ?? 'guest';
    
    // Determine dynamic routes based on authentication & role
    $messagesRoute = match($role) {
        'teacher' => route('teacher.messages'),
        'parent' => route('parent.messages'),
        'admin' => route('admin.tickets'),
        default => route('login'),
    };

    $bookingsRoute = match($role) {
        'teacher' => route('teacher.bookings'),
        'parent' => route('parent.bookings'),
        'admin' => route('admin.bookings'),
        default => route('login'),
    };

    $accountRoute = match($role) {
        'teacher' => route('teacher.profile'),
        'parent' => route('parent.dashboard'),
        'admin' => route('admin.dashboard'),
        default => route('login'),
    };

    $isHomeActive = request()->routeIs('home');
    $isTeacherDashboardActive = request()->routeIs('teacher.dashboard');
    $isSchedulesActive = request()->routeIs('teacher.schedules*');
    $isSearchActive = request()->routeIs('teachers.search', 'teachers.show');
    $isMessagesActive = request()->routeIs('teacher.messages*', 'parent.messages*', 'admin.tickets.*');
    $isBookingsActive = request()->routeIs('*.bookings.*', 'bookings.*');
    $isProfileActive = request()->routeIs('teacher.profile*');
    $isAccountActive = request()->routeIs('login', 'register', 'parent.dashboard', 'parent.profile', 'admin.*');
@endphp

@unless(request()->routeIs('teachers.show'))
<!-- Sleek Native App-Style Mobile Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 w-full inset-x-0 bg-white/95 backdrop-blur-xl border-t border-slate-200/90 z-50 md:hidden shadow-[0_-4px_24px_rgba(8,15,99,0.07)] safe-pb transition-all" 
     style="position: fixed; bottom: 0; left: 0; right: 0; width: 100%; z-index: 50;" 
     aria-label="Navigasi Bawah">
    <div class="grid grid-cols-5 h-16 w-full max-w-md mx-auto px-2"
         style="display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); height: 64px; width: 100%;">
        
        @if($role === 'teacher')
            <!-- 1. Teacher Dashboard -->
            <a href="{{ route('teacher.dashboard') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isTeacherDashboardActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isTeacherDashboardActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isTeacherDashboardActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] mt-1 {{ $isTeacherDashboardActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Dashboard</span>
            </a>

            <!-- 2. Teacher Schedules -->
            <a href="{{ route('teacher.schedules') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isSchedulesActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isSchedulesActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isSchedulesActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[10px] mt-1 {{ $isSchedulesActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Jadwal</span>
            </a>

            <!-- 3. Teacher Messages -->
            <a href="{{ route('teacher.messages') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isMessagesActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isMessagesActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <div class="relative">
                    <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isMessagesActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <span class="text-[10px] mt-1 {{ $isMessagesActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Pesan</span>
            </a>

            <!-- 4. Teacher Bookings -->
            <a href="{{ route('teacher.bookings') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isBookingsActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isBookingsActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isBookingsActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-[10px] mt-1 {{ $isBookingsActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Pesanan</span>
            </a>

            <!-- 5. Teacher Profile -->
            <a href="{{ route('teacher.profile') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isProfileActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isProfileActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <div class="w-5 h-5 rounded-full overflow-hidden ring-1.5 {{ $isProfileActive ? 'ring-[#080F63]' : 'ring-slate-300' }} transition-all">
                    <img src="{{ $user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <span class="text-[10px] mt-1 {{ $isProfileActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Profil</span>
            </a>
        @else
            <!-- 1. Home -->
            <a href="{{ route('home') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isHomeActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isHomeActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isHomeActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] mt-1 {{ $isHomeActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Beranda</span>
            </a>

            <!-- 2. Cari Guru -->
            <a href="{{ route('teachers.search') }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isSearchActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isSearchActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isSearchActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="text-[10px] mt-1 {{ $isSearchActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Cari Guru</span>
            </a>

            <!-- 3. Pesan / Chat -->
            <a href="{{ $messagesRoute }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isMessagesActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isMessagesActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <div class="relative">
                    <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isMessagesActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <span class="text-[10px] mt-1 {{ $isMessagesActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Pesan</span>
            </a>

            <!-- 4. Pesanan / Jadwal -->
            <a href="{{ $bookingsRoute }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isBookingsActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isBookingsActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isBookingsActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[10px] mt-1 {{ $isBookingsActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Pesanan</span>
            </a>

            <!-- 5. Akun / Profil -->
            <a href="{{ $accountRoute }}" 
               class="flex flex-col items-center justify-center py-1 transition-all duration-200 relative group {{ $isAccountActive ? 'text-[#080F63]' : 'text-slate-400 hover:text-slate-600' }}">
                @if($isAccountActive)
                    <span class="absolute top-1.5 w-6 h-1 bg-[#080F63] rounded-full"></span>
                @endif
                
                @auth
                    <div class="w-5 h-5 rounded-full overflow-hidden ring-1.5 {{ $isAccountActive ? 'ring-[#080F63]' : 'ring-slate-300' }} transition-all">
                        <img src="{{ $user->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <span class="text-[10px] mt-1 {{ $isAccountActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Akun</span>
                @else
                    <svg class="w-5 h-5 transition-transform group-active:scale-90 {{ $isAccountActive ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-[10px] mt-1 {{ $isAccountActive ? 'font-bold tracking-tight text-[#080F63]' : 'font-medium' }}">Masuk</span>
                @endauth
            </a>
        @endif
    </div>
</nav>
@endunless
