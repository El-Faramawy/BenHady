<?php

namespace App\Enums\User;

enum UserStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case IN_ACTIVE = 'in_active';
    case REJECTED = 'rejected';
}
