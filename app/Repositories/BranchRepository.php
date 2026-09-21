<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Branch;
use App\Models\BranchWorkingHour;
use App\Models\Car;

class BranchRepository
{
    public function __construct(
        protected Branch $branchModel,
        protected BranchWorkingHour $workingHourModel,
        protected Car $carModel
    ) {
    }

    public function findActiveById(int $id): ?Branch
    {
        return $this->branchModel
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    public function getWorkingHour(int $branchId, string $dayOfWeek): ?BranchWorkingHour
    {
        return $this->workingHourModel
            ->where('branch_id', $branchId)
            ->where('day_of_week', $dayOfWeek)
            ->first();
    }

    public function isCarAvailableAtBranch(int $carId, int $branchId): bool
    {
        return $this->carModel
            ->where('id', $carId)
            ->where('is_active', true)
            ->where(function ($query) use ($branchId) {
                $query->where('branch_id', $branchId)
                    ->orWhereHas('branches', function ($b) use ($branchId) {
                        $b->where('branches.id', $branchId)
                          ->where('branches.is_active', true);
                    });
            })
            ->exists();
    }
}
