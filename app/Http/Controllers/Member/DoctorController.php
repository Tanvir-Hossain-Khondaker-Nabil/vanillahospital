<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Http\Controllers\Controller;
use App\Http\Traits\FileUploadTrait;
class DoctorController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['doctors'] = Doctor::AuthCompany()->get();

        return view('member.doctors.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.doctors.create');
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


        $inputs = $request->all();

        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '.' . $request->file('image')->extension();
            $filePath = public_path() . '/uploads/doctor/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }


        $inputs['marketing_officer_id'] = 0;
        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;
         Doctor::create($inputs);

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
        $data['model'] = Doctor::findOrFail($id);
        return view('member.doctors.edit', $data);
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
        $this->validate($request, $this->validationRules());

        $data = Doctor::findOrFail($id);
        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;


        $inputs = $request->all();

        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '.' . $request->file('image')->extension();
            $filePath = public_path() . '/uploads/doctor/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }


        $inputs['marketing_officer_id'] = 0;
        $inputs['updated_by'] = $user_id;
        $data->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated'];

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
        $modal = Doctor::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function validationRules($id = '')
    {

        $rules = [
            'name' => 'required',
            'degree' => 'required',
            'mobile' => 'required',
            'type' => 'required',
        ];

        return $rules;

   }

   public function fetchDoctor(Request $request)
    {

        $data = Doctor::AuthCompany()->where('id',$request->id)->first();
        $schedule = DoctorSchedule::AuthCompany()->where('doctor_id',$request->id)->with('scheduleDay')->first();
        // dd($schedule);
        return response()->json([
            'data' => $data,
            'schedule' => $schedule,
            'status' => 200,
        ]);


   }
}