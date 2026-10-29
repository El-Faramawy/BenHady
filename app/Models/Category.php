<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'icon',
        'image_url',
        'is_active',
        'sort_order',
    ];

    protected $appends = [
        'name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->getLocalized('name');
    }

    public function getImageUrlAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return request()->is('admin*') ? null : get_file(null);
        }

        return get_file($value);
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

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
