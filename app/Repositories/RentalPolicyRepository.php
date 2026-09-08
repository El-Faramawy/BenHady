<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\RentalPolicy;

class RentalPolicyRepository
{
    public function __construct(protected RentalPolicy $model)
    {
    }

    public function getPolicy(): ?RentalPolicy
    {
        return $this->model->first();
    }
}
