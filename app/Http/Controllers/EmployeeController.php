<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Services\Services\EmployeeService;
use App\Services\Services\FileService;
use App\Services\Services\TeamService;
use Auth;
use Exception;
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
        //Initial value
        $teams = $this->teamService->findAll();
        $sortBy = $request->input('sortBy');
        $direction = $request->input('direction', 'asc');
        $config = $this->config();

        $employees = $this->employeeService
            ->search($request->all(), $sortBy, $direction)
            ->appends($request->query());
        $employeeIds = $this->employeeService
            ->findAllSearchedId($request->all(), $sortBy, $direction);

        return view(
            'dashboard.layout',
            compact(['config', 'employees', 'employeeIds', 'teams', "direction"])
        );
    }
    public function edit($id)
    {
        try {
            $teams = $this->teamService->findAll();
            $employee = $this->employeeService->findById($id);
            $config = $this->config();

            $config['template'] = "dashboard.employee.update";

            return view('dashboard.layout', compact(['config', 'employee', 'teams']));
        } catch (Exception $e) {
            \Log::info($e->getMessage(), [
                'action' => __METHOD__,
                'id' => $id
            ]);
            return redirect()->route('employee.index')->with(SESSION_ERROR, $e->getMessage());
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
        //prepare data
        $this->employeeService->prepareConfirmForUpdate($id, $request);

        $config = $this->config();
        $config['template'] = "dashboard.employee.update_confirm";

        return view('dashboard.layout', compact(['config', 'id']));
    }
    public function showUpdateConfirm()
    {
        // Check exists data
        if (!session()->has('employee_data')) {
            return redirect()->route('employee.index')->with(SESSION_ERROR, ERROR_ACCESS_DENIED);
        }

        $config = $this->config();
        $config['template'] = "dashboard.employee.update_confirm";

        return view('dashboard.layout', compact(['config']));
    }
    public function createConfirm(EmployeeCreateRequest $request)
    {
        //prepare data
        $this->employeeService->prepareConfirmForCreate($request);

        $config = $this->config();
        $config['template'] = "dashboard.employee.create_confirm";

        return view('dashboard.layout', compact(['config']));
    }
    public function showCreateConfirm()
    {
        // Check exists data
        if (!session()->has('employee_data')) {
            return redirect()->route('employee.create')->with(SESSION_ERROR, ERROR_ACCESS_DENIED);
        }

        $config = $this->config();
        $config['template'] = "dashboard.employee.create_confirm";

        return view('dashboard.layout', compact(['config']));
    }
    public function update(Request $request, $id)
    {
        try {
            $this->employeeService->update($id, $request->all());

            return redirect()->route('employee.index')->with(SESSION_SUCCESS, UPDATE_SUCCESS);
        } catch (Exception $e) {
            $this->fileService->removeFile($request->avatar);
            \Log::info(
                $e->getMessage(),
                [
                    'action' => __METHOD__,
                    'data' => array_merge(['id' => $id], $request->all())
                ]
            );
            return redirect()->route('employee.index')->with(SESSION_ERROR, $e->getMessage());
        }
    }
    public function create(Request $request)
    {
        try {
            $this->employeeService->create($request->all());

            return redirect()->route('employee.index')->with(SESSION_SUCCESS, CREATE_SUCCESS);
        } catch (Exception $e) {
            $this->fileService->removeFile($request->avatar);
            \Log::info(
                $e->getMessage(),
                [
                    'action' => __METHOD__,
                    'data' => request()->all()
                ]
            );
            return redirect()->route('employee.index')->with(SESSION_ERROR, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->employeeService->delete($id);

            return redirect()->route('employee.index')->with(SESSION_SUCCESS, DELETE_SUCCESS);
        } catch (Exception $e) {
            \Log::info(
                $e->getMessage(),
                [
                    'action' => __METHOD__,
                    'id' => $id
                ]
            );
            return redirect()->route('employee.index')->with(SESSION_ERROR, $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $ids = $request->input('ids'); // Get id list form request

        $this->employeeService->exportToCSV(explode(', ', $ids));
    }

    public function config()
    {
        return [
            'user' => Auth::user(),
            'template' => "dashboard.employee.index",
        ];
    }

}