<?php

namespace App\Http\Controllers\Member;


use App\DataTables\SalesByRequisitionDataTable;
use App\DataTables\SalesFormRequisitionDataTable;
use App\DataTables\SalesRequisitionsDataTable;
use App\DataTables\DealerSalesRequisitionsDataTable;
use App\Http\Traits\CompanyInfoTrait;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SalesRequisition;
use App\Models\SalesRequisitionDetail;
use App\Models\Store;
use App\Models\SupplierOrCustomer;
use App\Models\User;
use App\Services\SmsGatewayService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\SmsTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;
use App\Models\Customer;

class SalesRequisitionController extends Controller
{
    use CompanyInfoTrait, SmsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SalesRequisitionsDataTable $dataTable)
    {
        return $dataTable->render('member.sales_requisitions.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dealer_index(DealerSalesRequisitionsDataTable $dataTable)
    {
        return $dataTable->render('member.sales_requisitions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['products'] = Item::authCompany()->get()->pluck('item_details', 'id');

        $user = new User();
        $data['dealers'] = User::whereIn('id',$user->findRoleUser(['dealer']))->active()->authCompany()->get()->pluck('user_phone', 'id');

        $data['customers'] = Store::approved()->active()->authArea()->authCompany()
                                                ->get()->pluck('name_phone', 'id');

        return view('member.sales_requisitions.create', $data);
    }

    /**
     * Store a newly created resource in storage.sale_requisition
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();


        $requisition = [];
        $requisition['date'] = $requisitionDate = db_date_format($inputs['date']);
        $requisition['total_price'] = $inputs['total_bill'];
        $requisition['notation'] = $inputs['notation'];
        $requisition['dealer_id'] = $inputs['dealer_id'] ?? null;
        $requisition['customer_id'] = $inputs['customer_id'] ?? null;

        if(Auth::user()->hasRole('sales_man'))
        {
            $requisition['requisition_request_by'] = 1;
            $customer = Store::findOrFail($inputs['customer_id']);
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
            $total_price = $request->total_price;
            $available_stock = $request->available_stock;
            $description = $request->description;

            $total_amount = 0;
            for($i=0; $i<count($product); $i++){

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;


                if($qty[$i] < 1 || $total_price[$i] < 1 )
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
                $requisitionDetails['available_stock'] = $available_stock[$i];
                $requisitionDetails['description'] = $description[$i];
                $requisitionDetails['req_date'] = $requisitionDate;

                $total_amount += $qty[$i]*$price[$i];

                SalesRequisitionDetail::create($requisitionDetails);

            }
            $requisitionId->total_price = $total_amount;
            $requisitionId->update();

            $companyName = Auth::user()->company->company_name;


            if(Auth::user()->hasRole('sales_man') || Auth::user()->hasRole('dealer'))
            {
                // $sms = new SmsGatewayService();


                if(Auth::user()->hasRole('dealer')){
                    $msg = "Congrats your order Id : $requisitionId->id,  amount of $total_amount/=BDT has confirmed. It will delivered to you shortly by RG. Thanks for being with $companyName";

                    $this->sendSMS(Auth::user()->phone, $msg);
                    // $sms->send_single_sms(Auth::user()->phone, $msg);

                }else{
                    $msg = "Congrats your order Id : $requisitionId->id,  amount of $total_amount/=BDT has confirmed. It will delivered to you shortly from '". $requisitionId->dealer->uc_full_name."'. Thanks for being with $companyName";

                    // $sms->send_single_sms($customer->phone, $msg);
                    $this->sendSMS($customer->phone, $msg);
                }

            }

             $status = ['type' => 'success', 'message' => 'Requisition done Successfully'];
//
         }catch (\Exception $e){

             $status = ['type' => 'danger', 'message' => 'Unable to save'];
             DB::rollBack();
         }

         DB::commit();


        if($status['type'] == 'success')
        {
            return redirect()->route('member.sales_requisitions.show', $requisitionId->id)->with('status', $status);
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
        $data['requisition'] = $requisition = SalesRequisition::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $requisition->id.Str::random(5);
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 25) . '" alt="' . $requisition->id . '"   />';

        $data = $this->company($data);

        return view('member.sales_requisitions.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_requisition($id)
    {
        $data['requisition'] = $requisition = SalesRequisition::authUser()->findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $requisition->id.Str::random(5);
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $requisition->id . '"   />';
        $data = $this->company($data);

        $data['report_title'] = "Requisition Invoice";

        return view('member.sales_requisitions.print_requisitions', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = $requisition = SalesRequisition::authUser()->findOrFail($id);
        $data['products'] = Item::authCompany()->get()->pluck('item_details', 'id');
        $data['requisition_products'] = Item::whereNotIn('id', $requisition->sales_requisition_details()->pluck('item_id')->toArray())->get()->pluck('item_details', 'id');

        $user = new User();
        $data['dealers'] = User::whereIn('id',$user->findRoleUser(['dealer']))->active()->authCompany()->get()->pluck('user_phone', 'id');

        $data['customers'] = Store::approved()->active()->authArea()->authCompany()->get()->pluck('name_phone', 'id');

        return view('member.sales_requisitions.edit', $data);
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
        $requisitionId = SalesRequisition::find($id);

        $inputs = $request->all();

        $requisition = [];
        $requisition['date'] = $requisitionDate = db_date_format($inputs['date']);
        $requisition['total_price'] = $inputs['total_bill'];


        DB::beginTransaction();
        try{

            $requisitionId->update($requisition);
            $requisitionDetails = [];
            $requisitionDetails['sales_requisition_id'] = $id;

            $product = $request->product_id;
            $qty = $request->qty;
            $price = $request->price;
            $available_stock = $request->available_stock;
            $description = $request->description;

            $requisition_details_id = $request->requisitionDetails;


            $deleteRequisitionDetail = SalesRequisitionDetail::where('sales_requisition_id', $id)->whereNotIn('id', $requisition_details_id)->get();


            foreach ($deleteRequisitionDetail as $value)
            {
                $requisition_details_id = SalesRequisitionDetail::find($value->id);
                $requisition_details_id->update();

                $value->delete();
            }

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

                if($requisition_details_id[$i] != "new"){
                    $requisitions_details_id = SalesRequisitionDetail::find($requisition_details_id[$i]);
                }else{
                    $requisitionDetails['sales_requisition_id'] = $requisitionId->id;
                }

                $requisitionDetails['item_id'] = $item_id = $product[$i];
                $requisitionDetails['unit'] = $item->unit;
                $requisitionDetails['qty'] = $quantity = $qty[$i];
                $requisitionDetails['price'] = $price[$i];
                $requisitionDetails['total_price'] = $total_price = $qty[$i]*$price[$i];
                $requisitionDetails['free_qty'] = $item->free_qty ?? 0;
                $requisitionDetails['free'] = $free = $item->free_qty ? floor($quantity/$item->free_qty) : 0;
                $requisitionDetails['pack_qty'] = $item->pack_qty ?? 0;
                $requisitionDetails['carton'] =  $item->pack_qty ? floor($quantity/$item->pack_qty) : 0;
                $requisitionDetails['available_stock'] = $available_stock[$i];
                $requisitionDetails['description'] = $description[$i];
                $requisitionDetails['req_date'] = $requisitionDate;

                $total_amount += $total_price;

                if($requisition_details_id[$i] != "new" && $requisitions_details_id) {
                    $requisitions_details_id->update($requisitionDetails);
                }else{
                    SalesRequisitionDetail::create($requisitionDetails);
                }
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
            return redirect()->route('member.sales_requisitions.show', $requisitionId->id)->with('status', $status);
        }else{
            return redirect()->back()->with('status', $status);
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sale_requisitions_list(SalesByRequisitionDataTable $dataTable)
    {
        return $dataTable->render('member.sales.requisition_sale_list');
    }


    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function requisitions(SalesFormRequisitionDataTable $dataTable)
    {
        return $dataTable->render('member.sales.requisition_sale_list');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sale_requisition($id)
    {
        $lastId = Sale::latest()->pluck('id')->first();
        $data['is_requisition'] = 1;
        $data['model'] = $sales_requisition = SalesRequisition::find($id);
        $data['sale_products'] = Item::whereNotIn('id', $sales_requisition->sales_requisition_details()
            ->pluck('item_id')->toArray())
            ->latest()->pluck('item_name', 'id');

        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();
        $data['products'] = Item::whereHas('category', function ($query){
            $query->where('name','!=', 'shopping_bags');
        })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');

        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()
            ->authCompany()->latest()->pluck('title', 'id');

        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['bags'] = Item::whereHas('category', function ($query){
            $query->where('name', 'shopping_bags');
        })->authCompany()->latest()->get();

//        if(Auth::user()->hasRole(['dealer']))
//            $data['customers'] = SupplierOrCustomer::where('user_id',Auth::user()->id)->latest()->get()->pluck('name_phone', 'id')->toArray();


        if(Auth::user()->hasRole('sales_man'))
        {
            $data['customers'] = Store::approved()->active()->authArea()->authCompany()->get()->pluck('name_phone', 'id')->toArray();

        }elseif(Auth::user()->hasRole('dealer')){

            $data['customers'] = Store::approved()->active()->authCompany()->get()->pluck('name_phone', 'id')->toArray();

        }else{
            $data['customers'] = SupplierOrCustomer::latest()->get()->pluck('name_phone', 'id')->toArray();

        }


        return view('member.sales.sale_requisition', $data);
    }



}
