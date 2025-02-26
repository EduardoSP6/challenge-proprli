<?php

namespace Domain\Core\Enum;

enum UserRole: string
{
    case OWNER = 'owner';
    case TEAM_MEMBER = 'team_member';
}
