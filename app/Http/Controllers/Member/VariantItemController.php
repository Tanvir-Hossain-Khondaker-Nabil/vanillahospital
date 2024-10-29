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
use App\Models\ItemVariant;
use App\Models\MainProduct;
use App\Models\Unit;
use App\Models\Variant;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Milon\Barcode\DNS1D;

class VariantItemController extends Controller
{
    use FileUploadTrait, CompanyInfoTrait, StockTrait;


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());

        $variant_products = $request->variants;
        $product_skuCodes = $request->skuCode;
        $product_purchase_prices = $request->purchase_price;
        $product_prices = $request->price;
        $product_initial_date = $request->initial_date;
        $product_stocks = $request->stock;
        $product_warehouses = $request->warehouses;

        $product_variant_ids = $request->variant_id;
        $product_images = $request->file('product_image');


        $product = [];
        $product['name'] = $request->item_name;
        $product['company_id'] = Auth::user()->company_id;
        $mainProduct = MainProduct::firstOrCreate($product);

        $message = "";
        foreach ($variant_products as $key => $product)
        {
            $upload = '';
            if(isset($product_images[$key]))
            {
                $image = $product_images[$key];
                $upload = $this->fileUpload($image, '/product_image/', null);
            }


            $inputs = [];
            $inputs['item_name'] = $item_name = $request->item_name." ".$product;
            $inputs['productCode'] = $productCode = $request->productCode."-".$key;
            $inputs['unit'] = $request->unit;
            $inputs['skuCode'] = $product_skuCodes[$key] ?? $productCode;
            $inputs['category_id'] = $request->category_id;
            $inputs['main_product_id'] = $mainProduct->id;

            $customMessages = [
                'item_name.unique_with' => 'The :attribute is already exist for this company'
            ];

            $validator = Validator::make($inputs, $this->rules(), $customMessages);

            if($validator->fails()) {
                $message .= $item_name."-".$productCode.", ";
                Session::put('validation_errors', $validator->errors());
                break;
            }

            $inputs['brand_id'] = $request->brand_id;
            $inputs['is_service'] = $request->is_service;
            $inputs['is_repair_item'] = $request->is_repair_item;
            $inputs['guarantee'] = $request->guarantee;
            $inputs['warranty'] = $request->warranty;

            $inputs['date']  = $date = db_date_format($product_initial_date[$key]);
            $inputs['purchase_price'] = $product_purchase_prices[$key];
            $inputs['price'] = $product_prices[$key];
            $inputs['company_id'] = Auth::user()->company_id;
            $inputs['member_id'] = Auth::user()->member_id;
            $inputs['product_image'] = $upload;

            $main_stock = array_sum($product_stocks[$key]);
            $warehouse_initial_stock = $product_stocks[$key];
            $warehouse_ids = $product_warehouses[$key];

            $item = Item::create($inputs);


            foreach ($product_variant_ids as $product_variant_id)
            {
                $variant_value = $request['variant_value_'.$product_variant_id];

                $itemVariants = [];
                $itemVariants['item_id'] = $item->id;
                $itemVariants['variant_id'] = $product_variant_id;
                $itemVariants['values'] = implode(",", $variant_value);

                ItemVariant::create($itemVariants);
            }

            $this->stockIn($item->id, $main_stock);
            $this->stock_report($item->id, $main_stock, 'initial', $date);


            if(config('settings.warehouse') && count($warehouse_ids)>0)
            {
                foreach ($warehouse_ids as $key2 => $warehouse_id)
                {
                    $code = date("Ymdhis");

                    $load_qty = $warehouse_initial_stock[$key2];

                    $inputs = [];
                    $inputs['code'] = $code;
                    $inputs['company_id'] = $item->company_id;
                    $inputs['warehouse_id'] = $warehouse_id;
                    $inputs['model'] = "Initial";
                    $inputs['model_id'] = 1;
                    $inputs['item_id'] = $item->id;
                    $inputs['qty'] = $load_qty;
                    $inputs['date'] = $date;

                    WarehouseHistory::create($inputs);

                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($item->id, $warehouse_id, $load_qty, $date, 'initial');
                    $warehouse_stock->stockIn();

                }
            }
        }

        if(Session::has('validation_errors'))
            $status = ['type' => 'danger', 'message' => 'Unable to Add Variant Product '. $message ];
        else
            $status = ['type' => 'success', 'message' => 'Variant Product Added Successfully'];


        return back()->with('status', $status);
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
        $Item_Product = Item::find($id);
        $variant_ids = $Item_Product->variants->pluck('variant_id')->toArray();
        $initial_warehouses = $Item_Product->initial_warehouses->pluck('warehouse_id')->toArray();

        $variant_itemIds = $request->item_ids;
        $variant_products = $request->variants;
        $product_skuCodes = $request->skuCode;
        $product_purchase_prices = $request->purchase_price;
        $product_prices = $request->price;
        $product_initial_date = $request->initial_date;
        $product_stocks = $request->stock;
        $variant_warehouses = $request->warehouses;

        $product_variant_ids = $variant_ids;
        $product_images = $request->file('product_image');


        $product = [];
        $product['name'] = $request->item_name;
        $product['company_id'] = Auth::user()->company_id;
        $mainProduct = MainProduct::firstOrCreate($product);

        $message = "";
        foreach ($variant_products as $key => $product)
        {
            $itemId = $variant_itemIds[$key];
            $item = Item::find($itemId);

            $upload = $item->product_image;
            if(isset($product_images[$key]))
            {
                $image = $product_images[$key];
                $upload = $this->fileUpload($image, '/product_image/', null);
            }


            $inputs = [];
            $inputs['item_name'] = $item_name = $request->item_name." ".$product;
            $inputs['productCode'] = $productCode = $request->productCode."-".$key;
            $inputs['unit'] = $request->unit;
            $inputs['skuCode'] = $product_skuCodes[$key] ?? $productCode;
            $inputs['category_id'] = $request->category_id;
            $inputs['main_product_id'] = $mainProduct->id;

            $customMessages = [
                'item_name.unique_with' => 'The :attribute is already exist for this company'
            ];

            $validator = Validator::make($inputs, $this->rules($itemId), $customMessages);

            if($validator->fails()) {
                $message .= $item_name."-".$productCode.", ";
                Session::put('validation_errors', $validator->errors());
                break;
            }

            $inputs['brand_id'] = $request->brand_id;
            $inputs['is_service'] = $request->is_service;
            $inputs['is_repair_item'] = $request->is_repair_item;
            $inputs['guarantee'] = $request->guarantee;
            $inputs['warranty'] = $request->warranty;

            $inputs['date']  = $date = db_date_format($product_initial_date[$key]);
            $inputs['purchase_price'] = $product_purchase_prices[$key];
            $inputs['price'] = $product_prices[$key];
            $inputs['product_image'] = $upload;

            $item->update($inputs);

            $main_stock = array_sum($product_stocks[$itemId]);
            $warehouse_initial_stock = $product_stocks[$itemId];
            $warehouse_ids = $variant_warehouses[$itemId];

            $item_opening_stock = $item->stock_report()->orderBy('date', 'asc')->first();
            $last_opening_stock = $item_opening_stock->opening_stock;
            $last_initial_date = $item_opening_stock->date;

            if($item_opening_stock->opening_stock != $main_stock)
            {
                $differBetweenStock = $item_opening_stock->opening_stock - $main_stock;
                if($differBetweenStock>0)
                {
                    $this->stockOut($item->id, $differBetweenStock, 'stock out');
                }else{
                    $this->stockIn($item->id, $differBetweenStock*(-1) );
                }

                $this->stock_report($item->id, $main_stock, 'initial', db_date_format($date));
            }


            if(config('settings.warehouse') && count($warehouse_ids)>0)
            {
                $warehouse_stocks = WarehouseHistory::where('model', 'initial')->where('item_id', $item->id)->get();

                foreach ($warehouse_stocks as $value)
                {
                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($item->id, $value->warehouse_id, 0, $value->date, 'initial');
                    $warehouse_stock->stockIn();
                }


                WarehouseHistory::where('model', 'initial')->where('item_id', $item->id)->delete();

                foreach ($warehouse_ids as $key2 => $warehouse_id)
                {
                    $code = date("Ymdhis");

                    $load_qty = $warehouse_initial_stock[$warehouse_id];

                    $inputs = [];
                    $inputs['code'] = $code;
                    $inputs['company_id'] = $item->company_id;
                    $inputs['warehouse_id'] = $warehouse_id;
                    $inputs['model'] = "Initial";
                    $inputs['model_id'] = 1;
                    $inputs['item_id'] = $item->id;
                    $inputs['qty'] = $load_qty;
                    $inputs['date'] = $date;

                    WarehouseHistory::create($inputs);

                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($item->id, $warehouse_id, $load_qty, $date, 'initial');
                    $warehouse_stock->stockIn();

                }
            }
        }

        if(Session::has('validation_errors'))
            $status = ['type' => 'danger', 'message' => 'Unable to Update Variant Product '. $message ];
        else
            $status = ['type' => 'success', 'message' => 'Variant Product Updated Successfully'];

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
                'message' => 'Successfully deleted'
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
