<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'icon',
        'is_active',
    ];

    protected $appends = [
        'name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->getLocalized('name');
    }

    public function getIconAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return request()->is('admin*') ? null : get_file(null);
        }

        if (str_starts_with($value, 'fa ') || str_starts_with($value, 'fe ') || str_starts_with($value, 'bi ') || str_starts_with($value, 'mdi ') || str_starts_with(trim($value), '<svg')) {
            return $value;
        }

        return get_file($value);
    }

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_feature');
    }
}
