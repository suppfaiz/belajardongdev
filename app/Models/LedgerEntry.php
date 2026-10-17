<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LedgerEntry extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'entry_code',
        'transaction_type',
        'direction',
        'amount',
        'user_id',
        'booking_id',
        'payout_id',
        'balance_after',
        'description',
        'reference_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (LedgerEntry $entry): void {
            if (empty($entry->uuid)) {
                $entry->uuid = (string) Str::uuid();
            }
            if (empty($entry->entry_code)) {
                $entry->entry_code = 'LED-'.strtoupper(Str::random(4)).'-'.rand(1000, 9999);
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return BelongsTo<Payout, $this>
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }
}
