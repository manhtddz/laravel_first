<?php

namespace App\Services\Repository;

use App\Models\Team;
use App\Services\Interfaces\IRepository;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;

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
            // return redirect()->route('user.index')->with('success', 'tao thanh cong');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function findAll()
    {
        try {
            return $this->model::all();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function findAllPaging($amount)
    {
        try {
            // return $this->model->paginate(10, ['*'], 'page', $page);
            return $this->model::paginate($amount);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function create(array $requestData)
    {
        try {
            unset($requestData['_token']);
            // dd($requestData);
            $this->model::create($requestData);
            // return redirect()->route('user.index')->with('success', 'tao thanh cong');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update($id, array $requestData)
    {
        try {
            $item = $this->model::findOrFail($id);
            unset($requestData['_token']);
            $item->update($requestData);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            $item = $this->model::findOrFail($id);
            $item->delete();
            // return redirect()->route('user.index')->with('success', 'delete thanh cong');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function searchPaging($amount, array $requestData, $sort = null, $direction = 'asc')
    {
        try {
            $filters = array_filter(
                $requestData,
                fn($value) => $value !== null && $value !== ''
            );
            $columns = \Schema::getColumnListing((new $this->model())->getTable()); // Lấy danh sách cột từ model
            // dd($columns);
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

            if ($sort === 'name') {
                $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$direction}");
            } else {
                if ($sort && in_array(strtolower($sort), $columns)) {
                    $query->orderBy($sort, strtolower($direction));
                }
            }
            return $query->paginate($amount);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}