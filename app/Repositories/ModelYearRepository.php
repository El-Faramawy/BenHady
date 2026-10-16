<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ModelYear;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ModelYearRepository
{
    public function __construct(protected ModelYear $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->orderBy('year', 'desc');
    }

    public function findById(int $id): ?ModelYear
    {
        return $this->model->find($id);
    }

    public function create(array $data): ModelYear
    {
        return $this->model->create($data);
    }

    public function update(ModelYear $modelYear, array $data): bool
    {
        return $modelYear->update($data);
    }

    public function delete(ModelYear $modelYear): bool
    {
        return (bool) $modelYear->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, ModelYear>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('year', 'desc')
            ->get();
    }
}
