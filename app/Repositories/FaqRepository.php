<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FaqRepository
{
    public function __construct(protected Faq $model)
    {
    }

    public function getDataTableQuery(): Builder
    {
        return $this->model->newQuery()->orderBy('sort_order', 'asc')->latest('id');
    }

    public function findById(int $id): ?Faq
    {
        return $this->model->find($id);
    }

    public function create(array $data): Faq
    {
        return $this->model->create($data);
    }

    public function update(Faq $faq, array $data): bool
    {
        return $faq->update($data);
    }

    public function delete(Faq $faq): bool
    {
        return (bool) $faq->delete();
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * @return Collection<int, Faq>
     */
    public function getFaqs(): Collection
    {
        return $this->model
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}
