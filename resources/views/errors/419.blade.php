<x-layouts.app title="Sesi Berakhir (419) — BelajarYuk">
    <div class="min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 card-shadow">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <div class="space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                    Kode Status 419 • Sesi Kedaluwarsa
                </span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sesi Anda Telah Diperbarui</h1>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Token keamanan form telah kedaluwarsa demi melindungi data akun Anda. Silakan klik tombol di bawah untuk melanjutkan secara aman.
                </p>
            </div>

            <div class="pt-2 space-y-3">
                <a href="{{ route('login') }}" class="w-full btn-primary py-3.5 text-sm shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Masuk ke Akun Sekarang</span>
                </a>
                <a href="{{ route('home') }}" class="w-full py-3 px-4 rounded-xl border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-50 transition block">
                    Kembali ke Beranda
                </a>
            </div>

            <p class="text-[11px] text-slate-400">
                Otomatis mengarahkan ke halaman masuk dalam <span id="countdown" class="font-bold text-slate-600">3</span> detik...
            </p>
        </div>
    </div>

    <script>
        (function() {
            let seconds = 3;
            const el = document.getElementById('countdown');
            const timer = setInterval(function() {
                seconds--;
                if (el) el.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = "{{ route('login') }}";
                }
            }, 1000);
        })();
    </script>
</x-layouts.app>
