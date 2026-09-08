<?php

declare(strict_types=1);

namespace App\Services\ModelYear;

use App\Repositories\ModelYearRepository;
use Illuminate\Database\Eloquent\Collection;

class ModelYearService
{
    public function __construct(protected ModelYearRepository $modelYearRepository)
    {
    }

    public function getModelYears(): Collection
    {
        return $this->modelYearRepository->getAllActive();
    }
}
