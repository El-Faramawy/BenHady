<?php

declare(strict_types=1);

namespace App\Constants\Messages;

class NotificationMessages
{
    public static function deleteSuccess(): string
    {
        return __('messages.notification.delete_success');
    }

    public static function tokenSavedSuccess(): string
    {
        return __('messages.notification.token_saved_success');
    }

    public static function tokenDeletedSuccess(): string
    {
        return __('messages.notification.token_deleted_success');
    }
}
