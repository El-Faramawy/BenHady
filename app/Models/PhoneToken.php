<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneToken extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'phone_token',
    ];

    /**
     * @return BelongsTo<User, PhoneToken>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
