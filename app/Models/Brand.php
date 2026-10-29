<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'logo_url',
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

    public function getLogoUrlAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return request()->is('admin*') ? null : get_file(null);
        }

        return get_file($value);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
