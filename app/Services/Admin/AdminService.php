<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Admin;
use App\Repositories\AdminRepository;
use DomainException;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class AdminService
{
    public function __construct(protected AdminRepository $repository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $currentAdminId = (int) (admin()->id() ?? 0);
        $query = $this->repository->getDataTableQuery($currentAdminId);

        return DataTables::of($query)
            ->addColumn('action', fn (Admin $admin): string => tableAction($admin->id, true, true))
            ->addColumn('checkbox', fn (Admin $admin): string => tableCheckbox($admin->id))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Admin
    {
        return $this->repository->create($data);
    }

    public function update(Admin $admin, array $data): bool
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->repository->update($admin, $data);
    }

    public function delete(Admin $admin): bool
    {
        if ($admin->id === (int) admin()->id()) {
            throw new DomainException(__('messages.general.cannot_delete_self'));
        }

        return $this->repository->delete($admin);
    }

    public function deleteMultiple(array $ids): int
    {
        $safeIds = array_diff($ids, [(int) admin()->id()]);

        return !empty($safeIds) ? $this->repository->deleteMultiple($safeIds) : 0;
    }
}
