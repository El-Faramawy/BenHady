<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Car\CarStatusEnum;
use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'brand_id',
        'category_id',
        'branch_id',
        'model_year_id',
        'transmission_id',
        'fuel_type_id',
        'name_ar',
        'name_en',
        'seats',
        'daily_price',
        'description_ar',
        'description_en',
        'is_featured',
        'is_handpicked',
        'is_active',
        'status',
    ];

    protected $appends = [
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'daily_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_handpicked' => 'boolean',
            'is_active' => 'boolean',
            'status' => CarStatusEnum::class,
        ];
    }

    public function getNameAttribute(): ?string
    {
        return $this->getLocalized('name');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getLocalized('description');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function modelYear(): BelongsTo
    {
        return $this->belongsTo(ModelYear::class);
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo(Transmission::class);
    }

    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order', 'asc');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(CarImage::class)->where('is_primary', true);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'car_feature');
    }
}
