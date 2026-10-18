<x-layouts.app title="Untuk Guru — Mengajar di BelajarYuk">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12 bg-[#FAFAFA]">
        <div class="text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-[#080F63] bg-blue-50 px-4 py-1.5 rounded-full border border-blue-200">Peluang Karir Pengajar</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#171717] tracking-tight font-['Poppins']">Raih Penghasilan Tambahan dengan Mengajar Les Privat</h1>
            <p class="text-base text-[#525252] max-w-2xl mx-auto leading-relaxed font-normal">
                Khusus untuk guru honorer, mahasiswa kependidikan, dan pengajar profesional di seluruh Indonesia.
            </p>
            <div class="pt-2">
                <a href="{{ route('register') }}" class="btn-primary text-sm font-bold shadow-md">
                    Daftar Jadi Guru Sekarang →
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-7 rounded-2xl border border-[#E5E5E5] shadow-xs space-y-3 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#080F63] flex items-center justify-center border border-blue-100">
                    <x-icons.wallet class="w-6 h-6 text-[#080F63]" />
                </div>
                <h3 class="text-base font-extrabold text-[#171717] font-['Poppins']">Tentukan Tarif Anda Sendiri</h3>
                <p class="text-xs text-[#525252] leading-relaxed font-normal">
                    Anda memegang kendali penuh atas tarif per sesi les dan durasi belajar berdasarkan keahlian dan jenjang yang Anda ajarkan.
                </p>
            </div>

            <div class="bg-white p-7 rounded-2xl border border-[#E5E5E5] shadow-xs space-y-3 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-[#ECFEFF] text-[#0891B2] flex items-center justify-center border border-[#A5F3FC]">
                    <x-icons.map-pin class="w-6 h-6 text-[#0891B2]" />
                </div>
                <h3 class="text-base font-extrabold text-[#171717] font-['Poppins']">Radius & Lokasi Fleksibel</h3>
                <p class="text-xs text-[#525252] leading-relaxed font-normal">
                    Tentukan sendiri seberapa jauh Anda bersedia bepergian (misalnya 3 km atau 5 km di sekitar tempat tinggal Anda).
                </p>
            </div>

            <div class="bg-white p-7 rounded-2xl border border-[#E5E5E5] shadow-xs space-y-3 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] text-[#166534] flex items-center justify-center border border-[#BBF7D0]">
                    <x-icons.bank class="w-6 h-6 text-[#166534]" />
                </div>
                <h3 class="text-base font-extrabold text-[#171717] font-['Poppins']">Pencairan Dana Pasti</h3>
                <p class="text-xs text-[#525252] leading-relaxed font-normal">
                    Orang tua membayar di muka ke rekening penampungan. Anda dijamin menerima saldo bersih tepat waktu setelah sesi les selesai.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#E5E5E5] shadow-xs space-y-6">
            <h3 class="text-xl font-extrabold text-[#171717] font-['Poppins']">Persyaratan Bergabung:</h3>
            <ol class="list-decimal list-inside space-y-3 text-sm text-[#525252] leading-relaxed pl-2 font-medium">
                <li>Memiliki komitmen tulus mendidik dan membimbing karakter siswa.</li>
                <li>Memiliki KTP elektronik asli dan masih berlaku.</li>
                <li>Memiliki Ijazah S1/D4/D3 atau Surat Keterangan Mahasiswa aktif dari perguruan tinggi terakreditasi.</li>
                <li>Bersedia mematuhi kode etik dan standar keselamatan les offline BelajarYuk.</li>
            </ol>
        </div>
    </div>
</x-layouts.app>
