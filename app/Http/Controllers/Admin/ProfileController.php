<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('Admin.Profile.index');
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $admin = admin()->user();
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['confirm_password']);

        $admin->update($data);

        return response()->json(['message' => __('messages.user.profile_update_success')]);
    }
}
