<?php

namespace Domain\Core\Enum;

enum TeamRole: string
{
    case MANAGER = 'manager';
    case WORKER = 'worker';
}
