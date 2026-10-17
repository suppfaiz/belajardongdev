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
        Schema::table('bookings', function (Blueprint $table): void {
            $table->string('plan_type')->default('daily')->after('session_duration_minutes');
            $table->unsignedInteger('total_sessions')->default(1)->after('plan_type');
            $table->json('selected_days')->nullable()->after('total_sessions');
            $table->decimal('discount_percent', 5, 2)->default(0.00)->after('total_sessions');
            $table->unsignedBigInteger('discount_amount')->default(0)->after('discount_percent');
            $table->json('schedule_dates')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropColumn([
                'plan_type',
                'total_sessions',
                'selected_days',
                'discount_percent',
                'discount_amount',
                'schedule_dates',
            ]);
        });
    }
};
