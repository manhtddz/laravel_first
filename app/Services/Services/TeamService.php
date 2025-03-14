<?php

namespace App\Services\Services;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamSearchRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Services\Interfaces\ITeamRepository;
use App\Services\Repository\TeamRepository;
use Exception;

class TeamService
{
    private TeamRepository $teamRepository;
    public function __construct(ITeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function findAll()
    {
        return $this->teamRepository->findAll();
    }
    public function findAllPaging()
    {
        return $this->teamRepository->findAllPaging(2);
    }
    public function findById($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("That type of id is not accepted");
        }
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception("Data doesn't exist");
        }
        return $team;
    }
    public function search(array $request)
    {
        return $this->teamRepository->searchPaging(2, $request);
    }
    public function create(array $request)
    {
        return $this->teamRepository->create($request);
    }
    public function update(int $id, array $request)
    {
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception("Data doesn't exist");
        }
        return $this->teamRepository->update($id, $request);
    }
    public function delete(int $id)
    {
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception("Data doesn't exist");
        }
        return $this->teamRepository->delete($id);
    }
}