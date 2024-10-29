<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeathCertificate;

class DeathCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['death_certificates'] = DeathCertificate::latest()->get();

        return view('member.death_certificate.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.death_certificate.create');
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

        DeathCertificate::create($request->all());
        $status = ['type' => 'success', 'message' => 'Successfully Added.'];
        return redirect()->route('member.death_certificate.index', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DeathCertificate $deathCertificate)
    {
        return view ('member.death_certificate.show', compact('deathCertificate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DeathCertificate $deathCertificate)
    {
        return view('member.death_certificate.create',compact('deathCertificate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeathCertificate $deathCertificate)
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

        $deathCertificate->update($request->all());
        $status = ['type' => 'success', 'message' => 'Successfully Updated'];
        return redirect()->route('member.death_certificate.index', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeathCertificate $deathCertificate)
    {
        $deathCertificate->delete();
        return redirect()->route('member.death_certificate.index');
    }
}
