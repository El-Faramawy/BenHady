<?php

declare(strict_types=1);

namespace App\Services\Transmission;

use App\Repositories\TransmissionRepository;
use Illuminate\Database\Eloquent\Collection;

class TransmissionService
{
    public function __construct(protected TransmissionRepository $transmissionRepository)
    {
    }

    public function getTransmissions(): Collection
    {
        return $this->transmissionRepository->getAllActive();
    }
}
