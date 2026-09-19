<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'booking_id',
        'title_ar',
        'title_en',
        'body_ar',
        'body_en',
        'type',
        'is_read',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'title',
        'body',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->getLocalized('title');
    }

    public function getBodyAttribute(): ?string
    {
        return $this->getLocalized('body');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
