<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Coupon\CouponDiscountTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_booking_amount',
        'is_global',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'start_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_type' => CouponDiscountTypeEnum::class,
            'discount_value' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'min_booking_amount' => 'decimal:2',
            'is_global' => 'boolean',
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count' => 'integer',
            'start_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withTimestamps();
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }
}
