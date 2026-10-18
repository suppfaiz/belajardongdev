<x-layouts.dashboard title="Mata Pelajaran — BelajarYuk" headerTitle="Mata Pelajaran & Jenjang" headerSubtitle="Kelola kurikulum, kategori jenjang, dan mata pelajaran bimbingan belajar">
    <div class="space-y-8 pt-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- Add New Subject Form --}}
            <div class="lg:col-span-4">
                <div class="panel-window p-6 sm:p-7 space-y-5 text-xs">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900">Tambah Mata Pelajaran Baru</h3>
                        <p class="text-slate-500 text-[11px] mt-0.5">Pelajaran baru akan tersedia untuk dipilih guru.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="subject_name" class="block font-bold text-slate-700 uppercase mb-1.5">Nama Mata Pelajaran</label>
                            <input id="subject_name" type="text" name="name" required
                                placeholder="Contoh: Geografi, Ekonomi, Bahasa Mandarin"
                                value="{{ old('name') }}"
                                class="w-full @error('name') border-rose-400 bg-rose-50/30 @enderror">
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject_category" class="block font-bold text-slate-700 uppercase mb-1.5">Kategori Jenjang Utama</label>
                            <select id="subject_category" name="category" required>
                                <option value="sd" {{ old('category') === 'sd' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                <option value="smp" {{ old('category') === 'smp' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                <option value="sma" {{ old('category') === 'sma' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                                <option value="ujian" {{ old('category') === 'ujian' ? 'selected' : '' }}>Persiapan UTBK / Kedinasan</option>
                                <option value="umum" {{ old('category') === 'umum' ? 'selected' : '' }}>Umum & Bahasa Asing</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center text-xs font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Simpan Mata Pelajaran
                        </button>
                    </form>
                </div>
            </div>

            {{-- Subject List --}}
            <div class="lg:col-span-8">
                <div class="panel-window p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Daftar Mata Pelajaran Aktif</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Tersedia untuk dipilih oleh guru terdaftar.</p>
                        </div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                            {{ $subjects->count() }} Pelajaran
                        </span>
                    </div>

                    @if($subjects->isEmpty())
                        <div class="py-12 text-center flex flex-col items-center gap-2">
                            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-xs text-slate-400 font-medium">Belum ada mata pelajaran. Tambahkan di panel kiri.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($subjects as $s)
                                <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-slate-200 flex items-center justify-between text-xs transition group">
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-900 truncate">{{ $s->name }}</h4>
                                        <span class="text-[10px] text-slate-400 uppercase font-semibold tracking-wider">{{ $s->category }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 ml-3">
                                        <span class="text-[11px] text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                                            {{ $s->teachers_count }} Guru
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-layouts.dashboard>
