<?php

declare(strict_types=1);

namespace App\Constants\Messages;

class NotificationMessages
{
    public static function deleteSuccess(): string
    {
        return __('messages.notification.delete_success');
    }
}
