<?php

namespace Infrastructure\Services;

use Domain\Core\Entity\Building;
use Domain\Core\Entity\User;
use Infrastructure\Persistence\Repositories\TeamEloquentRepository;

class TeamService
{
    public static function userBelongsToTeam(Building $building, User $user): bool
    {
        $teamRepository = new TeamEloquentRepository();

        return $teamRepository->checkIfUserBelongsToBuildingTeam($building, $user);
    }
}
