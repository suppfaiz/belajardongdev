<x-layouts.dashboard title="Ulasan Murid — BelajarYuk" headerTitle="Ulasan & Rating Murid" headerSubtitle="Daftar umpan balik dan penilaian yang diberikan orang tua murid atas sesi les Anda">
    <div class="space-y-6 pt-4">

        {{-- Rating Overview Card --}}
        <div class="panel-window p-6 sm:p-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Performa Mengajar Anda</h3>
                    <p class="text-xs text-slate-500 mt-1">Rating dihitung rata-rata dari seluruh transaksi les selesai yang telah diulas.</p>
                </div>
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 px-5 py-3.5 rounded-xl shrink-0">
                    <x-icons.star class="w-7 h-7 fill-amber-500 text-amber-500" />
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-black text-amber-600 tabular-nums">{{ number_format($profile->rating_cache, 1) }}</span>
                        <span class="text-sm font-bold text-slate-500">/ 5.0</span>
                    </div>
                    <div class="border-l border-amber-200 pl-3 text-left">
                        <span class="text-xs font-bold text-slate-700 block">{{ $profile->reviews_count_cache }}</span>
                        <span class="text-[10px] text-slate-400">total ulasan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews List --}}
        <div class="space-y-3">
            @forelse($reviews as $rev)
                <div class="panel-window p-5 sm:p-6 space-y-3.5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center shrink-0 border border-blue-200">
                                {{ substr($rev->parent->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate">{{ $rev->parent->name }}</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $rev->booking->subject?->name ?? '—' }}
                                    <span class="mx-1">·</span>
                                    {{ $rev->created_at->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i < $rev->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    @if($rev->comment)
                        <p class="text-xs text-slate-600 leading-relaxed pl-12 border-l-2 border-slate-100 ml-1">
                            "{{ $rev->comment }}"
                        </p>
                    @endif
                </div>
            @empty
                <div class="panel-window p-12 text-center flex flex-col items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                        <x-icons.star class="w-7 h-7 text-amber-300" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700">Belum ada ulasan murid</p>
                        <p class="text-xs text-slate-400 mt-1">Selesaikan sesi les pertama Anda untuk mendapatkan rating dan ulasan dari orang tua murid.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($reviews->hasPages())
            <div>
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
