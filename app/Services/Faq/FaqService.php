<?php

declare(strict_types=1);

namespace App\Services\Faq;

use App\Repositories\FaqRepository;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    public function __construct(protected FaqRepository $faqRepository)
    {
    }

    /**
     * @return Collection<\App\Models\Faq>
     */
    public function getFaqs(): Collection
    {
        return $this->faqRepository->getFaqs();
    }
}
