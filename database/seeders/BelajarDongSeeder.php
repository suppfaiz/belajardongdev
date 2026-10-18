<?php

namespace Database\Seeders;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\LessonSession;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BelajarDongSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Subjects Exist
        $subjectsList = [
            ['name' => 'Matematika', 'slug' => 'matematika', 'category' => 'smp', 'icon' => 'calculator'],
            ['name' => 'Bahasa Inggris', 'slug' => 'bahasa-inggris', 'category' => 'smp', 'icon' => 'globe'],
            ['name' => 'Bahasa Indonesia', 'slug' => 'bahasa-indonesia', 'category' => 'sd', 'icon' => 'book'],
            ['name' => 'Fisika', 'slug' => 'fisika', 'category' => 'sma', 'icon' => 'zap'],
            ['name' => 'Biologi', 'slug' => 'biologi', 'category' => 'sma', 'icon' => 'dna'],
            ['name' => 'Kimia', 'slug' => 'kimia', 'category' => 'sma', 'icon' => 'flask'],
            ['name' => 'IPA Terpadu', 'slug' => 'ipa-terpadu', 'category' => 'smp', 'icon' => 'atom'],
        ];

        $subjects = [];
        foreach ($subjectsList as $s) {
            $subjects[$s['slug']] = Subject::firstOrCreate(
                ['slug' => $s['slug']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $s['name'],
                    'category' => $s['category'],
                    'icon' => $s['icon'],
                    'is_active' => true,
                ]
            );
        }

        // 2. Seed Andi Pratama (Parent)
        $andi = User::where('email', 'andi@belajardong.id')->first()
            ?? User::firstOrCreate(
                ['email' => 'andi.pratama@belajardong.id'],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Andi Pratama',
                    'phone' => '081234567890',
                    'role' => 'parent',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                    'email_verified_at' => now(),
                ]
            );

        // Ensure email and avatar are up to date
        $andi->update([
            'email' => 'andi.pratama@belajardong.id',
            'name' => 'Andi Pratama',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
        ]);

        // Seed or update Admin Belajar Dong
        User::updateOrCreate(
            ['email' => 'admin@belajardong.id'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Admin BelajarYuk',
                'phone' => '081199887766',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Children for Andi Pratama
        $rian = Student::firstOrCreate(
            ['parent_id' => $andi->id, 'name' => 'Rian Pratama'],
            [
                'uuid' => (string) Str::uuid(),
                'grade_level' => 'smp',
                'class_grade' => 'Kelas 7',
                'school_name' => 'SMP Negeri 11 Jakarta',
                'notes' => 'Fokus pada aljabar dan geometri dasar, butuh bimbingan intensif persiapan ujian.',
                'address' => 'Jl. Panglima Polim No. 12, Kebayoran Baru, Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'village' => 'Melawai',
                'is_active' => true,
            ]
        );

        $nadia = Student::firstOrCreate(
            ['parent_id' => $andi->id, 'name' => 'Nadia Pratama'],
            [
                'uuid' => (string) Str::uuid(),
                'grade_level' => 'sd',
                'class_grade' => 'Kelas 5',
                'school_name' => 'SD Mentari Pagi Jakarta',
                'notes' => 'Fokus kosakata dan percakapan Bahasa Inggris, serta matematika tematik.',
                'address' => 'Jl. Panglima Polim No. 12, Kebayoran Baru, Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'village' => 'Melawai',
                'is_active' => true,
            ]
        );

        $kevin = Student::firstOrCreate(
            ['parent_id' => $andi->id, 'name' => 'Kevin Pratama'],
            [
                'uuid' => (string) Str::uuid(),
                'grade_level' => 'smp',
                'class_grade' => 'Kelas 8',
                'school_name' => 'SMP Negeri 11 Jakarta',
                'notes' => 'Fokus pada IPA Terpadu dan persiapan olimpiade sains sekolah.',
                'address' => 'Jl. Panglima Polim No. 12, Kebayoran Baru, Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'village' => 'Melawai',
                'is_active' => true,
            ]
        );

        // 3. Seed 6 Verified Tutors matching the UI Design Screens
        $tutorsData = [
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@belajardong.id',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
                'education' => 'S1 Pendidikan Matematika, Universitas Indonesia',
                'experience_years' => 5,
                'hourly_rate' => 100000,
                'rating_cache' => 4.9,
                'reviews_count_cache' => 120,
                'headline' => 'Guru Matematika & IPA SMP-SMA Berpengalaman',
                'bio' => "Halo! Saya Siti Nurhaliza, lulusan Pendidikan Matematika Universitas Indonesia. Berpengalaman lebih dari 5 tahun membimbing siswa sekolah dasar hingga menengah atas.\n\nMetode mengajar saya fokus pada pemahaman konsep yang mudah dicerna, trik pemecahan soal cepat, serta suasana belajar yang interaktif dan ramah.",
                'subject_slug' => 'matematika',
            ],
            [
                'name' => 'Ahmad Rizky',
                'email' => 'ahmad.rizky@belajardong.id',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'education' => 'S1 Pendidikan Bahasa Inggris, UNJ',
                'experience_years' => 4,
                'hourly_rate' => 120000,
                'rating_cache' => 4.9,
                'reviews_count_cache' => 95,
                'headline' => 'Spesialis Bahasa Inggris Komunikatif & Persiapan Ujian',
                'bio' => 'Guru Bahasa Inggris dengan sertifikasi TOEFL & IELTS. Mengajar dengan metode fun learning dan conversation practice agar anak percaya diri berbicara sejak pertemuan pertama.',
                'subject_slug' => 'bahasa-inggris',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@belajardong.id',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80',
                'education' => 'S1 Sastra Indonesia, Universitas Gadjah Mada',
                'experience_years' => 3,
                'hourly_rate' => 90000,
                'rating_cache' => 4.7,
                'reviews_count_cache' => 76,
                'headline' => 'Guru Bahasa Indonesia & Literasi Membaca',
                'bio' => 'Membantu siswa menguasai tata bahasa, pemahaman teks naratif & analitis, serta teknik menulis esai berkualitas tinggi untuk persiapan asesmen nasional.',
                'subject_slug' => 'bahasa-indonesia',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@belajardong.id',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                'education' => 'S1 Fisika, Institut Teknologi Bandung',
                'experience_years' => 5,
                'hourly_rate' => 110000,
                'rating_cache' => 4.8,
                'reviews_count_cache' => 88,
                'headline' => 'Spesialis Fisika & Matematika SMA',
                'bio' => 'Pendekatan visual konsep mekanika, termodinamika, dan elektromagnetik. Terbukti membantu puluhan murid lolos seleksi masuk perguruan tinggi negeri impian.',
                'subject_slug' => 'fisika',
            ],
            [
                'name' => 'Rina Amelia',
                'email' => 'rina.amelia@belajardong.id',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80',
                'education' => 'S1 Pendidikan Biologi, UPI',
                'experience_years' => 4,
                'hourly_rate' => 100000,
                'rating_cache' => 4.9,
                'reviews_count_cache' => 100,
                'headline' => 'Guru IPA Terpadu & Biologi SMP-SMA',
                'bio' => 'Belajar IPA jadi seru dengan analogi kehidupan sehari-hari dan mind mapping materi biologi & fisika dasar.',
                'subject_slug' => 'ipa-terpadu',
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@belajardong.id',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                'education' => 'S1 Kimia, Universitas Indonesia',
                'experience_years' => 3,
                'hourly_rate' => 120000,
                'rating_cache' => 4.8,
                'reviews_count_cache' => 62,
                'headline' => 'Spesialis Kimia & IPA Terpadu',
                'bio' => 'Membuat stoikiometri, reaksi redoks, dan ikatan kimia menjadi sangat mudah dipahami tanpa perlu menghafal rumus secara buta.',
                'subject_slug' => 'kimia',
            ],
        ];

        $createdTeacherProfiles = [];

        foreach ($tutorsData as $td) {
            $user = User::firstOrCreate(
                ['email' => $td['email']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $td['name'],
                    'phone' => '0812'.rand(10000000, 99999999),
                    'role' => 'teacher',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'avatar' => $td['avatar'],
                    'email_verified_at' => now(),
                ]
            );

            // Update avatar & name
            $user->update([
                'name' => $td['name'],
                'avatar' => $td['avatar'],
            ]);

            $profile = TeacherProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'uuid' => (string) Str::uuid(),
                    'headline' => $td['headline'],
                    'bio' => $td['bio'],
                    'education' => $td['education'],
                    'experience_years' => $td['experience_years'],
                    'hourly_rate' => $td['hourly_rate'],
                    'session_duration_minutes' => 90,
                    'service_city' => 'Jakarta Selatan',
                    'service_district' => 'Kebayoran Baru',
                    'service_village' => 'Melawai',
                    'service_address' => 'Kebayoran Baru, Jakarta Selatan',
                    'latitude' => -6.2442,
                    'longitude' => 106.8006,
                    'max_travel_distance_km' => 10,
                    'is_verified' => true,
                    'verification_status' => 'verified',
                    'rating_cache' => $td['rating_cache'],
                    'reviews_count_cache' => $td['reviews_count_cache'],
                    'bank_name' => 'BCA',
                    'bank_account_number' => '800'.rand(1000000, 9999999),
                    'bank_account_name' => $td['name'],
                ]
            );

            // Sync subjects
            if (isset($subjects[$td['subject_slug']])) {
                $profile->subjects()->syncWithoutDetaching([
                    $subjects[$td['subject_slug']]->id => ['grade_levels' => json_encode(['sd', 'smp', 'sma']), 'custom_hourly_rate' => $td['hourly_rate']],
                ]);
            }

            // Sync availability
            foreach ([1, 2, 3, 4, 5, 6] as $day) {
                AvailabilitySlot::firstOrCreate(
                    ['teacher_profile_id' => $profile->id, 'day_of_week' => $day],
                    [
                        'start_time' => '08:00:00',
                        'end_time' => '18:00:00',
                        'is_active' => true,
                    ]
                );
            }

            $createdTeacherProfiles[$td['name']] = $profile;
        }

        // 4. Seed Upcoming Bookings for Andi Pratama
        $sitiTeacher = User::where('name', 'Siti Nurhaliza')->first();
        $ahmadTeacher = User::where('name', 'Ahmad Rizky')->first();
        $rinaTeacher = User::where('name', 'Rina Amelia')->first();

        if ($sitiTeacher) {
            $b1 = Booking::firstOrCreate(
                ['booking_code' => 'BD-2610-101'],
                [
                    'uuid' => (string) Str::uuid(),
                    'parent_id' => $andi->id,
                    'teacher_id' => $sitiTeacher->id,
                    'student_id' => $rian->id,
                    'subject_id' => $subjects['matematika']->id,
                    'booking_date' => Carbon::now()->addDays(1)->toDateString(),
                    'start_time' => '15:00:00',
                    'end_time' => '16:30:00',
                    'session_duration_minutes' => 90,
                    'lesson_address' => $rian->address,
                    'status' => 'confirmed',
                    'hourly_rate_snapshot' => 100000,
                    'session_fee' => 150000,
                    'platform_commission_rate' => 10.00,
                    'platform_commission_amount' => 15000,
                    'teacher_net_amount' => 135000,
                    'total_amount' => 150000,
                    'payment_deadline_at' => Carbon::now()->addDays(1),
                ]
            );

            LessonSession::firstOrCreate(
                ['booking_id' => $b1->id],
                [
                    'uuid' => (string) Str::uuid(),
                    'scheduled_start' => Carbon::now()->addDays(1)->setTime(15, 0),
                    'scheduled_end' => Carbon::now()->addDays(1)->setTime(16, 30),
                    'status' => 'scheduled',
                ]
            );
        }

        if ($ahmadTeacher) {
            $b2 = Booking::firstOrCreate(
                ['booking_code' => 'BD-2610-102'],
                [
                    'uuid' => (string) Str::uuid(),
                    'parent_id' => $andi->id,
                    'teacher_id' => $ahmadTeacher->id,
                    'student_id' => $nadia->id,
                    'subject_id' => $subjects['bahasa-inggris']->id,
                    'booking_date' => Carbon::now()->addDays(2)->toDateString(),
                    'start_time' => '10:00:00',
                    'end_time' => '11:30:00',
                    'session_duration_minutes' => 90,
                    'lesson_address' => $nadia->address,
                    'status' => 'pending',
                    'hourly_rate_snapshot' => 120000,
                    'session_fee' => 180000,
                    'platform_commission_rate' => 10.00,
                    'platform_commission_amount' => 18000,
                    'teacher_net_amount' => 162000,
                    'total_amount' => 180000,
                    'payment_deadline_at' => Carbon::now()->addDays(2),
                ]
            );
        }

        if ($rinaTeacher) {
            $b3 = Booking::firstOrCreate(
                ['booking_code' => 'BD-2610-103'],
                [
                    'uuid' => (string) Str::uuid(),
                    'parent_id' => $andi->id,
                    'teacher_id' => $rinaTeacher->id,
                    'student_id' => $kevin->id,
                    'subject_id' => $subjects['ipa-terpadu']->id,
                    'booking_date' => Carbon::now()->addDays(4)->toDateString(),
                    'start_time' => '14:00:00',
                    'end_time' => '15:30:00',
                    'session_duration_minutes' => 90,
                    'lesson_address' => $kevin->address,
                    'status' => 'confirmed',
                    'hourly_rate_snapshot' => 100000,
                    'session_fee' => 150000,
                    'platform_commission_rate' => 10.00,
                    'platform_commission_amount' => 15000,
                    'teacher_net_amount' => 135000,
                    'total_amount' => 150000,
                    'payment_deadline_at' => Carbon::now()->addDays(4),
                ]
            );

            // Also create a past payment for Andi
            Payment::firstOrCreate(
                ['payment_code' => 'PAY-BD-9001'],
                [
                    'uuid' => (string) Str::uuid(),
                    'booking_id' => $b3->id,
                    'payment_gateway' => 'mock_pg',
                    'payment_method' => 'virtual_account',
                    'amount' => 150000,
                    'status' => 'paid',
                    'transaction_reference' => 'TXN-BCA-98172',
                    'va_number' => '8077700001234',
                    'paid_at' => Carbon::now()->subDays(1),
                ]
            );
        }
    }
}
