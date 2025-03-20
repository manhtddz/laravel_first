<?php

namespace App\Services\Repository;

use App\Models\Employee;
use App\Services\Interfaces\IEmployeeRepository;
use Exception;

class EmployeeRepository extends BaseRepository implements IEmployeeRepository
{
    private const MODEL = Employee::class;
    public function __construct()
    {
        parent::__construct(self::MODEL);
    }
    public function findNotActiveEmployeeByEmail($email)
    {
        try {
            return Employee::withoutGlobalScopes()
                ->where('email', $email)
                ->where('del_flag', 1)
                ->first();
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function findActiveEmployeeByEmail($email)
    {
        try {
            return Employee::withoutGlobalScopes()
                ->where('email', $email)
                ->where('del_flag', 0)
                ->first();
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function findAllEmployeeId()
    {
        try {
            return Employee::all()->pluck('id')->toArray();
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function findAllSearchedId(array $requestData, $sort = null, $direction = 'asc')
    {
        try {
            $filters = array_filter(
                $requestData,
                fn($value) => $value !== null && $value !== ''
            );
            $columns = \Schema::getColumnListing((new Employee())->getTable());
            // dd($columns);
            $query = Employee::query();
            foreach ($filters as $key => $value) {
                if ($key === 'name') {
                    $query->where($key, 'like', '%' . $value . '%');
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
            return $query->pluck("id")->toArray();

        } catch (Exception $e) {
            dd($e->getMessage());
        }

    }
}