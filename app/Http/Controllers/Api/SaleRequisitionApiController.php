<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\SmsTrait;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesRequisition;
use App\Models\SalesRequisitionDetail;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Validator;

class SaleRequisitionApiController extends Controller
{
    use SmsTrait;

    public function index(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $data['sale_requisitions']  = SalesRequisition::where('is_sale', 0)->authCompany()
            ->where('sales_requisitions.created_by',Auth::user()->id)
            ->where('requisition_request_by', 1)
            ->latest()
            ->with(['dealer', 'store'])->get();

        return response($data);

    }


    public function create()
    {
        $data['products'] = Item::authCompany()->get()->pluck('item_details', 'id');

        $user = new User();
        $data['dealers'] = User::whereIn('id',$user->findRoleUser(['dealer']))->active()->authCompany()->get()->pluck('user_phone', 'id');

        $data['stores'] = Store::approved()->active()->authCompany()->get()->pluck('name_phone', 'id');

        return response($data);

    }


    /**
     * @param string $id
     * @return array
     */
    private function getValidationRules($id='')
    {
        $rules = [
            "date" => "required",
            "grand_total" => "required",
            "store_id" => "required",
            "dealer_id" => "required",
            "product_id"    => "required|array|min:1",
            "product_id.*"  => "required|string|distinct|min:1",
            "qty"    => "required|array|min:1",
            "qty.*"  => "required|string|distinct|min:1",
            "price"    => "required|array|min:1",
            "price.*"  => "required|string|distinct|min:1",
//            "total_price"    => "required|array|min:1",
//            "total_price.*"  => "required|string|distinct|min:1",
        ];

        return $rules;
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all(), 'status' =>'failed'], 422);
        }

        $requisition = [];
        $requisition['date'] = $requisitionDate = db_date_format($inputs['date']);
        $requisition['total_price'] = $inputs['grand_total'];
        $requisition['notation'] = $inputs['notation'];
        $requisition['dealer_id'] = $inputs['dealer_id'];
        $requisition['customer_id'] = $inputs['store_id'];


        if(Auth::user()->hasRole('sales_man'))
        {
            $requisition['requisition_request_by'] = 1;
            $customer = Store::findOrFail($inputs['store_id']);
            $auth = Auth::user()->full_name;

        }elseif(Auth::user()->hasRole('dealer')){

            $requisition['requisition_request_by'] = 0;
            $requisition['dealer_id'] = Auth::user()->id;
        }else{
            $requisition['requisition_request_by'] = 2;
        }



        DB::beginTransaction();
        try{

            $requisitionId = SalesRequisition::create($requisition);

            $requisitionDetails = [];
            $requisitionDetails['sales_requisition_id'] = $requisitionId->id;

            $product = $request->product_id;
            $qty = $request->qty;
            $price = $request->price;

            $total_amount = $insert = 0;
            for($i=0; $i<count($product); $i++){

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;


                if($qty[$i] < 1 || $price[$i] < 1 )
                {
                    break;
                }

                $item = Item::find($product[$i]);
                $requisitionDetails['item_id'] = $item_id = $product[$i];
                $requisitionDetails['unit'] = $item->unit;
                $requisitionDetails['qty'] = $quantity = $qty[$i];
                $requisitionDetails['price'] = $price[$i];
                $requisitionDetails['total_price'] = $qty[$i]*$price[$i];
                $requisitionDetails['free_qty'] = $item->free_qty ?? 0;
                $requisitionDetails['free'] = $free = $item->free_qty ? floor($quantity/$item->free_qty) : 0;
                $requisitionDetails['pack_qty'] = $item->pack_qty ?? 0;
                $requisitionDetails['carton'] =  $item->pack_qty ? floor($quantity/$item->pack_qty) : 0;
                $requisitionDetails['req_date'] = $requisitionDate;

                $total_amount += $qty[$i]*$price[$i];

                SalesRequisitionDetail::create($requisitionDetails);

                $insert++;

            }



            $requisitionId->total_price = $total_amount;
            $requisitionId->update();

            if($insert==0)
            {
                $requisitionId->delete();


                $status = ['type' => 'danger', 'message' => 'Unable to save, Product, Qty or Price - missing/Zero (0) '];

            }else{
                $companyName = Auth::user()->company->company_name;


                if(Auth::user()->hasRole('sales_man') || Auth::user()->hasRole('dealer'))
                {
                    // $sms = new SmsGatewayService();


//                if(Auth::user()->hasRole('dealer')){
//                    $msg = "Congrats your order Id : $requisitionId->id,  amount of $total_amount/=BDT has confirmed. It will delivered to you shortly by RG. Thanks for being with $companyName";
//
//                    $this->sendSMS(Auth::user()->phone, $msg);
//                    // $sms->send_single_sms(Auth::user()->phone, $msg);
//
//                }else{
//                    $msg = "Congrats your order Id : $requisitionId->id,  amount of $total_amount/=BDT has confirmed. It will delivered to you shortly from '". $requisitionId->dealer->uc_full_name."'. Thanks for being with $companyName";
//
//                    // $sms->send_single_sms($customer->phone, $msg);
//                    $this->sendSMS($customer->phone, $msg);
//                }

                }

                $status = ['type' => 'success', 'message' => 'Requisition done Successfully'];
            }



        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();


        if($status['type'] == 'success')
        {
            return response($status);
        }else{

            return response($status, 422);
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
        $data['requisition'] = $requisition = SalesRequisition::authUser()->findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $requisition->id.Str::random(5);
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 25) . '" alt="' . $requisition->id . '"   />';

        $data = $this->company($data);

        return response($data);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = $requisition = SalesRequisition::authUser()->with('sales_requisition_details', 'dealer', 'store')->findOrFail($id);
        $data['products'] = Item::authCompany()->get()->pluck('item_details', 'id');

        $user = new User();
        $data['dealers'] = User::whereIn('id',$user->findRoleUser(['dealer']))->active()->authCompany()->get()->pluck('user_phone', 'id');

        $data['stores'] = Store::approved()->active()->authCompany()->get()->pluck('name_phone', 'id');


        return response($data);
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

        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all(), 'status' =>'failed'], 422);
        }


        $requisitionId = SalesRequisition::find($id);

        $inputs = $request->all();

        $requisition = [];
        $requisition['date'] = $requisitionDate = db_date_format($inputs['date']);
        $requisition['total_price'] = $inputs['grand_total'];
        $requisition['notation'] = $inputs['notation'];
        $requisition['dealer_id'] = $inputs['dealer_id'] ?? null;
        $requisition['customer_id'] = $inputs['store_id'];

       if(Auth::user()->hasRole('dealer')){
            $requisition['dealer_id'] = Auth::user()->id;
        }

        DB::beginTransaction();
        try{

            $requisitionId->update($requisition);
            $requisitionDetails = [];
            $requisitionDetails['sales_requisition_id'] = $id;

            $product = $request->product_id;
            $qty = $request->qty;
            $price = $request->price;

            SalesRequisitionDetail::where('sales_requisition_id', $id)->delete();


            $requisitionDetails = [];
            $total_amount = 0;
            for($i=0; $i<count($product); $i++){
                $item = Item::find($product[$i]);

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;


                if($qty[$i] < 1 || $price[$i] <1 )
                {
                    break;
                }


                $requisitionDetails['sales_requisition_id'] = $requisitionId->id;
                $requisitionDetails['item_id'] = $item_id = $product[$i];
                $requisitionDetails['unit'] = $item->unit;
                $requisitionDetails['qty'] = $quantity = $qty[$i];
                $requisitionDetails['price'] = $price[$i];
                $requisitionDetails['total_price'] = $total_price = $qty[$i]*$price[$i];
                $requisitionDetails['free_qty'] = $item->free_qty ?? 0;
                $requisitionDetails['free'] = $free = $item->free_qty ? floor($quantity/$item->free_qty) : 0;
                $requisitionDetails['pack_qty'] = $item->pack_qty ?? 0;
                $requisitionDetails['carton'] =  $item->pack_qty ? floor($quantity/$item->pack_qty) : 0;
                $requisitionDetails['req_date'] = $requisitionDate;

                $total_amount += $total_price;

                SalesRequisitionDetail::create($requisitionDetails);

            }


            $requisition = [];
            $requisition['total_price'] = $total_amount;
            $requisitionId->update($requisition);

            $status = ['type' => 'success', 'message' => 'Requisition update Successfully'];


        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to update'];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return response($status);
        }else{

            return response($status, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requisition = SalesRequisition::findOrFail($id);

        if(!$requisition)
        {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }


        $requisition->sales_requisition_details()->delete();
        $requisition->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }



}
