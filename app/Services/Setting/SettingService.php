<?php

declare(strict_types=1);

namespace App\Services\Setting;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\UploadedFile;

class SettingService
{
    use UploadTrait;

    public function __construct(protected SettingRepository $settingRepository)
    {
    }

    public function getSettings(): Setting
    {
        return $this->settingRepository->getOrCreate();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Setting $setting, array $data, ?UploadedFile $logo = null, ?UploadedFile $favIcon = null): Setting
    {
        if ($logo !== null) {
            $data['logo'] = $this->uploadImage($logo, 'setting', $setting->getRawOriginal('logo') ?: $setting->logo);
        }

        if ($favIcon !== null) {
            $data['fav_icon'] = $this->uploadImage($favIcon, 'setting', $setting->getRawOriginal('fav_icon') ?: $setting->fav_icon);
        }

        $this->settingRepository->update($setting, $data);

        return $setting->fresh();
    }
}
