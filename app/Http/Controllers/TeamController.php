<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamSearchRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Services\Services\TeamService;
use Auth;
use Exception;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private TeamService $teamService;
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }
    public function index(Request $request)
    {
        // dd($request->all());
        $teams = $this->teamService->findAllPaging();
        $config = $this->config();
        $filtered = array_filter(
            $request->all(),
            fn($value) => $value !== "" && $value !== null
        );

        if (!empty($filtered)) { // Chỉ gọi service nếu có dữ liệu tìm kiếm
            $teams = $this->teamService->search($filtered)->appends($request->query());
        }
        return view('dashboard.layout', compact(['config', 'teams']));
    }
    public function edit($id)
    {
        try {
            $team = $this->teamService->findById($id);
            $config = $this->config();
            $config['template'] = "dashboard.team.update";
            return view('dashboard.layout', compact(['config', 'team']));
        } catch (Exception $e) {
            return redirect()->route('team.index')->with('error', $e->getMessage());
        }
    }
    public function getCreateForm()
    {
        $config = $this->config();
        $config['template'] = "dashboard.team.create";
        return view('dashboard.layout', compact(['config']));
    }

    public function updateConfirm($id, TeamUpdateRequest $request)
    {
        $validatedData = $request->validated();
        session()->flash('team_data', $validatedData);
        $config = $this->config();
        $config['template'] = "dashboard.team.update_confirm";
        return view('dashboard.layout', compact(['config', 'id']));
    }
    public function showUpdateConfirm()
    {            

        // Kiểm tra session có dữ liệu hay không
        if (!session()->has('team_data')) {
            return redirect()->route('team.index')->with('error', "Can't go to this page directly");
        }

        $config = $this->config();
        $config['template'] = "dashboard.team.update_confirm";
        return view('dashboard.layout', compact(['config']));
    }
    public function createConfirm(TeamCreateRequest $request)
    {
        $validatedData = $request->validated();
        session()->flash('team_data', $validatedData);
        $config = $this->config();
        $config['template'] = "dashboard.team.create_confirm";
        return view('dashboard.layout', compact(['config']));
    }
    public function showCreateConfirm()
    {
        // Kiểm tra session có dữ liệu hay không
        if (!session()->has('team_data')) {
            return redirect()->route('team.create')->with('error', 'Please fill blank field');
        }

        $config = $this->config();
        $config['template'] = "dashboard.team.create_confirm";
        return view('dashboard.layout', compact(['config']));
    }

    public function update(Request $request, $id)
    {
        // sleep(10);
        try {
            $this->teamService->update($id, $request->all());
            return redirect()->route('team.index')->with('success', 'Update successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function create(Request $request)
    {
        try {
            $this->teamService->create($request->all());
            return redirect()->route('team.index')->with('success', 'Create successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->teamService->delete($id);
            return redirect()->route('team.index')->with('success', 'Delete successfully');
        } catch (Exception $e) {
            return redirect()->route('team.index')->with('error', $e->getMessage());
        }
    }

    public function config()
    {
        return [
            'user' => Auth::user(),
            'template' => "dashboard.team.index",
        ];
    }
}