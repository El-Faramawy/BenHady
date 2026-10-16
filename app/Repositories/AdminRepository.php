<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;

class AdminRepository
{
    public function __construct(protected Admin $model)
    {
    }

    public function getDataTableQuery(int $currentAdminId): Builder
    {
        return $this->model->newQuery()
            ->where('id', '!=', $currentAdminId)
            ->latest('id');
    }

    public function findById(int $id): ?Admin
    {
        return $this->model->find($id);
    }

    public function create(array $data): Admin
    {
        return $this->model->create($data);
    }

    public function update(Admin $admin, array $data): bool
    {
        return $admin->update($data);
    }

    public function delete(Admin $admin): bool
    {
        return (bool) $admin->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
