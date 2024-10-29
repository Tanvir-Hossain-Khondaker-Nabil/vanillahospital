<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Appoinment;
use App\Models\Transactions;
use App\Models\Doctor;
use App\Models\Company;
use App\Models\SupplierOrCustomer;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;

use Intervention\Image\Facades\Image;
use App\Http\Traits\CompanyInfoTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\TransactionTrait;
use App\Http\Services\CustomerSave;
use Carbon\Carbon;

class AppoinmentController extends Controller
{
    use TransactionTrait, CompanyInfoTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  dd($request->all());

        //  dd(db_date_format($request->date));
        $query = Appoinment::AuthCompany()->orderBy('created_at','DESC');



        if(@$request->date && $request->date != ""){

            $query = $query->whereDate('date', db_date_format($request->date));
        }

        if(@$request->patient_name){
            // dd('2');
            $query = $query->where('patient_name', 'like', '%'.$request->patient_name.'%' );
        }

        if(@$request->doctor_id && $request->doctor_id != ""){
            // dd('3',$request->all());
            $query = $query->where('doctor_id', $request->doctor_id);
        }


        $data['appoinments'] = $query->get();
        $data['doctors'] = Doctor::AuthCompany()->orderBy('name','ASC')->get();
        // dd($data);
        return view('member.appoinment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(time());
        $data['appiontment_id'] = "APP".time();
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->get();
        // dd($data);
        return view('member.appoinment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

        $company = Company::where('id',$company_id)->value('company_name');

        $phone = trim($request->phone);

        // dd($request->all());

        $inputs_is = $request->all();

        if(@$request->image){
            $png_url = time().".png";
            $path = public_path('uploads/appointment/' . $png_url);
            Image::make(file_get_contents($request->image))->save($path);
            $inputs_is['image'] = $png_url;
        }


        $inputs_is['date'] = db_date_format($request->date);
        $inputs_is['created_by'] = $user_id;
        $inputs_is['company_id'] = $company_id;

        $add_customer = SupplierOrCustomer::where('phone',$phone)->first();


        DB::beginTransaction();
        try{


        $account = AccountType::where('display_name', 'Appoinment')->first();
        $discount_account_type = AccountType::where('display_name', 'Discount')->first();

        $customerSave = new CustomerSave();


        $trans['transaction_method'] = $inputs['transaction_method'] = "Received";
        $trans['cash_or_bank_id'] = $request->cash_or_bank_id;
        $trans['supplier_id'] = "";
        $trans['date'] = $inputs['date'] = Carbon::today();
        $trans['initial_balance'] = $request->fee;

         if(!$add_customer){
            $add_customer = $customerSave->create_pos_customer($phone);

            if($add_customer){
                $add_customer->account_type->update([
                    'display_name'=>$request->patient_name,
                ]);
            }
         }


        $set_transaction = $this->set_transaction($trans);

        $inputs_is['transaction_id'] = $set_transaction;
        $inputs_is['account_type_id'] = $add_customer->account_type->id;
        $appion_data = Appoinment::create($inputs_is);

            // $account= CashOrBankAccount::AuthCompany()->with('account_type')->where('id',$request->cash_or_bank_id)->first();
            $against_account_type= CashOrBankAccount::AuthCompany()->with('account_type')->where('id',$request->cash_or_bank_id)->first();

            $inputs['account_type_id'] = $account->id;
            $inputs['account_name'] = $account->display_name;

            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->account_type->display_name;


            $inputs['to_account_name'] = '';
            $inputs['payment_method_id'] = 1; // 1= Cash, 2= Bank
            $inputs['transaction_id'] = $set_transaction;
            $inputs['amount'] = $request->fee;
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = " Appointment id : " . $appion_data->appointment_id . ", patient name: " . $appion_data->patient_name;
            $this->createCreditAmount($inputs);


            $inputs['account_type_id'] = $against_account_type->account_type_id  ;
            $inputs['account_name'] = $against_account_type->account_type->display_name;
            $inputs['against_account_type_id'] = $account->id;
            $inputs['against_account_name'] = $account->display_name;
            $inputs['amount'] = $request->paid_amount;
            $inputs['to_account_name'] = '';
            $inputs['transaction_type'] = 'dr';
            $this->createDebitAmount($inputs);

            if($request->discount > 0){

                $inputs['account_type_id'] = $discount_account_type->id ;
                $inputs['account_name'] =$discount_account_type->display_name;

                $inputs['against_account_type_id'] = $account->id;
                $inputs['against_account_name'] = $account->display_name;
                $inputs['amount'] = $request->discount;
                $inputs['to_account_name'] = '';
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);
            }

            if($request->due > 0 && $add_customer){

                $inputs['account_type_id'] = $add_customer->account_type_id;
                $inputs['account_name'] =$add_customer->account_type->display_name;

                $inputs['against_account_type_id'] = $account->id;
                $inputs['against_account_name'] = $account->display_name;
                $inputs['amount'] = $request->due;
                $inputs['to_account_name'] = '';
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);
            }


            $status = ['type' => 'success', 'message' => "Successfully Added"];
         }catch (\Exception $e){
            dd($e);
            $status = ['type' => 'danger', 'message' => 'Appoinment unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->back()->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
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
        $data['appoinment'] = Appoinment::authCompany()->where('id',$id)->first();
        $data = $this->company($data);
        return view('member.appoinment.print_appiontment',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['doctors'] = Doctor::AuthCompany()->where('status','active')->get();
        $data['model'] = Appoinment::findOrFail($id);
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        // dd($data);
        return view('member.appoinment.edit', $data);
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

        $user_id = \Auth::user()->id;

        $data = Appoinment::findOrFail($id);
        // dd($request->all(), $data);

        $inputs = $request->all();
        // dd($inputs);
        if($request->image){
            $png_url = time().".png";
            $path = public_path('uploads/appointment/' . $png_url);
            Image::make(file_get_contents($request->image))->save($path);
            $inputs['image'] = $png_url;
        }


        $inputs['date'] = db_date_format($request->date);
        $inputs['updated_by'] = $user_id;
        // dd($request->all(), $data);
         $add_customer = AccountType::where('id', $data->account_type_id)->first();
        //  dd($data->account_type_id, $request->all());
        DB::beginTransaction();
        try{

            $data->update($inputs);

            $modal = Transactions::authCompany()->where('id', $data->transaction_id)->first();

            $this->transactionRevertAmount($modal->id);
            $modal->transaction_details()->delete();


            $account = AccountType::where('display_name', 'Appoinment')->first();
            $discount_account_type = AccountType::where('display_name', 'Discount')->first();


            $inputs_is['transaction_id'] = $data->transaction_id;


                // $account= CashOrBankAccount::AuthCompany()->with('account_type')->where('id',$request->cash_or_bank_id)->first();
                $against_account_type= CashOrBankAccount::AuthCompany()->with('account_type')->where('id',$request->cash_or_bank_id)->first();

                $inputs['transaction_method'] = "Received";
                $inputs['date'] = Carbon::today();
                $inputs['account_type_id'] = $account->id;
                $inputs['account_name'] = $account->display_name;

                $inputs['against_account_type_id'] = $against_account_type->account_type_id;
                $inputs['against_account_name'] = $against_account_type->account_type->display_name;


                $inputs['to_account_name'] = '';
                $inputs['payment_method_id'] = 1; // 1= Cash, 2= Bank
                $inputs['transaction_id'] = $data->transaction_id;
                $inputs['amount'] = $request->fee;
                $inputs['transaction_type'] = 'cr';
                $inputs['description'] = " Appointment id : " . $data->appointment_id . ", patient name: " . $data->patient_name;
                $this->createCreditAmount($inputs);


                $inputs['account_type_id'] = $against_account_type->account_type_id  ;
                $inputs['account_name'] = $against_account_type->account_type->display_name;
                $inputs['against_account_type_id'] = $account->id;
                $inputs['against_account_name'] = $account->display_name;
                $inputs['amount'] = $request->paid_amount;
                $inputs['to_account_name'] = '';
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);

                if($request->discount > 0){

                    $inputs['account_type_id'] = $discount_account_type->id ;
                    $inputs['account_name'] =$discount_account_type->display_name;

                    $inputs['against_account_type_id'] = $account->id;
                    $inputs['against_account_name'] = $account->display_name;
                    $inputs['amount'] = $request->discount;
                    $inputs['to_account_name'] = '';
                    $inputs['transaction_type'] = 'dr';
                    $this->createDebitAmount($inputs);
                }

                if($request->due > 0 && $add_customer){

                    $inputs['account_type_id'] = $add_customer->id;
                    $inputs['account_name'] =$add_customer->display_name;

                    $inputs['against_account_type_id'] = $account->id;
                    $inputs['against_account_name'] = $account->display_name;
                    $inputs['amount'] = $request->due;
                    $inputs['to_account_name'] = '';
                    $inputs['transaction_type'] = 'dr';
                    $this->createDebitAmount($inputs);
                }

            $status = ['type' => 'success', 'message' => 'Successfully Updated'];

        }catch (\Exception $e){
            dd($e);
            $status = ['type' => 'danger', 'message' => 'Appoinment unable to update'];
            DB::rollBack();
        }

        DB::commit();

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
        $modal = Appoinment::findOrFail($id);

        if (!$modal) {
            return response()->json([
                'data' => [
                    'message' => trans('common.unable_to_delete')
                ]
            ], 400);
        }

        DB::beginTransaction();
        try{

            $this->transactionDestroy($modal->transaction_id);
            $modal->delete();

        }catch (\Exception $e){
            dd($e);
            $status = ['type' => 'danger', 'message' => 'Appoinment unable to delete'];
            DB::rollBack();
        }

        DB::commit();

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
            'phone' => 'required',
            'visit_time' => 'required',
            'doctor_id' => 'required',
            'schedule' => 'required',
            'date' => 'required',
        ];

        return $rules;

   }

   public function fetchSchedule(Request $request){

    // dd($request->all(),db_date_format($request->date));

    $data = Appoinment::AuthCompany()
                         ->whereDate('date',db_date_format($request->date))
                         ->where('doctor_schedule_day_id',$request->doctor_schedule_day_id)
                         ->where('doctor_id',$request->doctor_id)
                         ->count();

    // dd($schedule);
    return response()->json([
        'data' => $data,
        'status' => 200,
    ]);
   }


}