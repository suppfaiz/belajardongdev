<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeacherProfile extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'headline',
        'bio',
        'education',
        'experience_years',
        'hourly_rate',
        'session_duration_minutes',
        'service_city',
        'service_district',
        'service_village',
        'city_id',
        'district_id',
        'village_id',
        'service_address',
        'latitude',
        'longitude',
        'max_travel_distance_km',
        'is_verified',
        'verification_status',
        'rejection_reason',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'rating_cache',
        'reviews_count_cache',
        'child_protection_pledged_at',
        'ktp_verified',
        'pddikti_verified',
        'skck_verified',
        'sk_guru_verified',
        'nik',
        'full_name_ktp',
        'birth_place',
        'birth_date',
        'gender',
        'ktp_address',
        'degree_level',
        'university_name',
        'major',
        'diploma_number',
        'graduation_year',
        'gpa',
        'teaching_status',
        'school_origin',
        'sk_guru_number',
        'skck_number',
        'skck_issuer',
        'skck_valid_until',
        'child_protection_signature_name',
    ];

    /**
     * Attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'hourly_rate' => 'integer',
            'session_duration_minutes' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'max_travel_distance_km' => 'integer',
            'is_verified' => 'boolean',
            'rating_cache' => 'float',
            'reviews_count_cache' => 'integer',
            'child_protection_pledged_at' => 'datetime',
            'ktp_verified' => 'boolean',
            'pddikti_verified' => 'boolean',
            'skck_verified' => 'boolean',
            'sk_guru_verified' => 'boolean',
            'birth_date' => 'date',
            'graduation_year' => 'integer',
            'gpa' => 'float',
            'skck_valid_until' => 'date',
        ];
    }

    /**
     * Check if Milestone 1 (Identitas KTP) is complete.
     */
    public function isMilestone1Complete(): bool
    {
        return ! empty($this->nik) && ! empty($this->full_name_ktp) && $this->documents()->where('document_type', 'ktp')->exists();
    }

    /**
     * Check if Milestone 2 (Akademik & SK Guru) is complete.
     */
    public function isMilestone2Complete(): bool
    {
        return ! empty($this->university_name) && ! empty($this->diploma_number) && $this->documents()->where('document_type', 'ijazah')->exists();
    }

    /**
     * Check if Milestone 3 (SKCK Kepolisian) is complete.
     */
    public function isMilestone3Complete(): bool
    {
        return ! empty($this->skck_number) && $this->documents()->where('document_type', 'skck')->exists();
    }

    /**
     * Check if Milestone 4 (Pakta Perlindungan Anak) is complete.
     */
    public function isMilestone4Complete(): bool
    {
        return ! empty($this->child_protection_pledged_at);
    }

    /**
     * Calculate milestone verification completion percentage (0 - 100%).
     */
    public function getMilestoneProgressPercent(): int
    {
        $completed = 0;
        if ($this->isMilestone1Complete()) {
            $completed++;
        }
        if ($this->isMilestone2Complete()) {
            $completed++;
        }
        if ($this->isMilestone3Complete()) {
            $completed++;
        }
        if ($this->isMilestone4Complete()) {
            $completed++;
        }

        return (int) round(($completed / 4) * 100);
    }

    /**
     * Auto generate UUID on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (TeacherProfile $profile): void {
            if (empty($profile->uuid)) {
                $profile->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * User relation.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Teacher documents relation.
     *
     * @return HasMany<TeacherDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TeacherDocument::class);
    }

    /**
     * Taught subjects relation.
     *
     * @return BelongsToMany<Subject, $this>
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
            ->withPivot('grade_levels', 'custom_hourly_rate')
            ->withTimestamps();
    }

    /**
     * Weekly availability slots.
     *
     * @return HasMany<AvailabilitySlot, $this>
     */
    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    /**
     * Reviews received.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Payout requests.
     *
     * @return HasMany<Payout, $this>
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Scope only verified teachers.
     *
     * @param  Builder<TeacherProfile>  $query
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true)
            ->where('verification_status', 'verified');
    }

    /**
     * Scope distance and filter by both student radius and teacher maximum travel distance.
     *
     * @param  Builder<TeacherProfile>  $query
     */
    public function scopeWithinRadius(Builder $query, float $latitude, float $longitude, float $searchRadiusKm): Builder
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite test fallback formula
            $sql = '(6371 * 2 * asin(sqrt(
                sin(radians((latitude - ?) / 2)) * sin(radians((latitude - ?) / 2)) +
                cos(radians(?)) * cos(radians(latitude)) *
                sin(radians((longitude - ?) / 2)) * sin(radians((longitude - ?) / 2))
            )))';
            $bindings = [$latitude, $latitude, $latitude, $longitude, $longitude];
        } else {
            // PostgreSQL formula (exact sphere distance in KM)
            $sql = '(6371 * acos(
                least(1.0, greatest(-1.0,
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                ))
            ))';
            $bindings = [$latitude, $longitude, $latitude];
        }

        return $query->select('teacher_profiles.*')
            ->selectRaw("{$sql} AS distance_km", $bindings)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereRaw("{$sql} <= ?", array_merge($bindings, [$searchRadiusKm]))
            ->whereRaw("{$sql} <= max_travel_distance_km", $bindings);
    }

    /**
     * Recalculate rating and reviews count cache.
     */
    public function recalculateRating(): void
    {
        $stats = $this->reviews()->where('is_published', true)->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')->first();
        $this->update([
            'rating_cache' => $stats ? round((float) ($stats->avg_rating ?? 0), 2) : 0.00,
            'reviews_count_cache' => $stats ? (int) ($stats->count ?? 0) : 0,
        ]);
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
