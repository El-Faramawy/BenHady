<?php

declare(strict_types=1);

namespace App\Services\FuelType;

use App\Models\FuelType;
use App\Repositories\FuelTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class FuelTypeService
{
    public function __construct(protected FuelTypeRepository $fuelTypeRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->fuelTypeRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (FuelType $fuel): string => tableCheckbox($fuel->id))
            ->addColumn('status', fn (FuelType $fuel): string => tableStatus((bool) $fuel->is_active))
            ->addColumn('action', fn (FuelType $fuel): string => tableAction($fuel->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): FuelType
    {
        return $this->fuelTypeRepository->create($data);
    }

    public function update(FuelType $fuelType, array $data): bool
    {
        return $this->fuelTypeRepository->update($fuelType, $data);
    }

    public function delete(FuelType $fuelType): bool
    {
        return $this->fuelTypeRepository->delete($fuelType);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->fuelTypeRepository->deleteMultiple($ids);
    }

    public function getFuelTypes(): Collection
    {
        return $this->fuelTypeRepository->getAllActive();
    }
}
