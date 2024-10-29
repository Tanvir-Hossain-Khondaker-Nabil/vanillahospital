<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PathologyFinalReport;
use App\Models\OutDoorPatientTest;

class PathologyFinalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

            $inputs['created_by'] = $user_id;
            $inputs['company_id'] = $company_id;
            $inputs['sub_test_group_id'] = $request->sub_test_group_id;
            $inputs['out_door_registration_id'] = $request->out_door_registration_id;
            $inputs['out_door_patient_test_id'] = $request->out_door_patient_test_id;
            $inputs['description'] = $request->description;
            $status_is=  PathologyFinalReport::create($inputs);

        if($status_is){
            $patient_data = OutDoorPatientTest::find($request->out_door_patient_test_id);

            $patient_data->update([
                'report_making_status'=>1,
            ]);

            $status = ['type' => 'success', 'message' => 'Successfully Added'];

            // return back()->with('status', $status);
            return redirect()->route('member.fetch.pathology_list')->with('status', $status);
          
        }else{
            $status = ['type' => 'error', 'message' => 'Something Went Wrong'];
            return back()->with('status', $status);
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}