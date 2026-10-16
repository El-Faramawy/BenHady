<?php

declare(strict_types=1);

namespace App\Services\Branch;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class BranchService
{
    public function __construct(protected BranchRepository $branchRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->branchRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Branch $branch): string => tableCheckbox($branch->id))
            ->addColumn('city_name', fn (Branch $branch): string => $branch->city?->name_ar ?? '-')
            ->addColumn('status', fn (Branch $branch): string => tableStatus((bool) $branch->is_active))
            ->addColumn('action', fn (Branch $branch): string => tableAction($branch->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Branch
    {
        return $this->branchRepository->create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        return $this->branchRepository->update($branch, $data);
    }

    public function delete(Branch $branch): bool
    {
        return $this->branchRepository->delete($branch);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->branchRepository->deleteMultiple($ids);
    }
}
