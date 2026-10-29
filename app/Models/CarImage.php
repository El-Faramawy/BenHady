<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImagePathAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return request()->is('admin*') ? null : get_file(null);
        }

        return get_file($value);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
