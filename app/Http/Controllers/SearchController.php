<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomerSave;
use App\Models\Region;
use App\Models\AccountType;
use App\Models\Area;
use App\Models\Company;
use App\Models\District;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\QuoteAttention;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SupplierOrCustomer;
use App\Models\SupplierPurchases;
use App\Models\BankBranch;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SearchController extends Controller
{

    public function order_search(Request $request){

        $id = $request->sale_code;
        $type = $request->type;

        if($type == "single")
            $sales = Sale::where('sale_code', $id)->with(['sale_details'])->first();
        else
            $sales = Sale::where('sale_code', 'like', "%".$id."%")->latest()->limit(6)->get();

        $data['orders'] = $sales;

        if($sales)
        {
            $data['status'] = 'success';

            $data['order_products'] = [];
            if($type == "single")
            {
                if($sales->sale_details->count()>1)
                {
                    $product_html = "<select id='select_product' name='product_name' class='select2 form-control'>";

                    foreach ($sales->sale_details as $value)
                    {
                        $product_html .= "<option value='".$value->item->item_name."'>".$value->item->item_name."</option>";
                    }

                    $product_html .= "</select>";
                }else{
                    $product_html = '<input id="product_name" class="form-control" readonly value="'.$sales->sale_details[0]->item->item_name.'" name="product_name" type="text">';
                }

                $data['order_products'] = $product_html;
                $data['pos_html'] = View::make('member.repair_orders.order_details', compact('sales'))->render();

            }
        }else{
            $data['status'] = 'failure';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function item_details(Request $request){

        $id = $request->item_id;
        $supplierId = $request->supplier_id;
        $item = Item::find($id);

        $data = $item;
        $data['stock'] = !empty($item->stock_details) ? $item->stock_details->stock : 0;
        $data['supplier_purchases'] = $this->supplier_last_purchase_by_item($supplierId, $id);
        $data['last_price'] = $this->purchase_items_price($request->item_id);
        $data['purchase_avg_price'] = $this->purchase_items_avg_price($request->item_id);
        $data['last_purchase_qty'] = $this->purchase_items_qty($id);

        if(isset($request->purchase_id))
        {
            $data['last_qty'] = $this->purchase_items($request->purchase_id, $id);
        }

        if($item)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function sale_item_details(Request $request){

        $id = $request->item_id;
        $product_code = $request->product_code;
        $item = Item::where('id', $id)->orWhere('productCode', $product_code)->first();

        $data = [];
        $data = $item;
        $data['stock'] = !empty($item->stock_details) ? $item->stock_details->stock : 0;
//        $data['price'] = PurchaseDetail::where('item_id', $id)->orderBy('created_at', 'desc')->select('price')->first();
        $data['last_sale_qty'] = $this->sale_items($id);

//        if(isset($request->sale_id))
//        {
//            $data['last_qty'] = $this->sale_items($request->sale_id, $id);
//        }

        if($item)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function search_item(Request $request){

        $productName = $request->product;
        $products = Item::where('item_name', "like", "%".$productName."%")
            ->orWhere('productCode',"like" ,"%".$productName."%")
            ->authCompany()
            ->select('id', 'item_name', 'price', 'productCode', 'skuCode', 'product_image', 'unit')
            ->latest('id')
            ->limit(10)
            ->get()->load('stock_details');

        $data = [];
        $data['items'] = $products;
        $data['pos_html'] = View::make('member.sales.pos_items', compact('products'))->render();

        if(count($products)>0)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function search_variant(Request $request){

        $id = $request->id;
        $variants = Variant::whereIn('id', $id)->get();

        $array = [];
        $array['variants'] = $variants;

        $data['variant_html'] = View::make('member.items.variants.variant_values', $array)->render();

        if($variants)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function generate_variant_product(Request $request){

        $variantValues = $request->variantValues ?? [];
        $variant_ids = $request->variant_id ?? [];
        $warehouses = $request->warehouse_id ?? [];
        $variantValues = array_filter($variantValues);

        $variantData = [];
        $variantTitles = [];
        foreach ($variant_ids as $variant_id)
        {
            $variantData[$variant_id]['id'] = Variant::find($variant_id);
            $variantData[$variant_id]['values'] = VariantValue::whereIn('id',$variantValues[$variant_id])->get();
            $variantTitles[] = collect($variantData[$variant_id]['values'])->pluck('name');
            $variantIDs[] = collect($variantData[$variant_id]['values'])->pluck('id');
        }


        $variantArray = [];
        for ($j=0; $j<count($variantTitles); $j++){
            $variantArray = variants_multiple($variantTitles[$j], $variantArray);
        }
        $variantIDArray = [];
        for ($j=0; $j<count($variantIDs); $j++){
            $variantIDArray = variants_multiple($variantIDs[$j], $variantIDArray);
        }

        $array = [];
        $array['variantIDArray'] = $variantIDArray;
        $array['variantArray'] = $variantArray;
        $array['variantData'] = $variantData;
        $array['warehouses'] = Warehouse::whereIn('id', $warehouses)->pluck('title','id')->toArray();

        $data = [];
        $data['variant_product'] = View::make('member.items.variants.variant_product', $array)->render();


        if($array)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function search_customer_phone(Request $request){

        $phone = trim($request->customer_phone);
        $membership = SupplierOrCustomer::where('phone', "like", "%".$phone)
            ->select('id', 'membership_no', 'point', 'phone', 'name')
            ->first();

        if(!$membership)
        {
            $customerSave = new CustomerSave();
            $customer = $customerSave->create_pos_customer($phone);

            $membership = SupplierOrCustomer::where('id', $customer->id)
                ->select('id', 'membership_no', 'point', 'phone', 'name')
                ->first();

        }

        $data = [];
        $data['customer'] = $membership;

        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function search_membership(Request $request){

        $member_code = $request->membership;
        $membership = SupplierOrCustomer::where('membership_no',  $member_code)
            ->select('id', 'membership_no', 'point', 'phone', 'name')
            ->first();

        $data = [];
        $data['customer'] = $membership;

        if($membership)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function check_item_serial(Request $request){

        $serial = $request->serial_number;
        $item_id = $request->item_id;

        $item = ItemDetail::where('serial_number',$serial)->where('item_id', $item_id)->authCompany()->first();

        if($item)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');

        echo json_encode($data);
    }


    public function sale_bags(Request $request){

        $id = $request->item_id;
        $item = Item::where('id', $id)->first();

        $data = [];
        $data = $item;
        $data['stock'] = !empty($item->stock_details) ? $item->stock_details->stock : 0;

        if($item)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function supplier_last_purchase_by_item($supplierId, $itemId)
    {
       $supplierPurchases =  SupplierPurchases::authMember()->authCompany()
                            ->where('supplier_id', $supplierId)
                            ->where('item_id', $itemId)
                            ->orderBy('created_at', 'DESC')
                            ->first();

       return $supplierPurchases;
    }

    public function supplier_info(Request $request){

        $data['supplier'] = $supplier = SupplierOrCustomer::find($request->supplier_id);
        $data['last_purchase_amount'] = Purchase::where('supplier_id', $request->supplier_id)->latest()->first();


        if($supplier)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function purchase_items( $purchase_id, $item_id)
    {
        $purchase = PurchaseDetail::where('purchase_id',$purchase_id)
            ->where('item_id',$item_id)->sum('qty');

        return $purchase;
    }


    public function purchase_items_avg_price($item_id)
    {
        $pur = PurchaseDetail::where('item_id',$item_id)->orderBy('id','desc')->avg('price');

        return create_float_format($pur,2);
    }

    public function purchase_items_price($item_id)
    {
        $pur = PurchaseDetail::where('item_id',$item_id)->orderBy('id','desc')->select('price')->first();

        return $pur ? $pur->price : 0;
    }

    public function purchase_items_qty($item_id)
    {
        $pur = PurchaseDetail::where('item_id',$item_id)->orderBy('id','desc')->select('qty')->first();

        return $pur ? $pur->qty : 0;
    }

    public function customer_info(Request $request){

        $data['customer'] = $customer = SupplierOrCustomer::find($request->customer_id);
        $data['last_sale_amount'] = Sale::where('customer_id', $request->customer_id)->latest()->first();


        if($customer)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function sale_items($item_id)
    {
        $sales = SaleDetails::where('item_id',$item_id)->orderBy('id', 'DESC')->groupBy('sale_id')->sum('qty');

        return $sales;
    }

    public function select_bank_branch(Request $request)
    {
        $data['modals'] = BankBranch::where('bank_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('branch_name', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_district(Request $request)
    {
        $data['modals'] = District::where('division_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('display_name_bd', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_region(Request $request)
    {
        $data['modals'] = Region::where('division_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('name', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_region_district(Request $request)
    {
        $data['modals'] = District::where('region_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('name', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }


    public function select_district_thana(Request $request)
    {
        $data['modals'] = Thana::where('district_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('name', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_upazilla(Request $request)
    {
        $data['modals'] = Upazilla::where('district_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('display_name_bd', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_union(Request $request)
    {
        $data['unions'] = Union::where('upazilla_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('display_name_bd', 'id');
        $data['areas'] = Area::where('upazilla_id',$request->id)->orderBy('id', 'DESC')->get()->pluck('display_name_bd', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_manager(Request $request)
    {
        $data['users'] = User::where('id',$request->id)->orderBy('id', 'DESC')->get()->pluck('full_name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function select_sharer(Request $request)
    {
        $manager_id = $request->manager_id;

        $user = User::findOrFail($manager_id);
        $sharer_type = $user->customer_type;

        $sharer = SupplierOrCustomer::where('manager_id', $manager_id)->whereNotNull('account_type_id')->active();
        if($sharer_type == "customer")
        {
            $sharer = $sharer->onlyCustomers();
        }else{
            $sharer =  $sharer->onlySuppliers();
        }

        $sharer =  $sharer->pluck('account_type_id');

        $data['accounts'] = AccountType::active()->whereIn('id', $sharer)->get()->pluck('display_name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        echo json_encode($data);
    }


    public function search_brand_items(Request $request)
    {
        $item_id = $request->item_id;

        $data['items'] = $items = Item::where('brand_id',$item_id)->authCompany()->pluck('item_name', 'id');


        if(count($items)>0)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');


        echo json_encode($data);
    }

    public function search_quote_attention(Request $request)
    {
        $company_id = $request->company_id;

        $data['quote_attentions'] = $items = QuoteAttention::active()->where('quotationer_id',$company_id)->orWhereNull('quotationer_id')->get();


        if(count($items)>0)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');


        echo json_encode($data);
    }


    public function warehouse_item_details(Request $request){


        $id = $request->item_id;
        $warehouse_id = $request->warehouse_id;

        $item = \App\Models\WarehouseStock::where('item_id', $id)->where('warehouse_id', $warehouse_id)->first();

        $data = [];
        $data['stock'] = !empty($item->stock) ? $item->stock : 0;

        if($item)
            $data['status'] = 'success';
        else
            $data['status'] = 'failure';

        header('Content-Type: application/json');
        echo json_encode($data);
    }


}
