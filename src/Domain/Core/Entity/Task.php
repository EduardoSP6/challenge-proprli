<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Core\Enum\TaskStatus;
use Domain\Core\Enum\TeamRole;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;
use DomainException;

final class Task extends BaseEntity
{
    private readonly Uuid $id;
    private readonly Building $building;
    private readonly Owner $createdBy;
    private string $title;
    private ?string $description;
    private ?User $assignedTo;
    private array $comments;
    private TaskStatus $status;
    private readonly DateTimeImmutable $createdAt;
    private readonly ?DateTimeImmutable $updatedAt;

    /**
     * @param Uuid $id
     * @param Building $building
     * @param Owner $createdBy
     * @param string $title
     * @param string|null $description
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     * @param User|null $assignedTo
     * @param array $comments
     * @param TaskStatus $status
     */
    public function __construct(
        Uuid               $id,
        Building           $building,
        Owner              $createdBy,
        string             $title,
        ?string            $description,
        DateTimeImmutable  $createdAt,
        ?DateTimeImmutable $updatedAt = null,
        ?User              $assignedTo = null,
        array              $comments = [],
        TaskStatus         $status = TaskStatus::OPENED
    )
    {
        parent::__construct($id, $createdAt, $updatedAt);

        $this->id = $id;
        $this->building = $building;
        $this->createdBy = $createdBy;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->assignedTo = $assignedTo;
        $this->comments = $comments;
        $this->status = $status;

        $this->validate();
    }

    protected function validate(): void
    {
        throw_if(
            empty(trim($this->title)),
            new DomainException("Title can not be empty")
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
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @return Owner
     */
    public function getCreatedBy(): Owner
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return User|null
     */
    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    /**
     * @param User $user
     * @param Team $team
     * @return void
     */
    public function assignTo(User $user, Team $team): void
    {
        if ($team->getBuilding() !== $this->building) {
            throw new DomainException("The user does not belong to the team in this building.");
        }

        $teamMember = $team->getMemberByUser($user);
        if (!$teamMember || $teamMember->getRole() !== TeamRole::WORKER) {
            throw new DomainException(
                "The task can only be assigned to team members with the WORKER role."
            );
        }

        $this->assignedTo = $user;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    /**
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * @param TaskStatus $status
     * @return void
     */
    public function changeStatus(TaskStatus $status): void
    {
        $this->status = $status;
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
