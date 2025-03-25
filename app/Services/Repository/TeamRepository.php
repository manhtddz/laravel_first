<?php

namespace App\Services\Repository;

use App\Models\Team;
use App\Services\Interfaces\ITeamRepository;

class TeamRepository extends BaseRepository implements ITeamRepository
{
    private const MODEL = Team::class;
    public function __construct()
    {
        parent::__construct(self::MODEL);
    }
}