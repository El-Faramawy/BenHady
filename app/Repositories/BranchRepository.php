<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Branch;
use App\Models\BranchWorkingHour;
use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository
{
    public function __construct(
        protected Branch $branchModel,
        protected BranchWorkingHour $workingHourModel,
        protected Car $carModel
    ) {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->branchModel->newQuery()->with('city')->latest('id');
    }

    public function findById(int $id): ?Branch
    {
        return $this->branchModel->find($id);
    }

    public function create(array $data): Branch
    {
        return $this->branchModel->create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        return $branch->update($data);
    }

    public function delete(Branch $branch): bool
    {
        return (bool) $branch->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->branchModel->whereIn('id', $ids)->delete();
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

    /**
     * @return Collection<int, BranchWorkingHour>
     */
    public function getWorkingHours(Branch $branch): Collection
    {
        return $this->workingHourModel
            ->where('branch_id', $branch->id)
            ->get();
    }

    /**
     * @param array<int, array<string, mixed>> $workingHours
     */
    public function syncWorkingHours(Branch $branch, array $workingHours): void
    {
        foreach ($workingHours as $data) {
            $dayOfWeek = $data['day_of_week'] ?? null;
            if (!$dayOfWeek) {
                continue;
            }

            $isOpen = (bool) ($data['is_open'] ?? false);
            $is24 = (bool) ($data['is_24_hours'] ?? false);

            $this->workingHourModel->updateOrCreate(
                [
                    'branch_id' => $branch->id,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'is_open' => $isOpen,
                    'is_24_hours' => $is24,
                    'open_at' => $is24 ? '00:00:00' : ($data['open_at'] ?? null),
                    'close_at' => $is24 ? '23:59:59' : ($data['close_at'] ?? null),
                    'reservation_start_at' => $is24 ? '00:00:00' : ($data['reservation_start_at'] ?? $data['open_at'] ?? null),
                    'reservation_close_at' => $is24 ? '23:59:59' : ($data['reservation_close_at'] ?? $data['close_at'] ?? null),
                ]
            );
        }
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

    /**
     * Get total count of branches.
     */
    public function getTotalCount(): int
    {
        return $this->branchModel->newQuery()->count();
    }

    /**
     * Get branches summary with city and car count.
     *
     * @return Collection<int, Branch>
     */
    public function getBranchesSummary(int $limit = 6): Collection
    {
        return $this->branchModel->newQuery()
            ->with('city')
            ->withCount('cars')
            ->orderByDesc('cars_count')
            ->take($limit)
            ->get();
    }
}
