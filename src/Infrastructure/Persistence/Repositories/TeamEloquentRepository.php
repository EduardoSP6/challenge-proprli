<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Repositories\TeamRepository;
use Domain\Core\Entity\Team;
use Domain\Core\Entity\TeamMember;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Infrastructure\Persistence\Models\Team as TeamModel;
use Infrastructure\Persistence\Models\TeamMember as TeamMemberModel;

class TeamEloquentRepository implements TeamRepository
{
    /**
     * Create a new team.
     *
     * @param Team $team
     * @return void
     */
    public function create(Team $team): void
    {
        throw_if(
            count($team->getMembers()) === 0,
            new Exception("Its not possible to create a team without members.")
        );

        try {
            DB::beginTransaction();

            /** @var TeamModel $newTeam */
            $newTeam = TeamModel::query()->create([
                "id" => $team->getId()->value(),
                "building_id" => $team->getBuilding()->getId()->value(),
            ]);

            $teamMembers = [];
            foreach ($team->getMembers() as $member) {
                $teamMembers[] = [
                    "id" => $member->getId()->value(),
                    "team_id" => $team->getId()->value(),
                    "user_id" => $member->getUser()->getId()->value(),
                    "role" => $member->getRole()->value,
                ];
            }

            $newTeam->members()->createMany($teamMembers);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error to persist team", [
                'class' => get_class($this),
                'method' => 'create',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Add a new member to a team.
     *
     * @param TeamMember $teamMember
     * @return void
     */
    public function addMember(TeamMember $teamMember): void
    {
        TeamMemberModel::query()->updateOrCreate([
            "team_id" => $teamMember->getTeam()->getId()->value(),
            "user_id" => $teamMember->getUser()->getId()->value(),
        ], [
            "id" => $teamMember->getId()->value(),
            "role" => $teamMember->getRole()->value,
        ]);
    }
}
