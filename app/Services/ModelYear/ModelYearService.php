<?php

declare(strict_types=1);

namespace App\Services\ModelYear;

use App\Models\ModelYear;
use App\Repositories\ModelYearRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ModelYearService
{
    public function __construct(protected ModelYearRepository $modelYearRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->modelYearRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (ModelYear $my): string => tableCheckbox($my->id))
            ->addColumn('status', fn (ModelYear $my): string => tableStatus((bool) $my->is_active))
            ->addColumn('action', fn (ModelYear $my): string => tableAction($my->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): ModelYear
    {
        return $this->modelYearRepository->create($data);
    }

    public function update(ModelYear $modelYear, array $data): bool
    {
        return $this->modelYearRepository->update($modelYear, $data);
    }

    public function delete(ModelYear $modelYear): bool
    {
        return $this->modelYearRepository->delete($modelYear);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->modelYearRepository->deleteMultiple($ids);
    }

    public function getModelYears(): Collection
    {
        return $this->modelYearRepository->getAllActive();
    }
}
