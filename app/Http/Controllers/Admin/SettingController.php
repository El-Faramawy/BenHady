<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\Setting\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settingService)
    {
    }

    public function index(): View
    {
        $setting = $this->settingService->getSettings();

        return view('Admin.Setting.index', compact('setting'));
    }

    public function update(UpdateSettingRequest $request): JsonResponse
    {
        $setting = $this->settingService->getSettings();

        $this->settingService->update(
            $setting,
            $request->safe()->except(['logo', 'fav_icon']),
            $request->file('logo'),
            $request->file('fav_icon')
        );

        return response()->json(['message' => __('messages.general.updated_success')]);
    }
}
