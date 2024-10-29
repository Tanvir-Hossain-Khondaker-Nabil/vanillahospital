<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Company;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ReturnPurchase;
use App\Models\SupplierOrCustomer;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseReportController extends BaseReportController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function purchase_report_by_product($type, Request $request)
    {

        $inputs = $request->all();

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');

        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['managers'] = User::get()->pluck('full_name', 'id');
        $data['search_type'] = $type;

        $query = new PurchaseDetail();
        $data['item'] = null;
        if (!empty($inputs['item_id'])) {
            $query = $query->where('item_id', $inputs['item_id']);
            $data['item'] = $inputs['item_id'];
        }

        if (!empty($inputs['warehouse_id'])) {
            $warehouse_id = $inputs['warehouse_id'];

            $warehouseSale = WarehouseHistory::where('warehouse_id', $warehouse_id)->where('model', "PurchaseDetail");
            $warehouseSale = $this->searchPurchaseDate($inputs, $warehouseSale);
            $warehouseSale = $warehouseSale->pluck('model_id')->toArray();

            $query = $query->whereIn('id', $warehouseSale);
        }

        $query = $this->searchPurchaseDate($inputs, $query);
        $query = $this->authCompany($query, $request);


        if (!empty($inputs['manager_id'])) {

            $manager_id = $inputs['manager_id'];

            $user = User::findOrFail($manager_id);
            $sharer = SupplierOrCustomer::where('manager_id', $manager_id)->whereNotNull('account_type_id')->active();
            $sharer = $sharer->pluck('id');

            $query = $query->whereHas('purchases', function ($q) use ($sharer) {
                $q->whereIn('supplier_id', $sharer);
            });
        }

        $query = $query->with('purchases');
        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('purchase_id');



        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        // $data['report_title'] = $report_title = " Purchase Report by " . ucfirst($type);
        $data['report_title'] = $report_title = trans('common.purchase_report_by')." " . trans('common.'.strtolower($type));

        if ($request->type == "print" || $request->type == "download") {

            $data['purchases'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.purchases.print_product_purchase_report', $data);
            } else if ($request->type == "download") {

                $pdf = PDF::loadView('member.reports.purchases.print_product_purchase_report', $data);

                $file_name = file_name_generator($report_title);
                return $pdf->download($file_name);
            }
        } else {

            $data['purchases'] = $query->paginate(100)->appends(request()->query());
            return view('member.reports.purchases.product_purchase_report', $data);
        }
    }

    public function supplier_purchase_report(Request $request)
    {

        $inputs = $request->all();

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');


        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['suppliers'] = SupplierOrCustomer::onlySuppliers()->get()->pluck('name', 'id');
        $data['item'] = $data['sharer'] = null;
        $query = new PurchaseDetail();

        if (!empty($inputs['item_id'])) {
            $data['item'] = $inputs['item_id'];
            $query = $query->where('item_id', $inputs['item_id']);
        }

        if (!empty($inputs['warehouse_id'])) {
            $warehouse_id = $inputs['warehouse_id'];

            $warehouseSale = WarehouseHistory::where('warehouse_id', $warehouse_id)->where('model', "PurchaseDetail");
            $warehouseSale = $this->searchPurchaseDate($inputs, $warehouseSale);
            $warehouseSale = $warehouseSale->pluck('model_id')->toArray();

            $query = $query->whereIn('id', $warehouseSale);
        }

        if (!empty($inputs['supplier_id'])) {
            $data['sharer'] = $inputs['supplier_id'];
            $query = $query->whereHas('purchases', function ($query) use ($inputs) {
                $query->where('supplier_id', $inputs['supplier_id']);
            });
        }

        $query = $this->searchPurchaseDate($inputs, $query);

        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('purchase_id');

        $query = $this->authCompany($query, $request);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('report.purchase_report_by_supplier');

        if ($request->type == "print" || $request->type == "download") {
            $data['purchases'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.purchases.print_supplier_purchase_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.purchases.print_supplier_purchase_report', $data);


                $file_name = file_name_generator("Supplier_Purchase_Report_");
                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['purchases'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.purchases.purchase-from-supplier', $data);
        }
    }

    public function purchase_report(Request $request)
    {
        $inputs = $request->all();
        $query = new Purchase();

        $query = $this->searchPurchaseDate($inputs, $query);
        $query = $query->orderBy('date');
        $query = $query->orderBy('id');

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        $data['report_title'] = "Purchase Report";
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $query = $this->authCompany($query, $request);

//        dd($query->toSql());
        if ($request->type == "print" || $request->type == "download") {

            $data['purchases'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.purchases.print_purchase_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.purchases.print_purchase_report', $data);

                $file_name = file_name_generator("Purchase_Report_");
                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['purchases'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.purchases.purchase_report', $data);
        }
    }

    public function warehouse_purchase_report(Request $request)
    {

        $inputs = $request->all();

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');

        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['managers'] = User::get()->pluck('full_name', 'id');

        $query = new PurchaseDetail();
        $data['item'] = null;
        if (!empty($inputs['item_id'])) {
            $query = $query->where('item_id', $inputs['item_id']);
            $data['item'] = $inputs['item_id'];
        }

        if (!empty($inputs['warehouse_id'])) {
            $warehouse_id = $inputs['warehouse_id'];

            $warehouseSale = WarehouseHistory::where('warehouse_id', $warehouse_id)->where('model', "PurchaseDetail");
            $warehouseSale = $this->searchPurchaseDate($inputs, $warehouseSale);
            $warehouseSale = $warehouseSale->pluck('model_id')->toArray();

            $query = $query->whereIn('id', $warehouseSale);
        }

        $query = $this->searchPurchaseDate($inputs, $query);
        $query = $this->authCompany($query, $request);


        if (!empty($inputs['manager_id'])) {

            $manager_id = $inputs['manager_id'];

            $user = User::findOrFail($manager_id);
            $sharer = SupplierOrCustomer::where('manager_id', $manager_id)->whereNotNull('account_type_id')->active();
            $sharer = $sharer->pluck('id');

            $query = $query->whereHas('purchases', function ($q) use ($sharer) {
                $q->whereIn('supplier_id', $sharer);
            });
        }

        $query = $query->with('purchases');
        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('purchase_id');



        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = $report_title = "Warehouse Purchase Report ";

        if ($request->type == "print" || $request->type == "download") {

            $data['purchases'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.purchases.print_warehouse_purchase_report', $data);
            } else if ($request->type == "download") {

                $pdf = PDF::loadView('member.reports.purchases.print_warehouse_purchase_report', $data);

                $file_name = file_name_generator($report_title);
                return $pdf->download($file_name);
            }
        } else {

            $data['purchases'] = $query->paginate(100)->appends(request()->query());
            return view('member.reports.purchases.warehouse_purchase_report', $data);
        }
    }

    public function daywise_total_purchases(Request $request){

        $inputs = $request->all();

        $purchase_details = new PurchaseDetail();
        $purchase_details = $this->searchPurchaseDate($inputs, $purchase_details);

        $data['item'] = '';
        if (!empty($inputs['item_id'])) {
            $data['item'] = $inputs['item_id'];
            $purchase_details = $purchase_details->where('item_id', $inputs['item_id']);
        }

        $data['purchase_details'] = $purchase_details
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->groupBy('date')
            ->groupBy('item_id')
            ->orderBy('item_id')
            ->orderBy('date')
            ->get();

        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('common.total_purchases_report_as_per_day');


        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.purchases.print_daywise_total_purchases', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.purchases.print_daywise_total_purchases', $data);
                $file_name = file_name_generator("Total_Sales_and_Purchases_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.reports.purchases.daywise_total_purchases', $data);
        }
    }

    public function purchase_return_report(Request $request)
    {

        $inputs = $request->all();
        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['suppliers'] = SupplierOrCustomer::onlySuppliers()->get()->pluck('name', 'id');
        $data['item'] = $data['sharer'] = null;
        $query = new ReturnPurchase();

        if (!empty($inputs['item_id'])) {
            $data['item'] = $inputs['item_id'];
            $query = $query->where('item_id', $inputs['item_id']);
        }

        if (!empty($inputs['supplier_id'])) {
            $data['sharer'] = $inputs['supplier_id'];
            $query = $query->whereHas('purchases', function ($query) use ($inputs) {
                $query->where('supplier_id', $inputs['supplier_id']);
            });
        }

        $query = $this->searchPurchaseReturnDate($inputs, $query);

        $query = $query->orderBy('return_date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('purchase_id');

        $query = $this->authCompany($query, $request);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('common.purchase_return_report')." "."<br/> ".trans('common.date').": ".$this->fromDate." to ".$this->toDate ;

        if ($request->type == "print" || $request->type == "download") {
            $data['purchases'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.purchases.print_purchase_return_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.purchases.print_purchase_return_report', $data);


                $file_name = file_name_generator("Purchase_Return_Report_");
                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['purchases'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.purchases.purchase-return-report', $data);
        }
    }

    private function searchPurchaseDate($inputs, $query){

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if ($fromDate == null && $toDate != "") {
            $query = $query->whereDate('date', '<=', $toDate);
        } elseif ($fromDate != null && $toDate == null) {
            $query = $query->whereDate('date', '>=', $fromDate);
        } elseif ($fromDate != null && $toDate != null) {
            $query = $query->whereBetween('date', [$fromDate, $toDate]);
        } else {
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereDate('date', '>=', $fromDate);
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $query;
    }

    private function searchPurchaseReturnDate($inputs, $query){

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if ($fromDate == null && $toDate != "") {
            $query = $query->whereDate('return_date', '<=', $toDate);
        } elseif ($fromDate != null && $toDate == null) {
            $query = $query->whereDate('return_date', '>=', $fromDate);
        } elseif ($fromDate != null && $toDate != null) {
            $query = $query->whereBetween('return_date', [$fromDate, $toDate]);
        } else {
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereDate('return_date', '>=', $fromDate);
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $query;
    }

}
