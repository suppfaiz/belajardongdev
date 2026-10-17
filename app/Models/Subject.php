<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'category',
        'icon',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Subject $subject): void {
            if (empty($subject->uuid)) {
                $subject->uuid = (string) Str::uuid();
            }
            if (empty($subject->slug)) {
                $subject->slug = Str::slug($subject->name);
            }
        });
    }

    /**
     * @return BelongsToMany<TeacherProfile, $this>
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(TeacherProfile::class, 'teacher_subjects')
            ->withPivot('grade_levels', 'custom_hourly_rate')
            ->withTimestamps();
    }
}
