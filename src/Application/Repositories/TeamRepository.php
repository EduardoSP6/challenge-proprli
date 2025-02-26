<?php

namespace Application\Repositories;

use Domain\Core\Entity\Team;
use Domain\Core\Entity\TeamMember;


interface TeamRepository
{
    public function create(Team $team): void;

    public function addMember(TeamMember $teamMember): void;
}
