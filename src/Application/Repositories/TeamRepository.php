<?php

namespace Application\Repositories;

use Domain\Core\Entity\Building;
use Domain\Core\Entity\Team;
use Domain\Core\Entity\TeamMember;
use Domain\Core\Entity\User;


interface TeamRepository
{
    public function create(Team $team): void;

    public function addMember(TeamMember $teamMember): void;

    public function checkIfUserBelongsToBuildingTeam(Building $building, User $user): bool;
}
