<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\IpdPatientInfo;
use App\Models\Doctor;
use App\Models\LiveConsultation;
use App\Models\OutDoorRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jubaer\Zoom\Facades\Zoom;

class LiveConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['live_consultations'] = LiveConsultation::latest()->get();

        return view('member.live_consultation.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = Doctor::pluck('name', 'id');
        $outdoor_registrations = OutDoorRegistration::pluck('patient_name', 'id');
        $ipd_patient_infos = IpdPatientInfo::pluck('patient_name', 'id');
        return view('member.live_consultation.create', compact('doctors', 'outdoor_registrations', 'ipd_patient_infos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LiveConsultation::create([
            'title' => $request->title,
            'date' => $request->date,
            'duration' => $request->duration,
            'doctor_id' => $request->doctor_id,
            'ipd_patient_info_registration_id' => $request->ipd_patient_info_registration_id,
            'outdoor_registration_id' => $request->outdoor_registration_id,
            'patient_name' => $request->patient_name,
            'patient_email' => $request->patient_email,
            'patient_phone_one' => $request->patient_phone_one,
            'gender' => $request->gender,
            'age' => $request->age,
            'date_of_birth' => $request->date_of_birth,
            'blood_group' => $request->blood_group,
            'created_by' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
        ]);



        return redirect()->route('member.live_consultation.index');
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
