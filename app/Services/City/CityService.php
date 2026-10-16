<?php

declare(strict_types=1);

namespace App\Services\City;

use App\Models\City;
use App\Repositories\CityRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class CityService
{
    public function __construct(protected CityRepository $cityRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->cityRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (City $city): string => tableCheckbox($city->id))
            ->addColumn('status', fn (City $city): string => tableStatus((bool) $city->is_active))
            ->addColumn('action', fn (City $city): string => tableAction($city->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): City
    {
        return $this->cityRepository->create($data);
    }

    public function update(City $city, array $data): bool
    {
        return $this->cityRepository->update($city, $data);
    }

    public function delete(City $city): bool
    {
        return $this->cityRepository->delete($city);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->cityRepository->deleteMultiple($ids);
    }

    public function getCities(): Collection
    {
        return $this->cityRepository->getAllActiveWithBranches();
    }
}
