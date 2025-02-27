<?php

namespace Application\UseCases\Tasks\Create;

use Application\Exceptions\UserDoesNotBelongTeamException;
use Application\Exceptions\UserIsNotBuildingOwnerException;
use Application\Repositories\BuildingRepository;
use Application\Repositories\TaskRepository;
use DateTimeImmutable;
use Domain\Core\Entity\Building;
use Domain\Core\Entity\Task;
use Domain\Core\Entity\User;
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
            $building = $this->findBuilding($inputDto->buildingId);
            $assignedUser = $this->findAssignedUser($inputDto->assignedUserId);

            throw_if(
                $inputDto->ownerId !== $building->getOwner()->getId()->value(),
                new UserIsNotBuildingOwnerException()
            );

            throw_if(
                !TeamService::userBelongsToTeam($building, $assignedUser),
                new UserDoesNotBelongTeamException()
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

    /**
     * Find the Building.
     *
     * @param string $id
     * @return Building|null
     */
    private function findBuilding(string $id): ?Building
    {
        $building = $this->buildingRepository->find($id);

        throw_if(!$building, new Exception("Building not found"));

        return $building;
    }

    /**
     * Find the user.
     *
     * @param string $id
     * @return User|null
     */
    private function findAssignedUser(string $id): ?User
    {
        $user = UserService::findUserById($id);

        throw_if(!$user, new Exception("User not found"));

        return $user;
    }
}
