<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->userService->getDataTable();
        }

        return view('Admin.User.index');
    }

    public function create(): View
    {
        return view('Admin.User.parts.create');
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->userService->store($request->validated());

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    public function show(User $user): View
    {
        $user = $this->userService->getUserWithDetails($user);

        return view('Admin.User.parts.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('Admin.User.parts.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->userService->update($user, $request->validated());

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->userService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }
}
