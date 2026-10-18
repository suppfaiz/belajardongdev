<x-layouts.dashboard title="Verifikasi Regulasi Guru: {{ $teacherProfile->user->name }} — BelajarYuk" headerTitle="Tinjau Kepatuhan & Legalitas Guru" headerSubtitle="Pemeriksaan berkas resmi identitas dan kualifikasi mengajar sesuai UU No. 14/2005 dan UU No. 35/2014">
    <div class="max-w-5xl space-y-6 pt-4">
        <a href="{{ route('admin.teachers') }}" class="text-xs text-blue-600 font-semibold hover:underline inline-flex items-center gap-1">
            ← Kembali ke Antrean Verifikasi Guru
        </a>

        <!-- Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 card-shadow space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 font-bold text-xl flex items-center justify-center shrink-0">
                        @if($teacherProfile->user->avatar)
                            <img src="{{ $teacherProfile->user->avatar }}" alt="{{ $teacherProfile->user->name }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                            {{ substr($teacherProfile->user->name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-extrabold text-slate-900">{{ $teacherProfile->user->name }}</h2>
                            @if($teacherProfile->verification_status === 'verified')
                                <span class="badge-verified text-xs py-0.5 px-2.5">Terverifikasi Resmi</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500">{{ $teacherProfile->user->email }} • {{ $teacherProfile->user->phone }}</p>
                        <p class="text-xs text-blue-600 font-semibold mt-0.5">{{ $teacherProfile->education ?? 'Pendidikan Guru' }} ({{ $teacherProfile->experience_years }} tahun pengalaman)</p>
                    </div>
                </div>

                <div class="sm:text-right">
                    @if($teacherProfile->verification_status === 'verified')
                        <span class="badge-verified text-xs py-1.5 px-3">Terverifikasi Sah</span>
                    @elseif($teacherProfile->verification_status === 'rejected')
                        <span class="bg-rose-50 text-rose-700 text-xs px-3 py-1.5 rounded-full font-bold">Ditolak</span>
                    @else
                        <span class="badge-pending text-xs py-1.5 px-3">Menunggu Keputusan</span>
                    @endif
                </div>
            </div>

            <!-- Statutory Verification Standards Checklist for Verifiers -->
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <x-icons.shield class="w-5 h-5 text-blue-600" />
                        <h3 class="text-sm font-bold text-slate-900">Pedoman Kepatuhan Regulasi RI untuk Verifikator</h3>
                    </div>
                    <a href="https://pddikti.kemdikbud.go.id/" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 font-bold hover:underline inline-flex items-center gap-1">
                        Cek PDDikti Kemendikbud ↗
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900">1. Validasi KTP WNI (UU No. 24/2013)</span>
                        <p class="text-slate-500 text-[11px]">Pastikan NIK 16 digit tertera jelas dan nama lengkap identik dengan profil akun.</p>
                        <span class="text-[11px] font-semibold {{ $teacherProfile->ktp_verified ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $teacherProfile->ktp_verified ? '✓ Telah Ditandai Sah' : '○ Menunggu validasi berkas' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900">2. Validasi Ijazah S1/D-IV (UU No. 14/2005)</span>
                        <p class="text-slate-500 text-[11px]">Cek keaslian ijazah S1/D-IV perguruan tinggi terakreditasi lewat Pangkalan Data Dikti.</p>
                        <span class="text-[11px] font-semibold {{ $teacherProfile->pddikti_verified ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $teacherProfile->pddikti_verified ? '✓ Telah Ditandai Sah' : '○ Menunggu validasi berkas' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900">3. Validasi SKCK Kepolisian (UU No. 35/2014)</span>
                        <p class="text-slate-500 text-[11px]">Pastikan SKCK aktif (maksimal 6 bulan) dengan catatan bebas kriminalitas.</p>
                        <span class="text-[11px] font-semibold {{ $teacherProfile->skck_verified ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $teacherProfile->skck_verified ? '✓ Telah Ditandai Sah' : '○ Menunggu validasi berkas' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900">4. Pakta Perlindungan Anak (UU No. 35/2014)</span>
                        <p class="text-slate-500 text-[11px]">Persetujuan digital terhadap kode etik mengajar di kediaman murid.</p>
                        @if($teacherProfile->child_protection_pledged_at)
                            <span class="text-[11px] font-semibold text-emerald-600">
                                ✓ Ditandatangani ({{ $teacherProfile->child_protection_pledged_at->format('d M Y') }})
                            </span>
                        @else
                            <span class="text-[11px] font-semibold text-rose-600">
                                ✕ Belum Ditandatangani oleh Guru
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Milestone Detailed Data Inspection -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Rincian Data Milestone Guru</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Milestone 1 Card -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2 shadow-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-bold text-blue-900">Milestone 1: Identitas KTP WNI</span>
                            @if($teacherProfile->isMilestone1Complete())
                                <span class="badge-verified text-[10px]">Lengkap</span>
                            @else
                                <span class="badge-pending text-[10px]">Belum Lengkap</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-slate-600">
                            <p><strong>NIK:</strong> <span class="font-mono text-slate-900 font-semibold">{{ $teacherProfile->nik ?? '-' }}</span></p>
                            <p><strong>Nama KTP:</strong> {{ $teacherProfile->full_name_ktp ?? '-' }}</p>
                            <p><strong>TTL:</strong> {{ $teacherProfile->birth_place ?? '-' }}, {{ $teacherProfile->birth_date?->format('d M Y') ?? '-' }}</p>
                            <p><strong>Jenis Kelamin:</strong> {{ $teacherProfile->gender === 'male' ? 'Laki-laki' : ($teacherProfile->gender === 'female' ? 'Perempuan' : '-') }}</p>
                            <p><strong>Alamat KTP:</strong> {{ $teacherProfile->ktp_address ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Milestone 2 Card -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2 shadow-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-bold text-blue-900">Milestone 2: Kualifikasi Akademik & SK</span>
                            @if($teacherProfile->isMilestone2Complete())
                                <span class="badge-verified text-[10px]">Lengkap</span>
                            @else
                                <span class="badge-pending text-[10px]">Belum Lengkap</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-slate-600">
                            <p><strong>Perguruan Tinggi:</strong> {{ $teacherProfile->university_name ?? '-' }} ({{ $teacherProfile->degree_level ?? 'S1' }})</p>
                            <p><strong>Program Studi:</strong> {{ $teacherProfile->major ?? '-' }}</p>
                            <p><strong>Nomor Ijazah:</strong> <span class="font-mono text-slate-900 font-semibold">{{ $teacherProfile->diploma_number ?? '-' }}</span></p>
                            <p><strong>Lulus / IPK:</strong> {{ $teacherProfile->graduation_year ?? '-' }} / {{ $teacherProfile->gpa ?? '-' }}</p>
                            <p><strong>Status Mengajar:</strong> {{ $teacherProfile->teaching_status ?? '-' }}</p>
                            <p><strong>Asal Sekolah / SK:</strong> {{ $teacherProfile->school_origin ?? '-' }} (SK: {{ $teacherProfile->sk_guru_number ?? '-' }})</p>
                        </div>
                    </div>

                    <!-- Milestone 3 Card -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2 shadow-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-bold text-blue-900">Milestone 3: SKCK Kepolisian</span>
                            @if($teacherProfile->isMilestone3Complete())
                                <span class="badge-verified text-[10px]">Lengkap</span>
                            @else
                                <span class="badge-pending text-[10px]">Belum Lengkap</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-slate-600">
                            <p><strong>Nomor SKCK:</strong> <span class="font-mono text-slate-900 font-semibold">{{ $teacherProfile->skck_number ?? '-' }}</span></p>
                            <p><strong>Penerbit:</strong> {{ $teacherProfile->skck_issuer ?? '-' }}</p>
                            <p><strong>Masa Berlaku:</strong> s.d. {{ $teacherProfile->skck_valid_until?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Milestone 4 Card -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2 shadow-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-bold text-blue-900">Milestone 4: Pakta Perlindungan Anak</span>
                            @if($teacherProfile->isMilestone4Complete())
                                <span class="badge-verified text-[10px]">Ditandatangani</span>
                            @else
                                <span class="badge-pending text-[10px]">Belum Ditandatangani</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-slate-600">
                            <p><strong>Status Komitmen:</strong> {{ $teacherProfile->child_protection_pledged_at ? 'Sah & Mengikat' : 'Menunggu tanda tangan' }}</p>
                            <p><strong>Penandatangan:</strong> {{ $teacherProfile->child_protection_signature_name ?? '-' }}</p>
                            <p><strong>Waktu Penandatanganan:</strong> {{ $teacherProfile->child_protection_pledged_at?->format('d M Y, H:i') ?? '-' }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents to Review -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Berkas Lampiran Identitas (Privat — UU PDP)</h3>
                    <span class="text-xs text-slate-400">{{ $teacherProfile->documents->count() }} Dokumen Terunggah</span>
                </div>

                <div class="space-y-3">
                    @forelse($teacherProfile->documents as $doc)
                        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-between gap-4 text-xs">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 uppercase">
                                        @if($doc->document_type === 'ktp')
                                            KTP (Kartu Tanda Penduduk)
                                        @elseif($doc->document_type === 'ijazah')
                                            Ijazah S1 / D-IV
                                        @elseif($doc->document_type === 'skck')
                                            SKCK Kepolisian
                                        @elseif($doc->document_type === 'sk_guru')
                                            SK Guru Honorer / Sekolah
                                        @elseif($doc->document_type === 'sertifikat')
                                            Sertifikat Pelatihan
                                        @elseif($doc->document_type === 'transkrip')
                                            Transkrip Nilai
                                        @else
                                            {{ strtoupper($doc->document_type) }}
                                        @endif
                                    </span>
                                    @if($doc->document_number)
                                        <span class="text-slate-600 font-mono text-[11px] bg-white px-2 py-0.5 rounded border border-slate-200">
                                            No: {{ $doc->document_number }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500">{{ $doc->original_filename }} (Diunggah: {{ $doc->created_at->format('d M Y, H:i') }} WIB)</p>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('documents.download', $doc->id) }}" class="btn-secondary py-1.5 px-3 text-xs font-semibold text-blue-600">
                                    Unduh & Periksa Berkas ⤓
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 rounded-2xl border border-dashed border-slate-200 text-center text-slate-400 text-xs">
                            Guru ini belum mengunggah dokumen apapun.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Bio & Details -->
            <div class="space-y-3 pt-4 border-t border-slate-100 text-xs">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Biodata & Area Layanan</h3>
                <p class="text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ $teacherProfile->bio }}
                </p>
                <div class="grid grid-cols-2 gap-4 text-slate-600 pt-2">
                    <p><strong>Titik Awal:</strong> {{ $teacherProfile->service_city }}, {{ $teacherProfile->service_district }}</p>
                    <p><strong>Radius Maksimal:</strong> {{ $teacherProfile->max_travel_distance_km }} km</p>
                    <p><strong>Tarif Standar:</strong> Rp {{ number_format($teacherProfile->hourly_rate, 0, ',', '.') }} / jam</p>
                    <p><strong>Rekening Bank:</strong> {{ $teacherProfile->bank_name ?? '-' }} ({{ $teacherProfile->bank_account_number ?? '-' }})</p>
                </div>
            </div>

            <!-- Approval / Rejection Actions -->
            <div class="pt-6 border-t border-slate-100" x-data="{ rejectModal: false }">
                <form method="POST" action="{{ route('admin.teachers.approve', $teacherProfile->id) }}" class="space-y-4">
                    @csrf
                    
                    <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 space-y-2 text-xs">
                        <span class="font-bold text-blue-900 block">Konfirmasi Kepatuhan Regulasi Sebelum Menyetujui:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-slate-700">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="ktp_verified" value="1" {{ $teacherProfile->ktp_verified || $teacherProfile->documents->where('document_type', 'ktp')->count() > 0 ? 'checked' : '' }} class="rounded text-blue-600">
                                <span>KTP WNI Sah</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="pddikti_verified" value="1" {{ $teacherProfile->pddikti_verified || $teacherProfile->documents->where('document_type', 'ijazah')->count() > 0 ? 'checked' : '' }} class="rounded text-blue-600">
                                <span>Ijazah S1 Valid</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="skck_verified" value="1" {{ $teacherProfile->skck_verified || $teacherProfile->documents->where('document_type', 'skck')->count() > 0 ? 'checked' : '' }} class="rounded text-blue-600">
                                <span>SKCK Kepolisian Aktif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="sk_guru_verified" value="1" {{ $teacherProfile->sk_guru_verified || $teacherProfile->documents->where('document_type', 'sk_guru')->count() > 0 ? 'checked' : '' }} class="rounded text-blue-600">
                                <span>SK Pengajar / Honorer</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                        <button type="button" @click="rejectModal = true" class="btn-secondary text-rose-600 hover:bg-rose-50 border-rose-200 text-xs py-2.5 px-5 font-bold w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <x-icons.x-mark class="w-3.5 h-3.5 text-rose-600" />
                            <span>Tolak Verifikasi</span>
                        </button>

                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700 text-xs py-2.5 px-6 font-bold shadow-sm w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <x-icons.check class="w-4 h-4 text-white" />
                            <span>Setujui & Terbitkan Profil Resmi</span>
                        </button>
                    </div>
                </form>

                <!-- Rejection Modal -->
                <div x-show="rejectModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
                    <div @click.away="rejectModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 text-left card-shadow">
                        <h4 class="text-sm font-bold text-slate-900">Alasan Penolakan Verifikasi Sesuai Regulasi</h4>
                        <form method="POST" action="{{ route('admin.teachers.reject', $teacherProfile->id) }}" class="space-y-3 text-xs">
                            @csrf
                            <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Berkas Ijazah S1 tidak terbaca nomor registrasinya, atau SKCK telah melewati masa kedaluwarsa 6 bulan..." class="w-full p-3 border rounded-xl outline-none focus:border-rose-500"></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="rejectModal = false" class="btn-secondary py-1.5 px-3">Batal</button>
                                <button type="submit" class="btn-primary bg-rose-600 hover:bg-rose-700 py-1.5 px-4 font-bold">Kirim Penolakan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
