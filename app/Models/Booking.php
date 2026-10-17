<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'booking_code',
        'parent_id',
        'teacher_id',
        'student_id',
        'subject_id',
        'booking_date',
        'start_time',
        'end_time',
        'session_duration_minutes',
        'plan_type',
        'total_sessions',
        'selected_days',
        'discount_percent',
        'discount_amount',
        'schedule_dates',
        'lesson_address',
        'lesson_latitude',
        'lesson_longitude',
        'notes',
        'city_id',
        'district_id',
        'village_id',
        'status',
        'cancellation_reason',
        'cancelled_by',
        'hourly_rate_snapshot',
        'session_fee',
        'platform_commission_rate',
        'platform_commission_amount',
        'teacher_net_amount',
        'total_amount',
        'payment_deadline_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'session_duration_minutes' => 'integer',
            'plan_type' => 'string',
            'total_sessions' => 'integer',
            'selected_days' => 'array',
            'discount_percent' => 'float',
            'discount_amount' => 'integer',
            'schedule_dates' => 'array',
            'lesson_latitude' => 'float',
            'lesson_longitude' => 'float',
            'hourly_rate_snapshot' => 'integer',
            'session_fee' => 'integer',
            'platform_commission_rate' => 'float',
            'platform_commission_amount' => 'integer',
            'teacher_net_amount' => 'integer',
            'total_amount' => 'integer',
            'payment_deadline_at' => 'datetime',
        ];
    }

    /**
     * Human-friendly label for the booking plan.
     */
    public function getPlanLabelAttribute(): string
    {
        return match ($this->plan_type) {
            'monthly' => 'Paket 1 Bulan ('.($this->total_sessions ?? 8).' Sesi)',
            'six_months' => 'Paket 6 Bulan / 1 Semester ('.($this->total_sessions ?? 48).' Sesi)',
            default => 'Harian (1 Sesi)',
        };
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (empty($booking->uuid)) {
                $booking->uuid = (string) Str::uuid();
            }
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BY-'.strtoupper(Str::random(3)).'-'.rand(1000, 9999);
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<Subject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * @return HasOne<Payment, $this>
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * @return HasOne<LessonSession, $this>
     */
    public function session(): HasOne
    {
        return $this->hasOne(LessonSession::class);
    }

    /**
     * @return HasOne<Review, $this>
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * @return HasMany<LedgerEntry, $this>
     */
    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * @return BelongsTo<District, $this>
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * @return BelongsTo<Village, $this>
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
