<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'question_ar',
        'question_en',
        'answer_ar',
        'answer_en',
        'sort_order',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'question',
        'answer',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getQuestionAttribute(): ?string
    {
        return $this->getLocalized('question');
    }

    public function getAnswerAttribute(): ?string
    {
        return $this->getLocalized('answer');
    }
}
