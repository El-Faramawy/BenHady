<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository
{
    public function __construct(protected Brand $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?Brand
    {
        return $this->model->find($id);
    }

    public function create(array $data): Brand
    {
        return $this->model->create($data);
    }

    public function update(Brand $brand, array $data): bool
    {
        return $brand->update($data);
    }

    public function delete(Brand $brand): bool
    {
        return (bool) $brand->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
