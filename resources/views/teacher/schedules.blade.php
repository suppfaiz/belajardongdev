<x-layouts.dashboard title="Jadwal Mengajar — BelajarYuk" headerTitle="Jadwal Mengajar Mingguan" headerSubtitle="Atur slot ketersediaan waktu rutin Anda untuk menerima pesanan les privat tatap muka">
    <div class="space-y-8 pt-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- Add New Slot Form --}}
            <div class="lg:col-span-4">
                <div class="panel-window p-6 sm:p-7 space-y-5 text-xs">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900">Tambah Slot Jadwal</h3>
                        <p class="text-slate-500 text-[11px] mt-0.5">Orang tua murid hanya dapat memilih jam les di dalam slot ketersediaan Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('teacher.schedules.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="day_of_week" class="block font-bold text-slate-700 uppercase mb-1.5">Hari dalam Seminggu</label>
                            <select id="day_of_week" name="day_of_week" required>
                                <option value="1">Senin</option>
                                <option value="2">Selasa</option>
                                <option value="3">Rabu</option>
                                <option value="4">Kamis</option>
                                <option value="5">Jumat</option>
                                <option value="6">Sabtu</option>
                                <option value="0">Minggu</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="start_time" class="block font-bold text-slate-700 uppercase mb-1.5">Jam Mulai (WIB)</label>
                                <input id="start_time" type="time" name="start_time" required value="15:00">
                            </div>
                            <div>
                                <label for="end_time" class="block font-bold text-slate-700 uppercase mb-1.5">Jam Selesai (WIB)</label>
                                <input id="end_time" type="time" name="end_time" required value="17:00">
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center text-xs font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Slot Jadwal
                        </button>
                    </form>
                </div>
            </div>

            {{-- Current Schedule Slots --}}
            <div class="lg:col-span-8">
                <div class="panel-window p-6 sm:p-7 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Slot Jadwal Mingguan</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Klik × untuk menghapus slot yang tidak dibutuhkan.</p>
                        </div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                            {{ $slots->count() }} Slot Aktif
                        </span>
                    </div>

                    @php
                        $days = [
                            1 => 'Senin',
                            2 => 'Selasa',
                            3 => 'Rabu',
                            4 => 'Kamis',
                            5 => 'Jumat',
                            6 => 'Sabtu',
                            0 => 'Minggu',
                        ];
                    @endphp

                    <div class="space-y-2.5">
                        @foreach($days as $dIndex => $dName)
                            @php $daySlots = $slots->where('day_of_week', $dIndex); @endphp
                            <div class="p-3.5 rounded-xl border {{ $daySlots->count() > 0 ? 'border-slate-200 bg-white' : 'border-slate-100 bg-slate-50/50' }} flex items-start gap-4">
                                <div class="w-16 shrink-0 pt-0.5">
                                    <span class="text-xs font-bold text-slate-900 block">{{ $dName }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $daySlots->count() > 0 ? $daySlots->count().' slot' : 'Libur' }}</span>
                                </div>

                                <div class="flex-1 flex flex-wrap gap-2">
                                    @forelse($daySlots as $sl)
                                        <div class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg text-xs font-bold text-blue-700">
                                            <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ substr($sl->start_time, 0, 5) }} – {{ substr($sl->end_time, 0, 5) }}</span>
                                            <form method="POST" action="{{ route('teacher.schedules.delete', $sl->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    title="Hapus slot ini"
                                                    class="text-slate-400 hover:text-rose-600 font-bold transition focus:outline-none"
                                                    onclick="return confirm('Hapus slot {{ substr($sl->start_time, 0, 5) }}–{{ substr($sl->end_time, 0, 5) }} {{ $dName }}?')">
                                                    &times;
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <span class="text-xs text-slate-400 italic py-1">Tidak ada jadwal / libur</span>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts.dashboard>
