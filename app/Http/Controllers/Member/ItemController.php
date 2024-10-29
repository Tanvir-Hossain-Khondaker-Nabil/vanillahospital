<?php

namespace App\Http\Controllers\Member;

use App\DataTables\ItemsDataTable;
use App\Http\Services\WarehouseStock;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\StockTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Variant;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\DNS1D;

class ItemController extends Controller
{
    use FileUploadTrait, CompanyInfoTrait, StockTrait;


    public function __construct()
    {
//        if($this->checkCompanySet() == false)
//        {
//            $status = [
//                'type' => 'danger',
//                'message' => 'You are not assign for any company. please confirm company'
//            ];
//
//            return redirect()->route('member.dashboard')->with('status', $status)->send();
//        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ItemsDataTable $dataTable)
    {
        return $dataTable->render('member.items.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['categories'] = Category::all()->pluck('display_name', 'id');
        $data['units'] = Unit::all()->pluck('name', 'name');
        $data['brands'] = Brand::all()->pluck('name', 'id');
        $data['product_types'] = ['Normal','Capex','Opex'];

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        if(config('settings.variant') && $request->variant=="active")
        {
            $data['variants'] = Variant::authCompany()->active()->pluck('title', 'id');
            return view('member.items.create_variant_product', $data);
        }else{
            return view('member.items.create', $data);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        if($request->ajax())
        {
            $request['productCode'] = sprintf("%04d", (Item::count()+1));
        }

        $request['skuCode'] = $request->productCode;
        $request['skuCode'] = $request->productCode;
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $inputs = $request->all();
        $inputs['date']  = $date = db_date_format($request->initial_date);
        $customMessages = [
            'item_name.unique_with' => 'The :attribute is already exist for this company'
        ];

        $this->validate($request, $this->rules(), $customMessages);

        $inputs['purchase_price'] = $request->purchase_price ?? 0;
        $inputs['productCode'] = $request->productCode;
        $inputs['skuCode'] = $request->skuCode;
        $inputs['company_id'] = Auth::user()->company_id;
        $inputs['member_id'] = Auth::user()->member_id;

        if($request->hasFile('product_image'))
        {
            $image = $request->file('product_image');

            $upload = $this->fileUpload($image, '/product_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => trans('common.image_must_be').' JPG'];

                if($request->ajax())
                {
                    return response()->json($status, 404);
                }else{
                    return back()->with('status', $status)->withInput();
                }
            }

            $inputs['product_image'] = $upload;
        }

        $item = Item::create($inputs);

        if(isset($request->stock)){

            $this->stockIn($item->id, $request->stock);
            $this->stock_report($item->id, $request->stock, 'initial', $inputs['date']);
        }


        if(config('settings.warehouse') && isset($request->warehouse_id) && count($request->warehouse_id)>0)
        {
            $code = date("Ymdhis");

            $warehouses = $request->warehouse_id;
            $load_qty = $request->unload_qty;
            $warehouseCount = count($warehouses);

            for ($j = 0; $j < $warehouseCount; $j++) {

                $inputs = [];
                $inputs['code'] = $code;
                $inputs['company_id'] = $item->company_id;
                $inputs['warehouse_id'] = $warehouseId = $warehouses[$j];
                $inputs['model'] = "Initial";
                $inputs['model_id'] = 1;
                $inputs['item_id'] = $item->id;
                $inputs['qty'] = $load_qty[$j];
                $inputs['date'] = $date;

                WarehouseHistory::create($inputs);

                $warehouse_stock = new WarehouseStock();
                $warehouse_stock->set($item->id,$warehouseId, $load_qty[$j], $date, 'initial');
                $warehouse_stock->stockIn();
            }
        }


        if($request->ajax())
        {
            $products = Item::whereHas('category', function ($query){
                $query->where('name','!=', 'shopping_bags');
            })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');

            $status = ['type' => 'success', 'message' => trans('common.successfully_added'), 'items' => $item, 'products'=> $products ];

            return response()->json($status, 200);
        }else{
            $status = ['type' => 'success', 'message' => trans('common.successfully_added')];

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
        $data['modal'] = $item = Item::findorFail($id);
        $data['categories'] = Category::all()->pluck('display_name', 'id');
        $data['units'] = Unit::all()->pluck('name', 'name');
        $data['brands'] = Brand::all()->pluck('name', 'id');

        if(config('settings.warehouse'))
        {
            $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');
            $data['modal']['warehouses'] = WarehouseHistory::where('model', 'initial')->where('item_id', $id)->get();
        }

        if(config('settings.variant') && $item->main_product_id>0)
        {
            $data['variants'] = Variant::authCompany()->active()->pluck('title', 'id');
            $data['variant_ids'] = $item->variants->pluck('variant_id')->toArray();
            $data['variantData'] = $item->variants;
            $data['initial_warehouses'] = $item->initial_warehouses->pluck('warehouse_id')->toArray();

            $main_product = optional($item->main_product)->name;
            $items = Item::where('main_product_id', $item->main_product_id)->get();

            foreach ($items as $main_item)
            {
                $item_opening_stock = $main_item->stock_report()->orderBy('date', 'asc')->first();
                $data['variant_products'][$main_item->id]['name'] = trim(str_replace($main_product, "", $main_item->item_name));
                $data['variant_products'][$main_item->id]['id'] = $main_item->id;
                $data['variant_products'][$main_item->id]['image'] = $main_item->product_image ? $main_item->product_image_path : '';
                $data['variant_products'][$main_item->id]['skuCode'] = $main_item->skuCode;
                $data['variant_products'][$main_item->id]['purchase_price'] = $main_item->purchase_price;
                $data['variant_products'][$main_item->id]['price'] = $main_item->price;
                $data['variant_products'][$main_item->id]['initial_warehouses'] = $main_item->initial_warehouses;
                $data['variant_products'][$main_item->id]['stock'] = $item_opening_stock ? $item_opening_stock->opening_stock : 0;
                $data['variant_products'][$main_item->id]['initial_date'] = create_date_format($item_opening_stock ? $item_opening_stock->date :Carbon::parse($item->created_at)->toDateString(), '/');
            }

            return view('member.items.edit_variant_product', $data);
        }else{

            $item_opening_stock = $item->stock_report()->orderBy('date', 'asc')->first();
            $data['modal']['stock'] = $item_opening_stock ? $item_opening_stock->opening_stock : 0;
            $data['modal']['initial_date'] = create_date_format($item_opening_stock ? $item_opening_stock->date :Carbon::parse($item->created_at)->toDateString(), '/');

            return view('member.items.edit', $data);
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
        $modal = $item = Item::find($id);
        $request['skuCode'] = $request->productCode;
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $inputs = $request->all();

        $customMessages = [
            'item_name.unique_with' => 'The :attribute is already exist for this company'
        ];

        $this->validate($request, $this->rules($id), $customMessages);


        if($request->hasFile('product_image'))
        {
            $image = $request->file('product_image');

            $upload = $this->fileUpload($image, '/product_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => trans('common.image_must_be').' JPG'];
                return back()->with('status', $status);
            }
            $inputs['product_image'] = $upload;
        }

        $modal->update($inputs);

        if(isset($request->stock)){

            $item_opening_stock = $modal->stock_report()->orderBy('date', 'asc')->first();
            $last_opening_stock = $item_opening_stock->opening_stock;
            $last_initial_date = $item_opening_stock->date;

            if($item_opening_stock->opening_stock != $request->stock)
            {
                $differBetweenStock = $item_opening_stock->opening_stock - $request->stock;
                if($differBetweenStock>0)
                {
                    $this->stockOut($modal->id, $differBetweenStock, 'stock out');
                }else{
                    $this->stockIn($modal->id, $differBetweenStock*(-1) );
                }

                $this->stock_report($modal->id, $request->stock, 'initial', db_date_format($inputs['initial_date']));
            }



            if(config('settings.warehouse') && isset($request->warehouse_id) && count($request->warehouse_id)>0)
            {
                $warehouse_stocks = WarehouseHistory::where('model', 'initial')->where('item_id', $id)->get();

                foreach ($warehouse_stocks as $value)
                {

                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($item->id, $value->warehouse_id, 0, $value->date, 'initial');
                    $warehouse_stock->stockIn();
                }


                WarehouseHistory::where('model', 'initial')->where('item_id', $id)->delete();

                $warehouses = $request->warehouse_id;
                $code = date("Ymdhis");
                $date = db_date_format($inputs['initial_date']);
                $load_qty = $request->unload_qty;
                $warehouseCount = count($warehouses);

                for ($j = 0; $j < $warehouseCount; $j++) {

                    $inputs = [];
                    $inputs['code'] = $code;
                    $inputs['company_id'] = $item->company_id;
                    $inputs['warehouse_id'] = $warehouseId = $warehouses[$j];
                    $inputs['model'] = "Initial";
                    $inputs['model_id'] = 1;
                    $inputs['item_id'] = $item->id;
                    $inputs['qty'] = $load_qty[$j];
                    $inputs['date'] = $date;

                    WarehouseHistory::create($inputs);

                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($item->id, $warehouseId, $load_qty[$j], $date, 'initial');
                    $warehouse_stock->stockIn();
                }

            }
        }

        /**
         *  TODO: Before update image last image will be deleted.
         */

        $status = ['type' => 'success', 'message' => trans('common.successfully_updated')];

        return back()->with('status', $status);
    }

    public function print_barcode_form(Request $request)
    {
        $data['products'] = Item::get()->pluck('item_name', 'id');

        return view('member.items.barcode_print_form', $data);
    }

    public function print_barcode(Request $request)
    {
        $item = Item::find($request->item_id);

        $data['item'] = $item;
        $data['barcode'] = DNS1D::getBarcodeHTML( $item->productCode, "C128", 1,33);
        $data['print_qty'] = $request->print_qty;
        $data = $this->company($data);
//        $data['full_url'] =  $request->fullUrl().($request->fullUrl() == $request->url() ? "?" : "&");

//        if($request->type=="print" || $request->type=="download") {
//            if ($request->type == "print") {
//                return View('member.items.print_barcode', $data);
//            } else if ($request->type == "download") {
//                $pdf = PDF::loadView('member.items.print_barcode', $data);
//                $file_name = file_name_generator("Print_Product_Barcode");
//                return $pdf->download($file_name);
//            }
//        }else{
//            return view('member.items.show_product_barcode', $data);
        return View('member.items.print_barcode', $data);
//        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = Item::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);
    }

    private function rules($id='')
    {
        if($id==''){
            $rules = [
                'item_name' => 'required|unique_with:items,item_name,company_id,member_id',
                'productCode' => 'required|unique:items,productCode',
                'skuCode' => 'unique:items,skuCode',
                'unit' => 'required',
            ];
        }else{
            $rules = [
                'item_name' => 'required|unique_with:items,item_name,company_id,member_id,'.$id,
                'productCode' => 'required|unique:items,productCode,'.$id,
                'skuCode' => 'unique:items,skuCode,'.$id,
                'unit' => 'required',
            ];
        }

        return $rules;
    }
}
