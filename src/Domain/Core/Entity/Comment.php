<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;
use DomainException;

class Comment extends BaseEntity
{
    private readonly Uuid $id;
    private readonly Task $task;
    private readonly User $user;
    private readonly string $content;
    private readonly DateTimeImmutable $createdAt;
    private readonly ?DateTimeImmutable $updatedAt;

    /**
     * @param Uuid $id
     * @param Task $task
     * @param User $user
     * @param string $content
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        Uuid               $id,
        Task               $task,
        User               $user,
        string             $content,
        DateTimeImmutable  $createdAt,
        ?DateTimeImmutable $updatedAt = null
    )
    {
        parent::__construct($id, $createdAt, $updatedAt);

        $this->id = $id;
        $this->task = $task;
        $this->user = $user;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->validate();
    }

    protected function validate(): void
    {
        throw_if(
            empty(trim($this->content)),
            new DomainException("Comment can not be empty")
        );
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
