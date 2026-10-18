<x-layouts.dashboard title="Pengaduan & Keamanan — BelajarYuk" headerTitle="Pengaduan & Keamanan Les" headerSubtitle="Tangani laporan keselamatan, kendala pembayaran, dan sengketa sesi les offline">
    <div class="space-y-6 pt-4">
        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kode Tiket</th>
                            <th>Pelapor</th>
                            <th>Kategori & Subjek</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $t)
                            <tr x-data="{ resolveModal: false }">
                                <td class="font-mono font-bold text-slate-900 text-[11px]">
                                    {{ $t->ticket_code }}
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $t->user->name }}</p>
                                    <p class="text-slate-400 text-[11px] capitalize">{{ $t->user->role }}</p>
                                </td>
                                <td>
                                    <span class="text-[10px] font-bold uppercase text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $t->category }}</span>
                                    <p class="font-semibold text-slate-800 mt-1 text-[12px]">{{ $t->subject }}</p>
                                    <p class="text-slate-500 text-[11px] line-clamp-1 mt-0.5">"{{ $t->message }}"</p>
                                </td>
                                <td>
                                    @php
                                        $priorityClass = match($t->priority) {
                                            'urgent' => 'bg-rose-100 text-rose-700 border border-rose-200',
                                            'high'   => 'bg-amber-100 text-amber-700 border border-amber-200',
                                            default  => 'bg-slate-100 text-slate-600 border border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $priorityClass }}">
                                        {{ $t->priority }}
                                    </span>
                                </td>
                                <td>
                                    @if($t->status === 'resolved')
                                        <span class="badge-verified">Terselesaikan</span>
                                    @elseif($t->status === 'open')
                                        <span class="badge-pending">Terbuka</span>
                                    @else
                                        <span class="badge-active">Investigasi</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <button type="button" @click="resolveModal = true" class="btn-primary btn-sm">
                                        Tanggapi
                                    </button>

                                    {{-- Resolve Modal --}}
                                    <div x-show="resolveModal"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm text-left"
                                         role="dialog"
                                         aria-modal="true"
                                         aria-label="Tanggapi Tiket {{ $t->ticket_code }}">
                                        <div @click.away="resolveModal = false"
                                             class="panel-window max-w-lg w-full p-6 space-y-4">
                                            {{-- Modal Header --}}
                                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900">Penanganan Tiket</h4>
                                                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">{{ $t->ticket_code }}</p>
                                                </div>
                                                <button @click="resolveModal = false"
                                                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition"
                                                        aria-label="Tutup modal">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            {{-- Ticket Summary --}}
                                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 text-[11px] text-slate-600 space-y-1">
                                                <p class="font-bold text-slate-800 text-xs">{{ $t->subject }}</p>
                                                <p class="leading-relaxed">{{ $t->message }}</p>
                                            </div>

                                            {{-- Resolution Form --}}
                                            <form method="POST" action="{{ route('admin.tickets.resolve', $t->id) }}" class="space-y-4 text-xs">
                                                @csrf
                                                <div>
                                                    <label for="status_{{ $t->id }}" class="block font-bold text-slate-700 uppercase mb-1.5">Status Tiket Baru</label>
                                                    <select id="status_{{ $t->id }}" name="status">
                                                        <option value="in_investigation" {{ $t->status === 'in_investigation' ? 'selected' : '' }}>Sedang Diinvestigasi</option>
                                                        <option value="resolved" {{ $t->status === 'resolved' ? 'selected' : '' }}>Selesai / Resolusi Diberikan</option>
                                                        <option value="closed" {{ $t->status === 'closed' ? 'selected' : '' }}>Tutup Kasus</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="resolution_{{ $t->id }}" class="block font-bold text-slate-700 uppercase mb-1.5">Catatan Resolusi Admin</label>
                                                    <textarea id="resolution_{{ $t->id }}" name="admin_resolution" rows="3" required
                                                        placeholder="Tuliskan tindakan investigasi atau solusi yang diambil...">{{ $t->admin_resolution }}</textarea>
                                                </div>
                                                <div class="flex justify-end gap-2 pt-1">
                                                    <button type="button" @click="resolveModal = false" class="btn-secondary text-xs">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="btn-primary text-xs font-bold">
                                                        Simpan Solusi
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-xs text-slate-400 font-medium">Tidak ada tiket pengaduan aktif.</p>
                                        <p class="text-[11px] text-slate-300">Semua laporan telah ditangani.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
