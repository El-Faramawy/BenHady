<?php

declare(strict_types=1);

namespace App\Services\Faq;

use App\Models\Faq;
use App\Repositories\FaqRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class FaqService
{
    public function __construct(protected FaqRepository $faqRepository)
    {
    }

    public function getDataTable(): JsonResponse
    {
        $query = $this->faqRepository->getDataTableQuery();

        return DataTables::of($query)
            ->addColumn('checkbox', fn (Faq $faq): string => tableCheckbox($faq->id))
            ->addColumn('action', fn (Faq $faq): string => tableAction($faq->id, true, true))
            ->escapeColumns([])
            ->make(true);
    }

    public function store(array $data): Faq
    {
        return $this->faqRepository->create($data);
    }

    public function update(Faq $faq, array $data): bool
    {
        return $this->faqRepository->update($faq, $data);
    }

    public function delete(Faq $faq): bool
    {
        return $this->faqRepository->delete($faq);
    }

    public function deleteMultiple(array $ids): int
    {
        return $this->faqRepository->deleteMultiple($ids);
    }

    /**
     * @return Collection<int, Faq>
     */
    public function getFaqs(): Collection
    {
        return $this->faqRepository->getFaqs();
    }
}
