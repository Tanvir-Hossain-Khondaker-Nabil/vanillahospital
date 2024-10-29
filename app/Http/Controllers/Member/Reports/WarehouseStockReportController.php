<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Brand;
use App\Models\Company;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\LossStockReconcile;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SaleReturn;
use App\Models\SalesRequisitionDetail;
use App\Models\Stock;
use App\Models\StockOverflowReconcile;
use App\Models\StockReport;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockReport;
use App\Services\PurchaseService;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DealerStock;
use App\Models\DealerStockReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseStockReportController extends BaseReportController
{

    public function __construct()
    {
//        parent::__construct();
    }

    public function stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        $query = new WarehouseStock();


        $unique_Items = [];

        if(isset($request->item_id))
        {
            $unique_Items = array($request->item_id);
        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;
        }

        if(count($unique_Items)>0)
        {
            $query = $query->whereIn('item_id', $unique_Items);
        }


        if(isset($request->warehouse_id))
        {
            $query = $query->where('warehouse_id', $request->warehouse_id);
        }

        $data['full_url'] = $request->fullUrl();

        $data['report_title'] = "Warehouse Stock Report <br/> Date:".db_date_month_year_format(date("Y-m-d"));

        $title = "Warehouse Stock Report";
        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_warehouse_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_warehouse_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.warehouse_stocks', $data);
        }

    }

    public function daily_stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        $inputs = $request->all();

        $query = new WarehouseStockReport();

        $unique_Items = [];

        if(isset($request->item_id))
        {
            $unique_Items = array($request->item_id);
        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;
        }

        if(count($unique_Items)>0)
        {
            $query = $query->whereIn('item_id', $unique_Items);
        }


        if(isset($request->warehouse_id))
        {
            $query = $query->where('warehouse_id', $request->warehouse_id);
        }

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->where('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->where('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereBetween('date', [$fromDate, $toDate]);
        }

        $data['from_date'] = isset($inputs['from_date']) ? create_date_format($fromDate,"/") : "";
        $data['to_date'] =  isset($inputs['to_date']) ?  create_date_format($toDate,"/"): "";


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['title'] = $data['report_title'] = $title = "Daily Warehouse Stock Report";

        $query = $this->authCompany($query, $request);
        $query = $query->orderBy('date', 'DESC');

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_daily_warehouse_stock_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_daily_warehouse_stock_report', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.daily_warehouse_stock_report', $data);
        }

    }

    public function total_stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');


        $query = DB::table('items')
            ->join('warehouse_stocks', 'warehouse_stocks.item_id', 'items.id')
            ->join('warehouses', 'warehouses.id', 'warehouse_stocks.warehouse_id')
            ->leftjoin('purchase_details', 'items.id', 'purchase_details.item_id');

//        $query = $query->whereNotNull('items.deleted_at');

        $unique_Items = Item::pluck('id')->toArray();

        if(isset($request->item_id))
        {
            $unique_Items = array($request->item_id);
        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;
        }

        if(count($unique_Items)>0)
        {
            $query = $query->whereIn('warehouse_stocks.item_id', $unique_Items);
        }


        if(isset($request->warehouse_id))
        {
            $query = $query->where('warehouse_stocks.warehouse_id', $request->warehouse_id);
        }

        if(!isset($request->group_by))
            $request['group_by'] = 1;


        $data['group_by'] = $request->group_by ? "yes" : "no";
        $data['full_url'] = $request->fullUrl();

        $query = $query->selectRaw('items.item_name, items.productCode, items.unit, items.purchase_price, warehouse_stocks.warehouse_id, warehouses.title, warehouse_stocks.stock, (SUM(purchase_details.total_price))/(SUM(purchase_details.qty)) as purchase_qty_price');

        if($request->group_by)
            $query = $query->orderBy('warehouse_stocks.warehouse_id');

        $query = $query->where('warehouse_stocks.stock', '!=', 0)->orderBy('items.item_name', 'asc')->groupBy('items.id')->groupBy('warehouse_stocks.warehouse_id');

        $title = "Warehouse Stock Price Report";
        $data['report_title'] = "Warehouse Stock Price Report <br/> Date:".db_date_month_year_format(date("Y-m-d"));
        $data = $this->company($data);

        $query = $this->authCompany($query, $request, 'warehouse_stocks');

        if ($request->type == "print" || $request->type == "download") {

            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_warehouse_total_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_warehouse_total_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.warehouse_total_stock', $data);
        }

    }

    public function warehouse_type_stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        $query =  WarehouseHistory::whereIn('model', ['Damage',"Loss","Overflow"]);


        $unique_Items = [];


        if(isset($request->item_id))
        {
            $unique_Items = array($request->item_id);
        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;
        }

        if(count($unique_Items)>0)
        {
            $query = $query->whereIn('item_id', $unique_Items);
        }


        if(isset($request->warehouse_id))
        {
            $query = $query->where('warehouse_id', $request->warehouse_id);
        }

        if(isset($request->stock_type))
        {
            if( $request->stock_type == "Damage")
            {
                $query = $query->where('model', $request->stock_type)->orWhere('model', "Loss");
            }else{
                $query = $query->where('model', $request->stock_type);
            }
        }

        $inputs = $request->all();

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->where('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->where('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereBetween('date', [$fromDate, $toDate]);
        }

        $data['from_date'] = isset($inputs['from_date']) ? month_date_year_format($fromDate) : "";
        $data['to_date'] =  isset($inputs['to_date']) ? month_date_year_format($toDate): "";

        $data['stock_type'] = $request->stock_type;
        $data['full_url'] = $request->fullUrl();

        $data['report_title'] = $title = "Warehouse ".$request->stock_type." Stock Report";
        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_warehouse_type_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_warehouse_type_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.warehouse_type_stocks', $data);
        }

    }
}
