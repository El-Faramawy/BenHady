<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FuelType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FuelTypeRepository
{
    public function __construct(protected FuelType $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?FuelType
    {
        return $this->model->find($id);
    }

    public function create(array $data): FuelType
    {
        return $this->model->create($data);
    }

    public function update(FuelType $fuelType, array $data): bool
    {
        return $fuelType->update($data);
    }

    public function delete(FuelType $fuelType): bool
    {
        return (bool) $fuelType->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, FuelType>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
