<?php

namespace App\Http\Controllers\Member;

use App\DataTables\BookingDataTable;
use App\Http\Controllers\Controller;
use App\IpdPatientInfo;
use App\Models\Driver;
use App\Models\OutDoorRegistration;
use App\Models\VehicleDetail;
use App\Models\VehicleInfo;
use App\Models\VehicleSchedule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['Vehicle_details'] = VehicleDetail::where('status', 0)->latest()->get();

        return view('member.vehicle_detail.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $drivers = Driver::where('status', 1)->pluck('name', 'id');
        $vehicleInfos = VehicleInfo::where('status', 1)->pluck('model_no', 'id');
        $outdoor_registrations = OutDoorRegistration::pluck('patient_name', 'id');
        $ipd_patient_infos = IpdPatientInfo::pluck('patient_name', 'id');
        return view('member.vehicle_detail.create', compact('drivers', 'vehicleInfos', 'outdoor_registrations', 'ipd_patient_infos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'driver_id' => 'required',
            'vehicle_info_id' => 'required',
            'vehicle_schedule_id' => 'required',
            'patient_name' => 'required',
            'patient_phone_one' => 'required',
            'start_date_and_time' => 'required',
            'due' => 'required',
            'subtotal' => 'required',
            'discount' => 'required',
            'paid' => 'required',
            'patient_address' => 'required',
        ]);

        $booking = VehicleDetail::select('id')->latest()->first();

        if (@$booking->id > 0) {
            $booking_num = $booking->id + '1' . date('dmy');
        } else {
            $booking_num = '1' . date('dmy');
        }


        VehicleDetail::create([
            'invoice_number' => $booking_num,
            'driver_id' => $request->driver_id,
            'vehicle_info_id' => $request->vehicle_info_id,
            'vehicle_schedule_id' => $request->vehicle_schedule_id,
            'ipd_patient_info_registration_id' => $request->ipd_patient_info_registration_id,
            'outdoor_registration_id' => $request->outdoor_registration_id,
            'patient_name' => $request->patient_name,
            'patient_email' => $request->patient_email,
            'patient_phone_one' => $request->patient_phone_one,
            'patient_phone_two' => $request->patient_phone_two,
            'patient_address' => $request->patient_address,
            'gender' => $request->gender,
            'age' => $request->age,
            'paid' => $request->paid,
            'due' => $request->due,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount,
            'date_of_birth' => $request->date_of_birth,
            'blood_group' => $request->blood_group,
            'start_date_and_time' => $request->start_date_and_time,
            'end_date_and_time' => $request->end_date_and_time,
            'vehicle_status' => $request->vehicle_status,
            // 'status'=> '1',
            'created_by' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
        ]);

        // VehicleInfo::where('id',$request->vehicle_info_id)->update([
        //     'status'=> '0',
        // ]);

        // Driver::where('id',$request->driver_id)->update([
        //     'status'=> '0',
        // ]);

        $fileName = $request->patient_name . '-' . $booking_num . '.' . 'pdf';

        $Vehicle_details = VehicleDetail::where('invoice_number', $booking_num)->first();

        $pdf = Pdf::loadView('member.vehicle_detail.pdf', compact('Vehicle_details'))->setPaper(array(0, 0, 550, 550))->save(public_path($fileName));


        return redirect()->route('member.reserve_vehicle');
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
    public function edit(VehicleDetail $vehicleDetail)
    {
        $drivers = Driver::pluck('name', 'id');
        $vehicleInfos = VehicleInfo::pluck('model_no', 'id');
        $vehicleSchedules = VehicleSchedule::get();


        return view('member.vehicle_detail.edit', compact('drivers', 'vehicleInfos', 'vehicleDetail', 'vehicleSchedules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleDetail $vehicle_detail)
    {
        $vehicle_detail->update($request->all());

        $booking_num = $request->invoice_number;


        $fileName = $request->patient_name . '-' . $booking_num . '.' . 'pdf';

        $Vehicle_details = VehicleDetail::where('invoice_number', $booking_num)->first();

        if (file_exists(public_path() . $fileName)) {
            //File::delete($image_path);
            unlink($fileName);
        }

        $pdf = Pdf::loadView('member.vehicle_detail.pdf', compact('Vehicle_details'))->save(public_path($fileName));


        return redirect()->route('member.reserve_vehicle');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleDetail $vehicle_detail)
    {
        $vehicle_detail->delete();
        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function reserve(BookingDataTable $dataTable)
    {
        $data['drivers'] = Driver::pluck('name', 'id');
        $data['vehicleInfos']  = VehicleInfo::pluck('model_no', 'id');


        return $dataTable->render('member.vehicle_detail.reserve', $data);
    }

    public function dateReserveBookingSearch(Request $request)
    {
        $start_date = date($request->start_date);
        $end_date = date($request->end_date);

        $type_of_patient = $request->type_of_patient;

        $driver_id = $request->driver_id;
        $vehicle_info_id = $request->vehicle_info_id;


        $Vehicle_details  = VehicleDetail::where(function ($query) {
            if (request('type_of_patient') == 'ipd') {
                $query->whereNotNull('ipd_patient_info_registration_id');
            } elseif (request('type_of_patient') == 'outdoor') {
                $query->whereNotNull('outdoor_registration_id');
            } elseif (request('type_of_patient') == 'normal') {
                $query->where('ipd_patient_info_registration_id', null)->where('outdoor_registration_id', null);
            }
            if (request('driver_id') !== 'Select') {
                $query->where('driver_id', request('driver_id'));
            }
            if (request('vehicle_info_id') !== 'Select') {
                $query->where('vehicle_info_id', request('vehicle_info_id'));
            }
        })->whereBetween('start_date_and_time', [$start_date, $end_date])->latest()->get();

        $drivers = Driver::pluck('name', 'id');
        $vehicleInfos  = VehicleInfo::pluck('model_no', 'id');

        return view('member.vehicle_detail.reserve', compact('drivers','vehicleInfos','Vehicle_details', 'start_date', 'end_date', 'vehicle_info_id', 'driver_id', 'type_of_patient'));
        
    }

    public function dateReserveBookingReport(Request $request)
    {
        $start_date = date($request->start_date);
        $end_date = date($request->end_date);

        $type_of_patient = $request->type_of_patient;

        $driver_id = $request->driver_id;
        $vehicle_info_id = $request->vehicle_info_id;

        $vehicle_details = VehicleDetail::where(function ($query) {
            if (request('type_of_patient') == 'ipd') {
                $query->whereNotNull('ipd_patient_info_registration_id');
            } elseif (request('type_of_patient') == 'outdoor') {
                $query->whereNotNull('outdoor_registration_id');
            } elseif (request('type_of_patient') == 'normal') {
                $query->where('ipd_patient_info_registration_id', null)->where('outdoor_registration_id', null);
            }
            if (request('driver_id') !== 'Select') {
                $query->where('driver_id', request('driver_id'));
            }
            if (request('vehicle_info_id') !== 'Select') {
                $query->where('vehicle_info_id', request('vehicle_info_id'));
            }
        })->whereBetween('start_date_and_time', [$start_date, $end_date])->latest()->get();

        return view('member.vehicle_detail.report', compact('vehicle_details', 'start_date', 'end_date', 'vehicle_info_id', 'driver_id', 'type_of_patient'));
        
    }

    public function downloadReserveBooking(Request $request)
    {
        // dd($request->all());
        $start_date = date($request->start_date);
        $end_date = date($request->end_date);

        $type_of_patient = $request->type_of_patient;

        $driver_id = $request->driver_id;
        $vehicle_info_id = $request->vehicle_info_id;

        $vehicle_details = VehicleDetail::where(function ($query) {
            if (request('type_of_patient') == 'ipd') {
                $query->whereNotNull('ipd_patient_info_registration_id');
            } elseif (request('type_of_patient') == 'outdoor') {
                $query->whereNotNull('outdoor_registration_id');
            } elseif (request('type_of_patient') == 'normal') {
                $query->where('ipd_patient_info_registration_id', null)->where('outdoor_registration_id', null);
            }
            if (request('driver_id') !== 'Select') {
                $query->where('driver_id', request('driver_id'));
            }
            if (request('vehicle_info_id') !== 'Select') {
                $query->where('vehicle_info_id', request('vehicle_info_id'));
            }
        })->whereBetween('start_date_and_time', [$start_date, $end_date])->latest()->get();


        $fileName = time() . '.' . 'pdf';
        $pdf = Pdf::loadView('member.vehicle_detail.report_pdf', compact('vehicle_details', 'start_date', 'end_date'))->setPaper(array(0, 0, 1500, 800));
        return $pdf->download($fileName);
    }

    public function reserveUpdate(Request $request)
    {
        // $this->validate($request, [
        //     'end_date_and_time'=>'required',
        // ]);

        // VehicleDetail::where('id',$request->vehicle_detail_id)->update([
        //     'status'=> '0',
        //     'end_date_and_time'=> $request->end_date_and_time,
        // ]);

        // $vehicleDetail = VehicleDetail::where('id',$request->vehicle_detail_id)->first();


        // VehicleInfo::where('id',$vehicleDetail->vehicle_info_id)->update([
        //     'status'=> '1',
        // ]);

        // Driver::where('id',$vehicleDetail->driver_id)->update([
        //     'status'=> '1',
        // ]);

        // return redirect()->route('member.vehicle_detail.index');
    }

    public function outdoorRegistrationId(int $id)
    {
        $outdoor_form = OutDoorRegistration::where('id', $id)->get();
        return response()->json($outdoor_form);
    }

    public function ipdPatientInfRegistrationId(int $id)
    {
        $ipd_patient_info_form = IpdPatientInfo::where('id', $id)->get();
        return response()->json($ipd_patient_info_form);
    }

    public function vehicleScheduleId(int $id)
    {
        $vehicle_info_id = VehicleSchedule::where('vehicle_info_id', $id)->get();
        return response()->json($vehicle_info_id);
    }

    public function vehicleScheduleSingleId(int $id)
    {
        $vehicle_info_id = VehicleSchedule::where('id', $id)->first();
        return response()->json($vehicle_info_id);
    }
}
