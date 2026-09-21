<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Booking\BookingStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'car_id',
        'pickup_branch_id',
        'return_branch_id',
        'pickup_at',
        'return_at',
        'daily_price',
        'total_days',
        'subtotal',
        'discount',
        'total',
        'notes',
        'status',
    ];

    protected $appends = [
        'status_label',
    ];

    protected function casts(): array
    {
        return [
            'pickup_at' => 'datetime',
            'return_at' => 'datetime',
            'daily_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'total_days' => 'integer',
            'status' => BookingStatusEnum::class,
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status instanceof BookingStatusEnum
            ? $this->status->label()
            : BookingStatusEnum::from((string) $this->status)->label();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function pickupBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'pickup_branch_id');
    }

    public function returnBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'return_branch_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            BookingStatusEnum::PENDING->value,
            BookingStatusEnum::ACTIVE->value,
        ]);
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->whereIn('status', [
            BookingStatusEnum::COMPLETED->value,
            BookingStatusEnum::CANCELLED->value,
        ]);
    }
}
