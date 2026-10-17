<?php

namespace Database\Seeders;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\City;
use App\Models\District;
use App\Models\LessonSession;
use App\Models\Payment;
use App\Models\PlatformSetting;
use App\Models\Review;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SupportTicket;
use App\Models\TeacherDocument;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Ledger\LedgerService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $ledgerService = app(LedgerService::class);

        // 1. Regional Hierarchy Seeding (Provinces, Cities, Districts, Villages)
        $this->call(IndonesiaRegionsSeeder::class);

        // Lookup common regions
        $jakselCity = City::where('name', 'Jakarta Selatan')->first();
        $kebayoranDistrict = District::where('name', 'Kebayoran Baru')->first();
        $tebetDistrict = District::where('name', 'Tebet')->first();
        $cilandakDistrict = District::where('name', 'Cilandak')->first();
        $pancoranDistrict = District::where('name', 'Pancoran')->first();
        $pasarMingguDistrict = District::where('name', 'Pasar Minggu')->first();

        // 2. Platform Settings
        PlatformSetting::set('commission_rate_percent', '10.00', 'Persentase komisi platform');
        PlatformSetting::set('min_payout_amount', '50000', 'Batas minimal pencairan dana');

        // 3. Core Subjects
        $subjectsData = [
            ['name' => 'Matematika', 'slug' => 'matematika', 'category' => 'smp', 'icon' => 'calculator'],
            ['name' => 'IPA Terpadu', 'slug' => 'ipa-terpadu', 'category' => 'smp', 'icon' => 'atom'],
            ['name' => 'Fisika', 'slug' => 'fisika', 'category' => 'sma', 'icon' => 'zap'],
            ['name' => 'Kimia', 'slug' => 'kimia', 'category' => 'sma', 'icon' => 'flask'],
            ['name' => 'Biologi', 'slug' => 'biologi', 'category' => 'sma', 'icon' => 'dna'],
            ['name' => 'Bahasa Inggris', 'slug' => 'bahasa-inggris', 'category' => 'smp', 'icon' => 'globe'],
            ['name' => 'Bahasa Indonesia', 'slug' => 'bahasa-indonesia', 'category' => 'sd', 'icon' => 'book'],
            ['name' => 'Calistung & Tematik SD', 'slug' => 'calistung-sd', 'category' => 'sd', 'icon' => 'pencil'],
            ['name' => 'Persiapan UTBK / SNBT', 'slug' => 'utbk-snbt', 'category' => 'ujian', 'icon' => 'award'],
            ['name' => 'IPS & Ekonomi', 'slug' => 'ips-ekonomi', 'category' => 'smp', 'icon' => 'trending-up'],
        ];

        $createdSubjects = [];
        foreach ($subjectsData as $s) {
            $createdSubjects[$s['slug']] = Subject::create([
                'uuid' => (string) Str::uuid(),
                'name' => $s['name'],
                'slug' => $s['slug'],
                'category' => $s['category'],
                'icon' => $s['icon'],
                'is_active' => true,
            ]);
        }

        // 4. Admin Account
        $admin = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Admin Belajar Yuk',
            'email' => 'admin@belajaryuk.id',
            'phone' => '081199887766',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 5. Verified Teacher 1: Siti Rahmawati, S.Pd (Matematika & IPA)
        $teacher1User = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Siti Rahmawati, S.Pd',
            'email' => 'siti.rahmawati@belajaryuk.id',
            'phone' => '081298765432',
            'role' => 'teacher',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $teacher1Profile = TeacherProfile::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $teacher1User->id,
            'headline' => 'Guru Matematika SMP & SMA Berpengalaman',
            'bio' => "Saya adalah guru matematika berpengalaman lebih dari 3 tahun mengajar siswa SMP dan SMA. Metode pembelajaran saya menyesuaikan dengan kebutuhan dan gaya belajar setiap anak, agar lebih mudah memahami materi dan meningkatkan kepercayaan diri.\n\nFokus bimbingan meliputi pemahaman konsep dasar, trik cepat pengerjaan soal aljabar dan geometri, serta persiapan menghadapi ujian sekolah dan asesmen nasional.",
            'education' => 'S1 Pendidikan Matematika, Universitas Negeri Jakarta (UNJ)',
            'experience_years' => 3,
            'hourly_rate' => 100000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Kebayoran Baru',
            'service_village' => 'Melawai',
            'city_id' => $jakselCity?->id,
            'district_id' => $kebayoranDistrict?->id,
            'village_id' => $kebayoranDistrict?->villages()->first()?->id,
            'service_address' => 'Jl. Panglima Polim No. 45, Kebayoran Baru',
            'latitude' => -6.2442,
            'longitude' => 106.8006,
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
            'bank_name' => 'BCA',
            'bank_account_number' => '8001234567',
            'bank_account_name' => 'Siti Rahmawati',
            'rating_cache' => 4.90,
            'reviews_count_cache' => 124,
        ]);

        // Attach Subjects to Teacher 1
        $teacher1Profile->subjects()->attach([
            $createdSubjects['matematika']->id => ['grade_levels' => json_encode(['smp', 'sma']), 'custom_hourly_rate' => 100000],
            $createdSubjects['ipa-terpadu']->id => ['grade_levels' => json_encode(['smp']), 'custom_hourly_rate' => 95000],
        ]);

        // Weekly availability slots for Teacher 1
        foreach ([1, 2, 3, 4, 5] as $day) {
            AvailabilitySlot::create([
                'teacher_profile_id' => $teacher1Profile->id,
                'day_of_week' => $day,
                'start_time' => '15:00:00',
                'end_time' => '18:00:00',
                'is_active' => true,
            ]);
        }
        AvailabilitySlot::create([
            'teacher_profile_id' => $teacher1Profile->id,
            'day_of_week' => 6, // Sabtu
            'start_time' => '09:00:00',
            'end_time' => '16:00:00',
            'is_active' => true,
        ]);

        // Documents for Teacher 1
        TeacherDocument::create([
            'uuid' => (string) Str::uuid(),
            'teacher_profile_id' => $teacher1Profile->id,
            'document_type' => 'ktp',
            'document_number' => '3171012345670001',
            'file_path' => 'teacher_documents/ktp_siti.pdf',
            'original_filename' => 'KTP_Siti_Rahmawati.pdf',
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
        TeacherDocument::create([
            'uuid' => (string) Str::uuid(),
            'teacher_profile_id' => $teacher1Profile->id,
            'document_type' => 'ijazah',
            'document_number' => 'UNJ-2022-MAT-0491',
            'file_path' => 'teacher_documents/ijazah_siti.pdf',
            'original_filename' => 'Ijazah_S1_Pendidikan_Matematika_UNJ.pdf',
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        // 5. Verified Teacher 2: Ahmad Fauzi, S.Pd (Fisika & Matematika SMA)
        $teacher2User = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Ahmad Fauzi, S.Pd',
            'email' => 'ahmad.fauzi@belajaryuk.id',
            'phone' => '081387654321',
            'role' => 'teacher',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $teacher2Profile = TeacherProfile::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $teacher2User->id,
            'headline' => 'Spesialis Fisika SMA & Persiapan UTBK-SNBT',
            'bio' => 'Lulusan Fisika Universitas Indonesia dengan pengalaman 4 tahun mendampingi siswa tembus PTN favorit (ITB, UI, UGM). Pendekatan visual dan pemecahan rumus praktis.',
            'education' => 'S1 Fisika, Universitas Indonesia (UI)',
            'experience_years' => 4,
            'hourly_rate' => 120000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Tebet',
            'service_village' => 'Tebet Barat',
            'city_id' => $jakselCity?->id,
            'district_id' => $tebetDistrict?->id,
            'village_id' => $tebetDistrict?->villages()->first()?->id,
            'service_address' => 'Jl. Tebet Barat Raya No. 12',
            'latitude' => -6.2372,
            'longitude' => 106.8524,
            'max_travel_distance_km' => 12,
            'is_verified' => true,
            'verification_status' => 'verified',
            'bank_name' => 'Mandiri',
            'bank_account_number' => '1370019283741',
            'bank_account_name' => 'Ahmad Fauzi',
            'rating_cache' => 4.95,
            'reviews_count_cache' => 98,
        ]);

        $teacher2Profile->subjects()->attach([
            $createdSubjects['fisika']->id => ['grade_levels' => json_encode(['sma']), 'custom_hourly_rate' => 120000],
            $createdSubjects['matematika']->id => ['grade_levels' => json_encode(['sma']), 'custom_hourly_rate' => 110000],
            $createdSubjects['utbk-snbt']->id => ['grade_levels' => json_encode(['ujian']), 'custom_hourly_rate' => 135000],
        ]);

        foreach ([1, 3, 5, 6] as $day) {
            AvailabilitySlot::create([
                'teacher_profile_id' => $teacher2Profile->id,
                'day_of_week' => $day,
                'start_time' => '16:00:00',
                'end_time' => '19:30:00',
                'is_active' => true,
            ]);
        }

        // 6. Verified Teacher 3: Dewi Lestari, S.Pd (Bahasa Inggris)
        $teacher3User = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Dewi Lestari, S.Pd',
            'email' => 'dewi.lestari@belajaryuk.id',
            'phone' => '081578901234',
            'role' => 'teacher',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $teacher3Profile = TeacherProfile::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $teacher3User->id,
            'headline' => 'Guru Bahasa Inggris Komunikatif SD - SMA',
            'bio' => 'Berpengalaman mengajar TOEFL preparation dan percakapan interaktif untuk anak. Menggunakan metode fun learning agar anak percaya diri berbicara bahasa Inggris.',
            'education' => 'S1 Pendidikan Bahasa Inggris, UNJ',
            'experience_years' => 3,
            'hourly_rate' => 95000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Cilandak',
            'service_village' => 'Cilandak Barat',
            'city_id' => $jakselCity?->id,
            'district_id' => $cilandakDistrict?->id,
            'village_id' => $cilandakDistrict?->villages()->first()?->id,
            'service_address' => 'Jl. Fatmawati No. 8',
            'latitude' => -6.2911,
            'longitude' => 106.7992,
            'max_travel_distance_km' => 10,
            'is_verified' => true,
            'verification_status' => 'verified',
            'bank_name' => 'BCA',
            'bank_account_number' => '5432109876',
            'bank_account_name' => 'Dewi Lestari',
            'rating_cache' => 4.85,
            'reviews_count_cache' => 87,
        ]);

        $teacher3Profile->subjects()->attach([
            $createdSubjects['bahasa-inggris']->id => ['grade_levels' => json_encode(['sd', 'smp', 'sma']), 'custom_hourly_rate' => 95000],
        ]);

        // 7. Verified Teacher 4: Rizky Pratama, S.Pd (SD & Calistung)
        $teacher4User = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Rizky Pratama, S.Pd',
            'email' => 'rizky.pratama@belajaryuk.id',
            'phone' => '081689012345',
            'role' => 'teacher',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $teacher4Profile = TeacherProfile::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $teacher4User->id,
            'headline' => 'Spesialis Guru Les Privat SD & Calistung',
            'bio' => 'Sabar, teliti, dan komunikatif dalam membimbing siswa SD kelas 1 sampai 6 dalam membaca, menulis, berhitung dan penguasaan materi tematik sekolah.',
            'education' => 'S1 PGSD, Universitas Negeri Jakarta',
            'experience_years' => 4,
            'hourly_rate' => 80000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Pancoran',
            'service_village' => 'Kalibata',
            'city_id' => $jakselCity?->id,
            'district_id' => $pancoranDistrict?->id,
            'village_id' => $pancoranDistrict?->villages()->first()?->id,
            'service_address' => 'Jl. Duren Tiga No. 20',
            'latitude' => -6.2520,
            'longitude' => 106.8437,
            'max_travel_distance_km' => 8,
            'is_verified' => true,
            'verification_status' => 'verified',
            'bank_name' => 'BRI',
            'bank_account_number' => '020601001234567',
            'bank_account_name' => 'Rizky Pratama',
            'rating_cache' => 4.80,
            'reviews_count_cache' => 110,
        ]);

        $teacher4Profile->subjects()->attach([
            $createdSubjects['calistung-sd']->id => ['grade_levels' => json_encode(['sd']), 'custom_hourly_rate' => 80000],
            $createdSubjects['bahasa-indonesia']->id => ['grade_levels' => json_encode(['sd']), 'custom_hourly_rate' => 80000],
        ]);

        // 8. Pending Teacher (For Admin verification queue testing)
        $teacherPendingUser = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Budi Gunawan, S.Pd',
            'email' => 'budi.guru@belajaryuk.id',
            'phone' => '081789012345',
            'role' => 'teacher',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $teacherPendingProfile = TeacherProfile::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $teacherPendingUser->id,
            'headline' => 'Pengajar Matematika & IPA Baru',
            'bio' => 'Lulusan baru dengan dedikasi tinggi mengajar anak-anak.',
            'education' => 'S1 Pendidikan IPA, Universitas Negeri Malang',
            'experience_years' => 1,
            'hourly_rate' => 75000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Pasar Minggu',
            'service_village' => 'Pejaten Barat',
            'city_id' => $jakselCity?->id,
            'district_id' => $pasarMingguDistrict?->id,
            'village_id' => $pasarMingguDistrict?->villages()->first()?->id,
            'service_address' => 'Jl. Ragunan No. 5',
            'latitude' => -6.2843,
            'longitude' => 106.8398,
            'max_travel_distance_km' => 5,
            'is_verified' => false,
            'verification_status' => 'pending',
            'bank_name' => 'BCA',
            'bank_account_number' => '1239874560',
            'bank_account_name' => 'Budi Gunawan',
        ]);

        TeacherDocument::create([
            'uuid' => (string) Str::uuid(),
            'teacher_profile_id' => $teacherPendingProfile->id,
            'document_type' => 'ktp',
            'document_number' => '3579012345670002',
            'file_path' => 'teacher_documents/ktp_budi.pdf',
            'original_filename' => 'KTP_Budi_Gunawan.pdf',
            'verification_status' => 'pending',
        ]);

        // 9. Parent Account: Budi Santoso
        $parentUser = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@belajaryuk.id',
            'phone' => '081311223344',
            'role' => 'parent',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Students for Budi Santoso
        $student1 = Student::create([
            'uuid' => (string) Str::uuid(),
            'parent_id' => $parentUser->id,
            'name' => 'Ananda Putri',
            'grade_level' => 'smp',
            'class_grade' => 'Kelas 9',
            'school_name' => 'SMP Negeri 11 Jakarta Selatan',
            'notes' => 'Perlu bimbingan intensif materi aljabar dan persiapan asesmen akhir sekolah.',
            'address' => 'Jl. Melati No. 12, Kebayoran Baru, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'village' => 'Melawai',
            'city_id' => $jakselCity?->id,
            'district_id' => $kebayoranDistrict?->id,
            'village_id' => $kebayoranDistrict?->villages()->first()?->id,
            'latitude' => -6.2480,
            'longitude' => 106.8040,
            'is_active' => true,
        ]);

        $student2 = Student::create([
            'uuid' => (string) Str::uuid(),
            'parent_id' => $parentUser->id,
            'name' => 'Dimas Raditya',
            'grade_level' => 'sd',
            'class_grade' => 'Kelas 5',
            'school_name' => 'SD Mentari Pagi Jakarta',
            'notes' => 'Fokus pada latihan perkalian dan soal cerita IPA tematik.',
            'address' => 'Jl. Melati No. 12, Kebayoran Baru, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'village' => 'Melawai',
            'city_id' => $jakselCity?->id,
            'district_id' => $kebayoranDistrict?->id,
            'village_id' => $kebayoranDistrict?->villages()->first()?->id,
            'latitude' => -6.2480,
            'longitude' => 106.8040,
            'is_active' => true,
        ]);

        // 10. Completed Historical Booking (With Payment, Session, Ledger & Review)
        $pastBooking = Booking::create([
            'uuid' => (string) Str::uuid(),
            'booking_code' => 'BY-2610-1001',
            'parent_id' => $parentUser->id,
            'teacher_id' => $teacher1User->id,
            'student_id' => $student1->id,
            'subject_id' => $createdSubjects['matematika']->id,
            'booking_date' => Carbon::now()->subDays(3)->toDateString(),
            'start_time' => '15:00:00',
            'end_time' => '16:30:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Melati No. 12, Kebayoran Baru, Jakarta Selatan',
            'city_id' => $jakselCity?->id,
            'district_id' => $kebayoranDistrict?->id,
            'village_id' => $kebayoranDistrict?->villages()->first()?->id,
            'lesson_latitude' => -6.2480,
            'lesson_longitude' => 106.8040,
            'notes' => 'Materi persiapan ujian akhir semester aljabar',
            'status' => 'completed',
            'hourly_rate_snapshot' => 100000,
            'session_fee' => 150000, // 90 mins = 1.5 * 100k
            'platform_commission_rate' => 10.00,
            'platform_commission_amount' => 15000,
            'teacher_net_amount' => 135000,
            'total_amount' => 150000,
            'payment_deadline_at' => Carbon::now()->subDays(4),
            'created_at' => Carbon::now()->subDays(4),
        ]);

        $pastPayment = Payment::create([
            'uuid' => (string) Str::uuid(),
            'booking_id' => $pastBooking->id,
            'payment_code' => 'PAY-2610-8801',
            'payment_gateway' => 'mock_pg',
            'payment_method' => 'virtual_account',
            'amount' => 150000,
            'status' => 'paid',
            'transaction_reference' => 'TXN-BCA-99281',
            'va_number' => '8077700001234',
            'paid_at' => Carbon::now()->subDays(4)->addMinutes(15),
            'created_at' => Carbon::now()->subDays(4),
        ]);

        LessonSession::create([
            'uuid' => (string) Str::uuid(),
            'booking_id' => $pastBooking->id,
            'scheduled_start' => Carbon::now()->subDays(3)->setTime(15, 0),
            'scheduled_end' => Carbon::now()->subDays(3)->setTime(16, 30),
            'actual_start' => Carbon::now()->subDays(3)->setTime(15, 0),
            'actual_end' => Carbon::now()->subDays(3)->setTime(16, 30),
            'status' => 'completed',
            'teacher_confirmed_at' => Carbon::now()->subDays(3)->setTime(16, 35),
            'parent_confirmed_at' => Carbon::now()->subDays(3)->setTime(16, 40),
            'lesson_notes' => 'Sesi berjalan sangat lancar, murid menguasai faktorisasi persamaan kuadrat dengan sangat baik.',
        ]);

        // Record to Ledger
        $ledgerService->recordPaymentReceived($pastPayment);
        $ledgerService->recordSessionCompleted($pastBooking);

        // Review for Teacher 1
        Review::create([
            'uuid' => (string) Str::uuid(),
            'booking_id' => $pastBooking->id,
            'parent_id' => $parentUser->id,
            'teacher_profile_id' => $teacher1Profile->id,
            'rating' => 5,
            'comment' => 'Ibu Siti sangat sabar dan runtut dalam menjelaskan aljabar. Anak saya yang tadinya takut matematika sekarang jadi percaya diri dan nilainya meningkat pesat. Sangat direkomendasikan!',
            'is_published' => true,
        ]);

        // 11. Upcoming Confirmed Booking
        $upcomingBooking = Booking::create([
            'uuid' => (string) Str::uuid(),
            'booking_code' => 'BY-2610-2002',
            'parent_id' => $parentUser->id,
            'teacher_id' => $teacher1User->id,
            'student_id' => $student1->id,
            'subject_id' => $createdSubjects['matematika']->id,
            'booking_date' => Carbon::now()->addDays(2)->toDateString(),
            'start_time' => '15:30:00',
            'end_time' => '17:00:00',
            'session_duration_minutes' => 90,
            'lesson_address' => 'Jl. Melati No. 12, Kebayoran Baru, Jakarta Selatan',
            'city_id' => $jakselCity?->id,
            'district_id' => $kebayoranDistrict?->id,
            'village_id' => $kebayoranDistrict?->villages()->first()?->id,
            'lesson_latitude' => -6.2480,
            'lesson_longitude' => 106.8040,
            'notes' => 'Lanjutan materi geometri analitik',
            'status' => 'confirmed',
            'hourly_rate_snapshot' => 100000,
            'session_fee' => 150000,
            'platform_commission_rate' => 10.00,
            'platform_commission_amount' => 15000,
            'teacher_net_amount' => 135000,
            'total_amount' => 150000,
            'payment_deadline_at' => Carbon::now()->addDay(),
        ]);

        $upcomingPayment = Payment::create([
            'uuid' => (string) Str::uuid(),
            'booking_id' => $upcomingBooking->id,
            'payment_code' => 'PAY-2610-8802',
            'payment_gateway' => 'mock_pg',
            'payment_method' => 'gopay',
            'amount' => 150000,
            'status' => 'paid',
            'transaction_reference' => 'TXN-QRIS-77182',
            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=BELAJARYUK_QRIS_8802',
            'paid_at' => Carbon::now()->subHours(2),
        ]);

        LessonSession::create([
            'uuid' => (string) Str::uuid(),
            'booking_id' => $upcomingBooking->id,
            'scheduled_start' => Carbon::now()->addDays(2)->setTime(15, 30),
            'scheduled_end' => Carbon::now()->addDays(2)->setTime(17, 0),
            'status' => 'scheduled',
        ]);

        $ledgerService->recordPaymentReceived($upcomingPayment);

        // 12. Support Ticket
        SupportTicket::create([
            'uuid' => (string) Str::uuid(),
            'ticket_code' => 'TCK-2610-001',
            'user_id' => $parentUser->id,
            'booking_id' => $upcomingBooking->id,
            'subject' => 'Pertanyaan Terkait Reschedule Jadwal Les',
            'category' => 'scheduling',
            'priority' => 'low',
            'status' => 'open',
            'message' => 'Halo tim Belajar Yuk, jika di hari H anak saya ada ekstrakurikuler sekolah, apakah jam les bisa digeser 30 menit melalui kesepakatan dengan guru?',
        ]);
    }
}
