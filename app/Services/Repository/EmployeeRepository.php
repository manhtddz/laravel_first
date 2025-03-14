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
    public function findByEmailIgnoreDelFlag($email)
    {
        try {
            return Employee::withoutGlobalScopes()->where('email', $email)->first();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}