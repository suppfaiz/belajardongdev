<?php

namespace Tests\Feature;

use App\Models\TeacherDocument;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StatutoryTeacherVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacherUser;

    protected TeacherProfile $teacherProfile;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->teacherUser = User::create([
            'name' => 'Budi Santoso, S.Pd.',
            'email' => 'budi.santoso@guru.id',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'role' => 'teacher',
            'is_active' => true,
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacherUser->id,
            'education' => 'S1 Pendidikan Matematika UNJ',
            'experience_years' => 5,
            'hourly_rate' => 120000,
            'session_duration_minutes' => 90,
            'service_city' => 'Jakarta Selatan',
            'service_district' => 'Kebayoran Baru',
            'latitude' => -6.2442,
            'longitude' => 106.8006,
            'max_travel_distance_km' => 15,
            'is_verified' => false,
            'verification_status' => 'pending',
            'bio' => 'Guru matematika honorer berpengalaman membimbing siswa SD dan SMP.',
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin Verifikator Belajar Yuk',
            'email' => 'verifikator@belajaryuk.id',
            'phone' => '081299999999',
            'password' => bcrypt('adminsecret'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_teacher_can_upload_statutory_documents_including_sk_guru_and_skck(): void
    {
        $this->actingAs($this->teacherUser);

        // 1. Upload SK Guru Honorer (UU No. 14/2005)
        $skFile = UploadedFile::fake()->create('sk_penugasan_guru.pdf', 1024, 'application/pdf');
        $response = $this->post(route('teacher.documents.upload'), [
            'document_type' => 'sk_guru',
            'document_number' => '421.2/089/SMPN-01/2024',
            'document_file' => $skFile,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('teacher_documents', [
            'teacher_profile_id' => $this->teacherProfile->id,
            'document_type' => 'sk_guru',
            'document_number' => '421.2/089/SMPN-01/2024',
            'verification_status' => 'pending',
        ]);

        // 2. Upload SKCK Kepolisian (UU No. 35/2014 & Perkapolri 18/2014)
        $skckFile = UploadedFile::fake()->create('skck_polres_jaksel.pdf', 1024, 'application/pdf');
        $responseSkck = $this->post(route('teacher.documents.upload'), [
            'document_type' => 'skck',
            'document_number' => 'SKCK/YANMIN/VI/2024/POLRES-JAKSEL',
            'document_file' => $skckFile,
        ]);

        $responseSkck->assertRedirect();
        $this->assertDatabaseHas('teacher_documents', [
            'teacher_profile_id' => $this->teacherProfile->id,
            'document_type' => 'skck',
            'document_number' => 'SKCK/YANMIN/VI/2024/POLRES-JAKSEL',
            'verification_status' => 'pending',
        ]);
    }

    public function test_teacher_can_digitally_sign_child_protection_pact(): void
    {
        $this->actingAs($this->teacherUser);

        $this->assertNull($this->teacherProfile->child_protection_pledged_at);

        $response = $this->post(route('teacher.documents.pledge'), [
            'agree_child_protection' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->teacherProfile->refresh();
        $this->assertNotNull($this->teacherProfile->child_protection_pledged_at);
    }

    public function test_admin_can_review_and_approve_teacher_with_statutory_flags(): void
    {
        // Setup uploaded documents
        TeacherDocument::create([
            'teacher_profile_id' => $this->teacherProfile->id,
            'document_type' => 'ktp',
            'document_number' => '3171012345670001',
            'file_path' => 'teacher_documents/ktp.pdf',
            'original_filename' => 'ktp.pdf',
            'verification_status' => 'pending',
        ]);

        TeacherDocument::create([
            'teacher_profile_id' => $this->teacherProfile->id,
            'document_type' => 'ijazah',
            'document_number' => 'UNJ-2021-S1-MAT-049',
            'file_path' => 'teacher_documents/ijazah.pdf',
            'original_filename' => 'ijazah.pdf',
            'verification_status' => 'pending',
        ]);

        $this->actingAs($this->adminUser);

        $response = $this->post(route('admin.teachers.approve', $this->teacherProfile->id), [
            'ktp_verified' => '1',
            'pddikti_verified' => '1',
            'skck_verified' => '1',
            'sk_guru_verified' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->teacherProfile->refresh();
        $this->assertTrue($this->teacherProfile->is_verified);
        $this->assertEquals('verified', $this->teacherProfile->verification_status);
        $this->assertTrue($this->teacherProfile->ktp_verified);
        $this->assertTrue($this->teacherProfile->pddikti_verified);
        $this->assertTrue($this->teacherProfile->skck_verified);
        $this->assertTrue($this->teacherProfile->sk_guru_verified);

        // Verify audit log exists
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'verify_teacher',
            'auditable_id' => $this->teacherProfile->id,
        ]);
    }

    public function test_public_profile_renders_statutory_compliance_badges(): void
    {
        $this->teacherProfile->update([
            'is_verified' => true,
            'verification_status' => 'verified',
            'ktp_verified' => true,
            'pddikti_verified' => true,
            'skck_verified' => true,
            'child_protection_pledged_at' => now(),
        ]);

        $response = $this->get(route('teachers.show', $this->teacherProfile->id));

        $response->assertStatus(200);
        $response->assertSee('Kredensial & Kepatuhan Regulasi Resmi', false);
        $response->assertSee('KTP WNI Sah');
        $response->assertSee('Ijazah S1/D-IV Terverifikasi');
        $response->assertSee('Bebas Catatan Pidana (SKCK)');
        $response->assertSee('Pakta Integritas Ramah Anak');
        $response->assertSee('UU No. 27/2022 tentang Pelindungan Data Pribadi');
    }

    public function test_full_milestone_verification_workflow(): void
    {
        $this->actingAs($this->teacherUser);

        // 1. Milestone 1: KTP & Identitas
        $ktpFile = UploadedFile::fake()->create('ktp_budi.pdf', 1024, 'application/pdf');
        $resM1 = $this->post(route('teacher.documents.milestone1'), [
            'nik' => '3171012345670001',
            'full_name_ktp' => 'Budi Santoso, S.Pd.',
            'birth_place' => 'Jakarta',
            'birth_date' => '1995-05-15',
            'gender' => 'male',
            'ktp_address' => 'Jl. RS Fatmawati No. 10, Cilandak, Jakarta Selatan',
            'ktp_file' => $ktpFile,
        ]);
        $resM1->assertRedirect(route('teacher.documents', ['milestone' => 2]));

        $this->teacherProfile->refresh();
        $this->assertEquals('3171012345670001', $this->teacherProfile->nik);
        $this->assertTrue($this->teacherProfile->isMilestone1Complete());

        // 2. Milestone 2: Akademik & Ijazah
        $ijazahFile = UploadedFile::fake()->create('ijazah_s1_unj.pdf', 1024, 'application/pdf');
        $resM2 = $this->post(route('teacher.documents.milestone2'), [
            'degree_level' => 'S1',
            'university_name' => 'Universitas Negeri Jakarta',
            'major' => 'Pendidikan Matematika',
            'diploma_number' => 'UNJ/2018/S1/0987',
            'graduation_year' => 2018,
            'gpa' => 3.82,
            'teaching_status' => 'Guru Honorer Sekolah Negeri',
            'school_origin' => 'SMP Negeri 19 Jakarta',
            'sk_guru_number' => '421.2/019/SMPN19/2022',
            'ijazah_file' => $ijazahFile,
        ]);
        $resM2->assertRedirect(route('teacher.documents', ['milestone' => 3]));

        $this->teacherProfile->refresh();
        $this->assertEquals('Universitas Negeri Jakarta', $this->teacherProfile->university_name);
        $this->assertTrue($this->teacherProfile->isMilestone2Complete());

        // 3. Milestone 3: SKCK Kepolisian
        $skckFile = UploadedFile::fake()->create('skck_resmi.pdf', 1024, 'application/pdf');
        $resM3 = $this->post(route('teacher.documents.milestone3'), [
            'skck_number' => 'SKCK/YANMIN/2024/POLRES-JAKSEL/089',
            'skck_issuer' => 'Polres Metro Jakarta Selatan',
            'skck_valid_until' => now()->addMonths(5)->format('Y-m-d'),
            'skck_file' => $skckFile,
        ]);
        $resM3->assertRedirect(route('teacher.documents', ['milestone' => 4]));

        $this->teacherProfile->refresh();
        $this->assertEquals('SKCK/YANMIN/2024/POLRES-JAKSEL/089', $this->teacherProfile->skck_number);
        $this->assertTrue($this->teacherProfile->isMilestone3Complete());

        // 4. Milestone 4: Pakta Perlindungan Anak
        $resM4 = $this->post(route('teacher.documents.milestone4'), [
            'agree_child_protection' => '1',
            'child_protection_signature_name' => 'Budi Santoso, S.Pd.',
        ]);
        $resM4->assertRedirect(route('teacher.documents', ['milestone' => 5]));

        $this->teacherProfile->refresh();
        $this->assertNotNull($this->teacherProfile->child_protection_pledged_at);
        $this->assertTrue($this->teacherProfile->isMilestone4Complete());
        $this->assertEquals(100, $this->teacherProfile->getMilestoneProgressPercent());
        $this->assertEquals('pending', $this->teacherProfile->verification_status);
    }
}
