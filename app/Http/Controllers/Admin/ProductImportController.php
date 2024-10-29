<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\WarehouseStock;
use App\Http\Traits\StockTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\MainProduct;
use App\Models\VariantValue;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    use StockTrait;

    public function create(){

        return view('admin.import.product');
    }

    public function store(Request $request){

        $this->validate($request, [
            'product_import_file'  => 'required|mimes:xls,xlsx'
        ]);

        if($request->hasFile('product_import_file'))
        {

            Excel::load($request->file('product_import_file')->getRealPath(), function ($reader) {

                $data = $reader->toArray();

                $variantsData = collect($data)->pluck('variant')->unique();

                $variantsData = $variantsData->map(function ($item) {
                    return strtolower(trim($item));
                });

                foreach ($variantsData as $variantValue)
                {
                    $variantData = [];
                    $variantData['variant_id'] = 2;
                    $variantData['name'] = strtoupper($variantValue);
                    $variantData['company_id'] = \Auth::user()->company_id;
                    $variantData['created_by'] = \Auth::user()->id;

                    $variant = VariantValue::where('name', strtoupper($variantValue))->first();

                    if(!$variant && !empty($variantValue))
                        VariantValue::create($variantData);
                }



                foreach ($data as $key => $row) {


                    $variantName = $row['variant'] ? trim($row['variant']) : "";
                    $brandName = $row['brand'];
                    $categoryName = $row['category'];
                    $main_product_name = $brandName." ".ucfirst($row['product_name'])." ".$categoryName;
                    $product_name = $main_product_name." ".$variantName;


                    $product = [];
                    $product['name'] = $main_product_name;
                    $product['company_id'] = Auth::user()->company_id;
                    $mainProduct = MainProduct::firstOrCreate($product);

                    $slug = str_slug($categoryName);
                    $checkCategory = Category::where('name', $slug)->first();
                    if(!$checkCategory)
                    {
                        $inputs = [];
                        $inputs['display_name'] = $categoryName;
                        $inputs['name'] = $slug;
                        $inputs['status'] = 'active';
                        $inputs['company_id'] = Auth::user()->company_id;
                        $inputs['created_by'] = Auth::user()->id;

                        $checkCategory = Category::create($inputs);
                    }

                    $checkBrand = Brand::where('slug', str_slug($brandName))->first();
                    if(!$checkBrand)
                    {
                        $inputs = [];
                        $inputs['name'] = $brandName;
                        $inputs['slug'] = str_slug($brandName);
                        $inputs['status'] = 'active';
                        $inputs['member_id'] = Auth::user()->member_id;
                        $inputs['company_id'] = Auth::user()->company_id;
                        $inputs['created_by'] = Auth::user()->id;

                        $checkBrand = Brand::create($inputs);
                    }


                    $itemCheck = Item::where('item_name', $product_name)->first();

                    $product = [];
                    $product['productCode'] = $code = $checkBrand->id.$checkCategory->id.(Item::count());
                    $product['skuCode'] = $code;
                    $product['item_name'] = $product_name;
                    $product['unit'] = $row['unit'] ?? "Pcs";
                    $product['category_id'] = $checkCategory->id;
                    $product['brand_id'] = $checkBrand->id;
                    $product['member_id'] = Auth::user()->member_id;
                    $product['company_id'] = Auth::user()->company_id;
                    $product['created_by'] = Auth::user()->id;
                    $product['price'] = $row['price'] ?? 0;
                    $product['status'] = 'active';
                    $product['main_product_id'] = $mainProduct->id;
                    $product['description'] = $row['serial'];
                    $product['date']  = $date = date("Y-m-d");


                    if(!$itemCheck)
                    {
                        $item = Item::create($product);

                        $main_stock = $row['qty'] ?? 1;
                    }else {

                        $itemUpdate = [];
                        $itemUpdate['description'] = $itemCheck->description.", ".$row['serial'];
                        $itemCheck->update($itemUpdate);

                        $item = $itemCheck;

                        $main_stock = ($item->stock_details->stock)+$row['qty'];
                    }


                    if(!empty($variantName))
                    {
                        $mainItem = Item::where('main_product_id',$mainProduct->id)->latest()->first();

                        $variantValue = VariantValue::where('name', strtoupper($variantName))->first();
                        $itemVariant  = ItemVariant::where('item_id', $mainItem->id)
                            ->where('variant_id', 2)
                            ->first();


                        $itemVariants = [];

                        if($itemVariant)
                        {
                            $array = explode(',', $itemVariant->values);
                            $checkInArray = in_array($variantValue->id, $array);

                            if($checkInArray)
                                $itemVariants['values'] = $itemVariant->values;
                            else
                                $itemVariants['values'] = $itemVariant->values.$variantValue->id.",";

                            $itemVariant->update($itemVariants);

                        }else{
                            $itemVariants['item_id'] = $mainItem->id;
                            $itemVariants['variant_id'] = $variantValue->variant_id;
                            $itemVariants['values'] = ($variantValue->id).',';
                            ItemVariant::create($itemVariants);
                        }
                    }


                    $this->stockIn($item->id, $main_stock);
                    $this->stock_report($item->id, $main_stock, 'initial', $date);


                    if(config('settings.warehouse'))
                    {

                        $warehouse = Warehouse::where('title', $row['warehouse'])->first();

                        $wareInputs = [];
                        $wareInputs['title'] = $row['warehouse'];
                        $wareInputs['mobile'] = "1";
                        $wareInputs['address'] = $row['warehouse'];
                        $short = strtolower(substr($wareInputs['title'], 0, 2));
                        $wareInputs['shortcode'] = $short.(Warehouse::count());

                        if(!$warehouse)
                            $warehouse = Warehouse::create($wareInputs);

                        $code = date("Ymdhis");

                        $load_qty = $main_stock;

                        $inputs = [];
                        $inputs['code'] = $code;
                        $inputs['company_id'] = $item->company_id;
                        $inputs['warehouse_id'] = $warehouse_id = $warehouse->id;
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

            });

            $status = ['type' => 'success', 'message' => trans('common.product_import_successfully')];

        }else{

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_import_product')];

        }


        return back()->with('status', $status);
    }


    /*
     * product Import Sample
     */
    public function productImportSample(){

        return response()->download(public_path('sample_excel/product.xlsx'));
    }

}
