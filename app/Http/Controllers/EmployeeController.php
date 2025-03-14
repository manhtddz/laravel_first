<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeSearchRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Jobs\SendEmployeeEmailJob;
use App\Services\Services\EmployeeService;
use App\Services\Services\FileService;
use App\Services\Services\TeamService;
use Auth;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class EmployeeController extends Controller
{
    private EmployeeService $employeeService;
    private FileService $fileService;
    private TeamService $teamService;
    public function __construct(EmployeeService $employeeService, TeamService $teamService)
    {
        $this->employeeService = $employeeService;
        $this->teamService = $teamService;
        $this->fileService = FileService::getInstance();
    }
    public function index(Request $request)
    {
        // dd($request->all());
        $teams = $this->teamService->findAll();
        $employees = $this->employeeService->findAllPaging();
        $config = $this->config();
        $filtered = array_filter(
            $request->all(),
            fn($value) => $value !== "" && $value !== null && $value != 0
        );
        $sort = $_GET['sortBy'] ?? null;
        $direction = $_GET['direction'] ?? 'desc';
        $newDirection = $direction === 'asc' ? 'desc' : 'asc';
        if (!empty($filtered)) { // Chỉ gọi service nếu có dữ liệu tìm kiếm
            $employees = $this->employeeService->search($filtered, $sort, $direction)->appends($request->query());
        }
        return view(
            'dashboard.layout',
            compact(['config', 'employees', 'teams', 'sort', 'direction', 'newDirection'])
        );
        // exit;
    }
    public function edit($id)
    {
        try {
            if (session('employee_data.old_avatar')) {
                $this->fileService->removeFile('app/' . session('employee_data.avatar', ''));
            }
            $teams = $this->teamService->findAll();
            $employee = $this->employeeService->findById($id);
            $config = $this->config();
            $config['template'] = "dashboard.employee.update";
            return view('dashboard.layout', compact(['config', 'employee', 'teams']));
        } catch (Exception $e) {
            return redirect()->route('employee.index')->with('error', $e->getMessage());
        }
    }
    public function getCreateForm()
    {
        $teams = $this->teamService->findAll();
        $config = $this->config();
        $config['template'] = "dashboard.employee.create";
        return view('dashboard.layout', compact(['config', 'teams']));
    }

    public function updateConfirm($id, EmployeeUpdateRequest $request)
    {

        $validatedData = $request->validated();
        if ($request->hasFile('avatar')) {
            // dd($request->old_avatar);
            $fileName = $this->fileService->uploadFile($request->file('avatar'));
            $validatedData['avatar'] = $fileName;
            $validatedData['old_avatar'] = $request->old_avatar;
        } else {
            $validatedData['avatar'] = $request->old_avatar;
        }
        $validatedData['id'] = $id;
        // dd($validatedData);
        session()->flash('employee_data', $validatedData);
        $config = $this->config();
        $config['template'] = "dashboard.employee.update_confirm";
        return view('dashboard.layout', compact(['config', 'id']));
    }
    public function showUpdateConfirm()
    {
        // Kiểm tra session có dữ liệu hay không
        if (!session()->has('employee_data')) {
            return redirect()->route('employee.index')->with('error', "Can't go to this page directly");
        }

        $config = $this->config();
        $config['template'] = "dashboard.employee.update_confirm";
        return view('dashboard.layout', compact(['config']));
    }
    public function createConfirm(EmployeeCreateRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('avatar')) {
            // dd($request->old_avatar);
            $filePath = $this->fileService->uploadTempFile($request->file('avatar'), $request->old_avatar);
            $validatedData['avatar'] = $filePath;
        } else {
            $validatedData['avatar'] = $request->old_avatar;
        }
        // dd($validatedData);
        session()->flash('employee_data', $validatedData);
        $config = $this->config();
        $config['template'] = "dashboard.employee.create_confirm";
        return view('dashboard.layout', compact(['config']));
    }
    public function showCreateConfirm()
    {
        // Kiểm tra session có dữ liệu hay không
        if (!session()->has('employee_data')) {
            return redirect()->route('employee.create')->with('error', 'Please fill blank field');
        }

        $config = $this->config();
        $config['template'] = "dashboard.employee.create_confirm";
        return view('dashboard.layout', compact(['config']));
    }
    public function update(Request $request, $id)
    {
        // sleep(10);

        try {
            $this->fileService->removeFile('app/' . session('employee_data.old_avatar', ''));
            $this->employeeService->update($id, $request->all());

            $emailGetter['email'] = $request->email;
            $emailGetter['first_name'] = $request->first_name;
            $emailGetter['last_name'] = $request->last_name;
            SendEmployeeEmailJob::dispatch($emailGetter)->delay(now()->addSeconds(5));
            return redirect()->route('employee.index')->with('success', 'Update successfully');
        } catch (Exception $e) {
            $this->fileService->removeFile($request->avatar);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function create(Request $request)
    {
        // dd($request->all());
        try {
            $this->employeeService->create($request->all());
            $this->fileService->moveTempFileToApp($request->avatar);
            $emailGetter['email'] = $request->email;
            $emailGetter['first_name'] = $request->first_name;
            $emailGetter['last_name'] = $request->last_name;
            SendEmployeeEmailJob::dispatch($emailGetter)->delay(now()->addSeconds(5));
            return redirect()->route('employee.index')->with('success', 'Create successfully');
        } catch (Exception $e) {
            $this->fileService->removeFile($request->avatar);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->employeeService->delete($id);
            return redirect()->route('employee.index')->with('success', 'Delete successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $ids = $request->input('ids'); // Lấy danh sách ID từ body

        $this->employeeService->exportToCSV(explode(', ', $ids));
        // ob_end_clean();
        // return redirect()->route('employee.index');
    }

    public function config()
    {
        return [
            'user' => Auth::user(),
            'template' => "dashboard.employee.index",
        ];
    }

}