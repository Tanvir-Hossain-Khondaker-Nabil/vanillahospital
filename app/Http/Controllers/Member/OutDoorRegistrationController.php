<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Appoinment;
use App\Models\OutDoorRegistration;
use App\Models\OutDoorPatientTest;
use App\Models\SubTestGroup;
use App\Models\Specimen;
use App\Models\TestGroup;
use App\Models\RecordEditPassword;
use App\Models\opdDueCollectionHistory;
use App\Http\Traits\CompanyInfoTrait;
use Barryvdh\DomPDF\Facade as PDF;
class OutDoorRegistrationController extends Controller
{
    use CompanyInfoTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = OutDoorRegistration::authCompany()->where('due_status',1)->orderBy('created_at','ASCE');
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['opd_id'] = '';
        $data['opd_type'] = 'all_opd_list';

        if(@$request->from_date && @$request->to_date){
            $data['opd_type'] = 'due_opd_list';
            $data['from_date'] = $request->from_date;
            $data['to_date'] =  $request->to_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, $request->to_date]) ;
        }

        if(@$request->from_date){
            $data['opd_type'] = 'due_opd_list';
            $data['from_date'] = $request->from_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, date("Y-m-d")]) ;
        }

        if(@$request->opd_id){
            $data['opd_type'] = 'due_opd_list';
            $data['opd_id'] = $request->opd_id;
            $query = $query->where('opd_id',$request->opd_id);
        }



        $data['outdoor_due_list'] = $query->get();

        $data['patients'] = OutDoorRegistration::authCompany()->where('due_status',1)->get()->pluck('opd_id','id');
        $data['all_outdoor'] = OutDoorRegistration::authCompany()->orderBy('created_at','ASCE')->get();
        $data['outdoor_register'] = OutDoorRegistration::authCompany()->where('due_status',0)->orderBy('created_at','ASCE')->get();

        // dd($data);

        return view('member.out_door_registration.index',$data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['sub_test_groups'] = SubTestGroup::authCompany()->with('testGroup')->get();
        $data['patient'] = OutDoorRegistration::authCompany()->get();

        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->where('type','MBBS')->get()->pluck('name','id');
        $data['refer_doctor'] = Doctor::AuthCompany()->where('status','active')->where('type','QUACK')->get()->pluck('name','id');
        //  dd($data['sub_test_groups']);

        return view('member.out_door_registration.create',$data);
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


        if(@$request->year && @$request->month && @$request->day ){

            $date= $request->year."-".$request->month."-".$request->day;

           $inputs['age'] = calculateAge($date);
        }

        $inputs['patient_name'] = $request->patient_name;
        $inputs['gender'] = $request->gender;
        $inputs['blood_group'] = $request->blood_group;
        $inputs['doctor_id'] = $request->doctor_id;
        $inputs['phone'] = $request->phone;
        $inputs['ref_doctor_id'] = $request->ref_doctor_id;
        $inputs['date_of_service'] = $request->date_of_service;
        $inputs['total_doct_comission'] = $request->total_doct_comission;
        $inputs['net_amount'] = $request->net_total;
        $inputs['total_amount'] = $request->total_amount;
        $inputs['total_sub_c_o'] = $request->sub_c_o;
        $inputs['discount_percent'] = $request->discount_percent;
        $inputs['discount'] = $request->discount;
        $inputs['discount_ref'] = $request->discount_ref;
        $inputs['total_paid'] = $request->total_paid;
        $inputs['address'] = $request->address;
        $inputs['member_patient'] = $request->member_patient?? 'no';
        $inputs['ipd_patient'] = $request->ipd_patient?? 'no';
        $inputs['due'] = $request->due;
        $inputs['day'] = $request->day;
        $inputs['month'] = $request->month;
        $inputs['year'] = $request->year;
        $inputs['share_holder_id'] = $request->share_holder_id;
        $inputs['share_holder_type'] = $request->share_holder_type;
        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;

        if($request->due == 0){
            $inputs['due_status'] = 0;

        }else{

            $inputs['due_status'] = 1;
        }


        if($request->file('patient_image')){
            $file = $request->file('patient_image');
            $filename = time() . '.' . $request->file('patient_image')->extension();
            $filePath = public_path() . '/uploads/patient/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }

         $status = OutDoorRegistration::create($inputs);
         $ran = rand(0, 99999);
         $status->update([
            'opd_id' => 'OPD'.time().''.$status->id,
         ]);

          $opd_input['net_total'] = $request->net_total;
          $opd_input['paid'] = $request->total_paid;
          $opd_input['due'] = $request->due;
          $opd_input['discount_percent'] = $request->discount_percent;
          $opd_input['discount'] = $request->discount;
          $opd_input['discount_ref'] = $request->discount_ref;
          $opd_input['out_door_registration_id'] = $status->id;
          $opd_input['created_by'] = $user_id;

        if ($status) {

            opdDueCollectionHistory::create($opd_input);

            foreach ($request->price as$key=> $item) {

                $testInput['out_door_registration_id'] = $status->id;
                $testInput['price'] = $request->price[$key];
                $testInput['discount'] = $request->td_discount[$key];
                $testInput['net_amount'] = $request->td_net_amount[$key];
                $testInput['doctor_comission'] = $request->td_comission[$key];
                $testInput['sub_comission'] = $request->td_sub_comission[$key];
                $testInput['sub_test_group_id'] = $request->sub_test_group_id[$key];
                $testInput['test_group_id'] = $request->test_group_id[$key];
               $testData =  OutDoorPatientTest::create($testInput);

            }
        }

        if( $status && $testData){
            return redirect()->route('member.out_door_registration.show', $status->id);
        }else{
            $status = ['type' => 'error', 'message' => 'Something Went Wrong'];
            return back()->with('status', $status);
        }

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
        $data['data'] = OutDoorRegistration::authCompany()->with(['doctor','refDoctor','outdoorPatientTest'])->where('id',$id)->first();

        // dd($data);
        $data = $this->company($data);

        return view('member.out_door_registration.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $data['sub_test_groups'] = SubTestGroup::authCompany()->with('testGroup')->get();
        $data['patient'] = OutDoorRegistration::authCompany()->get();

        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->where('type','MBBS')->get()->pluck('name','id');
        $data['refer_doctor'] = Doctor::AuthCompany()->where('status','active')->where('type','QUACK')->get()->pluck('name','id');

        $data['model'] = $model = OutDoorRegistration::authCompany()->with(['doctor','refDoctor','outdoorPatientTest'])->where('id',$id)->first();

        // dd($data['model']);
        $data['testArray'] = $model->outdoorPatientTest->pluck('sub_test_group_id');
        $data['count_val'] = 0;

        // dd( $data['testArray']);

        return view('member.out_door_registration.edit',$data);
    }


    public function opdUpdate(Request $request,$id)
    {
        // dd($request->all(), $id);

        // $ids = OutDoorPatientTest::where('out_door_registration_id', $id)->pluck('sub_test_group_id');
        $ids = $request->sub_test_group_id;

        // dd($ids,$request->sub_test_group_id);




        $this->validate($request, $this->validationRules());

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

         $data = OutDoorRegistration::findOrFail($id);
        //  dd($data);

        if(@$request->year && @$request->month && @$request->day ){

            $date= $request->year."-".$request->month."-".$request->day;

           $inputs['age'] = calculateAge($date);
        }

        $inputs['patient_name'] = $request->patient_name;
        $inputs['gender'] = $request->gender;
        $inputs['blood_group'] = $request->blood_group;
        $inputs['doctor_id'] = $request->doctor_id;
        $inputs['phone'] = $request->phone;
        $inputs['ref_doctor_id'] = $request->ref_doctor_id;
        $inputs['date_of_service'] = $request->date_of_service;
        $inputs['total_doct_comission'] = $request->total_doct_comission;
        $inputs['net_amount'] = $request->net_total;
        $inputs['total_amount'] = $request->total_amount;
        $inputs['total_sub_c_o'] = $request->sub_c_o;
        $inputs['discount_percent'] = $request->discount_percent;
        $inputs['discount'] = $request->discount;
        $inputs['discount_ref'] = $request->discount_ref;
        $inputs['total_paid'] = $request->total_paid;
        $inputs['address'] = $request->address;
        $inputs['member_patient'] = $request->member_patient?? 'no';
        $inputs['ipd_patient'] = $request->ipd_patient?? 'no';
        $inputs['due'] = $request->due;
        $inputs['day'] = $request->day;
        $inputs['month'] = $request->month;
        $inputs['year'] = $request->year;
        $inputs['share_holder_id'] = $request->share_holder_id;
        $inputs['share_holder_type'] = $request->share_holder_type;
        $inputs['updated_by'] = $user_id;


        if($request->due == 0){
            $inputs['due_status'] = 0;

        }else{

            $inputs['due_status'] = 1;
        }

        if($request->file('patient_image')){
            $file = $request->file('patient_image');
            $filename = time() . '.' . $request->file('patient_image')->extension();
            $filePath = public_path() . '/uploads/patient/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }

         $status = $data->update($inputs);


          $opd_input['net_total'] = $request->net_total;
          $opd_input['paid'] = $request->total_paid;
          $opd_input['due'] = $request->due;
          $opd_input['discount_percent'] = $request->discount_percent;
          $opd_input['discount'] = $request->discount;
          $opd_input['discount_ref'] = $request->discount_ref;
          $opd_input['out_door_registration_id'] = $id;
          $opd_input['created_by'] = $user_id;

        if ($status) {

            opdDueCollectionHistory::create($opd_input);

            foreach ($request->price as$key=> $item) {

                $check = OutDoorPatientTest::where('out_door_registration_id', $id)->where('sub_test_group_id',$request->sub_test_group_id[$key])->first();

                $testInput['price'] = $request->price[$key];
                $testInput['discount'] = $request->td_discount[$key];
                $testInput['net_amount'] = $request->td_net_amount[$key];
                $testInput['doctor_comission'] = $request->td_comission[$key];
                $testInput['sub_comission'] = $request->td_sub_comission[$key];
                $testInput['test_group_id'] = $request->test_group_id[$key];
                if($check){
                    $check->update($testInput);
                }else{
                    $testInput['out_door_registration_id'] = $id;
                    $testInput['sub_test_group_id'] = $request->sub_test_group_id[$key];
                    $testData =  OutDoorPatientTest::create($testInput);
                }

            }
        }

        if( $status){
            OutDoorPatientTest::whereNotIn('sub_test_group_id', $ids)->where('out_door_registration_id',$id)->delete();
            return redirect()->route('member.out_door_registration.show', $id);
        }else{
            $status = ['type' => 'error', 'message' => 'Something Went Wrong'];
            return back()->with('status', $status);
        }


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
        // dd($id);
        $modal = OutDoorRegistration::findOrFail($id);
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
            'patient_name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'date_of_service' => 'required',
            'total_paid' => 'required',
        ];


        return $rules;
    }

    public function fetchPatient(Request $request)
    {
        // dd($request->all());

        $num = $request->phone;
          $searchData = OutDoorRegistration::authCompany()->where('phone','like', '%'.$num.'%')->get();

            //  dd($searchData);

          $height = '150px';
                $output = '';

            if (count($searchData) > 0) {
                $output =
                    '<ul class="list-group" style="display:block; position:relative; z-index:1; overflow-y: scroll;
            height: ' .
                    $height .
                    '">';

                foreach ($searchData as $key=> $data) {
                         $address_is = $data->address;
                        $output .= '<li data-address="'.$address_is.'"  data-patient-gender="'.$data->gender.'"  data-patient-phone="'.$data->phone.'" data-patient-id="'.$data->patient_name.'" onclick="selectPitent(this)" class="list-group-item">
                             <strong >'. $data->phone .'</strong>

                        </li>';

                }

                $output .= '</ul>';
            } else {
                $output .= '<li class="list-group-item"> No Data Found</li>';
            }

        return $output;



    }

    public function otdDueList(){

        $data['outdoor_register'] =  OutDoorRegistration::authCompany()->where('due_status',1)->orderBy('created_at','ASCE')->get();

     return view('member.otd_collection.index',$data);
    }

    public function otdDueCreate($id){


        // dd($id);
    $data['outdoor_due'] =  OutDoorRegistration::authCompany()->with(['outdoorPatientTest'])->where('id',$id)->first();

    // dd($data);
     return view('member.otd_collection.show',$data);
    }



    public function opdDueStore(Request $request){

        // dd($request->all());
         $id = $request->out_door_registration_id;
        $opd_input['net_total'] = $request->net_total;
        $opd_input['paid'] = $request->total_paid;
        $opd_input['due'] = $request->due;
        $opd_input['discount_percent'] = $request->discount_percent;
        $opd_input['discount'] = $request->discount;
        $opd_input['discount_ref'] = $request->discount_ref;
        $opd_input['out_door_registration_id'] = $id;
        $opd_input['created_by'] = \Auth::user()->id;

        $status = opdDueCollectionHistory::create($opd_input);

        $data = OutDoorRegistration::authCompany()->where('id',$id)->first();

            if($status){

                $check_due = $data->due - ($request->total_paid + $request->discount?? 0);
                 if( $check_due == 0){
                    $input['due_status'] = 0;
                 }
                $input['due'] = $check_due;
                $input['discount'] = $data->discount + ($request->discount?? 0) ;
                $input['total_paid'] = $data->total_paid + ($request->total_paid?? 0) ;
                $data->update($input);


            }

            $status = ['type' => 'success', 'message' => 'Successfully Added'];
            return redirect()->route('member.opd.due.list')->with('status', $status);
    }

    public function opdDuePrint($id)
    {
        $data['data'] =  OutDoorRegistration::authCompany()->with(['opdDueCollectionHistory'])->where('id',$id)->first();
        $data = $this->company($data);

        // dd($data['data']);
        return view('member.out_door_registration.opd_due_list_print',$data);
    }

    public function opdPaidPatientList( Request $request)
    {

        //   dd($request->all());



        $query = OutDoorRegistration::authCompany()->where('due_status',0);
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['opd_id'] = '';
        if(@$request->from_date && @$request->to_date){
            // dd('1');
            $data['from_date'] = $request->from_date;
            $data['to_date'] =  $request->to_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, $request->to_date]) ;
        }

        if(@$request->from_date){
            // dd('dds');
            $data['from_date'] = $request->from_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, date("Y-m-d")]) ;
        }

        if(@$request->opd_id){
            // dd('dds');
            $data['opd_id'] = $request->opd_id;
            $query = $query->where('opd_id',$request->opd_id);
        }



        $data['datas'] = $query->get();
        $data['patients'] = OutDoorRegistration::authCompany()->where('due_status',0)->get()->pluck('opd_id','id');



        // dd($data['patients']);
        return view('member.odp_patient.opd_paid_patient_list',$data);
    }


    public function opdAllPatientList( Request $request)
    {


        $query = OutDoorRegistration::authCompany();
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['opd_id'] = '';
        if(@$request->from_date && @$request->to_date){

            $data['from_date'] = $request->from_date;
            $data['to_date'] =  $request->to_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, $request->to_date]) ;
        }

        if(@$request->from_date){

            $data['from_date'] = $request->from_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, date("Y-m-d")]) ;
        }

        if(@$request->opd_id){

            $data['opd_id'] = $request->opd_id;
            $query = $query->where('opd_id',$request->opd_id);
        }



        $data['datas'] = $query->get();
        $data['patients'] = OutDoorRegistration::authCompany()->get()->pluck('opd_id','id');

        return view('member.odp_patient.opd_all_patient_list',$data);
    }

    public function opdDuePatientList( Request $request)
    {


        $query = OutDoorRegistration::authCompany()->where('due_status',1);
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['opd_id'] = '';
        if(@$request->from_date && @$request->to_date){

            $data['from_date'] = $request->from_date;
            $data['to_date'] =  $request->to_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, $request->to_date]) ;
        }

        if(@$request->from_date){

            $data['from_date'] = $request->from_date;
            $query = $query->whereBetween('date_of_service', [$request->from_date, date("Y-m-d")]) ;
        }

        if(@$request->opd_id){

            $data['opd_id'] = $request->opd_id;
            $query = $query->where('opd_id',$request->opd_id);
        }



        $data['datas'] = $query->get();
        $data['patients'] = OutDoorRegistration::authCompany()->where('due_status',1)->get()->pluck('opd_id','id');

        return view('member.odp_patient.opd_due_patient_list',$data);

   }


   public function specimenWiseCollection( Request $request)
   {


       $query = OutDoorRegistration::authCompany()->where('due_status',1);
       $data['from_date'] = '';
       $data['to_date'] = '';
       $data['opd_id'] = '';
       if(@$request->from_date && @$request->to_date){

           $data['from_date'] = $request->from_date;
           $data['to_date'] =  $request->to_date;
           $query = $query->whereBetween('date_of_service', [$request->from_date, $request->to_date]) ;
       }

       if(@$request->from_date){

           $data['from_date'] = $request->from_date;
           $query = $query->whereBetween('date_of_service', [$request->from_date, date("Y-m-d")]) ;
       }

       if(@$request->opd_id){

           $data['opd_id'] = $request->opd_id;
           $query = $query->where('opd_id',$request->opd_id);
       }



       $data['datas'] = $query->get();
       $data['patients'] = OutDoorRegistration::authCompany()->where('due_status',1)->get()->pluck('opd_id','id');

       return view('member.odp_patient.opd_due_patient_list',$data);

  }


   public function groupWiseCollection( Request $request)
   {
      $data['test_group'] =  TestGroup::authCompany()->pluck('title','id')->toArray();

    //   dd($data);
    return view('member.opd_collection_report.group_wise_collection',$data);

   }

   public function groupWiseCollectionSearch( Request $request)
   {
    // dd($request->all());


      $total_discount = 0;
      $discount = 0;
      $data['from_date'] = $from_date=  $request->from_date;
      $data['to_date'] = $to_date = $request->to_date;
      $test_group_id = $request->test_group_id;
      $group_data = [];

    if($test_group_id == 'all'){
        $test_group = TestGroup::authCompany()->pluck('title','id')->toArray();

        foreach($test_group as $key=> $t_group){

            $total_discount = 0;
             $group_data_is[$t_group] = $test_data =  OutDoorPatientTest::authCompany()->with('subTestGroup')
             ->where('test_group_id',$key)
             ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
             ->get()->groupBy('sub_test_group_id');

             foreach($test_data as $t_data){

                 foreach($t_data as $per_data){
                     $total_discount =  $total_discount + $per_data->discount;
                 }
               }
               $discount = $discount + $total_discount;

         }

        $data['discount'] =  $discount;
        $group_data['data_is'] =  $group_data_is;

        $data = $this->company($data);

         return view('member.opd_collection_report.all_group_wise_collection_report',$data,$group_data);

    }
    else{

        $data['datas'] = $test_data =  OutDoorPatientTest::authCompany()->with('subTestGroup')->where('test_group_id',$test_group_id)
        ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
        ->get()->groupBy('sub_test_group_id');

            $data = $this->company($data);

            $data['tes_group'] = TestGroup::authCompany()->where('id',$test_group_id)->first();

            foreach($test_data as $t_data){

            foreach($t_data as $per_data){
            $total_discount =  $total_discount + $per_data->discount;
            }

            }

            $data['total_discount'] = $total_discount;

            return view('member.opd_collection_report.group_wise_collection_report',$data);
    }


   }

   public function subGroupWiseCollection( Request $request)
   {
      $data['sub_test_group'] =  SubTestGroup::authCompany()->pluck('title','id')->toArray();

    //   dd($data);
    return view('member.opd_collection_report.sub_group_wise_collection',$data);

   }

   public function subGroupWiseCollectionSearch( Request $request)
   {


    $discount = 0;
    $data['from_date'] = $from_date=  $request->from_date;
    $data['to_date'] = $to_date = $request->to_date;
    $sub_test_group_id = $request->sub_test_group_id;
    $sub_group_data = [];

    if( $sub_test_group_id == 'all'){
        $sub_test_group = SubTestGroup::authCompany()->pluck('title','id')->toArray();

        foreach($sub_test_group as $key=> $sub_t_group){


            $total_discount = 0;
             $sub_group_data_is[$sub_t_group] = $test_data = OutDoorPatientTest::authCompany()->with('subTestGroup')
             ->where('sub_test_group_id',$key)
             ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
             ->get();

             foreach($test_data as $t_data){
                $total_discount =  $total_discount + $t_data->discount;

               }

               $discount = $discount + $total_discount;

         }

        $data['discount'] =  $discount;
        $sub_group_data['data_is'] =  $sub_group_data_is;

        $data = $this->company($data);

        // dd($data,$sub_group_data);
         return view('member.opd_collection_report.all_sub_group_wise_collection_report',$data,$sub_group_data);

    }else{
        $data['datas'] =  OutDoorPatientTest::authCompany()->with('subTestGroup')
        ->where('sub_test_group_id',$sub_test_group_id)
        ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
        ->get();

        $data = $this->company($data);

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['sub_test_group'] = SubTestGroup::authCompany()->where('id',$sub_test_group_id)->first();


        return view('member.opd_collection_report.sub_group_wise_collection_report',$data);
    }


   }

   public function opdDiscountSummary()
   {

    return view('member.opd_collection_report.opd_discount_summary');

   }

   public function opdDiscountSummarySearch( Request $request)
   {

    $from_date = $request->from_date;
    $to_date = $request->to_date;

    $data['datas'] = OutDoorRegistration::authCompany()->whereNotNull('discount')
    ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
    ->get();


    $data = $this->company($data);

    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;

    // dd($data['data']);
    return view('member.opd_collection_report.opd_discount_summary_report',$data);

   }

  public function recordUpdateCheck(Request $request)
  {


    $user = RecordEditPassword::authCompany()->where('user_id',auth()->user()->id)->first();
    $status ='no';
    if(\Hash::check($request->password, $user->password)) {

        $status ='yes';
    }

    return response()->json([
        'status' => $status
    ]);


  }

  public function opdBalanceSheet(){

    return view('member.opd_collection_report.balance_sheet');
  }


    public function opdBalanceSheetSearch(Request $request){

    $total_test_price = 0;
    $total_due = 0;
    $total_discount = 0;
    $total_paid_amount = 0;
    $total_payable_amount = 0;
    $total_commission = 0;
    $total_appoinment_amount = 0;
    $total_appoinment_discount = 0;

    $from_date = $request->from_date;
    $to_date = $request->to_date;

    $data['datas'] = $opd_data = OutDoorRegistration::authCompany()->with(['outdoorPatientTest'])
    ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
    ->get();


    $data['appoinments'] = $appoinment_data = Appoinment::authCompany()
    ->whereBetween('date', [$from_date." 00:00:00",$to_date." 23:59:59"])
    ->get();


    $data = $this->company($data);

    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;

    foreach($appoinment_data as $app_data){

        $total_appoinment_discount =  $total_appoinment_discount + $app_data->discount;
        $total_appoinment_amount =  $total_appoinment_amount + $app_data->fee;

    }

    foreach($opd_data as $opdTest){

        $total_due =  $total_due + $opdTest->due;
        $total_discount =  $total_discount + $opdTest->discount;
        $total_paid_amount =  $total_paid_amount + $opdTest->total_paid;

        foreach($opdTest->outdoorPatientTest as $per_test){

            $total_test_price =  $total_test_price + $per_test->price;
            $total_commission =  $total_commission + $per_test->discount;
        }
    }

        // $total_payable_amount = $total_test_price - $total_discount;
        $data['total_test_price'] = $total_test_price;
        $data['total_discount'] = $total_discount;
        $data['total_due'] =  $total_due;

        $data['total_paid_amount'] = $total_paid_amount;
        $data['total_payable_amount'] = $total_test_price - $total_discount;
        $data['total_commission'] = $total_commission;
        $data['total_appoinment_amount'] = $total_appoinment_amount;
        $data['total_appoinment_discount'] = $total_appoinment_discount;


        //  dd($total_test_price - $total_discount , $data);
        return view('member.opd_collection_report.balance_sheet_report',$data);
    }


  public function opdnIdividualBillingDetails($id){


    // dd($id);
    $data['datas'] = $opd_data = OutDoorRegistration::authCompany()->with(['outdoorPatientTest','user','doctor','refDoctor'])->where('id',$id)
    ->first();
    $data = $this->company($data);

    $data['dob'] = $opd_data->day."/".$opd_data->month."/".$opd_data->year;


    // dd($data['datas'],$data['dob']);
    return view('member.opd_collection_report.opd_individual_billing_details',$data);
  }

        public function specimenCollection()
        {

            $data['specimens'] =  Specimen::authCompany()->pluck('specimen','id')->toArray();

        return view('member.opd_collection_report.specimen_wise_collection',$data);

        }

        public function specimenWiseCollectionSearch( Request $request)
        {


        // dd($request->all());

        $total_discount = 0;
        $discount = 0;
        $count = 0;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $specimen_id = $request->specimen_id;

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['specimen_type'] = '';
        $specimen_data = [];

     if($specimen_id == 'all'){

        $data['specimen_type'] = 'all';

      $data['specimens'] = $specimens =  Specimen::authCompany()->select('specimen','id')->get();


     foreach($specimens as $speci){
        $total_discount = 0;
         $specimen_data_is[$speci->specimen] = $test_data =  OutDoorPatientTest::authCompany()
         ->whereHas('testGroup', function($q) use($speci){
             $q->where('specimen_id',$speci->id);
            //  $q->where('specimen_id',7);
         })
         ->with(["testGroup","subTestGroup"])
         ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
         ->get()->groupBy('sub_test_group_id');

         foreach($test_data as $t_data){

             foreach($t_data as $per_data){
                 $total_discount =  $total_discount + $per_data->discount;
             }
           }
           $discount = $discount + $total_discount;

     }


    $data['discount'] =  $discount;
    $specimen_data['data_is'] =  $specimen_data_is;

    $data = $this->company($data);

     return view('member.opd_collection_report.all_specimen_wise_collection_report',$data,$specimen_data);
   }else{

    $data['datas'] = $test_data =  OutDoorPatientTest::authCompany()
        ->whereHas('testGroup', function($q) use($specimen_id){
            $q->where('specimen_id',$specimen_id);
        })
        ->with(["testGroup","subTestGroup"])
        ->whereBetween('created_at', [$from_date." 00:00:00",$to_date." 23:59:59"])
        ->get()->groupBy('sub_test_group_id');

        $data = $this->company($data);

        foreach($test_data as $t_data){

            foreach($t_data as $per_data){
                $total_discount =  $total_discount + $per_data->discount;
            }
          }

          $data['total_discount'] = $total_discount;

        $data['specimen_data'] =  Specimen::authCompany()->where('id',$specimen_id)->first();
   }



        return view('member.opd_collection_report.specimen_wise_collection_report',$data);

        }


        public function opdTestBarcodePrint($id){

            $data['barcode_data'] = OutDoorRegistration::authCompany()->with('outdoorPatientTest')->where('id',$id)->first();
             $data['no'] = 1;

             $pdf = PDF::loadView('member.out_door_registration.barcode_print',$data);
             $pdf->setPaper('a4', 'potrait');
             return $pdf->stream('odp_barcode');
            // return view('member.out_door_registration.barcode_print2',$data);

        }

}