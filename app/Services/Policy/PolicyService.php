<?php

declare(strict_types=1);

namespace App\Services\Policy;

use App\Models\RentalPolicy;
use App\Repositories\RentalPolicyRepository;

class PolicyService
{
    public function __construct(protected RentalPolicyRepository $rentalPolicyRepository)
    {
    }

    public function getPolicies(): ?RentalPolicy
    {
        return $this->rentalPolicyRepository->getPolicy();
    }
}
