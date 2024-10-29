<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestGroup;
use App\Models\SubTestGroup;
use App\Models\PathologyReport;
use App\Models\OutDoorRegistration;
use App\Models\OutDoorPatientTest;
use App\Models\PathologyFinalReport;
use App\Models\AssignTechnologist;
use App\Http\Traits\CompanyInfoTrait;


class PathologyReportController extends Controller
{
    use CompanyInfoTrait;
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
        $this->validate($request, $this->validationRules());

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

        $check = PathologyReport::AuthCompany()->where('sub_test_group_id',$request->sub_test_group_id)->first();



         if(!$check){
            $inputs['created_by'] = $user_id;
            $inputs['company_id'] = $company_id;
            $inputs['sub_test_group_id'] = $request->sub_test_group_id;
            $inputs['description'] = $request->description;
            $status_is=  PathologyReport::create($inputs);
         }else{
            $inputs['updated_by'] = $user_id;
            $inputs['description'] = $request->description;

             $status_is =   $check->update($inputs);

         }


        if($status_is){
            $status = ['type' => 'success', 'message' => 'Successfully Added'];

            return back()->with('status', $status);
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
        // dd($id);
        $data['data'] = $info= PathologyFinalReport::AuthCompany()->with(['outDoorRegistration','subTestGroup'])->where('out_door_patient_test_id',$id)->first();
        $data['technologist'] = $technologist= AssignTechnologist::AuthCompany()->with('technologist')->where('sub_test_group_id',$info->sub_test_group_id)->first();

          $test_name = $info->subTestGroup->title;

        if(!$technologist){
            $status = ['type' => 'error', 'message' => 'Please add befor technologis for '.$test_name];
            return back()->with('status', $status);

        }else{

            $data = $this->company($data);
            // dd($data);
            return view('member.pathology.pathology_report_print',$data);
        }

    }

    public function multipleTestReportPrint(Request $request)
    {

        $data['ids'] = $ids_data= explode(",",$request->ids[0]);
        $data['patient_info'] = PathologyFinalReport::AuthCompany()->with(['outDoorRegistration','subTestGroup'])->where('out_door_patient_test_id',$ids_data[0])->first();
        
        // dd($data_is);
        $data = $this->company($data);

        return view('member.pathology.multiple_pathology_report_print',$data);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        //  $data['out_door_registration_id'] = OutDoorPatientTest::find($id)->value('out_door_registration_id');
         $data['patient_test'] = $info = OutDoorPatientTest::find($id);
         $data['models'] = SubTestGroup::AuthCompany()->where('id',$info->sub_test_group_id)->with(['testGroup','pathologyReport'])->first();
        //  dd($data);

        // dd($data);

        return view('member.pathology.edit',$data);
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


    public function createPathologyReport($id)
    {
        // dd($id);
        $data['models'] = SubTestGroup::AuthCompany()->where('id',$id)->with('testGroup')->first();

        return view('member.pathology.pathology_report_create',$data);
    }

    public function validationRules($id = '')
    {
        $rules = [
            'description' => 'required',
        ];


        return $rules;
    }

    public function fetchPathologyList()
    {

        $data['pathology_list'] = OutDoorRegistration::AuthCompany()->with('outdoorPatientTest')->where('barcode_status',1)->get();
        // dd($data);
        return view('member.pathology.pathology_list',$data);
    }
}