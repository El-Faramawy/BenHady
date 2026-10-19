<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\Auth\AuthenticationFailedException;
use App\Models\User;
use App\Repositories\PhoneTokenRepository;
use App\Repositories\UserRepository;
use App\Services\User\DTO\UpdateProfileDTO;
use Illuminate\Support\Facades\Hash;

/**
 * Handles user-related business logic (profile, account management).
 * Does NOT interact with the database directly — uses UserRepository.
 */
class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected PhoneTokenRepository $phoneTokenRepository,
    ) {
    }

    /**
     * Get the currently authenticated user.
     *
     * @throws AuthenticationFailedException
     */
    public function getAuthenticatedUser(): User
    {
        if (!auth()->check()) {
            throw new AuthenticationFailedException();
        }

        return $this->userRepository->findOneByOrFail('id', (int) auth()->user()->id);
    }

    /**
     * Update the currently authenticated user's profile.
     *
     * @throws AuthenticationFailedException
     */
    public function updateProfile(UpdateProfileDTO $dto): User
    {
        $user = $this->getAuthenticatedUser();

        $data = $dto->toArray();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Delete the authenticated user account and their associated tokens.
     *
     * @throws AuthenticationFailedException
     */
    public function deleteAccount(): void
    {
        $user = $this->getAuthenticatedUser();

        $this->phoneTokenRepository->deleteTokensByUserId($user->id);

        if (auth()->check()) {
            auth()->logout();
        }

        $this->userRepository->delete($user);
    }

    /**
     * Get DataTable response for Admin users list.
     */
    public function getDataTable(): \Illuminate\Http\JsonResponse
    {
        $query = $this->userRepository->getDataTableQuery();

        return \Yajra\DataTables\DataTables::of($query)
            ->addColumn('checkbox', fn (User $user): string => tableCheckbox($user->id))
            ->addColumn('name_email', function (User $user): string {
                $email = $user->email ? '<div class="text-muted font-size-12" style="margin-top: 2px; line-height: 1.2;"><i class="fa fa-envelope-o ml-1"></i>' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '</div>' : '';
                return '<div class="font-weight-bold text-dark" style="line-height: 1.2;">' . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . '</div>' . $email;
            })
            ->addColumn('phone_formatted', fn (User $user): string => '<span dir="ltr" class="font-weight-bold text-primary">' . htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8') . '</span>')
            ->addColumn('type_badge', function (User $user): string {
                return match ($user->type) {
                    'national_id' => '<span class="badge badge-primary px-3 py-1 font-weight-bold" style="border-radius: 6px;">مواطن سعودي</span>',
                    'resident_id' => '<span class="badge badge-info px-3 py-1 font-weight-bold" style="border-radius: 6px;">مقيم</span>',
                    'visitor'     => '<span class="badge badge-secondary px-3 py-1 font-weight-bold" style="border-radius: 6px;">زائر</span>',
                    default       => '<span class="badge badge-light px-3 py-1 font-weight-bold" style="border-radius: 6px;">غير محدد</span>',
                };
            })
            ->addColumn('verifications', function (User $user): string {
                $phoneIcon = $user->phone_verified ? '<span class="badge badge-success mr-1" title="الهاتف موثق"><i class="fa fa-phone"></i></span>' : '<span class="badge badge-light text-muted mr-1" title="الهاتف غير موثق"><i class="fa fa-phone"></i></span>';
                $idIcon = $user->id_verified ? '<span class="badge badge-success mr-1" title="الهوية موثقة"><i class="fa fa-id-card"></i></span>' : '<span class="badge badge-light text-muted mr-1" title="الهوية غير موثقة"><i class="fa fa-id-card"></i></span>';
                $licenseIcon = $user->licence_verified ? '<span class="badge badge-success" title="الرخصة موثقة"><i class="fa fa-car"></i></span>' : '<span class="badge badge-light text-muted" title="الرخصة غير موثقة"><i class="fa fa-car"></i></span>';
                return '<div class="d-flex align-items-center justify-content-center">' . $phoneIcon . $idIcon . $licenseIcon . '</div>';
            })
            ->addColumn('wallet_formatted', fn (User $user): string => '<span class="font-weight-bold text-success">' . number_format((float) $user->wallet, 2) . ' ر.س</span>')
            ->addColumn('status', fn (User $user): string => tableStatus($user->status === 'active'))
            ->addColumn('created_at_formatted', fn (User $user): string => $user->created_at?->format('Y-m-d') ?? '-')
            ->addColumn('action', function (User $user): string {
                $viewBtn = '<button type="button" class="btn btn-default btn-action-view btn-sm mb-2 mb-xl-0 user-details-btn" data-id="' . $user->id . '" data-toggle="tooltip" title="عرض الملف والتفاصيل"><i class="fa fa-eye text-white"></i></button> ';
                return $viewBtn . tableAction($user->id, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store new user from Admin.
     *
     * @param array<string, mixed> $data
     */
    public function store(array $data): User
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->create($data);
    }

    /**
     * Update user attributes from Admin.
     *
     * @param array<string, mixed> $data
     */
    public function update(User $user, array $data): bool
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepository->update($user, $data);

        return true;
    }

    /**
     * Delete user.
     */
    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    /**
     * Batch delete users.
     *
     * @param array<int> $ids
     */
    public function deleteMultiple(array $ids): int
    {
        return $this->userRepository->deleteMultiple($ids);
    }

    /**
     * Get user with their bookings and details.
     */
    public function getUserWithDetails(User $user): User
    {
        return $this->userRepository->getUserWithBookings($user);
    }
}
