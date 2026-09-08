<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'city_id',
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'latitude',
        'longitude',
        'phone',
        'is_active',
    ];

    protected $appends = [
        'name',
        'address',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->getLocalized('name');
    }

    public function getAddressAttribute(): ?string
    {
        return $this->getLocalized('address');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(BranchWorkingHour::class);
    }
}
