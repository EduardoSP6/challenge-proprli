<?php

namespace Domain\Core\Enum;

enum TaskStatus: string
{
    case OPENED = "opened";
    case IN_PROGRESS = "in_progress";
    case COMPLETED = "completed";
    case REJECTED = "rejected";

    public function getDescription(): string
    {
        return match ($this) {
            self::OPENED => 'Opened',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::REJECTED => 'Rejected',
        };
    }
}
