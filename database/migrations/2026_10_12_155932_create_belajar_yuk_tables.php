<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Teacher Profiles
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('education')->nullable();
            $table->unsignedInteger('experience_years')->default(0);
            $table->unsignedBigInteger('hourly_rate')->default(100000); // Satuan Rupiah integer
            $table->unsignedInteger('session_duration_minutes')->default(90);
            $table->string('service_city')->nullable();
            $table->string('service_district')->nullable();
            $table->text('service_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('max_travel_distance_km')->default(10);
            $table->boolean('is_verified')->default(false);
            $table->string('verification_status')->default('pending'); // pending, verified, rejected
            $table->text('rejection_reason')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->decimal('rating_cache', 3, 2)->default(0.00);
            $table->unsignedInteger('reviews_count_cache')->default(0);
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index(['is_verified', 'verification_status']);
        });

        // 2. Teacher Documents (Stored Privately)
        Schema::create('teacher_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->string('document_type'); // ktp, ijazah, skck, sertifikat, transkrip
            $table->string('document_number')->nullable();
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('verification_status')->default('pending'); // pending, verified, rejected
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_notes')->nullable();
            $table->timestamps();

            $table->index(['teacher_profile_id', 'document_type']);
        });

        // 3. Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('grade_level'); // sd, smp, sma, ujian
            $table->string('class_grade')->nullable(); // Kelas 4, Kelas 8, Kelas 12
            $table->string('school_name')->nullable();
            $table->text('notes')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['parent_id', 'is_active']);
        });

        // 4. Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('sd'); // sd, smp, sma, ujian, umum
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });

        // 5. Teacher Subjects Pivot
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->json('grade_levels')->nullable(); // ["sd", "smp", "sma"]
            $table->unsignedBigInteger('custom_hourly_rate')->nullable();
            $table->timestamps();

            $table->unique(['teacher_profile_id', 'subject_id']);
        });

        // 6. Availability Slots
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 0=Minggu, 1=Senin, ..., 6=Sabtu
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['teacher_profile_id', 'day_of_week']);
        });

        // 7. Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('booking_code')->unique();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('session_duration_minutes')->default(90);
            $table->text('lesson_address');
            $table->decimal('lesson_latitude', 10, 7)->nullable();
            $table->decimal('lesson_longitude', 10, 7)->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending_teacher');
            // pending_teacher, accepted, rejected, paid, confirmed, in_progress, completed, cancelled, refunded
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('hourly_rate_snapshot');
            $table->unsignedBigInteger('session_fee');
            $table->decimal('platform_commission_rate', 5, 2)->default(10.00);
            $table->unsignedBigInteger('platform_commission_amount');
            $table->unsignedBigInteger('teacher_net_amount');
            $table->unsignedBigInteger('total_amount');
            $table->timestamp('payment_deadline_at')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'booking_date', 'start_time', 'end_time'], 'teacher_schedule_index');
            $table->index(['parent_id', 'status']);
        });

        // 8. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('payment_code')->unique();
            $table->string('payment_gateway')->default('mock_pg');
            $table->string('payment_method')->default('virtual_account'); // virtual_account, gopay, dana, qris, bank_transfer
            $table->unsignedBigInteger('amount');
            $table->string('status')->default('pending'); // pending, paid, expired, failed, refunded
            $table->string('transaction_reference')->nullable();
            $table->string('va_number')->nullable();
            $table->text('qr_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });

        // 9. Lesson Sessions
        Schema::create('lesson_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->dateTime('scheduled_start');
            $table->dateTime('scheduled_end');
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->string('status')->default('scheduled');
            // scheduled, teacher_arrived, in_progress, completed, disputed, cancelled
            $table->timestamp('teacher_confirmed_at')->nullable();
            $table->timestamp('parent_confirmed_at')->nullable();
            $table->text('lesson_notes')->nullable();
            $table->decimal('teacher_checkin_latitude', 10, 7)->nullable();
            $table->decimal('teacher_checkin_longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        // 10. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment');
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['teacher_profile_id', 'rating']);
        });

        // 11. Payouts
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('payout_code')->unique();
            $table->foreignId('teacher_profile_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('status')->default('requested'); // requested, approved, processing, completed, rejected
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_name');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['teacher_profile_id', 'status']);
        });

        // 12. Ledger Entries (Financial Immutability)
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('entry_code')->unique();
            $table->string('transaction_type');
            // payment_received, platform_commission, teacher_earnings, payout_disbursed, refund_issued
            $table->string('direction'); // credit, debit
            $table->unsignedBigInteger('amount');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('payout_id')->nullable()->constrained('payouts')->nullOnDelete();
            $table->bigInteger('balance_after')->default(0);
            $table->text('description');
            $table->string('reference_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'transaction_type']);
        });

        // 13. Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('general');
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
        });

        // 14. Support Tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('ticket_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->string('subject');
            $table->string('category')->default('general'); // safety, payment, teacher_conduct, scheduling, technical, other
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, in_investigation, resolved, closed
            $table->text('message');
            $table->text('admin_resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // 15. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_id', 'action']);
        });

        // 16. Platform Settings
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('lesson_sessions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('availability_slots');
        Schema::dropIfExists('teacher_subjects');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teacher_documents');
        Schema::dropIfExists('teacher_profiles');
    }
};
