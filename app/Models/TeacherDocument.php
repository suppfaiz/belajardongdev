<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TeacherDocument extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'teacher_profile_id',
        'document_type',
        'document_number',
        'file_path',
        'original_filename',
        'verification_status',
        'verified_at',
        'rejection_notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TeacherDocument $doc): void {
            if (empty($doc->uuid)) {
                $doc->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * @return BelongsTo<TeacherProfile, $this>
     */
    public function teacherProfile(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class);
    }
}
