<?php

namespace App\Services\Services;

use App\Http\Requests\TeamCreateRequest;
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
        $filtered = array_filter(
            $request,
            fn($value) => $value !== "" && $value !== null && $value != 0
        );

        $teams = $this->findAllPaging();

        if (!empty($filtered)) { // Call service when search data is not empty
            $teams = $this->teamRepository
                ->searchPaging(ITEM_PER_PAGE, $filtered, $sort, $direction);
        }

        return $teams;
    }
    public function create(array $request)
    {
        $result = $this->teamRepository->create($request);

        if (empty($result)) {
            throw new Exception(CREATE_FAILED);
        }

        return $result;
    }
    public function update(int $id, array $request)
    {
        $team = $this->teamRepository->findById($id);

        if (!$team) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        $result = $this->teamRepository->update($id, $request);
        if (!$result) {
            throw new Exception(UPDATE_FAILED);
        }
        return $result;
    }
    public function delete(int $id)
    {
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        $result = $this->teamRepository->delete($id);
        if (!$result) {
            throw new Exception(DELETE_FAILED);
        }
        return $result;
    }

    public function prepareConfirmForUpdate(TeamUpdateRequest $request)
    {
        $validatedData = $request->validated();

        session()->flash('team_data', $validatedData);
    }

    public function prepareConfirmForCreate(TeamCreateRequest $request)
    {
        $validatedData = $request->validated();

        session()->flash('team_data', $validatedData);
    }
}