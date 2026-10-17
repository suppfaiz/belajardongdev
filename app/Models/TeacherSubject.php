<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeacherSubject extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'teacher_subjects';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'teacher_profile_id',
        'subject_id',
        'grade_levels',
        'custom_hourly_rate',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade_levels' => 'array',
            'custom_hourly_rate' => 'integer',
        ];
    }
}
