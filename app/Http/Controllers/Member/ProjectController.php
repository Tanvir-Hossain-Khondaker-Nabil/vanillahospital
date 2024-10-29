<?php

namespace App\Http\Controllers\Member;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\{Country,
    Lead,
    ProjectCategory,
    Project,
    ProjectExpense,
    Role,
    Label,
    Client,
    Labeling,
    SupplierOrCustomer,
    Task,
    User,
    EmployeeInfo,
    EmployeeProject,
    Division,
    District,
    Area,
    QuotationCompany,
    TaskStatusHistory};

use App\Models\TaskEmployeeStatus;
use App\DataTables\ProjectDataTable;
use App\Http\Traits\FileUploadTrait;

class ProjectController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectDataTable $dataTable)
    {

        if (\Auth::user()->can(['member.project.index'])) {
            // dd('jkj');
            return $dataTable->render('member.project.index');

        } else if (\Auth::user()->hasRole(['project_manager'])) {

            $user = \Auth::user();
            $id = $user->employee->id;


            $data['tasks'] = EmployeeProject::where('employee_id', $id)->with(['project'])
                ->groupBy('project_id')->get();

            return view('member.project.manager-project-table', $data);

        } else if (\Auth::user()->hasRole(['user'])) {

            $user = \Auth::user();
            $id = $user->employee->id;

            $task_ids = TaskEmployeeStatus::where('employee_info_id', $id)->pluck('task_id')->toArray();
            $data['tasks'] = Task::whereIn('id',$task_ids)->with('project')->groupBy('project_id')->get();


            return view('member.project.member-project-table', $data);

        } else {

            return abort(404);
        }

    }

    public function UserProject()
    {

        $user = \Auth::user();
        $id = $user->employee->id;

        if (\Auth::user()->hasRole(['user'])) {

            $data['tasks'] = Task::where('employee_info_id', $id)
                ->with(['project'])->groupBy('project_id')->get();

            return view('member.project.member-project-table', $data);

        } else if (\Auth::user()->hasRole(['project_manager'])) {

            $data['tasks'] = EmployeeProject::where('employee_id', $id)
                ->with(['project'])->groupBy('project_id')->get();

            return view('member.project.manager-project-table', $data);
        } else {

            $data['tasks'] = [];

            return view('member.project.manager-project-table', $data);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'project_manager');
        })->pluck('id')->toArray();

        $data['employee'] = EmployeeInfo::whereIn('user_id', $users)->get()
            ->pluck('employee_name_id', 'id');

        $data['project_categories'] = ProjectCategory::where('status', 'active')
            ->pluck('display_name', 'id');

        $data['countries'] = Country::all()->pluck('countryName', 'id');
        $data['divisions'] = [];
        $data['districts'] = [];
        $data['areas'] = [];
        $data['client'] = Client::all()->pluck('name', 'id');
        $data['leads'] = Lead::all()->pluck('code_title', 'id');
        $data['brokers'] = SupplierOrCustomer::onlyBroker()->get()->pluck('name', 'id');
        $data['label'] = Label::where('label_type', 'project')->pluck('name', 'id');
        $data['progress_statuses'] = Project::get_progress_status();

        $data['companies'] = QuotationCompany::where('status', 1)->projectType()->pluck('company_name', 'id');
        $data['client_label'] = Label::where('label_type', 'client')->pluck('name', 'id');

        return view('member.project.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        $inputs['company_id'] = \Auth::user()->company_id;
        $inputs['created_by'] = \Auth::user()->id;
        // $request['project_category_id'] = $request->project_category_id;

        if ($request->hasFile('image')) {
            $id_photo = $request->file('image');

            $upload = $this->fileUpload($id_photo, '/project_image/', null);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Project Image Must be JPG'];
                return back()->withInput()->with('status', $status);
            }
            $inputs['image'] = $upload;

        }

        $project = Project::create($inputs);


        $employee_project = EmployeeProject::create([
            'project_id' => $project->id,
            'employee_id' => $request->employee_id,
        ]);

        $labels = $request->label_id ?? [];

        if ($project && isset($request->label_id)) {

            foreach ($labels as $id) {
                $label = Label::findOrFail($id);

                $labelingInputs = [];
                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $project->id;
                $labelingInputs['modal'] = 'Project';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);
            }

        }

        $status = ['type' => 'success', 'message' => 'Successfully Added.'];

        return back()->with('status', $status);
    }

    public function validationRules($id = '')
    {

        if ($id != '') {
            $rules['project'] = 'required|unique:projects,project,' . $id;

        } else {
            $rules['project'] = 'required|unique:projects,project';
        }

        $rules = [
            'price' => 'required|numeric',
            'address' => 'required',
            'country_id' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'expire_date' => 'required|date|after_or_equal:start_date',
            'long' => array('nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'),
            'lat' =>  array('nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'),
            'project_category_id' => 'required',
            'client_id' => 'required',
            'progress_status' => 'required',
            'status' => 'required'
        ];

        return $rules;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $search=false, $date='')
    {
        $today_date = date("Y-m-d");

        $project_data = Project::find($id);

        $project_start_date = Carbon::parse($project_data->start_date);
        $project_end_date = Carbon::parse($project_data->expire_date);

        $data['project_start_date'] = $project_start_date->format("Y/m/d");
        $data['project_end_date'] = $project_end_date->format("Y/m/d");

        $data['total_days_of_project'] = $total_days_of_project = $project_start_date->diffInDays($project_end_date)+1;

        if(Carbon::today()->format("Y-m-d") <= $project_end_date )
        {
            $data['complete_days_of_project'] = $complete_days_of_project =  Carbon::today()->diffInDays($project_start_date)+1;
            $data['due_days_of_project'] = Carbon::today()->diffInDays($project_end_date);
        }else{

            $data['complete_days_of_project'] = $complete_days_of_project =  $total_days_of_project;
            $data['due_days_of_project'] = 0;
        }

        // End time graph calculation
        $data['percentage_of_day'] = ceil(($complete_days_of_project / $total_days_of_project) * 100);
        $data['today_date'] = $today_date;


        $data['project_manager'] = EmployeeProject::where('project_id',$id)->with('employee')->groupBy('employee_id')->get();
        $data['project'] = Project::where('id', $id)
            ->with(['projectCategory', 'client', 'labeling', 'createdBy','task'])->first();

        $task_ids = Task::where('project_id',$id)->pluck('id');
        $task_date = $date ? $date : $today_date;


        $data['empployees'] = TaskEmployeeStatus::whereIn('task_id',$task_ids)->where('status', '!=', 'done')->with('employee')
            ->selectRaw('*,count(employee_info_id) as pending')
            ->groupBy('employee_info_id')->get();

        $gantt_chart =[];
        $total_tasks = $total_to_do = $total_in_progress = $total_review = $total_overdue = $total_done = 0;
        $total_low = $total_high = $total_medium = 0;


        foreach ($task_ids as $key => $value)
        {
            $getTask = TaskStatusHistory::where('task_id',$value)->whereDate('updated_at', "<=" ,$task_date)->orderBy('updated_at','desc')->first();
            if($getTask)
            {
                $taskOnDate[]=$getTask;
                $taskIdsDate[]=$getTask->task_id;
                $gantt_chart[$key] = $taskByID = Task::where('id', $getTask->task_id)->select( 'id','title as name','start_date','end_date', 'priority')->first()->toArray();;
                $gantt_chart[$key]['status'] = $getTask->status;

                $total_tasks++;
                if($taskByID['end_date']<date("Y-m-d") && $getTask->status != 'done')
                {
                    $total_overdue++;
                }else{
                    if ($getTask->status == 'review'){
                        $total_review++;
                    }elseif ($getTask->status == 'to_do'){
                        $total_to_do++;
                    }elseif ($getTask->status == 'in_progress'){
                        $total_in_progress++;
                    }elseif ($getTask->status == 'done'){
                        $total_done++;
                    }
                }

                if ($taskByID['priority'] == 'high'){
                    $total_high++;
                }elseif ($taskByID['priority'] == 'medium'){
                    $total_medium++;
                }else{
                    $total_low++;
                }

                unset($gantt_chart[$key]['task_image_path']);
                unset($gantt_chart[$key]['priority']);
            }
        }

//
//        $data['gantt_chart'] = Task::where('project_id',$id)->select( 'id','title as name','start_date','end_date', 'status')->get();
//        foreach($data['gantt_chart'] as $user) {
//            $user->makeHidden(['task_image_path']);
//        }
        $data['gantt_chart'] = json_encode($gantt_chart);


        $data['tasks'] = TaskEmployeeStatus::whereIn('task_id', $task_ids)->whereDate('updated_at', "<=",$task_date)->with(['employee','task'])->get();
        $data['total_to_do'] = $total_to_do;
        $data['total_in_progress'] = $total_in_progress;
        $data['total_review'] = $total_review;
        $data['total_done'] = $total_done;
        $data['total_overdue'] = $total_overdue;

        $data['total_tasks'] = $total_tasks;
        $data['total_complete'] = $data['total_done']>0 ? $data['total_done']*100/$data['total_tasks'] :0;
        $data['progress_status'] = $data['total_done'] == $total_tasks ? $project_data->progress_status : "Open";

        $data['total_low'] = $total_low;
        $data['total_high'] = $total_high;
        $data['total_medium'] = $total_medium;

        $data['total_expense'] = ProjectExpense::where('project_id', $id)->where('date',"<=", $task_date)->sum('total_amount');
        $data['total_budget'] = $project_data->price;

        if($search)
            return $data;
        else
            return view('member.project.show', $data);

    }

    public function singleProject(Request $request)
    {
        $data['total_to_do'] = count(Task::where('status', 'to_do')->where('project_id', $request->id)->get());
        $data['total_in_progress'] = count(Task::where('status', 'in_progress')->where('project_id', $request->id)->get());
        $data['total_review'] = count(Task::where('status', 'review')->where('project_id', $request->id)->get());
        $data['total_done'] = count(Task::where('status', 'done')->where('project_id', $request->id)->get());

        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);

    }

    public function project_wise_task($id)
    {
        $today_date = date("Y-m-d");
        $data['project'] = Project::findOrFail($id);


        $task_ids = Task::where('project_id',$id)->pluck('id');
        // dd($task_ids);
        $data['total_to_do'] = TaskEmployeeStatus::where('status', 'to_do')->whereIn('task_id', $task_ids)->count();
        $data['total_in_progress'] = TaskEmployeeStatus::where('status', 'in_progress')->whereIn('task_id', $task_ids)->count();

        $data['total_review'] = TaskEmployeeStatus::where('status', 'review')->whereIn('task_id', $task_ids)->count();
        $data['total_done'] = TaskEmployeeStatus::where('status', 'done')->whereIn('task_id', $task_ids)->count();

        $data['upcoming'] = TaskEmployeeStatus::whereIn('task_id', $task_ids)->with(['employee','task'=>function($q) use($today_date){
           $q->where('start_date','>', $today_date);
        }])->get();

        $data['overdue'] = TaskEmployeeStatus::whereIn('task_id', $task_ids)->where('status','!=', 'done')->with(['employee','task'=>function($q) use($today_date){
           $q->where('end_date','<', $today_date);
        }])->get();

        $data['complete'] = TaskEmployeeStatus::whereIn('task_id', $task_ids)->where('status', 'done')->with(['employee','task'])->get();

        return view('member.project.project_wise_task', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = $project = Project::findOrFail($id);
        $data['employee_id'] = $project->employee_project ? $project->employee_project->employee_id : '';

        $data['project_categories'] = ProjectCategory::all()->pluck('display_name', 'id');
        $data['client'] = Client::all()->pluck('name', 'id');
        $data['label'] = Label::where('label_type', 'project')->pluck('name', 'id');
        $data['progress_statuses'] = Project::get_progress_status();

        $data['countries'] = Country::all()->pluck('countryName', 'id');

        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['districts'] = District::active()->get()->pluck('name', 'id');
        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['companies'] = QuotationCompany::where('status', 1)->projectType()->pluck('company_name', 'id');
        $data['client_label'] = Label::where('label_type', 'client')->pluck('name', 'id');
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'project_manager');
        })->pluck('id')->toArray();

        $data['employee'] = EmployeeInfo::whereIn('user_id', $users)->get()
            ->pluck('employee_name_id', 'id');

        $data['labeling'] = Labeling::where('modal', 'Project')->where('modal_id', $id)
            ->pluck('label_id')->toArray();
        $data['brokers'] = SupplierOrCustomer::onlyBroker()->get()->pluck('name', 'id');
        $data['leads'] = Lead::all()->pluck('code_title', 'id');
        //  dd($data);
        return view('member.project.edit', $data);
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
        $this->validate($request, $this->validationRules($id));

        $project = Project::findOrFail($id);

        $inputs = $request->all();
        $inputs['updated_by'] = \Auth::user()->id;
        // $inputs['image'] = $project->image?? '';

        if ($request->hasFile('image')) {
            $id_photo = $request->file('image');

            $upload = $this->fileUpload($id_photo, '/project_image/', null);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Project Image Must be JPG'];
                return back()->with('status', $status);
            }
            $inputs['image'] = $upload;

        }

        $projectStatus = $project->update($inputs);

        $employee_project = EmployeeProject::create([
            'project_id' => $project->id,
            'employee_id' => $request->employee_id,
        ]);

        $labels = $request->label_id ?? [];

        if ($projectStatus && isset($request->label_id)) {

            Labeling::where('modal', 'Project')->where('modal_id', $project->id)->delete();

            foreach ($labels as $id) {
                $label = Label::findOrFail($id);

                $labelingInputs = [];
                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $project->id;
                $labelingInputs['modal'] = 'Project';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);
            }

        }

        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

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
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully Deleted',
            ]
        ], 200);
    }

    public function set_deadline(Request $request)
    {

        $deadline = find_end_date($request->date, $request->working_days);

        return response()->json([
            'data' => [
                'deadline' => $deadline,
                'status' => 200,
            ]
        ], 200);
    }
}
