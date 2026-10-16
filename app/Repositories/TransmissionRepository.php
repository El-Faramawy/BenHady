<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TransmissionRepository
{
    public function __construct(protected Transmission $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->latest('id');
    }

    public function findById(int $id): ?Transmission
    {
        return $this->model->find($id);
    }

    public function create(array $data): Transmission
    {
        return $this->model->create($data);
    }

    public function update(Transmission $transmission, array $data): bool
    {
        return $transmission->update($data);
    }

    public function delete(Transmission $transmission): bool
    {
        return (bool) $transmission->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Transmission>
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->get();
    }
}
