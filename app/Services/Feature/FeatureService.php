<?php

declare(strict_types=1);

namespace App\Services\Feature;

use App\Models\Feature;
use App\Repositories\FeatureRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class FeatureService
{
    use UploadTrait;

    public function __construct(protected FeatureRepository $featureRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->featureRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Feature $feat): string => tableCheckbox($feat->id))
            ->addColumn('icon_preview', fn (Feature $feat): string => tableIcon($feat->icon, 'fa fa-sliders'))
            ->addColumn('icon', fn (Feature $feat): string => tableIcon($feat->icon, 'fa fa-sliders'))
            ->addColumn('status', fn (Feature $feat): string => tableStatus((bool) $feat->is_active))
            ->addColumn('action', fn (Feature $feat): string => tableAction($feat->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data, ?UploadedFile $icon = null): Feature
    {
        if ($icon) {
            $data['icon'] = $this->uploadImage($icon, 'features');
        }

        return $this->featureRepository->create($data);
    }

    public function update(Feature $feature, array $data, ?UploadedFile $icon = null): bool
    {
        if ($icon) {
            $data['icon'] = $this->uploadImage($icon, 'features', $feature->getRawOriginal('icon') ?: $feature->icon);
        }

        return $this->featureRepository->update($feature, $data);
    }

    public function delete(Feature $feature): bool
    {
        return $this->featureRepository->delete($feature);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->featureRepository->deleteMultiple($ids);
    }

    public function getFeatures(): Collection
    {
        return $this->featureRepository->getAllActive();
    }
}
