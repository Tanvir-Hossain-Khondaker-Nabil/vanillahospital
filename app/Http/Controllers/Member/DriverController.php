<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    
    {
        $data['drivers'] = Driver::latest()->get();

        return view('member.driver.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.driver.create');
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

        Driver::create($request->all());
        $status = ['type' => 'success', 'message' => 'Successfully Added.'];
        return redirect()->route('member.driver.index', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        return view ('member.driver.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        return view('member.driver.create',compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
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

        $driver->update($request->all());
        $status = ['type' => 'success', 'message' => 'Successfully Updated'];
        return redirect()->route('member.driver.index', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('member.driver.index');
    }
}
