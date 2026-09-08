<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Branch\DayOfWeekEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchWorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'day_of_week',
        'is_open',
        'opening_time',
        'closing_time',
        'is_24_hours',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'is_24_hours' => 'boolean',
        'day_of_week' => DayOfWeekEnum::class,
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function isOpenAt(string $time): bool
    {
        if (!$this->is_open) {
            return false;
        }

        if ($this->is_24_hours) {
            return true;
        }

        if ($this->opening_time === null || $this->closing_time === null) {
            return true;
        }

        $checkTime = substr($time, 0, 5);
        $opening = substr((string) $this->opening_time, 0, 5);
        $closing = substr((string) $this->closing_time, 0, 5);

        if ($opening <= $closing) {
            return $checkTime >= $opening && $checkTime <= $closing;
        }

        // Overnight shift (e.g. 20:00 to 04:00)
        return $checkTime >= $opening || $checkTime <= $closing;
    }
}
