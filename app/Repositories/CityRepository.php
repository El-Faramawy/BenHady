<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{
    public function __construct(protected City $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?City
    {
        return $this->model->find($id);
    }

    public function create(array $data): City
    {
        return $this->model->create($data);
    }

    public function update(City $city, array $data): bool
    {
        return $city->update($data);
    }

    public function delete(City $city): bool
    {
        return (bool) $city->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, City>
     */
    public function getAllActiveWithBranches(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->with(['branches' => fn ($query) => $query->where('is_active', true)])
            ->get();
    }

    /**
     * @return Collection<int, City>
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('sort_order', 'asc')->get();
    }
}
