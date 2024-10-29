<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Technologist;
use App\Models\Doctor;
use App\Models\EmployeeInfo;
use App\Models\Specimen;
use App\Models\AssignTechnologist;
class TechnologistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['technologist'] = Technologist::AuthCompany()->with(['technologistDoctor','technologistEmployee','specimen','testGroup','assignTechnologist'])->get();
        $data['employees'] = EmployeeInfo::AuthCompany()->select('first_name','last_name','id')->get();
        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->pluck('name','id')->toArray();
        $data['specimens'] = Specimen::AuthCompany()->where('status','active')->pluck('specimen','id')->toArray();

        // dd($data);
        return view('member.technologist.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employees'] = EmployeeInfo::AuthCompany()->select('first_name','last_name','id')->get();
        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->pluck('name','id')->toArray();
        $data['specimens'] = Specimen::AuthCompany()->where('status','active')->pluck('specimen','id')->toArray();
        // dd($data);
        return view('member.technologist.create',$data);
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

        $this->validate($request, $this->validationRules());
        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;



           $inputs['technologist_doctor_id'] = $request->technologist_doctor_id;
           $inputs['prepared_doctor_id'] = $request->prepared_doctor_id;
           $inputs['checked_by_doctor_id'] = $request->checked_by_doctor_id;

           $inputs['technologist_employeeinfo_id'] = $request->technologist_employeeinfo_id;
           $inputs['prepared_employeeinfo_id'] = $request->prepared_employeeinfo_id;
           $inputs['checked_by_employeeinfo_id'] = $request->checked_by_employeeinfo_id;

           $inputs['technologist_status'] = $request->technologist_status;
           $inputs['prepared_status'] = $request->prepared_status;
           $inputs['checked_by_status'] = $request->checked_by_status;

           $inputs['technologist_degree'] = $request->technologist_degree;
           $inputs['prepared_by_degree'] = $request->prepared_by_degree;
           $inputs['checked_by_degree'] = $request->checked_by_degree;

           $inputs['test_group_id'] = $request->test_group_id;
           $inputs['specimen_id'] = $request->specimen_id;


           if($request->file('technologist_signature')){
            $file = $request->file('technologist_signature');
            $filename = time().'0'. '.' . $request->file('technologist_signature')->extension();
            $filePath = public_path() . '/uploads/signature/';
            $file->move($filePath, $filename);
            $inputs['technologist_signature'] = $filename;
        }
           if($request->file('prepared_by_signature')){
            $file = $request->file('prepared_by_signature');
            $filename1 = time().'1'. '.' . $request->file('prepared_by_signature')->extension();
            $filePath = public_path() . '/uploads/signature/';
            $file->move($filePath, $filename1);
            $inputs['prepared_by_signature'] = $filename1;
        }
           if($request->file('checked_by_signature')){
            $file = $request->file('checked_by_signature');
            $filename2 = time().'2'. '.' . $request->file('checked_by_signature')->extension();
            $filePath = public_path() . '/uploads/signature/';
            $file->move($filePath, $filename2);
            $inputs['checked_by_signature'] = $filename2;
        }


            $data_status = Technologist::create($inputs);

            if ($data_status) {
                foreach ($request->sub_test_group_id as $k => $val) {

                    // dd($val);
                    $input['sub_test_group_id'] =  $val;
                    $input['technologist_id'] = $data_status->id;
                    $input['company_id'] = $company_id;
                    AssignTechnologist::create($input);
                }
            }


        $status = ['type' => 'success', 'message' => 'Successfully Added'];

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
        // return view('member.technologist.show',$data);
        $data['technologists'] = Technologist::AuthCompany()->where('id',$id)
        ->with(['technologistDoctor','technologistEmployee','preparedEmployee','preparedDoctor','checkedEmployee','checkedDoctor','specimen','testGroup','assignTechnologist'])
        ->first();

        // dd( $data['technologists'] );
        return view('member.technologist.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = Technologist::AuthCompany()->where('id',$id)->with(['technologistDoctor','technologistEmployee','specimen','testGroup','assignTechnologist'])->first();
        $data['employees'] = EmployeeInfo::AuthCompany()->select('first_name','last_name','id')->get();
        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->pluck('name','id')->toArray();
        $data['specimens'] = Specimen::AuthCompany()->where('status','active')->pluck('specimen','id')->toArray();
        $data['assignTechnologist_ids'] = AssignTechnologist::AuthCompany()->where('technologist_id',$id)->pluck('sub_test_group_id')->toArray();

        // dd($data['assignTechnologist_ids']);

        return view('member.technologist.edit',$data);
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

        $this->validate($request, $this->validationRules($id));
        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;
        $inputs['updated_by'] = $user_id;

        $data = Technologist::AuthCompany()->where('id',$id)->first();
        $delete_ids = AssignTechnologist::AuthCompany()->where('technologist_id',$id)->pluck('id')->toArray();
        // dd($request->all(),$data,$delete_ids);

        $inputs['technologist_signature'] = $data->technologist_signature;
        $inputs['checked_by_signature'] = $data->checked_by_signature;
        $inputs['prepared_by_signature'] = $data->prepared_by_signature;


           $inputs['technologist_doctor_id'] = $request->technologist_doctor_id;
           $inputs['prepared_doctor_id'] = $request->prepared_doctor_id;
           $inputs['checked_by_doctor_id'] = $request->checked_by_doctor_id;

           $inputs['technologist_employeeinfo_id'] = $request->technologist_employeeinfo_id;
           $inputs['prepared_employeeinfo_id'] = $request->prepared_employeeinfo_id;
           $inputs['checked_by_employeeinfo_id'] = $request->checked_by_employeeinfo_id;

           $inputs['technologist_status'] = $request->technologist_status;
           $inputs['prepared_status'] = $request->prepared_status;
           $inputs['checked_by_status'] = $request->checked_by_status;

           $inputs['technologist_degree'] = $request->technologist_degree;
           $inputs['prepared_by_degree'] = $request->prepared_by_degree;
           $inputs['checked_by_degree'] = $request->checked_by_degree;

           $inputs['test_group_id'] = $request->test_group_id;
           $inputs['specimen_id'] = $request->specimen_id;


           if($request->file('technologist_signature')){
            $file = $request->file('technologist_signature');
            $filename = time().'0'. '.' . $request->file('technologist_signature')->extension();
            $filePath = public_path() . '/uploads/signature/';
            $file->move($filePath, $filename);
            $inputs['technologist_signature'] = $filename;
        }
           if($request->file('prepared_by_signature')){
            $file = $request->file('prepared_by_signature');
            $filename1 = time().'1'. '.' . $request->file('prepared_by_signature')->extension();
            $filePath = public_path() . '/uploads/signature/';
            $file->move($filePath, $filename1);
            $inputs['prepared_by_signature'] = $filename1;
        }
           if($request->file('checked_by_signature')){
            $file = $request->file('checked_by_signature');
            $filename2 = time().'2'. '.' . $request->file('checked_by_signature')->extension();
            $filePath = public_path() . '/uploads/signature/';
            $file->move($filePath, $filename2);
            $inputs['checked_by_signature'] = $filename2;
        }


           $data_status = $data->update($inputs);

            if ($data_status) {
                AssignTechnologist::destroy($delete_ids);

                foreach ($request->sub_test_group_id as $k => $val) {

                    $input['sub_test_group_id'] =  $val;
                    $input['technologist_id'] = $id;
                    $input['company_id'] = $company_id;
                    AssignTechnologist::create($input);
                }
            }


        $status = ['type' => 'success', 'message' => 'Successfully Added'];

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function technologistActiveInactive($id)
    {
        $data = Technologist::AuthCompany()->where('id',$id)->first();
        if($data->status == 0){
            $data->update([
                'status'=>1,
            ]);
        }else{
            $data->update([
                'status'=>0,
            ]);
        }
        $status = ['type' => 'success', 'message' => 'Status Updated Successfully'];

        return back()->with('status', $status);
    }
    public function destroy($id)
    {
        $modal = Technologist::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }



    public function validationRules($id = '')
    {

        if($id){
            $rules = [
                'specimen_id' => 'required',
                'test_group_id' => 'required',
                'sub_test_group_id' => 'required',
                'technologist_status' => 'required',
                'checked_by_status' => 'required',
                'prepared_status' => 'required',
            ];
        }else{
            $rules = [
                'specimen_id' => 'required',
                'test_group_id' => 'required',
                'sub_test_group_id' => 'required',
                'technologist_status' => 'required',
                'checked_by_status' => 'required',
                'prepared_status' => 'required',

                'checked_by_signature' => 'required',
                'prepared_by_signature' => 'required',
                'technologist_signature' => 'required',
            ];
        }



        return $rules;
    }
}