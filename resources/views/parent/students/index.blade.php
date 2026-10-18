<x-layouts.dashboard title="Anak Saya — BelajarYuk">
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ addModal: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#171923] tracking-tight">
                    Anak Saya
                </h1>
                <p class="text-sm text-[#747B8C] mt-1">
                    Kelola profil anak, jenjang pendidikan, dan catatan khusus kebutuhan belajar
                </p>
            </div>
            <button @click="addModal = true" class="btn-navy text-xs py-2.5 px-5 gap-2 shadow-md self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Profil Anak</span>
            </button>
        </div>

        <!-- Students Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($students as $st)
                <div class="bd-card p-6 flex flex-col justify-between space-y-4 hover:-translate-y-1 transition duration-200">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-blue-50 text-[#080F63] font-extrabold text-base flex items-center justify-center border border-blue-100">
                                    {{ substr($st->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-[#171923] leading-tight">{{ $st->name }}</h3>
                                    <p class="text-xs text-[#747B8C] mt-0.5">{{ $st->class_grade ?? 'Jenjang ' . strtoupper($st->grade_level) }} &bull; {{ $st->school_name ?? 'Sekolah Umum' }}</p>
                                </div>
                            </div>
                            <span class="badge-status badge-status-scheduled text-[10px] uppercase">
                                {{ $st->grade_level }}
                            </span>
                        </div>

                        <!-- Ringkasan Akademik & Catatan Belajar -->
                        @if($st->notes)
                            <div class="p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E5E8F0] text-xs text-[#171923] leading-relaxed">
                                <span class="font-bold text-[#080F63] block text-[11px] mb-1">Catatan Kebutuhan Belajar:</span>
                                {{ $st->notes }}
                            </div>
                        @endif

                        @if($st->address || $st->city)
                            <div class="text-xs text-[#747B8C] flex items-start gap-1.5 pt-1">
                                <span class="text-slate-400">📍</span>
                                <div>
                                    <p class="font-medium text-[#171923]">{{ $st->address ?? 'Alamat rumah belum diisi' }}</p>
                                    @if($st->village || $st->district || $st->city)
                                        <p class="text-[11px] text-[#747B8C]">
                                            {{ $st->village ? 'Kel. ' . $st->village . ', ' : '' }}
                                            {{ $st->district ? 'Kec. ' . $st->district . ', ' : '' }}
                                            {{ $st->city }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-[#E5E8F0] flex items-center justify-between">
                        <form method="POST" action="{{ route('parent.students.delete', $st->id) }}" onsubmit="return confirm('Hapus profil anak ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-medium transition">Hapus</button>
                        </form>
                        <a href="{{ route('teachers.search', ['city_id' => $st->city_id, 'district_id' => $st->district_id, 'village_id' => $st->village_id]) }}" class="btn-navy py-1.5 px-4 text-xs font-semibold">
                            Cari Guru &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bd-card p-12 text-center space-y-3">
                    <div class="w-14 h-14 rounded-full bg-blue-50 text-[#080F63] flex items-center justify-center mx-auto border border-blue-100 text-xl font-bold">
                        👶
                    </div>
                    <p class="text-base font-bold text-[#171923]">Belum ada profil anak yang terdaftar.</p>
                    <p class="text-xs text-[#747B8C] max-w-md mx-auto">Tambahkan profil anak Anda untuk mempermudah pencarian guru les privat yang sesuai dengan jenjang sekolah.</p>
                    <button @click="addModal = true" class="btn-navy text-xs py-2.5 px-5 mt-2">
                        + Tambah Profil Anak Sekarang
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Modal Tambah Profil Anak -->
        <div x-show="addModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs" 
             style="display: none;"
             @keydown.escape.window="addModal = false">
            <div class="bg-white rounded-[24px] max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-[#E5E8F0]" @click.away="addModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-[#E5E8F0]">
                    <h3 class="font-extrabold text-lg text-[#171923]">Tambah Profil Anak</h3>
                    <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('parent.students.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1">Nama Lengkap Anak</label>
                        <input type="text" name="name" required placeholder="Contoh: Rian Pratama" class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1">Jenjang</label>
                            <select name="grade_level" required class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                                <option value="sd">SD (Kelas 1-6)</option>
                                <option value="smp" selected>SMP (Kelas 7-9)</option>
                                <option value="sma">SMA (Kelas 10-12)</option>
                                <option value="ujian">Persiapan UTBK</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1">Kelas Spesifik</label>
                            <input type="text" name="class_grade" placeholder="Contoh: Kelas 7" class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1">Nama Sekolah</label>
                        <input type="text" name="school_name" placeholder="Contoh: SMP Negeri 11 Jakarta" class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2.5 px-3.5 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1">Alamat Rumah (Untuk Les Tatap Muka)</label>
                        <textarea name="address" rows="2" placeholder="Alamat lengkap rumah..." class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2 px-3 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#171923] uppercase tracking-wider mb-1">Catatan Khusus Kebutuhan Belajar</label>
                        <textarea name="notes" rows="2" placeholder="Contoh: Perlu pendalaman aljabar, anak cenderung visual..." class="w-full bg-[#F3F5FA] border border-[#E5E8F0] rounded-xl py-2 px-3 text-xs text-[#171923] focus:outline-none focus:border-[#080F63]"></textarea>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2">
                        <button type="button" @click="addModal = false" class="btn-white-pill text-xs py-2.5 px-4 font-semibold">Batal</button>
                        <button type="submit" class="btn-navy text-xs py-2.5 px-6 font-bold shadow-md">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
