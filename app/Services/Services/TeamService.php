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
        return $this->teamRepository->findAllPaging(ITEM_PER_PAGE);
    }
    public function findById($id)
    {
        if (!is_numeric($id)) {
            throw new Exception(WRONG_FORMAT_ID);
        }
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        return $team;
    }
    public function search(array $request, $sort, $direction)
    {
        $teams = $this->findAllPaging();

        if (!empty($request)) { // Call service when search data is not empty
            $teams = $this->teamRepository
                ->searchPaging(ITEM_PER_PAGE, $request, $sort, $direction);
        }

        return $teams;
    }
    public function create(array $request)
    {
        return $this->teamRepository->create($request);
    }
    public function update(int $id, array $request)
    {
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        return $this->teamRepository->update($id, $request);
    }
    public function delete(int $id)
    {
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        return $this->teamRepository->delete($id);
    }
}