<x-layouts.dashboard title="Manajemen Pengguna — BelajarYuk" headerTitle="Manajemen Pengguna" headerSubtitle="Kelola seluruh akun pengguna, peran hak akses, dan status penangguhan akun">
    <div class="space-y-5 pt-2">
        <!-- Search and Filter Bar -->
        <div class="panel-window p-4">
            <form action="{{ route('admin.users') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between text-xs">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-80">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, atau telepon..." class="w-full pl-9 pr-3 py-2 h-9 rounded-lg border border-slate-200 text-xs text-slate-900 placeholder-slate-400 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                    </div>
                    <button type="submit" class="btn-primary text-xs py-2 px-3.5 shrink-0">Cari</button>
                    @if($search || $role)
                        <a href="{{ route('admin.users') }}" class="btn-secondary text-xs py-2 px-3 shrink-0">Reset</a>
                    @endif
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto w-full sm:w-auto justify-end">
                    <span class="text-slate-500 text-xs font-medium">Filter Peran:</span>
                    <select name="role" onchange="this.form.submit()" class="h-9 border border-slate-200 rounded-lg px-3 text-xs font-medium text-slate-800 outline-none focus:border-blue-500 bg-white">
                        <option value="">Semua Peran</option>
                        <option value="parent" {{ $role === 'parent' ? 'selected' : '' }}>Orang Tua</option>
                        <option value="teacher" {{ $role === 'teacher' ? 'selected' : '' }}>Guru Les</option>
                        <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Nama Pengguna</th>
                            <th scope="col">Kontak (Email / HP)</th>
                            <th scope="col">Peran</th>
                            <th scope="col">Status Akun</th>
                            <th scope="col">Bergabung</th>
                            <th scope="col" class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 leading-tight">{{ $u->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono tabular-nums">{{ $u->uuid }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-800">{{ $u->email }}</p>
                                    <p class="text-slate-400 text-[11px] tabular-nums">{{ $u->phone ?? '—' }}</p>
                                </td>
                                <td>
                                    @php
                                        $roleBadge = match($u->role) {
                                            'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'teacher' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                        $roleLabel = match($u->role) {
                                            'admin' => 'Admin',
                                            'teacher' => 'Guru Les',
                                            default => 'Orang Tua',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $roleBadge }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td>
                                    @if($u->is_active)
                                        <span class="badge-verified text-[10px]">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditangguhkan</span>
                                    @endif
                                </td>
                                <td class="text-slate-500 tabular-nums">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    @if($u->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}" onsubmit="return confirm('Ubah status aktif akun ini?')">
                                            @csrf
                                            <button type="submit" class="btn-sm {{ $u->is_active ? 'btn-destructive' : 'btn-secondary text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100' }}">
                                                {{ $u->is_active ? 'Tangguhkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <x-icons.user class="w-8 h-8 text-slate-300" />
                                        <p class="font-medium text-slate-600">Tidak ada pengguna yang cocok dengan kriteria pencarian.</p>
                                        <p class="text-[11px] text-slate-400">Coba ubah kata kunci atau reset filter peran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.dashboard>
