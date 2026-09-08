<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalPolicy extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'tenant_instructions_ar',
        'tenant_instructions_en',
        'insurance_policy_ar',
        'insurance_policy_en',
        'cancellation_policy_ar',
        'cancellation_policy_en',
    ];

    protected $appends = [
        'tenant_instructions',
        'insurance_policy',
        'cancellation_policy',
    ];

    public function getTenantInstructionsAttribute(): ?string
    {
        return $this->getLocalized('tenant_instructions');
    }

    public function getInsurancePolicyAttribute(): ?string
    {
        return $this->getLocalized('insurance_policy');
    }

    public function getCancellationPolicyAttribute(): ?string
    {
        return $this->getLocalized('cancellation_policy');
    }
}
