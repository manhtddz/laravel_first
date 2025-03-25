<?php

namespace App\Services\Repository;

use App\Models\Employee;
use App\Models\Team;
use App\Services\Interfaces\IRepository;
use Exception;

abstract class BaseRepository implements IRepository
{
    protected string $model;
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        try {
            return $this->model::find($id);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function findAll()
    {
        try {
            return $this->model::all();
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function findAllPaging($amount)
    {
        try {
            return $this->model::paginate($amount);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function create(array $requestData)
    {
        try {
            unset($requestData['_token']);
            return $this->model::create($requestData);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function update($id, array $requestData)
    {
        try {
            $item = $this->model::findOrFail($id);
            unset($requestData['_token']);
            return $item->update($requestData);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function delete($id)
    {
        try {
            $item = $this->model::findOrFail($id);
            return $item->delete();
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function searchPaging($amount, array $requestData, $sort = null, $direction = 'asc')
    {
        try {
            $filters = array_filter(
                $requestData,
                fn($value) => $value !== null && $value !== ''
            );
            $columns = \Schema::getColumnListing((new $this->model())->getTable()); // Take column list
            $query = $this->model::query();
            foreach ($filters as $key => $value) {
                if ($key === 'name') {
                    if ($this->model !== Team::class) {
                        $query->searchName($value);
                    } else {
                        $query->where($key, 'like', '%' . $value . '%');
                    }
                }
                if (in_array(strtolower($key), $columns)) {
                    if ($key === 'email') {
                        $query->where($key, 'like', '%' . $value . '%');
                    }
                    if ($key === 'team_id') {
                        $query->where($key, $value);
                    }
                }
            }

            if ($sort === 'name' && $this->model === Employee::class) {
                $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$direction}");
            } else {
                if ($sort && in_array(strtolower($sort), $columns)) {
                    $query->orderBy($sort, strtolower($direction));
                }
            }
            return $query->paginate($amount);

        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }

    }
}