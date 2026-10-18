<x-layouts.dashboard title="Verifikasi Guru — BelajarYuk" headerTitle="Verifikasi Dokumen Guru" headerSubtitle="Validasi berkas identitas KTP dan ijazah guru honorer sebelum ditampilkan ke publik">
    <div class="space-y-5 pt-2">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100/90 rounded-lg text-xs font-medium overflow-x-auto border border-slate-200/60 max-w-full">
            <a href="{{ route('admin.teachers', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'pending' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Menunggu Verifikasi</a>
            <a href="{{ route('admin.teachers', ['status' => 'verified']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'verified' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Terverifikasi</a>
            <a href="{{ route('admin.teachers', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'rejected' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Ditolak</a>
            <a href="{{ route('admin.teachers', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-md transition whitespace-nowrap {{ $status === 'all' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Semua Guru</a>
        </div>

        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Nama Guru</th>
                            <th scope="col">Pendidikan</th>
                            <th scope="col">Area Layanan</th>
                            <th scope="col">Dokumen</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $t)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200">
                                            {{ substr($t->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 leading-tight">{{ $t->user->name }}</p>
                                            <p class="text-slate-400 text-[11px]">{{ $t->user->email }} • {{ $t->user->phone ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-800">{{ $t->education ?? 'Pendidikan Guru' }}</p>
                                </td>
                                <td>
                                    <p class="text-slate-700">{{ $t->service_city ?? 'Jakarta Selatan' }}</p>
                                    <p class="text-[11px] text-slate-400">Radius {{ $t->max_travel_distance_km }} km</p>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $t->documents->count() }} Berkas
                                    </span>
                                </td>
                                <td>
                                    @if($t->verification_status === 'verified')
                                        <span class="badge-verified text-[10px]">Terverifikasi</span>
                                    @elseif($t->verification_status === 'rejected')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                    @else
                                        <span class="badge-pending text-[10px]">Menunggu Review</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.teachers.show', $t->id) }}" class="btn-primary btn-sm inline-flex">
                                        Periksa Berkas &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <x-icons.user class="w-8 h-8 text-slate-300" />
                                        <p class="font-medium text-slate-600">Tidak ada guru dalam status ini.</p>
                                        <p class="text-[11px] text-slate-400">Pilih tab status lain untuk melihat data guru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>
