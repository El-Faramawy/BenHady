<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FeatureRepository
{
    public function __construct(protected Feature $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?Feature
    {
        return $this->model->find($id);
    }

    public function create(array $data): Feature
    {
        return $this->model->create($data);
    }

    public function update(Feature $feature, array $data): bool
    {
        return $feature->update($data);
    }

    public function delete(Feature $feature): bool
    {
        return (bool) $feature->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Feature>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
