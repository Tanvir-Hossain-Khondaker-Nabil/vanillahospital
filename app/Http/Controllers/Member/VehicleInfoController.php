<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\VehicleInfo;
use App\Models\Driver;

class VehicleInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['vehicle_infos'] = VehicleInfo::latest()->paginate(10);

        return view('member.vehicle_info.index',$data);
    }

    public function fatch(Request $request){
        if ($request->ajax()) {
            $data['vehicle_infos'] = VehicleInfo::latest()->paginate(10);

            return view('member.vehicle_info.table',$data);
        }
        
    }

    public function sorting($id){

            $data['vehicle_infos'] = VehicleInfo::limit($id)->latest()->paginate($id);

            return view('member.vehicle_info.table',$data);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.vehicle_info.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        VehicleInfo::create($request->all());
        
        $status = ['type' => 'success', 'message' => 'Successfully Added.'];
        return redirect()->route('member.vehicle_info.index', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleInfo $vehicleInfo)
    {
        return view ('member.vehicle_info.show', compact('vehicleInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VehicleInfo $vehicleInfo)
    {
        $drivers = Driver::pluck('name','id');
        return view('member.vehicle_info.create',compact('vehicleInfo','drivers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleInfo $vehicleInfo)
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

        $vehicleInfo->update($request->all());
        $status = ['type' => 'success', 'message' => 'Successfully Updated'];
        return redirect()->route('member.vehicle_info.index', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleInfo $vehicleInfo)
    {
        $vehicleInfo->delete();
        return redirect()->route('member.vehicle_info.index');
    }
}
