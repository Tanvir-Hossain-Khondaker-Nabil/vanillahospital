<?php

namespace App\Http\Controllers\Member;

use App\DataTables\EmployeeLeaveDataTable;
use App\Models\EmployeeInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\Auth;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(EmployeeLeaveDataTable $dataTable)
    {
        return $dataTable->render('member.employee_leaves.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employees'] = EmployeeInfo::get();

        return view('member.employee_leaves.create', $data);
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
            'employee_id' => 'required',
            'status' => 'required',
        ];

        $start_date =  db_date_format($request->start_date);
        $end_date =  db_date_format($request->end_date);

        $diff = $start_date->diffInDays($end_date);
        // dd($diff);



        $this->validate($request, $rules);

        $inputs = $request->all();
        $inputs['set_leave_id'] = EmployeeLeave::count().$request->employee_id;
        $inputs['company_id'] = Auth::user()->company_id;
        $inputs['created_by'] = Auth::user()->id;

        EmployeeLeave::create($inputs);

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
        $data['employee_leaves'] = EmployeeLeave::findOrFail($id);
        return view('member.employee_leaves.edit', $data);
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
        /*
         * TODO: Fiscal Year update will be check later
         */
        $fiscal = EmployeeLeave::findOrFail($id);

        $employee_leaves=explode('-', $request['employee_leaves']);

        $request['start_date']=db_date_format($employee_leaves[0]);
        $request['end_date']=db_date_format($employee_leaves[1]);
        $request['title'] = "FY (".formatted_date_string($request['start_date'])."-".formatted_date_string($request['end_date']).")";


        $inputs = $request->all();

        $fiscal->update($inputs);

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
        $modal = EmployeeLeave::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }
}