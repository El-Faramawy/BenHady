<?php

declare(strict_types=1);

namespace App\Services\Setting;

use App\Models\Setting;
use App\Repositories\SettingRepository;

class SettingService
{
    public function __construct(protected SettingRepository $settingRepository)
    {
    }

    public function getSettings(): ?Setting
    {
        return $this->settingRepository->getSettings();
    }
}
