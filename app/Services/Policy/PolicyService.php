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

    public function getPolicy(): RentalPolicy
    {
        return $this->rentalPolicyRepository->getOrCreate();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updatePolicy(RentalPolicy $policy, array $data): bool
    {
        return $this->rentalPolicyRepository->update($policy, $data);
    }
}
