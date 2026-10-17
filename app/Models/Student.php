<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'parent_id',
        'name',
        'grade_level',
        'class_grade',
        'school_name',
        'notes',
        'address',
        'city',
        'district',
        'village',
        'city_id',
        'district_id',
        'village_id',
        'latitude',
        'longitude',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Student $student): void {
            if (empty($student->uuid)) {
                $student->uuid = (string) Str::uuid();
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
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function cityRegion(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * @return BelongsTo<District, $this>
     */
    public function districtRegion(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * @return BelongsTo<Village, $this>
     */
    public function villageRegion(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
