<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\UploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use UploadTrait;

    public function index(): View
    {
        $setting = Setting::first();

        return view('Admin.Setting.index', compact('setting'));
    }

    public function update(Request $request, Setting $setting): JsonResponse
    {
        $data = $request->except(['logo', 'fav_icon']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'uploads/setting', $setting->logo);
        }

        if ($request->hasFile('fav_icon')) {
            $data['fav_icon'] = $this->uploadImage($request->file('fav_icon'), 'uploads/setting', $setting->fav_icon);
        }

        $setting->update($data);

        return response()->json(['message' => __('messages.general.updated_success')]);
    }
}
