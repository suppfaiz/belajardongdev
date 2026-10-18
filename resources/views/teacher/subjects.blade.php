<x-layouts.dashboard title="Mata Pelajaran & Jenjang — BelajarYuk" headerTitle="Mata Pelajaran yang Diajarkan" headerSubtitle="Pilih mata pelajaran, tingkat jenjang, dan sesuaikan tarif les Anda">
    <div class="max-w-4xl space-y-6 pt-4">

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-lg space-y-1">
                <p class="text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    Mohon periksa kembali:
                </p>
                <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5 pl-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.subjects.update') }}" class="space-y-4">
            @csrf

            <div class="panel-window p-6 sm:p-8 space-y-5 text-xs">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Katalog Mata Pelajaran</h3>
                        <p class="text-slate-500 text-[11px] mt-0.5">Centang mata pelajaran yang Anda kuasai dan tentukan jenjang murid yang bersedia Anda bimbing.</p>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100 shrink-0 ml-4">
                        {{ $allSubjects->count() }} Pelajaran
                    </span>
                </div>

                @php
                    $attachedSubjects = $profile->subjects->keyBy('id');
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($allSubjects as $subj)
                        @php
                            $isAttached = $attachedSubjects->has($subj->id);
                            $pivot = $isAttached ? $attachedSubjects[$subj->id]->pivot : null;
                            $savedGrades = $pivot && $pivot->grade_levels
                                ? (is_array($pivot->grade_levels) ? $pivot->grade_levels : json_decode($pivot->grade_levels, true))
                                : ['sd', 'smp'];
                            $customRate = $pivot ? $pivot->custom_hourly_rate : null;
                        @endphp

                        <div class="rounded-xl border transition {{ $isAttached ? 'border-blue-200 bg-blue-50/30' : 'border-slate-200 bg-white' }} p-4 space-y-3"
                             x-data="{ enabled: {{ $isAttached ? 'true' : 'false' }} }">

                            {{-- Subject Toggle Header --}}
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                    <input type="checkbox"
                                        name="subjects[{{ $subj->id }}][selected]"
                                        value="1"
                                        x-model="enabled"
                                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                    <span class="text-sm font-bold text-slate-900">{{ $subj->name }}</span>
                                </label>
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200 shrink-0">
                                    {{ $subj->category }}
                                </span>
                            </div>

                            {{-- Expanded Options --}}
                            <div x-show="enabled" x-transition class="pt-3 border-t border-slate-100 space-y-3">
                                {{-- Grade Levels --}}
                                <div>
                                    <p class="font-bold text-slate-600 text-[11px] mb-2 uppercase tracking-wider">Jenjang yang Dilayani:</p>
                                    <div class="flex flex-wrap gap-3 text-[11px]">
                                        @foreach(['sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA', 'ujian' => 'UTBK'] as $val => $label)
                                            <label class="flex items-center gap-1.5 cursor-pointer select-none">
                                                <input type="checkbox"
                                                    name="subjects[{{ $subj->id }}][grades][]"
                                                    value="{{ $val }}"
                                                    {{ in_array($val, $savedGrades ?? []) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                                <span class="font-semibold text-slate-700">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Custom Rate --}}
                                <div>
                                    <label for="rate_{{ $subj->id }}" class="block font-bold text-slate-600 text-[11px] mb-1.5 uppercase tracking-wider">
                                        Tarif Khusus Pelajaran Ini <span class="font-normal text-slate-400 normal-case">(opsional)</span>
                                    </label>
                                    <input id="rate_{{ $subj->id }}"
                                        type="number"
                                        name="subjects[{{ $subj->id }}][rate]"
                                        value="{{ $customRate }}"
                                        placeholder="Gunakan tarif default (Rp {{ number_format($profile->hourly_rate, 0, ',', '.') }})"
                                        class="w-full">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="btn-primary px-8 text-xs font-bold">
                        Simpan Pilihan Mata Pelajaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
