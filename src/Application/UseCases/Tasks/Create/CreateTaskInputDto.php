<?php

namespace Application\UseCases\Tasks\Create;

final class CreateTaskInputDto
{
    public function __construct(
        public readonly string      $title,
        public readonly string|null $description,
        public readonly string      $buildingId,
        public readonly string      $ownerId,
        public readonly string      $assignedUserId,
    )
    {
    }
}
