<?php

declare(strict_types=1);

namespace App\Services\Category;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\DataTables;

class CategoryService
{
    use UploadTrait;

    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->categoryRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Category $cat): string => tableCheckbox($cat->id))
            ->addColumn('icon', fn (Category $cat): string => tableIcon($cat->icon, 'fa fa-th-large'))
            ->addColumn('status', fn (Category $cat): string => tableStatus((bool) $cat->is_active))
            ->addColumn('action', fn (Category $cat): string => tableAction($cat->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data, ?UploadedFile $icon = null): Category
    {
        if ($icon) {
            $data['icon'] = $this->uploadImage($icon, 'categories/icons');
        }

        return $this->categoryRepository->create($data);
    }

    public function update(Category $category, array $data, ?UploadedFile $icon = null): bool
    {
        if ($icon) {
            $data['icon'] = $this->uploadImage($icon, 'categories/icons', $category->getRawOriginal('icon') ?: $category->icon);
        }

        return $this->categoryRepository->update($category, $data);
    }

    public function delete(Category $category): bool
    {
        return $this->categoryRepository->delete($category);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->categoryRepository->deleteMultiple($ids);
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getAllActive();
    }
}
