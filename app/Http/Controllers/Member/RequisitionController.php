<?php

namespace App\Http\Controllers\Member;


use App\DataTables\RequisitionsDataTable;
use App\Http\Traits\CompanyInfoTrait;
use App\Models\Item;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;

class RequisitionController extends Controller
{
    use CompanyInfoTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RequisitionsDataTable $dataTable)
    {
        return $dataTable->render('member.requisitions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['products'] = Item::authCompany()->latest()->pluck('item_name', 'id');

        return view('member.requisitions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
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


         DB::beginTransaction();
         try{

            $requisitionId = Requisition::create($requisition);

        $requisitionDetails = [];
        $requisitionDetails['requisition_id'] = $requisitionId->id;

        $product = $request->product_id;
        $qty = $request->qty;
        $price = $request->price;
        $total_price = $request->total_price;
        $last_purchase_price = $request->last_purchase_price;
        $last_purchase_qty = $request->last_purchase_qty;
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
            // $requisitionDetails['last_purchase_price'] = $last_purchase_price[$i];
            // $requisitionDetails['last_purchase_qty'] = $last_purchase_qty[$i];
            $requisitionDetails['available_stock'] = $available_stock[$i];
            $requisitionDetails['description'] = $description[$i];
            $requisitionDetails['req_date'] = $requisitionDate;

            $total_amount += $qty[$i]*$price[$i];

            RequisitionDetail::create($requisitionDetails);

        }
            $requisitionId->total_price = $total_amount;
            $requisitionId->update();


        $status = ['type' => 'success', 'message' => 'Requisition done Successfully'];



         }catch (\Exception $e){

             $status = ['type' => 'danger', 'message' => 'Unable to save'];
             DB::rollBack();
         }

         DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.requisition.show', $requisitionId->id)->with('status', $status);
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
        if(config('pos.requisition.user_based'))
            $data['requisition'] = $requisition = Requisition::authUser()->findorFail($id);
        else
            $data['requisition'] = $requisition = Requisition::findorFail($id);


        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $requisition->id.Str::random(5);
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 2, 50) . '" alt="' . $requisition->id . '"   />';
        $data = $this->company($data);

        return view('member.requisitions.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_requisition($id)
    {
        if(config('pos.requisition.user_based'))
            $data['requisition'] = $requisition = Requisition::authUser()->findorFail($id);
        else
            $data['requisition'] = $requisition = Requisition::findorFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $requisition->id.Str::random(5);
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 2, 50) . '" alt="' . $requisition->id . '"   />';
        $data = $this->company($data);


        $data['report_title'] = "Requisition Invoice";

        return view('member.requisitions.print_requisitions', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(config('pos.requisition.user_based'))
            $data['modal'] = $requisition = Requisition::authUser()->findOrFail($id);
        else
            $data['modal'] = $requisition = Requisition::findOrFail($id);
        

        $data['products'] = Item::authCompany()->latest()->pluck('item_name', 'id');
        $data['requisition_products'] = Item::whereNotIn('id', $requisition->requisition_details()->pluck('item_id')->toArray())->latest()->pluck('item_name', 'id');

        return view('member.requisitions.edit', $data);
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
        $requisitionId = Requisition::find($id);

        $inputs = $request->all();

        $requisition = [];
        $requisition['date'] = $requisitionDate = db_date_format($inputs['date']);
        $requisition['total_price'] = $inputs['total_bill'];

        DB::beginTransaction();
        try{

        $requisitionId->update($requisition);
        $requisitionDetails = [];
        $requisitionDetails['requisition_id'] = $id;

        $product = $request->product_id;
        $qty = $request->qty;
        $price = $request->price;
//        $last_purchase_price = $request->last_purchase_price;
//        $last_purchase_qty = $request->last_purchase_qty;
        $available_stock = $request->available_stock;
        $description = $request->description;

        $requisition_details_id = $request->requisitionDetails;


        $deleteRequisitionDetail = RequisitionDetail::where('requisition_id', $id)->whereNotIn('id', $requisition_details_id)->get();


        foreach ($deleteRequisitionDetail as $value)
        {
            $requisition_details_id = RequisitionDetail::find($value->id);
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
                $requisitions_details_id = RequisitionDetail::find($requisition_details_id[$i]);
            }else{
                $requisitionDetails['requisition_id'] = $requisitionId->id;
            }

            $requisitionDetails['item_id'] = $item_id = $product[$i];
            $requisitionDetails['unit'] = $item->unit;
            $requisitionDetails['qty'] = $quantity = $qty[$i];
            $requisitionDetails['price'] = $price[$i];
            $requisitionDetails['total_price'] = $total_price = $qty[$i]*$price[$i];
//            $requisitionDetails['last_purchase_price'] = $last_purchase_price[$i];
//            $requisitionDetails['last_purchase_qty'] = $last_purchase_qty[$i];
            $requisitionDetails['available_stock'] = $available_stock[$i];
            $requisitionDetails['description'] = $description[$i];
            $requisitionDetails['req_date'] = $requisitionDate;

            $total_amount += $total_price;

            if($requisition_details_id[$i] != "new") {
                $requisitions_details_id->update($requisitionDetails);
            }else{
                RequisitionDetail::create($requisitionDetails);
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
            return redirect()->route('member.requisition.show', $requisitionId->id)->with('status', $status);
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
        $requisition = Requisition::findOrFail($id);

        if(!$requisition)
        {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }


        $requisition->requisition_details()->delete();
        $requisition->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }
}
