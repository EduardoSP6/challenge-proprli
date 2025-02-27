<?php

namespace Application\UseCases\Tasks\AddComment;

final class AddCommentInputDto
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $userId,
        public readonly string $content,
    )
    {
    }
}
