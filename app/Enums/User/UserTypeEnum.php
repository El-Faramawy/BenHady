<?php

namespace App\Enums\User;

enum UserTypeEnum: string
{
    case VISITOR = 'visitor';
    case NATIONAL_ID = 'national_id';
    case RESIDENT_ID = 'resident_id';
}
