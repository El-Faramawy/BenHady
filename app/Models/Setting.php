<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'logo',
        'fav_icon',
        'about_us_ar',
        'about_us_en',
        'terms_conditions_ar',
        'terms_conditions_en',
        'privacy_policy_ar',
        'privacy_policy_en',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'about_us',
        'terms_conditions',
        'privacy_policy',
    ];

    public function getAboutUsAttribute(): ?string
    {
        return $this->getLocalized('about_us');
    }

    public function getTermsConditionsAttribute(): ?string
    {
        return $this->getLocalized('terms_conditions');
    }

    public function getPrivacyPolicyAttribute(): ?string
    {
        return $this->getLocalized('privacy_policy');
    }
}
