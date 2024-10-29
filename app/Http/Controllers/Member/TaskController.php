<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{EmployeeInfo, Project, Task, Label, Labeling, User, EmployeeProject, TaskEmployeeStatus};
use App\Models\TaskStatusHistory;
use App\Models\TaskComment;
use App\DataTables\TaskDataTable;
use App\Http\Traits\FileUploadTrait;

class TaskController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TaskDataTable $dataTable)
    {
        // dd('fff');
        if (\Auth::user()->hasRole(['user', 'project_manager'])) {
            $user = \Auth::user();
            $employee_id = $user->employee->id;
        }


        $company_id = \Auth::user()->company_id;

        $data = $this->findTaskStatusByCompanyId($company_id);

        // dd($data);
        if (\Auth::user()->can(['member.task.index']) && !\Auth::user()->hasRole(['project_manager', 'user'])) {
            // dd('task');
            return $dataTable->render('member.task.index', $data);

        } else if (\Auth::user()->hasRole(['user'])) {

            $data['tasks'] = Task::where('employee_info_id', $employee_id)->with(['project'])
                ->orderBy('created_at', 'desc')->get();

            return view('member.task.member-task-table', $data);

        } else if (\Auth::user()->hasRole(['project_manager'])) {

            $project_ids = EmployeeProject::where('employee_id', $employee_id)->get()->pluck('project_id');
            $task_ids = Task::whereIn('project_id', $project_ids)->get()->pluck('id');
            $data['tasks'] = TaskStatusHistory::whereIn('task_id', $task_ids)->with(['employee', 'task'])->orderBy('task_id', 'desc')->get();


            return view('member.task.manager-task-table', $data);
        }

    }

    public function findTaskStatusByCompanyId($company_id)
    {

        $data['to_do'] = TaskEmployeeStatus::where('status', 'to_do')
            ->with(['task' => function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            }])->get();

        $data['in_progress'] = TaskEmployeeStatus::where('status', 'in_progress')
            ->with(['task' => function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            }])->get();

        $data['review'] = TaskEmployeeStatus::where('status', 'review')
            ->with(['task' => function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            }])->get();

        $data['done'] = TaskEmployeeStatus::where('status', 'done')
            ->with(['task' => function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            }])->get();

        return $data;
    }

    public function user_task()
    {
        $id = \Auth::user()->id;
        $data['profile'] = $profile = User::where('id', $id)->with(['employee'])->first();
        $employee_id = $profile->employee->id;

        if (\Auth::user()->hasRole(['user'])) {
        //    dd('kkk');
            $data['tasks'] = TaskEmployeeStatus::where('employee_info_id', $employee_id)->with(['task'])
                ->orderBy('created_at', 'desc')->get();

            return view('member.task.member-task-table', $data);

        } else if (\Auth::user()->hasRole(['project_manager'])) {
            $data['tasks'] = EmployeeProject::where('employee_id', $employee_id)
                ->with(['project'])->get();

            return view('member.task.manager-task-table', $data);
        }

    }

    public function UserKanbanList($project_id)
    {

        // dd($data);
        if (\Auth::user()->hasRole(['user'])) {
            // dd($data);
            $data = $this->findUserTaskStatusByProjectId($project_id);
            //  dd($data);
            return view('member.task.user-kanban', $data);
        } else {

            $data = $this->findTaskStatusByProjectId($project_id);
            return view('member.task.admin_manager_kanaban', $data);
        }


    }

    public function findUserTaskStatusByProjectId($project_id)
    {
        $user = \Auth::user();
        $employee_id = $user->employee->id;
        $company_id = \Auth::user()->company_id;
        $data['project'] = Project::where('id', $project_id)->select('project')->first();

        $to_do = TaskEmployeeStatus::where('status', 'to_do');



        // $task_ids = Task::where('project_id',$project_id)->get()->pluck('id');

        // $data['to_do'] = TaskEmployeeStatus::whereIn('task_id',$task_ids)->where('employee_info_id',$employee_id)->where('status','to_do')->with(['task'])->get();
        // $data['review'] = TaskEmployeeStatus::whereIn('task_id',$task_ids)->where('employee_info_id',$employee_id)->where('status','review')->with(['task'])->get();
        // dd($task_ids,$task_empl);


        if (\Auth::user()->hasRole(['user'])) {

            $data['to_do'] = $to_do->where('employee_info_id', $employee_id);
        }

        $data['to_do'] = $to_do->with(['task'])
            ->whereHas('task', function ($q) use ($company_id, $project_id) {
                $q->where('company_id', $company_id)->where('project_id', $project_id);
            })
            ->get();

        $in_progress = TaskEmployeeStatus::where('status', 'in_progress');

        if (\Auth::user()->hasRole(['user'])) {
            $in_progress = $in_progress->where('employee_info_id', $employee_id);
        }

        $data['in_progress'] = $in_progress->with(['task'])
            ->whereHas('task', function ($q) use ($company_id, $project_id) {
                $q->where('company_id', $company_id)->where('project_id', $project_id);
            })
            ->get();


        $review = TaskEmployeeStatus::where('status', 'review');

        if (\Auth::user()->hasRole(['user'])) {
            $review = $review->where('employee_info_id', $employee_id);
        }

        $data['review'] = $review->with(['task'])
            ->whereHas('task', function ($q) use ($company_id, $project_id) {
                $q->where('company_id', $company_id)->where('project_id', $project_id);
            })
            ->get();


        $done = TaskEmployeeStatus::where('status', 'done');

        if (\Auth::user()->hasRole(['user'])) {
            $done = $done->where('employee_info_id', $employee_id);
        }

        $data['done'] = $done->with(['task'])
            ->whereHas('task', function ($q) use ($company_id, $project_id) {
                $q->where('company_id', $company_id)->where('project_id', $project_id);
            })
            ->get();

        return $data;

    }


    public function findTaskStatusByProjectId($project_id)
    {

        $user = \Auth::user();

        $company_id = \Auth::user()->company_id;
        $data['project'] = Project::where('id', $project_id)->select('project')->first();

        $to_do = Task::where('status', 'to_do');

        if (\Auth::user()->hasRole(['user'])) {
            $employee_id = $user->employee->id;
            // dd($employee_id,$project_id);
            $data['to_do'] = $to_do->where('employee_info_id', $employee_id);
        }

        $data['to_do'] = $to_do->where('company_id',$company_id)->where('project_id',$project_id)->with(['project'])->get();


        $in_progress = Task::where('status', 'in_progress');

        if (\Auth::user()->hasRole(['user'])) {
            $in_progress = $in_progress->where('employee_info_id', $employee_id);
        }

        $data['in_progress'] = $in_progress->where('company_id',$company_id)->where('project_id',$project_id)->with(['project'])->get();


        $review = Task::where('status', 'review');

        if (\Auth::user()->hasRole(['user'])) {
            $review = $review->where('employee_info_id', $employee_id);
        }
        $data['review'] = $review->where('company_id',$company_id)->where('project_id',$project_id)->with(['project'])->get();


        $done = Task::where('status', 'done');

        if (\Auth::user()->hasRole(['user'])) {
            $done = $done->where('employee_info_id', $employee_id);
        }

        $data['done'] = $done->where('company_id',$company_id)->where('project_id',$project_id)->with(['project'])->get();



        return $data;
    }

    // public function store(Request $request)
    // {
    //     $this->validate($request, $this->ValidationRules());

    //     $inputs = $request->all();
    //     $inputs['created_by'] = \Auth::user()->id;
    //     $inputs['company_id'] = \Auth::user()->company_id;

    //     if ($request->hasFile('image')) {
    //         $id_photo = $request->file('image');

    //         $upload = $this->fileUpload($id_photo, '/task_image/', null);

    //         if (!$upload) {
    //             $status = ['type' => 'danger', 'message' => 'Client Image Must be JPG'];
    //             return back()->with('status', $status);
    //         }
    //         $inputs['image'] = $upload;

    //     }

    //     $leadLabels = $request->label_id ?? [];
    //     $task = Task::create($inputs);

    //     if ($task) {

    //         foreach ($leadLabels as $id) {
    //             $label = Label::findOrFail($id);

    //             $labelingInputs['label_id'] = $label->id;
    //             $labelingInputs['modal_id'] = $task->id;
    //             $labelingInputs['modal'] = 'Task';
    //             $labelingInputs['created_by'] = \Auth::user()->id;
    //             $labelingInputs['company_id'] = \Auth::user()->company_id;

    //             Labeling::create($labelingInputs);
    //         }

    //     }

    //     $status = ['type' => 'success', 'message' => 'Successfully Added'];

    //     return back()->with('status', $status);

    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = '')
    {

        $data['project_id'] = $id;

        $data['project'] = [];
        if (\Auth::user()->can(['member.task.create'])) {
            $data['project'] = Project::get()->pluck('project', 'id');
        }

        if (\Auth::user()->hasRole(['project_manager'])) {
            $user = \Auth::user();
            $user_id = $user->employee->id;
            if (!empty($id)) {
                $data['project'] = Project::where('id', $id)->get()->pluck('project', 'id');

            } else {
                $data['project'] = Project::whereIn('id', EmployeeProject::where('employee_id', $user_id)->pluck('project_id'))->get()->pluck('project', 'id');
            }
            //    dd('ppppp');
        }

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->pluck('id')->toArray();

        $data['employees'] = EmployeeInfo::whereIn('user_id', $users)->get()
            ->pluck('employee_name_id', 'id');

        $data['statuses'] = Task::get_statuses();

        $data['label'] = Label::where('label_type', 'task')->pluck('name', 'id');
        //  dd($data);
        return view('member.task.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, $this->ValidationRules());

        $inputs = $request->all();
        $inputs['employee_info_id'] = '';
        $inputs['created_by'] = \Auth::user()->id;
        $inputs['company_id'] = \Auth::user()->company_id;
        // dd($inputs);
        if ($request->hasFile('image')) {
            $id_photo = $request->file('image');

            $upload = $this->fileUpload($id_photo, '/task_image/', null);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Client Image Must be JPG'];
                return back()->with('status', $status);
            }
            $inputs['image'] = $upload;

        }

        $task = Task::create($inputs);

        $leadLabels = $request->label_id ?? [];

        if ($task) {
            //sotre employee task

            foreach ($request->employee_info_id as $id) {

                $taskInputs['employee_info_id'] = $id;
                $taskInputs['task_id'] = $task->id;
                $taskInputs['status'] = $request->status;

                TaskEmployeeStatus::create($taskInputs);
                TaskStatusHistory::create($taskInputs);
            }

            //sotre label
            foreach ($leadLabels as $id) {
                $label = Label::findOrFail($id);

                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $task->id;
                $labelingInputs['modal'] = 'Task';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);
            }

        }

        $status = ['type' => 'success', 'message' => 'Successfully Added'];

        return back()->with('status', $status);

    }

    private function ValidationRules($id = '')
    {
        $rules = [
            'title' => 'required',
            'project_id' => 'required',
            'employee_info_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        return $rules;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function editTaskStatus(Request $request)
    {
        $data = TaskEmployeeStatus::where('task_id', $request->id);

        if (\Auth::user()->hasRole(['user'])) {
            $user = \Auth::user();
            $employee_id = $user->employee->id;

            $data = $data->where('employee_info_id', $employee_id);
        }

        $data->update(['status' => $request->status]);

        $inputs = [];
        $inputs['task_id'] = $request->id;
        $inputs['status'] = $request->status;
        $inputs['employee_info_id'] = $employee_id;
        TaskStatusHistory::create($inputs);

        return response()->json([
            'status' => 200,
        ]);
    }

    public function show_task(Request $request)
    {
        // return $request->id;
        $data = Task::where('id', $request->id)->with('project')->first();
        $admin_comment = TaskEmployeeStatus::where('task_id',$request->id)->groupBy('task_id')->first();
        $task_assigned_emp_ids = TaskEmployeeStatus::where('task_id', $request->id)->get()->pluck('employee_info_id');

        $assigned_to = TaskEmployeeStatus::where('task_id', $request->id)->with('employee')->get();
        $commets = TaskComment::where('task_id', $request->id)->with('employee')
            ->orderBy('created_at', 'DESC')->get();

        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })->pluck('id')->toArray();

        $employees = EmployeeInfo::whereNotIn('id', $task_assigned_emp_ids)->get()
                ->pluck('employee_name_id', 'id');
                // return $employees;
        $employee_id = '';
        if (\Auth::user()->hasRole(['user'])) {
            $user = \Auth::user();
            $employee_id = $user->employee->id;
        }
        if ($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
                'allComment' => $commets,
                'assigned_to' => $assigned_to,
                'employees' => $employees,
                'employee_id' => $employee_id,
                'admin_comment' => $admin_comment,
            ]);
        } else {
            return response()->json([
                'status' => 400,
            ]);
        }


    }

    public function change_task_status(Request $request)
    {

        $taskData = Task::find($request->id);
        $data = TaskEmployeeStatus::where('task_id', $request->id);

        if (\Auth::user()->hasRole(['user'])) {
            $user = \Auth::user();
            $employee_id = $user->employee->id;

            $data = $data->where('employee_info_id', $employee_id);
        }


        if (isset($request->comments)) {

            $update = [
                'comments' => $request->comments,
                'updated_by' => \Auth::user()->id,
            ];
        }

        $update['status'] =  $request->status;
        $tasks = $data->update($update);

        if (!\Auth::user()->hasRole(['user'])) {
            $taskData->update([
                'status'=>$request->status,
            ]);
        }

        foreach ($data->get() as $value)
        {
            $inputs = [];
            $inputs['task_id'] = $value->task_id;
            $inputs['status'] = $request->status;
            $inputs['employee_info_id'] = $value->employee_info_id;

            TaskStatusHistory::create($inputs);
        }

        return response()->json([
            'status' => 200,
        ]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $model = Task::findOrFail($id);
        $data['label'] = Label::where('label_type', 'task')->pluck('name', 'id');
        $data['labeling'] = Labeling::where('modal', 'Task')->where('modal_id', $id)
            ->pluck('label_id')->toArray();

        $data['task_employees'] = $model->taskEmployeeStatus->pluck('employee_info_id')->toArray('employee_info_id');

        $data['project'] = [];
        if (\Auth::user()->can(['member.task.create'])) {
            $data['project'] = Project::get()->pluck('project', 'id');
        }

        if (\Auth::user()->hasRole(['project_manager'])) {
            $user = \Auth::user();
            $user_id = $user->employee->id;

            $data['project'] = Project::whereIn('id', EmployeeProject::where('employee_id', $user_id)->pluck('project_id'))->get()->pluck('project', 'id');

        }

        // $employee_info_ids = TaskEmployeeStatus::where('task_id', $id)->pluck('employee_info_id')->toArray();


        // $data['employees'] = EmployeeInfo::whereIn('id', $employee_info_ids)->get()
        //     ->pluck('employee_name_id', 'id');

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->pluck('id')->toArray();
        //   dd($id);
        $employee_ids = TaskEmployeeStatus::where('task_id',$id)->get()->pluck('employee_info_id');

        $data['select_employee'] = EmployeeInfo::whereIn('id', $employee_ids)->get()
            ->pluck('id');

        $data['employees'] = EmployeeInfo::whereIn('user_id', $users)->get()
            ->pluck('employee_name_id', 'id');

        $data['statuses'] = Task::get_statuses();
        //  dd($data);
        return view('member.task.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->ValidationRules($id));

        $task = Task::findOrFail($id);
        $inputs = $request->all();
        $inputs['employee_info_id'] = '';
        $inputs['updated_by'] = \Auth::user()->id;


        if ($request->hasFile('image')) {
            $id_photo = $request->file('image');

            $upload = $this->fileUpload($id_photo, '/task_image/', null);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Client Image Must be JPG'];
                return back()->with('status', $status);
            }
            $inputs['image'] = $upload;

        }

        $taskStatus = $task->update($inputs);

        $leadLabels = $request->label_id ?? [];

        if ($taskStatus) {

            Labeling::where('modal', 'Task')->where('modal_id', $task->id)->delete();

            foreach ($leadLabels as $id) {

                $label = Label::findOrFail($id);

                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $task->id;
                $labelingInputs['modal'] = 'Task';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);
            }

        }
        $status = ['type' => 'success', 'message' => 'Successfully Updated'];

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully Deleted',
            ]

        ], 200);
    }

    public function addTaskComment(Request $request)
    {
        $user = \Auth::user();
        $input = $request->all();
        $input['employee_info_id'] = $user->employee->id;
        $status = TaskComment::create($input);

        if ($status) {
            $allComment = TaskComment::where('task_id', $request->task_id)->with('employee')->orderBy('created_at', 'DESC')->get();
            return response()->json([
                'allComment' => $allComment,
                'status' => 200,
            ]);
        }

    }

    public function DeleteTaskComment(Request $request)
    {

        $data = TaskComment::find($request->id);
        $data->delete();

        return response()->json([
            // 'allComment'=> $allComment,
            'status' => 200,
        ]);

    }

    public function assignEmployeeToTask(Request $request)
    {
        //  return $request->all();
         foreach ($request->employee_ids as $id) {

            $taskInputs['employee_info_id'] = $id;
            $taskInputs['task_id'] = $request->task_id;
            $taskInputs['status'] = 'to_do';

            TaskEmployeeStatus::create($taskInputs);
            TaskStatusHistory::create($taskInputs);
        }

        return response()->json([
            // 'allComment'=> $allComment,
            'status' => 200,
        ]);

    }
}
