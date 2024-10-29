<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleDay;
use App\Models\Doctor;
use App\Http\Controllers\Controller;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['doctor_schedule'] = DoctorSchedule::AuthCompany()->with(['scheduleDay','doctor'])->get();
        // dd($data);
        return view('member.doctor_schedule.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['doctor'] = Doctor::AuthCompany()->where('status','active')->get()->pluck('name','id');
        $data['days'] = get_daysOfWeek();

        return view('member.doctor_schedule.create',$data);
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

        $check = DoctorSchedule::AuthCompany()->where('doctor_id',$request->doctor_id)->first();
        if(!$check){
            $user_id = \Auth::user()->id;
            $company_id = \Auth::user()->company_id;


            $inputs['doctor_id'] = $request->doctor_id;
            $inputs['time_per_patient'] = $request->time_per_patient;


            $inputs['created_by'] = $user_id;
            $inputs['company_id'] = $company_id;
             $status =  DoctorSchedule::create($inputs);


                foreach($request->start_time as $k=> $time){
                    DoctorScheduleDay::create([
                        'doctor_schedule_id' =>  $status->id,
                        'start_time' =>  $request->start_time[$k],
                        'end_time' =>  $request->end_time[$k],
                        'day_name' => $request->day_name,
                      ]);
                }

        }else{

                foreach($request->start_time as $k=> $time){
                    $check_day = DoctorScheduleDay::where('doctor_schedule_id',$check->id)
                                                    ->where('start_time',$request->start_time[$k])
                                                    ->where('end_time',$request->end_time[$k])
                                                    ->where('day_name',$request->day_name)->first();
                        
                    if(!$check_day){
                        DoctorScheduleDay::create([
                            'doctor_schedule_id' =>  $check->id,
                            'start_time' =>  $request->start_time[$k],
                            'end_time' =>  $request->end_time[$k],
                            'day_name' => $request->day_name,
                          ]);


                    }else{
                        $check_day->update([
                            'start_time' =>  $request->start_time[$k],
                            'end_time' =>  $request->end_time[$k],
                        ]);
                    }

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
        // dd($id);
        $data['doctor_schedule'] = DoctorSchedule::AuthCompany()->where('id',$id)->with(['scheduleDay','doctor'])->first();
        //   dd($data);
        return view('member.doctor_schedule.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = DoctorSchedule::findOrFail($id);
        $data['days'] = DoctorScheduleDay::where('doctor_schedule_id',$id)->get()->pluck('day_name','id');
        $data['doctor'] = Doctor::AuthCompany()->where('status','active')->get()->pluck('name','id');

        //  dd($data);
        return view('member.doctor_schedule.edit',$data);
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
        $modal = DoctorSchedule::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function doctorScheduleDayEdit(Request $request)
    {
        // dd($request->schedule,$request->id);
        $id = $request->id;
        $schedule = $request->schedule;
        $result = DoctorSchedule::where('id',$schedule)->with(['scheduleDay'])->first();
        $data['days_is'] =  $result->scheduleDay->pluck('day_name')->unique();
        // dd( $days_is);

        $data['model'] = DoctorSchedule::where('id',$schedule)->with(['scheduleDay' => function($q) use($id) {
            $q->where('id',$id);
        }])->first();

        $data['days'] = DoctorScheduleDay::where('doctor_schedule_id',$id)->get()->pluck('day_name','id');
        $data['doctor'] = Doctor::AuthCompany()->where('status','active')->get()->pluck('name','id');
        // dd($data);

        return view('member.doctor_schedule.day_edit',$data);
    }

    public function validationRules($id = '')
    {

        $rules = [
            'doctor_id' => 'required',
            'end_time' => 'required',
            'start_time' => 'required',
            // 'start_time' => 'after:end_time|required',
        ];

        return $rules;

   }
}