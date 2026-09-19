<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContactMessage;

class ContactMessageRepository
{
    public function __construct(protected ContactMessage $model)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): ContactMessage
    {
        return $this->model->create($data);
    }
}
