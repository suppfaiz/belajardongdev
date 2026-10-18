<x-layouts.dashboard title="Audit Log — BelajarYuk" headerTitle="Audit Log Aktivitas Sistem" headerSubtitle="Rekam jejak seluruh tindakan administratif, perubahan komisi, dan persetujuan verifikasi">
    <div class="space-y-6 pt-4">
        <div class="data-table-wrapper">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Admin Pelaksana</th>
                            <th>Tindakan (Action)</th>
                            <th>Objek Terkait</th>
                            <th>Nilai Perubahan</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap text-slate-400 font-mono text-[11px]">
                                    {{ $log->created_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="font-semibold text-slate-900">
                                    {{ $log->user?->name ?? 'Sistem' }}
                                </td>
                                <td class="font-mono font-bold text-blue-600 uppercase text-[11px]">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </td>
                                <td class="text-slate-600">
                                    {{ class_basename($log->auditable_type ?? '-') }} #{{ $log->auditable_id ?? '-' }}
                                </td>
                                <td class="max-w-xs truncate font-mono text-[11px] text-slate-500">
                                    {{ json_encode($log->new_values) }}
                                </td>
                                <td class="text-slate-400 font-mono text-[11px]">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-xs text-slate-400 font-medium">Belum ada log aktivitas administratif tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
