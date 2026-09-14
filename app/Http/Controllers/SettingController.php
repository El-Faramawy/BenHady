<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\ApiResponse;
use App\Services\Setting\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settingService)
    {
    }

    public function index(): JsonResponse
    {
        $settings = $this->settingService->getSettings();

        return (new ApiResponse())
            ->setData($settings)
            ->create();
    }
}
