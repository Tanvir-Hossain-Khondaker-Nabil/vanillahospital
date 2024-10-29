<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Illuminate\Support\Facades\Auth;
use App\Models\{Project, Task, User, EmployeeProject};
use App\Models\TaskStatusHistory;
use App\Models\TaskEmployeeStatus;
use App\Models\SalaryManagement;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole(['user']))
            return $this->employee();
        elseif (Auth::user()->hasRole(['project_manager']))
            return $this->project_manager();
        else
            return view('member.dashboard.index');

    }

    public function employee()
    {

        $user = \Auth::user();
        $employee_id = $user->employee->id;
        $task_id = TaskEmployeeStatus::where('employee_info_id',$employee_id)->groupBy('task_id')->pluck('task_id');
        $project_ids = Task::whereIn('id', $task_id)->groupBy('project_id')->pluck('project_id');
        $task_ids = Task::whereIn('project_id',$project_ids)->get()->pluck('id');
//         dd($project_ids, $task_id);
        $data['total_project'] =  Project::whereIn('id', $project_ids)->count();
        $data['total_project_assigned'] =  Project::whereIn('id', $project_ids)->where('complete_status','!=', 'complete')->count();


        $data['total_task'] = TaskEmployeeStatus::where('employee_info_id',$employee_id)->whereIn('task_id', $task_ids)->count();
        $data['total_pending_task'] = TaskEmployeeStatus::where('employee_info_id',$employee_id)->whereIn('task_id', $task_ids)->where('status', '!=', 'done')->count();
        $data['total_complete_task'] = TaskEmployeeStatus::where('employee_info_id',$employee_id)->whereIn('task_id', $task_ids)->where('status', 'done')->count();

        $data['total_complete_project'] = Project::whereIn('id', $project_ids)->where('complete_status', 'complete')->count();

        $data['salary_unpaid'] = SalaryManagement::where("emp_id", $employee_id)->where('sign', 0)
                                                ->sum('net_payable');

        $data['unpaid_months'] = SalaryManagement::where("emp_id", $employee_id)->where('sign', 0)->count();

        $data['salary'] = SalaryManagement::where("emp_id", $employee_id)
                                            ->orderBy("id", 'desc')->limit(6)->get();

        $data['last_salary'] = SalaryManagement::where("emp_id", $employee_id)->where('sign', 1)
                                                ->orderBy("id", 'desc')->first();


        // dd($project_ids);
        return view('member.dashboard.employee', $data);
    }

    public function project_manager()
    {

        $user = \Auth::user();
        $employee_id = $user->employee->id;

        $project_ids = EmployeeProject::where('employee_id', $employee_id)->get()->pluck('project_id');
        $task_ids = Task::whereIn('project_id',$project_ids)->get()->pluck('id');
        $data['total_project'] = Project::count();
        $data['total_assigned'] = EmployeeProject::where('employee_id', $employee_id)->count();
        $data['total_task'] = TaskStatusHistory::whereIn('task_id', $task_ids)->count();
        $data['total_pending'] = TaskStatusHistory::whereIn('task_id', $task_ids)->where('status', '!=', 'done')->count();
        $data['total_complete'] = TaskStatusHistory::whereIn('task_id', $task_ids)->where('status', 'done')->count();
        $data['total_complete_project'] = Project::whereIn('id', $project_ids)->where('complete_status', 'complete')->count();

        $data['salary_unpaid'] = SalaryManagement::where("emp_id", $employee_id)->where('sign', 0)
            ->sum('net_payable');

        $data['unpaid_months'] = SalaryManagement::where("emp_id", $employee_id)->where('sign', 0)->count();

        $data['salary'] = SalaryManagement::where("emp_id", $employee_id)
            ->orderBy("id", 'desc')->limit(6)->get();

        $data['last_salary'] = SalaryManagement::where("emp_id", $employee_id)->where('sign', 1)
            ->orderBy("id", 'desc')->first();

        return view('member.dashboard.project_manager', $data);
    }

    public function project()
    {
        return view('member.dashboard.project');
    }

//     public function index(){
//        \Session::put('sidebar_menu', 'user');
//         return view('member.dashboard.index');
//    }
}
