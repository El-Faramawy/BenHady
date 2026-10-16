<?php

declare(strict_types=1);

namespace App\Services\Transmission;

use App\Models\Transmission;
use App\Repositories\TransmissionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class TransmissionService
{
    public function __construct(protected TransmissionRepository $transmissionRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->transmissionRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Transmission $trans): string => tableCheckbox($trans->id))
            ->addColumn('status', fn (Transmission $trans): string => tableStatus((bool) $trans->is_active))
            ->addColumn('action', fn (Transmission $trans): string => tableAction($trans->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Transmission
    {
        return $this->transmissionRepository->create($data);
    }

    public function update(Transmission $transmission, array $data): bool
    {
        return $this->transmissionRepository->update($transmission, $data);
    }

    public function delete(Transmission $transmission): bool
    {
        return $this->transmissionRepository->delete($transmission);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->transmissionRepository->deleteMultiple($ids);
    }

    public function getTransmissions(): Collection
    {
        return $this->transmissionRepository->getAllActive();
    }
}
