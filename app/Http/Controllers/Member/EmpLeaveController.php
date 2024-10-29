<?php

namespace App\Http\Controllers\Member;

use App\DataTables\EmpLeaveDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Models\EmpLeave;
use App\Models\EmployeeInfo;
use App\Models\Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmpLeaveController extends Controller
{

    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(EmpLeaveDataTable $dataTable)
    {
        return $dataTable->render('member.emp_leave.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employees']  = EmployeeInfo::get();
        $data['leaves']     = Leave::active()->get();

        return view('member.emp_leave.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'start_date' => 'required',
        ];

        $start_date =  db_date_format($request->start_date);

        $end_date = null;
        if(isset($request->end_date) && !empty($request->end_date))
            $end_date =  db_date_format($request->end_date);

        if(!\Auth::user()->hasRole(['user','project_manager']))
        {
            $rules ['emp_id'] = 'required';
        }


        $this->validate($request, $rules);

        $inputs = [];
        $inputs['emp_id'] = $request->emp_id ?? Auth::user()->employee->id;
        $inputs['leave_id'] = $request->leave_id;
        $inputs['start_date'] = $start_date;
        $inputs['end_date'] = $end_date;
        $inputs['l_note'] = $request->note;

        $inputs['status']     = $request->status ?? 3;

        $inputs['company_id'] = Auth::user()->company_id;


        if ($request->hasFile('attachment')) {

            $destinationPath = 'files';
            $upload = $this->fileUploadWithDetails($request->file('attachment'), $destinationPath);
            $file = $upload['file_store_path'] . "/" . $upload['file_name'];
            $inputs['attachment'] = $file;
        }


        EmpLeave::create($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Added.'];

        return back()->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['emp_leave'] = EmpLeave::findOrFail($id);

        $data['employees']  = EmployeeInfo::get();
        $data['leaves']     = Leave::active()->get();

        return view('member.emp_leave.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $emp_leaves = EmpLeave::findOrFail($id);

        $rules = [
            'start_date' => 'required',
        ];

        $start_date =  db_date_format($request->start_date);

        $end_date = null;
        if(isset($request->end_date) && !empty($request->end_date))
            $end_date =  db_date_format($request->end_date);


        if(!\Auth::user()->hasRole(['user','project_manager']))
        {
            $rules ['emp_id'] = 'required';
        }

        $this->validate($request, $rules);

        $inputs = [];
        $inputs['emp_id']     = $request->emp_id ?? Auth::user()->employee->id;
        $inputs['leave_id']   = $request->leave_id;
        $inputs['start_date'] = $start_date;
        $inputs['end_date']   = $end_date;
        $inputs['l_note']     = $request->note;

        $inputs['status']     = $request->status ?? 3;

        if ($request->hasFile('attachment')) {

            $destinationPath = 'files';
            $upload = $this->fileUploadWithDetails($request->file('attachment'), $destinationPath);
            $file = $upload['file_store_path'] . "/" . $upload['file_name'];
            $inputs['attachment'] = $file;
        }


        $emp_leaves->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = EmpLeave::findOrFail($id);
        $modal->update(['status'=>0]);

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }
}
