<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\VehicleInfo;
use App\Models\VehicleSchedule;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class VehicleScheduleContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['vehicle_schedules'] = VehicleSchedule::latest()->get();

        return view('member.vehicle_schedule.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicleInfos = VehicleInfo::where('status', 1)->pluck('model_no', 'id');
        $drivers = Driver::pluck('name', 'id');
        $vehicle_schedules = VehicleSchedule::latest()->get();
        return view('member.vehicle_schedule.create', compact('drivers', 'vehicle_schedules', 'vehicleInfos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {




        $vehicleSchedule = VehicleSchedule::count();


        if ($vehicleSchedule > 0) {

            $vehicle_schedules = VehicleSchedule::where('driver_id', $request->driver_id)->get();


            foreach ($vehicle_schedules as $vehicle_schedule) {


                $r_start_time = date('H:i', strtotime($request->start_time));
                $v_start_time = date('H:i', strtotime($vehicle_schedule->start_time));
                $v_end_time = date('H:i', strtotime($vehicle_schedule->end_time));
                $r_end_time = date('H:i', strtotime($request->end_time));



                if ($r_start_time > $v_start_time && $r_start_time < $v_end_time) {
                    return redirect()->back();
                }

                if ($r_end_time > $v_start_time && $r_end_time < $v_end_time) {
                    return redirect()->back();
                }

                if ($r_start_time == $v_start_time && $r_end_time == $v_end_time) {
                    return redirect()->back();
                }
            }
        }

        VehicleSchedule::create($request->all());




        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VehicleSchedule $vehicleSchedule)
    {
        $drivers = Driver::pluck('name', 'id');
        $vehicleInfos = VehicleInfo::where('status', 1)->pluck('model_no', 'id');
        $vehicle_schedules = VehicleSchedule::latest()->get();
        return view('member.vehicle_schedule.create', compact('vehicleSchedule', 'drivers', 'vehicleInfos', 'vehicle_schedules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleSchedule $vehicleSchedule)
    {
        // $this->validate($request, [
        //     'newborn_id_no'=>'required',
        //     'serial_no'=>'required',
        //     'name'=>'required', 
        //     'sex'=>'required',
        //     'date_and_time_of_birth'=>'required',
        //     'mother_s_id_no'=>'required',
        //     'mother_s_name'=>'required',
        //     'mother_s_nationality'=>'required',
        //     'mother_s_religion'=>'required',
        //     'father_s_id_no'=>'required',
        //     'father_s_name'=>'required',
        //     'father_s_nationality'=>'required',
        //     'father_s_religion'=>'required',
        //     'present_address'=>'required',
        //     'permanent_address'=>'required',
        // ]);

        $vehicleSchedule->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleSchedule $vehicleSchedule)
    {
        $vehicleSchedule->delete();
        return redirect()->back();
    }
}
