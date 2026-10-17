<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'booking_id',
        'payment_code',
        'payment_gateway',
        'payment_method',
        'amount',
        'status',
        'transaction_reference',
        'va_number',
        'qr_url',
        'paid_at',
        'expires_at',
        'raw_payload',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'raw_payload' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment): void {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) Str::uuid();
            }
            if (empty($payment->payment_code)) {
                $payment->payment_code = 'PAY-'.strtoupper(Str::random(3)).'-'.rand(1000, 9999);
            }
        });
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
