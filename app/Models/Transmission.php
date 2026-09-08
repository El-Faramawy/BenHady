<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transmission extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'is_active',
    ];

    protected $appends = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getNameAttribute(): ?string
    {
        return $this->getLocalized('name');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
