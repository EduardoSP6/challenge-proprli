<?php

namespace Application\UseCases\Tasks\Create;

use Application\Repositories\BuildingRepository;
use Application\Repositories\TaskRepository;
use DateTimeImmutable;
use Domain\Core\Entity\Task;
use Domain\Core\Enum\TaskStatus;
use Domain\Shared\ValueObject\Uuid;
use Exception;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\TeamService;
use Infrastructure\Services\UserService;

class CreateTaskUseCase
{
    private TaskRepository $taskRepository;
    private BuildingRepository $buildingRepository;

    /**
     * @param TaskRepository $taskRepository
     * @param BuildingRepository $buildingRepository
     */
    public function __construct(
        TaskRepository     $taskRepository,
        BuildingRepository $buildingRepository,
    )
    {
        $this->taskRepository = $taskRepository;
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * @throws Exception
     */
    public function execute(CreateTaskInputDto $inputDto): void
    {
        try {
            $building = $this->buildingRepository->find($inputDto->buildingId);
            $assignedUser = UserService::findUserById($inputDto->assignedUserId);

            throw_if(
                $inputDto->ownerId !== $building->getOwner()->getId()->value(),
                new Exception("You are not the owner of this building")
            );

            throw_if(
                !TeamService::userBelongsToTeam($building, $assignedUser),
                new Exception("The assigned user does not belong to this team")
            );

            $task = new Task(
                id: new Uuid(),
                building: $building,
                createdBy: $building->getOwner(),
                title: $inputDto->title,
                description: $inputDto->description,
                createdAt: new DateTimeImmutable(),
                assignedTo: $assignedUser,
                status: TaskStatus::OPENED
            );

            $this->taskRepository->create($task);
        } catch (Exception $e) {
            Log::error("Error to create task.", [
                'class' => get_class($this),
                'method' => 'execute',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
