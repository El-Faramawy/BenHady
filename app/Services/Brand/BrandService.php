<?php

declare(strict_types=1);

namespace App\Services\Brand;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class BrandService
{
    use UploadTrait;

    public function __construct(protected BrandRepository $brandRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->brandRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Brand $brand): string => tableCheckbox($brand->id))
            ->addColumn('logo', fn (Brand $brand): string => tableImage($brand->logo_url, $brand->name_ar))
            ->addColumn('status', fn (Brand $brand): string => tableStatus((bool) $brand->is_active))
            ->addColumn('action', fn (Brand $brand): string => tableAction($brand->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data, ?UploadedFile $logo = null): Brand
    {
        if ($logo) {
            $data['logo_url'] = $this->uploadImage($logo, 'brands');
        }

        return $this->brandRepository->create($data);
    }

    public function update(Brand $brand, array $data, ?UploadedFile $logo = null): bool
    {
        if ($logo) {
            $data['logo_url'] = $this->uploadImage($logo, 'brands', $brand->logo_url);
        }

        return $this->brandRepository->update($brand, $data);
    }

    public function delete(Brand $brand): bool
    {
        return $this->brandRepository->delete($brand);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->brandRepository->deleteMultiple($ids);
    }

    public function getBrands(): Collection
    {
        return $this->brandRepository->getAllActive();
    }
}
