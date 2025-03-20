<?php

namespace App\Services\Services;

use App\Models\Employee;
use App\Services\Interfaces\IEmployeeRepository;
use App\Services\Interfaces\ITeamRepository;
use App\Services\Repository\EmployeeRepository;
use App\Services\Repository\TeamRepository;
use Exception;
use Response;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;
    public function __construct(IEmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }
    public function findAll()
    {
        return $this->employeeRepository->findAll();
    }

    public function findAllPaging()
    {
        return $this->employeeRepository->findAllPaging(2);
    }
    public function findAllEmployeeId()
    {
        return $this->employeeRepository->findAllEmployeeId();
    }

    public function findById($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("That type of id is not accepted");
        }
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            throw new Exception("Data doesn't exist");
        }
        return $employee;
    }
    public function findNotActiveEmployeeByEmail($email)
    {
        return $this->employeeRepository->findNotActiveEmployeeByEmail($email);
    }
    public function findActiveEmployeeByEmail($email)
    {
        return $this->employeeRepository->findActiveEmployeeByEmail($email);
    }
    public function search(array $request, $sort, $direction)
    {
        return $this->employeeRepository->searchPaging(2, $request, $sort, $direction);
    }
    public function findAllSearchedId(array $request, $sort, $direction)
    {
        return $this->employeeRepository->findAllSearchedId($request, $sort, $direction);
    }
    public function create(array $request)
    {
        return $this->employeeRepository->create($request);
    }
    public function update(int $id, array $request)
    {
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            throw new Exception("Data doesn't exist");
        }
        return $this->employeeRepository->update($id, $request);
    }
    public function delete(int $id)
    {
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            throw new Exception("Data doesn't exist");
        }
        return $this->employeeRepository->delete($id);
    }

    public function exportToCSV(array $ids)
    {
        ob_start();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data.csv"',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $fileName = 'employees_' . time() . '.csv';
        $filePath = storage_path("app/public/temp/" . $fileName);

        $handle = fopen($filePath, "w");
        fputcsv($handle, ['ID', 'Team', 'Name', 'Email']);

        $emps = Employee::whereIn('id', $ids)->get(); // Lọc theo ID

        foreach ($emps as $emp) {
            fputcsv($handle, [
                strip_tags($emp->id),
                strip_tags($emp->team->name),
                strip_tags($emp->name),
                strip_tags($emp->email)
            ]);
        }

        fclose($handle);
        ob_end_clean();

        return Response::download($filePath, $fileName, $headers)->deleteFileAfterSend(true)->send();
    }


}