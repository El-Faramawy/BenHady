<?php

declare(strict_types=1);

namespace App\Constants\Messages;

/**
 * User-related success message keys.
 */
class UserMessages
{
    public static function loginSuccess(): string
    {
        return __('messages.user.login_success');
    }

    public static function registerSuccess(): string
    {
        return __('messages.user.register_success');
    }

    public static function logoutSuccess(): string
    {
        return __('messages.user.logout_success');
    }

    public static function phoneVerifiedSuccess(): string
    {
        return __('messages.user.phone_verified_success');
    }

    public static function profileUpdateSuccess(): string
    {
        return __('messages.user.profile_update_success');
    }
}
