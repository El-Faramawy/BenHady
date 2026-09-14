<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function __construct(protected Setting $model)
    {
    }

    public function getSettings(): ?Setting
    {
        return $this->model->first();
    }
}
