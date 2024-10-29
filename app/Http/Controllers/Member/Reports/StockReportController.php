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

class StockReportController extends BaseReportController
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

        $query = new Item();


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
            $query = $query->whereIn('id', $unique_Items);
        }

        $data['full_url'] = $request->fullUrl();

        $data['report_title'] = $title = trans('common.stock_report');
        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.stocks', $data);
        }

    }

    public function daily_stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $inputs = $request->all();

        $query = new StockReport();

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
        $data['from_date'] = month_date_year_format($fromDate);
        $data['to_date'] =  isset($inputs['to_date']) ? month_date_year_format($toDate): "";

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['title'] = $data['report_title'] = $title = trans('common.daily_stock_report');

        $query = $this->authCompany($query, $request);
        $query = $query->orderBy('date', 'DESC');

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_daily_stock_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_daily_stock_report', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.daily_stock_report', $data);
        }

    }

    public function gain_reconcile_stocks(Request $request){

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['fiscal_years'] = FiscalYear::get()->pluck('fiscal_year_details', 'id');

        $gain_query = new StockOverflowReconcile();

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
            $gain_query = $gain_query->whereIn('item_id', $unique_Items);
        }

        if(isset($request->fiscal_year_id))
        {
            $gain_query = $gain_query->where('fiscal_year_id', $request->fiscal_year_id);
        }

        $gain_query = $gain_query->orderBy('fiscal_year_id');

        $data['full_url'] =  $request->fullUrl();

        $data = $this->company($data);
        $data['report_title'] = $title = trans('common.reconcile_stock_report');


        if($request->type=="print"|| $request->type=="download") {

            $data['gain_stocks'] = $gain_query->get();
            if ($request->type == "print") {
                return view('member.stock_reconcile.print_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.stock_reconcile.print_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['gain_stocks']  = $gain_query->paginate(50)->appends(request()->query());
            return view('member.stock_reconcile.list', $data);
        }

    }

    public function loss_reconcile_stocks(Request $request){

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['fiscal_years'] = FiscalYear::get()->pluck('fiscal_year_details', 'id');

        $type = $request->loss_type;

        $query = new LossStockReconcile();


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

        if(isset($request->fiscal_year_id))
        {
            $query = $query->where('fiscal_year_id', $request->fiscal_year_id);
        }

        if(isset($request->loss_type))
        {
            $query = $query->where('loss_type', $type);
        }

        $query = $query->orderBy('fiscal_year_id');

        $data['full_url'] =  $request->fullUrl();

        $data = $this->company($data);
        $data['report_title'] = $title = trans('common.reconcile_loss_stock_report');

        if($request->type=="print"|| $request->type=="download") {

            $data['stocks'] = $query->get();

            if ($request->type == "print") {
                return view('member.stock_reconcile.print_loss_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.stock_reconcile.print_loss_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks']  = $query->paginate(50)->appends(request()->query());
            return view('member.stock_reconcile.loss_list', $data);
        }

    }

    public function total_stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');


        $query = DB::table('items')
            ->join('stocks', 'stocks.item_id', 'items.id')
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
            $query = $query->whereIn('items.id', $unique_Items);
        }


        $data['full_url'] = $request->fullUrl();
//        $purchase_rate = new PurchaseService();
//        $data = $purchase_rate->averagePurchasePrice($data);
//        dd($data);

        $query = $query->selectRaw('items.*, stocks.stock, (SUM(purchase_details.total_price))/(SUM(purchase_details.qty)) as purchase_qty_price')->groupBy('items.id');

        $data['report_title'] = $title = trans('common.stock_price_report');
        $data = $this->company($data);

        $query = $this->authCompany($query, $request, 'stocks');

        if ($request->type == "print" || $request->type == "download") {

            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_total_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_total_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.total_stock', $data);
        }

    }

    public function dealer_stocks(Request $request)
    {

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['dealers'] = [];

        $user = new User();
        if(Auth::user()->can(['super-admin','admin']) || Auth::user()->hasRole(['sales_man']))
        {
            $data['dealers'] = User::whereIn('id',$user->findRoleUser(['dealer']))->active()->authCompany()->get()->pluck('user_phone', 'id');
        }


        $query = new DealerStock();

        $unique_Items = [];
        if(isset($request->item_id))
        {
            $unique_Items = array($request->item_id);
        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;
        }

        if (count($unique_Items)>0) {
            $query = $query->whereIn('id', $unique_Items);
        }

        if (isset($request->dealer_id)) {
            $query = $query->where('dealer_id', $request->dealer_id);
        }

        $query = $query->authDealer();

        $data['full_url'] = $request->fullUrl();

        $data['report_title'] = $title = "Stock Report";
        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_dealer_stocks', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_dealer_stocks', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.dealer_stocks', $data);
        }

    }

    public function dealer_daily_stocks(Request $request)
    {
        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['dealers'] = [];

        $user = new User();
        if(Auth::user()->can(['super-admin','admin']) || Auth::user()->hasRole(['sales_man']))
        {
            $data['dealers'] = User::whereIn('id',$user->findRoleUser(['dealer']))->active()->authCompany()->get()->pluck('user_phone', 'id');
        }

        $inputs = $request->all();

        $query = new DealerStockReport();

        $unique_Items = [];
        if(isset($request->item_id))
        {
            $unique_Items = array($request->item_id);
        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;
        }

        if (count($unique_Items)>0) {
            $query = $query->whereIn('item_id', $unique_Items);
        }

        if (isset($request->dealer_id)) {
            $query = $query->where('dealer_id', $request->dealer_id);
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
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['title'] = $data['report_title'] = $title = "Daily Stock Report";

        $query = $this->authCompany($query, $request);
        $query = $query->authDealer();
        $query = $query->orderBy('date', 'DESC');

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['stocks'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.stock.print_daily_dealer_stock_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_daily_dealer_stock_report', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.stock.daily_dealer_stock_report', $data);
        }

    }

    public function daily_stock_report_by_requisition_damage(Request $request)
    {

        $inputs = $request->all();

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        else
            $inputs['from_date'] = $fromDate = Carbon::today();

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        else
            $inputs['to_date'] = $toDate = Carbon::today();

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');


        if (isset($request->item_id)) {
            $unique_Items = array($request->item_id);


            $requisition_dates = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('req_date')->toArray();

            $sale_dates = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('date')->toArray();

            $return_dates = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('return_date')->toArray();

            $damage_dates = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('closing_date')->toArray();

            $unique_dates = array_unique(array_merge($requisition_dates, $sale_dates, $return_dates, $damage_dates));

        }else{

            $requisition_items = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $sale_items = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $return_items = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $damage_items = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $unique_Items = array_unique(array_merge($requisition_items, $sale_items, $return_items, $damage_items));


            $requisition_dates = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->pluck('req_date')->toArray();

            $sale_dates = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->pluck('date')->toArray();

            $return_dates = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->pluck('return_date')->toArray();

            $damage_dates = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->pluck('closing_date')->toArray();

            $unique_dates = array_unique(array_merge($requisition_dates, $sale_dates, $return_dates, $damage_dates));
        }

        $items = Item::select('items.id', 'items.item_name','items.price',
            'items.unit')->whereIn('id', $unique_Items)->where('company_id', \Auth::user()->company_id)->get();


        $reports = [];

        $j = 0;
        foreach ($items as $value)
        {
            $item_id = $value->id;

            foreach ($unique_dates as $val)
            {

                $date =  $val;

                if(in_array($date, $requisition_dates))
                {
                    $requisition = SalesRequisitionDetail::where('req_date', $date)
                        ->where('item_id', $item_id)->sum('qty');
                }else{
                    $requisition = 0;
                }

                if(in_array($date, $sale_dates))
                {
                    $sale = SaleDetails::where('date', $date)
                    ->where('item_id', $item_id)->sum('qty');
                }else{
                    $sale = 0;
                }

                if(in_array($date, $return_dates))
                {
                    $return = SaleReturn::where('return_date', $date)
                    ->where('item_id', $item_id)->sum('return_qty');
                }else{
                    $return = 0;
                }

                if(in_array($date, $damage_dates))
                {
                    $damage = LossStockReconcile::where('closing_date', $date)
                    ->where('item_id', $item_id)->sum('qty');
                }else{
                    $damage = 0;
                }

                if($requisition>0 || $sale>0 || $return>0 || $damage>0)
                {
                    $reports[$j]['id']= $value->id;
                    $reports[$j]['item_name']= $value->item_name;
                    $reports[$j]['price']= $value->price;
                    $reports[$j]['unit']= $value->unit;
                    $reports[$j]['date'] = $date;
                    $reports[$j]['sales_requisition_qty'] = $requisition;
                    $reports[$j]['sale_qty'] = $sale;
                    $reports[$j]['sale_return_qty'] = $return;
                    $reports[$j]['damage_qty'] = $damage;
                    
                    $j++;
                }


            }


        }

        $reports = collect($reports)->sortBy('date');


        $data['from_date'] = create_date_format($fromDate,'/') ;
        $data['to_date'] =  isset($inputs['to_date']) ? create_date_format($toDate,'/'): "";

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['title'] = $data['report_title'] = $title = "Daily RSRD report";


        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);

            $data['stocks'] = $reports;
            if ($request->type == "print") {
                return view('member.reports.stock.print_daily_rsrd_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_daily_rsrd_report', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {
//            print_r($query->toSql());exit;
//            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            $data['stocks'] = $reports;
            return view('member.reports.stock.daily_rsrd_report', $data);
        }
    }

    public function daily_stock_by_rsrd(Request $request)
    {

        $inputs = $request->all();

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        else
            $inputs['from_date'] = $fromDate = Carbon::today();

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        else
            $inputs['to_date'] = $toDate = Carbon::today();

        $data['products'] = Item::active()->authCompany()->get()->pluck('item_name', 'id');
        $data['brands'] = Brand::active()->authCompany()->get()->pluck('name', 'id');


        if (isset($request->item_id)) {
            $unique_Items = array($request->item_id);


            $requisition_dates = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('req_date')->toArray();

            $sale_dates = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('date')->toArray();

            $return_dates = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('return_date')->toArray();

            $damage_dates = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->where('item_id', $request->item_id)->pluck('closing_date')->toArray();

            $unique_dates = array_unique(array_merge($requisition_dates, $sale_dates, $return_dates, $damage_dates));

        }else if (isset($request->brand_id)) {

            $brand_items = Item::where('brand_id', $request->brand_id)->pluck('id')->toArray();
            $unique_Items = $brand_items;


            $requisition_dates = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->whereIn('item_id', $brand_items)->pluck('req_date')->toArray();

            $sale_dates = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->whereIn('item_id', $brand_items)->pluck('date')->toArray();

            $return_dates = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->whereIn('item_id', $brand_items)->pluck('return_date')->toArray();

            $damage_dates = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->whereIn('item_id', $brand_items)->pluck('closing_date')->toArray();

            $unique_dates = array_unique(array_merge($requisition_dates, $sale_dates, $return_dates, $damage_dates));

        }else{

            $requisition_items = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $sale_items = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $return_items = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $damage_items = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->pluck('item_id')->toArray();

            $unique_Items = array_unique(array_merge($requisition_items, $sale_items, $return_items, $damage_items));


            $requisition_dates = SalesRequisitionDetail::whereBetween('req_date', [$fromDate, $toDate])
                ->pluck('req_date')->toArray();

            $sale_dates = SaleDetails::whereBetween('date', [$fromDate, $toDate])
                ->pluck('date')->toArray();

            $return_dates = SaleReturn::whereBetween('return_date', [$fromDate, $toDate])
                ->pluck('return_date')->toArray();

            $damage_dates = LossStockReconcile::whereBetween('closing_date', [$fromDate, $toDate])
                ->pluck('closing_date')->toArray();

            $unique_dates = array_unique(array_merge($requisition_dates, $sale_dates, $return_dates, $damage_dates));
        }

        $items = Item::select('items.id', 'items.item_name','items.price',
            'items.unit')->whereIn('id', $unique_Items)->where('company_id', \Auth::user()->company_id)->get();


        $reports = [];

        $j = 0;
        foreach ($items as $value)
        {
            $item_id = $value->id;

            foreach ($unique_dates as $val)
            {

                $date =  $val;

                if(in_array($date, $requisition_dates))
                {
                    $requisition = SalesRequisitionDetail::where('req_date', $date)
                        ->where('item_id', $item_id)->sum('qty');
                }else{
                    $requisition = 0;
                }

                if(in_array($date, $sale_dates))
                {
                    $sale = SaleDetails::where('date', $date)
                    ->where('item_id', $item_id)->sum('qty');
                }else{
                    $sale = 0;
                }

                if(in_array($date, $return_dates))
                {
                    $return = SaleReturn::where('return_date', $date)
                    ->where('item_id', $item_id)->sum('return_qty');
                }else{
                    $return = 0;
                }

                if(in_array($date, $damage_dates))
                {
                    $damage = LossStockReconcile::where('closing_date', $date)
                    ->where('item_id', $item_id)->sum('qty');
                }else{
                    $damage = 0;
                }

                if($requisition>0 || $sale>0 || $return>0 || $damage>0)
                {
                    $reports[$j]['id']= $value->id;
                    $reports[$j]['item_name']= $value->item_name;
                    $reports[$j]['price']= $value->price;
                    $reports[$j]['unit']= $value->unit;
                    $reports[$j]['date'] = $date;
                    $reports[$j]['sales_requisition_qty'] = $requisition;
                    $reports[$j]['sale_qty'] = $sale;
                    $reports[$j]['sale_return_qty'] = $return;
                    $reports[$j]['damage_qty'] = $damage;

                    $j++;
                }


            }


        }

        $data['sales_commission'] = Sale::whereBetween('date', [$fromDate, $toDate])->sum('total_discount');

        $reports = collect($reports)->sortBy('date');


        $data['from_date'] = create_date_format($fromDate,'/') ;
        $data['to_date'] =  isset($inputs['to_date']) ? create_date_format($toDate,'/'): "";

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['title']  = $title = "Daily RSRD report";
        $data['report_title'] =  "Daily RSRD report <br/> Date: ".$data['from_date']." to ".$data['to_date'];


        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);

            $data['stocks'] = $reports;
            if ($request->type == "print") {
                return view('member.reports.stock.print_daily_rsrd_actual_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.stock.print_daily_rsrd_actual_report', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {
//            print_r($query->toSql());exit;
//            $data['stocks'] = $query->paginate(20)->appends(request()->query());
            $data['stocks'] = $reports;
            return view('member.reports.stock.daily_rsrd_actual_report', $data);
        }
    }

}
