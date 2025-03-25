<?php

namespace App\Services\Services;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Jobs\SendEmployeeEmailJob;
use App\Models\Employee;
use App\Services\Interfaces\IEmployeeRepository;
use App\Services\Repository\EmployeeRepository;
use Exception;
use Response;
use Storage;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;
    private FileService $fileService;

    public function __construct(IEmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->fileService = FileService::getInstance();
    }
    public function findAll()
    {
        return $this->employeeRepository->findAll();
    }

    public function findAllPaging()
    {
        return $this->employeeRepository->findAllPaging(ITEM_PER_PAGE);
    }
    public function findAllEmployeeId()
    {
        return $this->employeeRepository->findAllEmployeeId();
    }

    public function findById($id)
    {
        if (!is_numeric($id)) {
            throw new Exception(WRONG_FORMAT_ID);
        }
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            throw new Exception(NOT_EXIST_ERROR);
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
    public function search(array $request, $sort = null, $direction = "asc")
    {
        $filtered = array_filter(
            $request,
            fn($value) => $value !== "" && $value !== null && $value != 0
        );

        $employees = $this->findAllPaging();

        if (!empty($filtered)) { // Call service when search data is not empty
            $employees = $this->employeeRepository
                ->searchPaging(ITEM_PER_PAGE, $filtered, $sort, $direction);
        }

        return $employees;
    }
    public function findAllSearchedId(array $request, $sort, $direction)
    {
        $filtered = array_filter(
            $request,
            fn($value) => $value !== "" && $value !== null && $value != 0
        );

        $employeeIds = $this->findAllEmployeeId();

        if (!empty($request)) { // Call service when search data is not empty
            $employeeIds = $this->employeeRepository
                ->findAllSearchedId($filtered, $sort, $direction);
        }

        return $employeeIds;
    }
    public function create(array $request)
    {
        $this->fileService->moveTempFileToApp($request['avatar']);

        $result = $this->employeeRepository->create($request);

        if (empty($result)) {
            throw new Exception(CREATE_FAILED);
        }

        $emailGetter['email'] = $request['email'];
        $emailGetter['first_name'] = $request['first_name'];
        $emailGetter['last_name'] = $request['last_name'];
        SendEmployeeEmailJob::dispatch($emailGetter)->delay(now()->addSeconds(5));

        return $result;
    }
    public function update(int $id, array $request)
    {
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        $result = $this->employeeRepository->update($id, $request);
        if (!$result) {
            throw new Exception(UPDATE_FAILED);
        }
        if ($request['avatar'] !== $request['old_avatar']) {
            $this->fileService->removeFile('app/' . $request['old_avatar']);
            $this->fileService->moveTempFileToApp($request['avatar']);
        }
        $emailGetter['email'] = $request['email'];
        $emailGetter['first_name'] = $request['first_name'];
        $emailGetter['last_name'] = $request['last_name'];
        SendEmployeeEmailJob::dispatch($emailGetter)->delay(now()->addSeconds(5));

        return $result;
    }
    public function delete(int $id)
    {
        $employee = $this->employeeRepository->findById($id);
        if (!$employee) {
            throw new Exception(NOT_EXIST_ERROR);
        }
        $result = $this->employeeRepository->delete($id);
        if (!$result) {
            throw new Exception(DELETE_FAILED);
        }
        
        return $result;
    }
    public function prepareConfirmForUpdate($id, EmployeeUpdateRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('avatar')) {
            session()->forget('temp_file');
            $filePath = $this->fileService->uploadTempFileAndDeleteTempFile(
                $request->file('avatar'),
                $request->uploaded_avatar
            );
            $validatedData['avatar'] = $filePath;
        } else {
            $validatedData['avatar'] = $request->uploaded_avatar;
        }
        $validatedData['old_avatar'] = $request->old_avatar;
        $validatedData['id'] = $id;

        session()->flash('employee_data', $validatedData);
    }

    public function prepareConfirmForCreate(EmployeeCreateRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('avatar')) {
            session()->forget('temp_file');
            $filePath = $this->fileService
                ->uploadTempFileAndDeleteTempFile(
                    $request->file('avatar'),
                    $request->old_avatar
                );
            $validatedData['avatar'] = $filePath;
        } else {
            $validatedData['avatar'] = $request->old_avatar;
        }

        session()->flash('employee_data', $validatedData);
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
        $filePath = 'temp/' . $fileName; // Store in `storage/app/public/temp/`

        // Create a white file in public disk
        Storage::disk('public')->put($filePath, '');

        // Take absolute path of white file
        $absolutePath = Storage::disk('public')->path($filePath);

        $handle = fopen($absolutePath, "w");
        fputcsv($handle, ['ID', 'Team', 'Name', 'Email']);

        $emps = Employee::whereIn('id', $ids)->get(); // Filter by ID

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

        return Response::download($absolutePath, $fileName, $headers)
            ->deleteFileAfterSend(true)
            ->send();
    }


}