<?php

namespace App\Http\Controllers\Member;

use App\HospitalServiceHistory;
use Carbon\Carbon;
use App\Models\Room;
use App\IpdFinalBill;
use App\DueCollection;
use App\Models\Doctor;
use App\IpdPatientInfo;
use App\Models\Country;
use App\Models\Division;
use App\PatientTimeLine;
use App\Marketing_officer;
use App\Models\BloodGroup;
use Illuminate\Http\Request;
use App\Models\HospitalService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\CompanyInfoTrait;
use Illuminate\Support\Facades\Session;

class PatientRegistrationController extends Controller
{
    use CompanyInfoTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        switch ($request->page) {
            case 'unreleased':
                $patinets = IpdPatientInfo::with('cabin')->where('released_date', null)->get();
                return view("member.patient.unrelease_list", compact('patinets'));
                break;
            case 'released':
                $patinets = IpdPatientInfo::with('cabin')->where(['released_date', '!=', null])->get();
                return view("member.patient.index", compact('patinets'));
                break;

            default:
                $patinets = IpdPatientInfo::with('cabin')->get();
                return view("member.patient.index", compact('patinets'));
                break;
        }
    }
    public function ipd_patient_search(Request $request)
    {
        // dd($request->all());
        $data['inputdata'] = $request->all();
        $condition = [];
        if ($request->from) {
            $from = Carbon::parse($request->from);
            $condition[] = ['created_at', '>=', $from];
        }
        if ($request->to) {
            $to = Carbon::parse($request->to);
            $condition[] = ['created_at', '<=', $to];
        }
        if ($request->id) {
            $condition[] = ['patient_info_id', $request->id];
        }
        $data['patinets'] = IpdPatientInfo::with('cabin')->where($condition)->get();
        // dd($patinets);
        if ($request->btn == "search") {
            return view("member.patient.index", $data);
        } else {
            $data['report_title'] = "IPD Patient List";
            $data = $this->company($data);
            // dd($data);
            return view("member.patient.print", $data);
        }
    }
    public function service_search(Request $request)
    {
        // dd($request->all());

        $data['inputdata'] = $request->all();
        $condition = [];
        if ($request->from) {
            $from = Carbon::parse($request->from);
            $condition[] = ['created_at', '>=', $from];
        }
        if ($request->to) {
            $to = Carbon::parse($request->to);
            $condition[] = ['created_at', '<=', $to];
        }
        if ($request->id) {
            $patinets = IpdPatientInfo::where('patient_info_id', $request->id)->first();
            // dd($patinets);
            if($patinets == null){
                $status = ['type' => 'danger', 'message' => 'Patient Not Found.'];
                session()->flash("status", $status);
                return redirect()->route('member.service_history');
            }
            $condition[] = ['patient_id', $patinets->id];
        }
        // dd($patinets);
        $data['serviceHistory'] = HospitalServiceHistory::AuthCompany()->orderBy('id','DESC')->with('service','doctor','marketingOfficer','user')->where($condition)->get();
        if ($request->btn == "search") {
            return view("member.patient.service_history", $data);
        } else {
            $data['report_title'] = "Patient Service List";
            $data = $this->company($data);
            // dd($data);
            return view("member.patient.service_history_print", $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function patient_form_print($id)
    {
        $data['patient'] = IpdPatientInfo::with('cabin')->find($id);
        $data['report_title'] = 'Registation Form';
        $data = $this->company($data);
        // dd($data);
        return view("member.patient.patient_recept", $data);
    }
    public function create()
    {
        $division = Division::all();
        $country = Country::all();
        $bloodGroups = BloodGroup::all();
        $marketingOfficers = Marketing_officer::all();

        $doctors = Doctor::all();
        $rooms = Room::where("is_busy", 0)->get();
        $reg_id = IpdPatientInfo::latest()->first();

        if ($reg_id == null) {
            $reg_id = 0 + 1;
        } else {
            $reg_id = $reg_id->id + 1;
        }
        return view("member.patient.create", compact('division', 'reg_id', 'country', 'bloodGroups', 'doctors', 'rooms', 'marketingOfficers'));
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
        $request->validate([
            'patient_name' => 'required',
            'phone_no' => 'required',
            'cabin_no' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'blood_group' => 'required',
            'advance_payment' => 'required',
            'admission_fee' => 'required',
            'admission_fee_paid' => 'required',
        ]);







        $roomId = $request->cabin_no;

        $staus = Room::find($roomId);
        if ($staus->is_busy == 1) {
            $status = ['type' => 'danger', 'message' => 'The Cabin Room Already booked.'];
            session()->flash("status", $status);
            return redirect()->back();
        } else {

            $patient_id = 'ipd-p-000001';
            $lastPatient = IpdPatientInfo::latest()->first();

            if ($lastPatient != null) {
                $patient_id = $lastPatient->patient_info_id;
                $patient_code_explode = explode('-', $patient_id);
                $patient_code_int = $patient_code_explode[2];
                $patient_code_number = str_pad(($patient_code_int + 1), 6, "0", STR_PAD_LEFT);
                $patient_id = $patient_code_explode[0] . '-' . $patient_code_explode[1] . '-' . $patient_code_number;
            }


            $doc_name = explode('#', $request->doc_name);
            // dd($doc_name);
            $ref_doc_name = explode('#', $request->ref_doc_name);
            //below for age
            $age = "";

            $Day = $request->Day;
            $Month = $request->Month;
            $Year = $request->Year;


            if (($Day != 0) and ($Month != 0) and ($Year != 0)) {
                $age = $Day . " D " . $Month . " M " . $Year . " Y";
            } elseif (($Day == 0) and ($Month != 0) and ($Year != 0)) {
                $age = $Year . " Y " . $Month . " M";
            } elseif (($Day != 0) and ($Month == 0) and ($Year != 0)) {
                $age = $Day . " D " . $Year . " Y";
            } elseif (($Day != 0) and ($Month != 0) and ($Year == 0)) {
                $age = $Day . " D " . $Month . " M";
            } elseif (($Day == 0) and ($Month == 0) and ($Year != 0)) {
                $age = $Year . " Y";
            } elseif (($Day == 0) and ($Month != 0) and ($Year == 0)) {
                $age = $Month . " M";
            } elseif (($Day != 0) and ($Month == 0) and ($Year == 0)) {
                $age = $Day . " D";
            } else {
                // $age="0 D 0 M 0 Y";
                $age = "";
            }


            //upper for age
            $bgroup = $request->blood_group;
            if (isset($bgroup)) {
                $bgroup = $bgroup;
            } else {
                $bgroup = 0;
            }

            $admit_date = $request->admit_date;
            $ad_date = $request->admit_date;
            $ipd_patient_id_r = $request->ipd_patient_id_r;
            if ($ipd_patient_id_r == "") {
                $mem_reg_id = "";
            } else {
                $mem_reg_id = $ipd_patient_id_r;
            }

            $ad_time = date('H:i:s');
            $admit_date_dtime = $ad_date . " " . $ad_time;

            $val = array(
                'company_id' => auth()->user()->company_id,
                'marketing_officer_id' => $request->marketing_officer_id,
                'patient_name' => $request->patient_name,
                'reg_id' => $request->reg_no,
                'patient_info_id' => $patient_id,
                'phone' => $request->phone_no,
                'description' => $request->description,
                'blood_pressure' => $request->blood_pressure,
                'pulse_rate' => $request->pulse_rate,
                'address' => $request->address,
                'guardian_name' => $request->guardian_name,
                'disease_name' => $request->disease_name,
                'age' => $age,
                'gender' => $request->gender,
                'cabin_no' => $request->cabin_no,
                'blood_group' => $bgroup,
                'date_of_birth' => db_date_format($request->date_of_birth),
                'email' => $request->email,
                'password' => Hash::make("123456"),
                'ref_doc_name' => $ref_doc_name[0],
                'ref_doctor_id' => $ref_doc_name[1],
                'doc_name' => $doc_name[0],
                'doctor_id' => $doc_name[1],
                'advance_payment' => $request->advance_payment,
                'admission_fee' => $request->admission_fee,
                'admission_fee_paid' => $request->admission_fee_paid,
                'operator_name' => auth()->user()->full_name,
                'operator_id' => auth()->user()->id,
                'hospital_id' => auth()->user()->company_id,
                'patient_image' => "default_patient.jpeg",
                'created_at' => date('Y-m-d H:i:s'),
                'admit_date_time' => $admit_date_dtime,
                'mem_reg_id' => $mem_reg_id
            );

            $id = IpdPatientInfo::insertGetId($val);


            $service_code = 'ipd-000001';
            $lastservice = IpdFinalBill::latest()->first();

            if ($lastservice != null) {
                $service_code = $lastservice->invoice_order_id;
                $service_code_explode = explode('-', $service_code);
                $service_code_int = $service_code_explode[1];
                $service_code_number = str_pad(($service_code_int + 1), 6, "0", STR_PAD_LEFT);
                $service_code = $service_code_explode[0] . '-' . $service_code_number;
            }


            $val1 = array(
                'p_id' => $id,
                'company_id' => auth()->user()->company_id,
                'invoice_order_id' => $service_code,
                'advance_payment' => $request->advance_payment,
                'admission_fee' => $request->admission_fee,
                'admission_fee_paid' => $request->admission_fee_paid,
                'total_paid' => $request->advance_payment + $request->admission_fee_paid,
                'created_at' => $request->admit_date
            );

            IpdFinalBill::insertGetId($val1);

            $d_data['company_id'] = auth()->user()->company_id;
            $d_data['old_due'] = $request->advance_payment;

            $d_data['order_id'] = $patient_id;
            $d_data['total_amount'] = $request->admission_fee;
            $d_data['patient_id'] = $id;

            $d_data['current_due'] = $request->admission_fee - ($request->advance_payment + $request->admission_fee_paid);

            $d_data['paid_due'] = $request->advance_payment + $request->admission_fee_paid;

            $d_data['admission_fee'] = $request->admission_fee;

            $d_data['advance_payment'] = $request->advance_payment;

            $d_data['admission_fee_paid'] = $request->admission_fee_paid;

            $d_data['created_at'] = $request->admit_date;
            $d_data['due_type'] = 2;
            $d_data['operator_name'] = auth()->user()->full_name;
            $d_data['operator_id'] = auth()->user()->id;

            DueCollection::insert($d_data);




            $room_id = $request->cabin_no;

            $room['is_busy'] = 1;
            Room::where('id', $room_id)->update($room);

            $val = array(
                'patient_id' => $id,
                'cabin_no' => $request->cabin_no,
                'type' => 1,
                'created_at' => $request->admit_date
            );

            PatientTimeLine::insert($val);
            $status = ['type' => 'success', 'message' => 'New Patient Registered Successfully Done'];
            session()->flash('status', $status);

            return redirect()->route('member.patient_registration.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function calculateAge(Request $request)
    {
        $date = $request->data;
        $dateYmd = explode("#", calculateAgeOnlyNumber($date));
        return response()->json(['data' => $dateYmd]);
    }
    public function calculateDate(Request $request)
    {
        $day = $request->day;
        $month = $request->month;
        $year = $request->year;

        $currentDate = Carbon::now();
        // dd($day, $month , $year );
        $newDate = $currentDate->subYears($year)->subMonths($month)->subDays($day)->format('m/d/Y');

        return response()->json(['data' => $newDate]);
    }
    public function show($id)
    {
        return view("member.patient.details");
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
    public function add_service($id)
    {
        // dd($id);

        $data['patient'] = IpdPatientInfo::AuthCompany()->find($id);
        $data['marketingOfficerId'] = $data['patient']->marketing_officer_id;
        // dd($marketingOfficerId);
        $data['doctors'] = Doctor::AuthCompany()->get();
        $data['services'] = HospitalService::AuthCompany()->get();

        return view("member.patient.add_service", $data);
    }
    public function storeService(Request $request)
    {
        // dd($request->all());
        $order_id = uniqid() . date('Ymd');
        $company_id = auth()->user()->company_id;
        $operator_id = auth()->user()->id;
        $marketing_officer_id = $request->marketing_officer_id;
        foreach ($request->doctor_id as $key => $doctor_id) {
            $serviesHistory = new HospitalServiceHistory();
            $serviesHistory['order_id'] =  $order_id;
            $serviesHistory['patient_id'] =  $request->patient_id;
            $serviesHistory['operator_id'] =  $operator_id;
            $serviesHistory['company_id'] =  $company_id;
            $serviesHistory['doctor_id'] =  $doctor_id;
            $serviesHistory['service_id'] =  $request->service[$key];
            $serviesHistory['qty'] =  $request->qty[$key];
            $serviesHistory['price'] =  $request->price[$key];
            $serviesHistory['marketing_officer_id'] =  $marketing_officer_id;
            $serviesHistory->save();
        }
        Session::forget('cart');
        return redirect()->route('member.service_history');
    }

    public function service_history(){
        $serviceHistory = HospitalServiceHistory::AuthCompany()->orderBy('id','DESC')->with('service','doctor','marketingOfficer','user')->get();
        // dd($serviceHistory );
        return view('member.patient.service_history',compact('serviceHistory'));
    }

    public function qtyChange(Request $request)
    {
        $serviceId = $request->item_id;
        $qty = intval($request->qty);

        $cart = Session::get('cart', []);
        if (isset($cart[$serviceId])) {
            $cart[$serviceId]['qty'] = $qty;
            session()->put('cart', $cart);
        }
        $cart = Session::get('cart', []);

        $tableContent = "";
        $k = 0;
        foreach ($cart as $c) {

            $tableContent .= "<tr>";
            $tableContent .= "<td>" . ++$k . "</td>";
            $tableContent .= "<td>" . $c['name'] . "</td>";
            $tableContent .= "<td>
            <input type='hidden' name='doctor_id[]' value='" . $c['doctor_id'] . "'>
            <input type='hidden' name='service[]' value='" . $c['id'] . "'>
             " . $c['doctor_name'] . "
             </td>";
            $tableContent .= "<td><input type='hidden' name='price[]' value='" . $c['price'] . "'> " . $c['price'] . "</td>";
            $tableContent .= "<td width='23%'><input class='form-control' min='1' name='qty[]' type='number' value='" . $c['qty'] . "' onkeyup='qtyChange(this," . $c['id'] . "," . $c['price'] . ")' ></td>";
            $tableContent .= "<td><a class='btn btn-danger mt-0 '
                                                        onclick='removeItem(" . $c['id'] . ")'><i
                                                            class='fa fa-trash'></i>
                                                    </a></td>";
            $tableContent .= "</tr>";
        }
        $data['content'] = $tableContent;
        return response()->json(['data' => $data]);
    }
    public function removeItem(Request $request)
    {
        $serviceId = $request->item_id;
        $cart = Session::get('cart', []);

        if (isset($cart[$serviceId])) {
            unset($cart[$serviceId]);
            Session::put('cart', $cart);
        }
        $cart = Session::get('cart', []);

        $tableContent = "";
        $k = 0;
        foreach ($cart as $c) {

            $tableContent .= "<tr>";
            $tableContent .= "<td>" . ++$k . "</td>";
            $tableContent .= "<td>" . $c['name'] . "</td>";
            $tableContent .= "<td>
            <input type='hidden' name='doctor_id[]' value='" . $c['doctor_id'] . "'>
            <input type='hidden' name='service[]' value='" . $c['id'] . "'>
             " . $c['doctor_name'] . "
             </td>";
            $tableContent .= "<td><input type='hidden' name='price[]' value='" . $c['price'] . "'> " . $c['price'] . "</td>";
            $tableContent .= "<td width='23%'><input class='form-control' min='1' name='qty[]' type='number' value='" . $c['qty'] . "' onkeyup='qtyChange(this," . $c['id'] . "," . $c['price'] . ")' ></td>";
            $tableContent .= "<td><a class='btn btn-danger mt-0 '
                                                        onclick='removeItem(" . $c['id'] . ")'><i
                                                            class='fa fa-trash'></i>
                                                    </a></td>";
            $tableContent .= "</tr>";
        }
        $data['content'] = $tableContent;
        return response()->json(['data' => $data]);
    }
    public function addItem(Request $request)
    {
        $serviceId = $request->item_id;
        $doc_id = $request->doctor_id;
        $service = HospitalService::find($serviceId);
        $doctor = Doctor::find($doc_id);



        $cart = Session::get('cart', []);

        // If cart is empty, initialize it
        if (!$cart) {
            $cart = [
                $serviceId => [
                    'name' => $service->title,
                    'id' => $serviceId,
                    'price' => $service->price,
                    'doctor_id' => $doc_id,
                    'doctor_name' => ($doctor != null) ? $doctor->name : '',
                    'qty' => 1
                ]
            ];
        } else {
            // If service already exists in cart, increment qty
            if (isset($cart[$serviceId])) {
                if (isset($cart[$serviceId])) {
                    $cart[$serviceId]['qty'] = $cart[$serviceId]['qty'] + 1;
                    $cart[$serviceId]['doctor_id'] = $doc_id;
                    $cart[$serviceId]['doctor_name'] = ($doctor != null) ? $doctor->name : '';
                    session()->put('cart', $cart);
                }
            } else {
                // Add new service to cart
                $cart[$serviceId] = [
                    'name' => $service->title,
                    'id' => $serviceId,
                    'price' => $service->price,
                    'doctor_id' => $doc_id,
                    'doctor_name' => ($doctor != null) ? $doctor->name : '',
                    'qty' => 1
                ];
            }
        }


        // Store cart data in session
        Session::put('cart', $cart);
        $cart = Session::get('cart');
        $tableContent = "";
        $k = 0;
        foreach ($cart as $c) {

            $tableContent .= "<tr>";
            $tableContent .= "<td>" . ++$k . "</td>";
            $tableContent .= "<td>" . $c['name'] . "</td>";
            $tableContent .= "<td>
            <input type='hidden' name='doctor_id[]' value='" . $c['doctor_id'] . "'>
            <input type='hidden' name='service[]' value='" . $c['id'] . "'>
             " . $c['doctor_name'] . "
             </td>";
            $tableContent .= "<td><input type='hidden' name='price[]' value='" . $c['price'] . "'> " . $c['price'] . "</td>";
            $tableContent .= "<td width='23%'><input class='form-control' min='1' name='qty[]' type='number' value='" . $c['qty'] . "' onkeyup='qtyChange(this," . $c['id'] . "," . $c['price'] . ")' ></td>";
            $tableContent .= "<td><a class='btn btn-danger mt-0 '
                                                        onclick='removeItem(" . $c['id'] . ")'><i
                                                            class='fa fa-trash'></i>
                                                    </a></td>";
            $tableContent .= "</tr>";
        }

        $data['content'] = $tableContent;
        return response()->json(['data' => $data]);
    }
}
