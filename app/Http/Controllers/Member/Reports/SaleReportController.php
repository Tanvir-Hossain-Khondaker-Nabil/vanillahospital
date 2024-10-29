<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Area;
use App\Models\Company;
use App\Models\District;
use App\Models\Division;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SaleReturn;
use App\Models\Shift;
use App\Models\SupplierOrCustomer;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SaleReportController extends BaseReportController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function customer_sale_report(Request $request)
    {

        $inputs = $request->all();
        $data['products'] = Item::get()->pluck('item_name', 'id');

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');


        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->get()->pluck('name', 'id');
        $data['item'] = $data['sharer'] = null;
        $query = new SaleDetails();

        if (!empty($inputs['item_id'])) {
            $data['item'] = $inputs['item_id'];
            $query = $query->where('item_id', $inputs['item_id']);
        }

        if (!empty($inputs['customer_id'])) {
            $data['sharer'] = $inputs['customer_id'];
            $query = $query->whereHas('sale', function ($query) use ($inputs) {
                $query->where('customer_id', $inputs['customer_id']);
            });
        }

        if (!empty($inputs['warehouse_id'])) {
            $warehouse_id = $inputs['warehouse_id'];

            $warehouseSale = WarehouseHistory::where('warehouse_id', $warehouse_id)->where('model', "SaleDetail");
            $warehouseSale = $this->searchSaleDate($inputs, $warehouseSale);
            $warehouseSale = $warehouseSale->pluck('model_id')->toArray();

            $query = $query->whereIn('id', $warehouseSale);
        }


        $query = $this->searchSaleDate($inputs, $query);
        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('sale_id');
        $query = $this->authCompany($query, $request);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] =trans('common.sale_report_by_customer')." <br/> ".trans('common.date').": ".$this->fromDate." to ".$this->toDate ;

        if ($request->type == "print" || $request->type == "download") {
            $data['sales'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.sales.print_customer_sale_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_customer_sale_report', $data);
                $file_name = file_name_generator("Customer_Sale_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['sales'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.sales.sale-from-customer', $data);
        }
    }

    public function warehouse_sale_report(Request $request)
    {

        $inputs = $request->all();

        $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->get()->pluck('name', 'id');
        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['users'] = User::authCompany()->systemUser()->get()->pluck('full_name', 'id');
        $data['managers'] = User::authCompany()->systemUser()->get()->pluck('full_name', 'id');


        $query = new SaleDetails();
        $data['item'] = null;

        if (!empty($inputs['item_id'])) {
            $query = $query->where('item_id', $inputs['item_id']);
            $data['item'] = $inputs['item_id'];
        }

        $data['sharer'] = '';
        if (!empty($inputs['customer_id'])) {
            $user = SupplierOrCustomer::findOrFail($inputs['customer_id']);
            $data['sharer'] = $user->name;
            $query = $query->whereHas('sale', function ($query) use ($inputs) {
                $query->where('customer_id', $inputs['customer_id']);
            });
        }

        $data['creator'] = '';
        if (!empty($inputs['user_id'])) {
            $creator = User::findOrFail($inputs['user_id']);
            $data['creator'] = $creator->full_name;
            $query = $query->whereHas('sale', function ($query) use ($inputs) {
                $query->where('created_by', $inputs['user_id']);
            });
        }


        if (!empty($inputs['warehouse_id'])) {
            $warehouse_id = $inputs['warehouse_id'];

            $warehouseSale = WarehouseHistory::where('warehouse_id', $warehouse_id)->where('model', "SaleDetail");
            $warehouseSale = $this->searchSaleDate($inputs, $warehouseSale);
            $warehouseSale = $warehouseSale->pluck('model_id')->toArray();

            $query = $query->whereIn('id', $warehouseSale);
        }


        $query = $this->searchSaleDate($inputs, $query);

        $data['manager'] = '';

        if (!empty($inputs['manager_id']) || !empty($inputs['division_id']) || !empty($inputs['district_id']) || !empty($inputs['upazilla_id']) || !empty($inputs['union_id']) || !empty($inputs['area_id'])) {


            $manager_id = isset($inputs['manager_id']) ? $inputs['manager_id'] : '';
            $division_id = isset($inputs['division_id']) ? $inputs['division_id'] : '';
            $district_id = isset($inputs['district_id']) ? $inputs['district_id'] : '';
            $upazilla_id = isset($inputs['upazilla_id']) ? $inputs['upazilla_id'] : '';
            $union_id = isset($inputs['union_id']) ? $inputs['union_id'] : '';
            $area_id = isset($inputs['area_id']) ? $inputs['area_id'] : '';


            $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->active();

            if (!empty($manager_id)){

                $user = User::findOrFail($manager_id);
                $sharer = $sharer->where('manager_id', $manager_id);
                $data['manager'] = $user->full_name;
            }

            if ($district_id) {
                $sharer = $sharer->where('district_id', $district_id);
                $data['district'] = District::find($district_id);
            }

            if (($upazilla_id)) {
                $sharer = $sharer->where('upazilla_id', $upazilla_id);
                $data['upazilla'] = Upazilla::find($upazilla_id);
            }

            if (($union_id)) {
                $sharer = $sharer->where('union_id', $union_id);
                $data['union'] = Union::find($union_id);
            }

            if (($area_id)) {
                $sharer = $sharer->where('area_id', $area_id);
                $data['area'] = Area::find($area_id);
            }

            $sharer = $sharer->pluck('id');


            $query = $query->whereHas('sale', function ($q) use ($sharer) {
                $q->whereIn('customer_id', $sharer)->orderBy('sales.customer_id');
            });
        }


        $query = $this->authCompany($query, $request);
        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('sale_id');


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $report_title = "Warehouse Sale Report "." <br/> Date: ".$this->fromDate." to ".$this->toDate."  <br/> " ;
        $report_title = $data['manager']  ? $report_title." Manager: ".$data['manager']."  <br/> " : $report_title;
        $report_title = $data['sharer'] ? $report_title.$data['sharer']."  <br/> " : $report_title;
        $report_title = $data['creator'] ? $report_title.$data['creator']."  <br/> " : $report_title;
        $report_title = isset($data['area']) ? $report_title.$data['area']->name."  <br/> " : $report_title;
        $report_title = isset($data['union']) ? $report_title." ".$data['union']->name : $report_title;
        $report_title = isset($data['upazilla']) ? $report_title.$data['upazilla']->name."  <br/> " : $report_title;
        $report_title = isset($data['district'])  ? $report_title." ".$data['district']->name : $report_title;

        $data['report_title'] = $report_title;

        $query = $query->with('sale');

        if ($request->type == "print" || $request->type == "download") {

            $data['sales'] = $query->get();
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.sales.print_product_warehouse_sale_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_product_warehouse_sale_report', $data);
                $file_name = file_name_generator($report_title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {


            $data['sales'] = $query->paginate(100)->appends(request()->query());
//            $data['sales']  = $query->get();
            return view('member.reports.sales.product_warehouse_sale_report', $data);
        }
    }

    public function sales_mini_statement(Request $request)
    {
        $inputs = $request->all();
        $data['manager'] = '';

        if (!empty($inputs['manager_id'])) {
            $manager_id = isset($inputs['manager_id']) ? $inputs['manager_id'] : '';
        }

        $query = new Sale();
        $shift = $request->shift_id ?? "";

        if($shift)
        {
            $shift = Shift::find($request->shift_id);
        }

        $query = $this->searchSaleDateShift($inputs, $query,'sales.', $shift);
        $query = $query->orderBy('sales.date');
        $query = $query->orderBy('sales.id');

        $query = $this->authCompany($query, $request, 'sales');


        if (!empty($manager_id)){
            $user = User::findOrFail($manager_id);
            $query = $query->where('sales.created_by', $manager_id);
            $data['manager'] = $user->full_name;
        }

        $data['total_sale'] = $query->sum('grand_total');
        $data['total_paid'] = $query->sum('paid_amount');
        $data['total_due'] = $query->sum('due');

        $data['sales'] = $query->join('sales_details', 'sales.id', '=', 'sales_details.sale_id')
            ->join('items', 'sales_details.item_id', '=', 'items.id')
            ->leftJoin('suppliers_or_customers', 'sales.customer_id', '=', 'suppliers_or_customers.id')
            ->where('due', ">", 0)
            ->select('items.item_name','items.unit','suppliers_or_customers.name','sales.customer_id', 'sales_details.item_id',
                DB::raw('SUM(sales_details.qty) as total_qty'),
                DB::raw('SUM(sales_details.total_price) as total_price'),
                DB::raw('count(sales_details.sale_id) as count'),
                DB::raw('SUM(sales.due) as total_due')
            )
            ->groupBy('sales.customer_id', 'sales_details.item_id')
            ->orderBy('sales.customer_id', 'asc')
            ->get();



        $report_title = "Sales Mini Statement"." <br/> Date: ".$this->fromDate;
        $report_title = $shift  ? $report_title."<br/> Shift: ".$shift->shift_type_name." (".$shift->time_in_out.")  <br/> " : $report_title;
        $data['report_title'] = $data['manager']  ? $report_title."<br/> Manager: ".$data['manager']."  <br/> " : $report_title;

        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['managers'] = User::authCompany()->systemUser()->get()->pluck('full_name', 'id');
        $data['shifts'] = Shift::get()->pluck('time_in_out', 'id');

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.sales.print_mini_statement', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_mini_statement', $data);
                $file_name = file_name_generator("Sale_mini_statement_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.reports.sales.mini_statement', $data);
        }
    }

    public function sale_report_by_product($type, Request $request)
    {

        $inputs = $request->all();

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');


        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->get()->pluck('name', 'id');
        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['managers'] = User::authCompany()->systemUser()->get()->pluck('full_name', 'id');

        $data['divisions'] = Division::get()->pluck('display_name_bd', 'id');
        $data['districts'] = District::get()->pluck('display_name_bd', 'id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd', 'id');
        $data['unions'] = Union::get()->pluck('display_name_bd', 'id');
        $data['areas'] = Area::get()->pluck('name', 'id');

        $query = new SaleDetails();
        $data['item'] = null;
        $data['search_type'] = $type;
        if (!empty($inputs['item_id'])) {
            $query = $query->where('item_id', $inputs['item_id']);
            $data['item'] = $inputs['item_id'];
        }

        $data['sharer'] = '';
        if (!empty($inputs['customer_id'])) {
            $user = SupplierOrCustomer::findOrFail($inputs['customer_id']);
            $data['sharer'] = $user->name;
            $query = $query->whereHas('sale', function ($query) use ($inputs) {
                $query->where('customer_id', $inputs['customer_id']);
            });
        }


        if (!empty($inputs['warehouse_id'])) {
            $warehouse_id = $inputs['warehouse_id'];

            $warehouseSale = WarehouseHistory::where('warehouse_id', $warehouse_id)->where('model', "SaleDetail");
            $warehouseSale = $this->searchSaleDate($inputs, $warehouseSale);
            $warehouseSale = $warehouseSale->pluck('model_id')->toArray();

            $query = $query->whereIn('id', $warehouseSale);
        }


        $query = $this->searchSaleDate($inputs, $query);

        $data['manager'] = '';

        if (!empty($inputs['manager_id']) || !empty($inputs['division_id']) || !empty($inputs['district_id']) || !empty($inputs['upazilla_id']) || !empty($inputs['union_id']) || !empty($inputs['area_id'])) {


            $manager_id = isset($inputs['manager_id']) ? $inputs['manager_id'] : '';
            $division_id = isset($inputs['division_id']) ? $inputs['division_id'] : '';
            $district_id = isset($inputs['district_id']) ? $inputs['district_id'] : '';
            $upazilla_id = isset($inputs['upazilla_id']) ? $inputs['upazilla_id'] : '';
            $union_id = isset($inputs['union_id']) ? $inputs['union_id'] : '';
            $area_id = isset($inputs['area_id']) ? $inputs['area_id'] : '';


            $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->active();

            if (!empty($manager_id)){

                $user = User::findOrFail($manager_id);
                $sharer = $sharer->where('manager_id', $manager_id);
                $data['manager'] = $user->full_name;
            }

            if ($district_id) {
                $sharer = $sharer->where('district_id', $district_id);
                $data['district'] = District::find($district_id);
            }

            if (($upazilla_id)) {
                $sharer = $sharer->where('upazilla_id', $upazilla_id);
                $data['upazilla'] = Upazilla::find($upazilla_id);
            }

            if (($union_id)) {
                $sharer = $sharer->where('union_id', $union_id);
                $data['union'] = Union::find($union_id);
            }

            if (($area_id)) {
                $sharer = $sharer->where('area_id', $area_id);
                $data['area'] = Area::find($area_id);
            }

            $sharer = $sharer->pluck('id');


            $query = $query->whereHas('sale', function ($q) use ($sharer) {
                $q->whereIn('customer_id', $sharer)->orderBy('sales.customer_id');
            });
        }


        $query = $this->authCompany($query, $request);
        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('sale_id');


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
    //    $report_title = "Sale Report by ".ucfirst(human_words($type))." <br/> Date: ".$this->fromDate." to ".$this->toDate."  <br/> " ;
        $report_title = trans('common.sale_report_by')." ".trans('common.'.strtolower(human_words($type)))." <br/> ".trans('common.date').": ".$this->fromDate." to ".$this->toDate."  <br/> " ;
       $report_title = $data['manager']  ? $report_title." ".trans('common.manager').": ".$data['manager']."  <br/> " : $report_title;
       $report_title = $data['sharer'] ? $report_title.$data['sharer']."  <br/> " : $report_title;
        $report_title = isset($data['area']) ? $report_title.$data['area']->name."  <br/> " : $report_title;
        $report_title = isset($data['union']) ? $report_title." ".$data['union']->name : $report_title;
        $report_title = isset($data['upazilla']) ? $report_title.$data['upazilla']->name."  <br/> " : $report_title;
       $report_title = isset($data['district'])  ? $report_title." ".$data['district']->name : $report_title;

        $data['report_title'] = $report_title;

        $query = $query->with('sale');

        if ($request->type == "print" || $request->type == "download") {

            $data['sales'] = $query->get();
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.sales.print_product_sale_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_product_sale_report', $data);
                $file_name = file_name_generator($report_title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {


            $data['sales'] = $query->paginate(100)->appends(request()->query());
//            $data['sales']  = $query->get();
            return view('member.reports.sales.product_sale_report', $data);
        }
    }

    public function sale_profit_report(Request $request)
    {

        $inputs = $request->all();
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->get()->pluck('name', 'id');
        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['managers'] = User::authCompany()->systemUser()->get()->pluck('full_name', 'id');

        $data['divisions'] = Division::get()->pluck('display_name_bd', 'id');
        $data['districts'] = District::get()->pluck('display_name_bd', 'id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd', 'id');
        $data['unions'] = Union::get()->pluck('display_name_bd', 'id');
        $data['areas'] = Area::get()->pluck('name', 'id');

        if(config('settings.warehouse'))
            $data['warehouses'] = Warehouse::authCompany()->get()->pluck('title', 'id');


        $query = new SaleDetails();
        $data['item'] = null;
        $data['search_type'] = $type = "Profit";
        if (!empty($inputs['item_id'])) {
            $query = $query->where('item_id', $inputs['item_id']);
            $data['item'] = $inputs['item_id'];
        }

        $data['sharer'] = '';
        if (!empty($inputs['customer_id'])) {
            $user = SupplierOrCustomer::findOrFail($inputs['customer_id']);
            $data['sharer'] = $user->name;
            $query = $query->whereHas('sale', function ($query) use ($inputs) {
                $query->where('customer_id', $inputs['customer_id']);
            });
        }

        $query = $this->searchSaleDate($inputs, $query);

        $data['manager'] = '';

        if (!empty($inputs['manager_id']) || !empty($inputs['division_id']) || !empty($inputs['district_id']) || !empty($inputs['upazilla_id']) || !empty($inputs['union_id']) || !empty($inputs['area_id'])) {


            $manager_id = isset($inputs['manager_id']) ? $inputs['manager_id'] : '';
            $division_id = isset($inputs['division_id']) ? $inputs['division_id'] : '';
            $district_id = isset($inputs['district_id']) ? $inputs['district_id'] : '';
            $upazilla_id = isset($inputs['upazilla_id']) ? $inputs['upazilla_id'] : '';
            $union_id = isset($inputs['union_id']) ? $inputs['union_id'] : '';
            $area_id = isset($inputs['area_id']) ? $inputs['area_id'] : '';


            $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->active();

            if (!empty($manager_id)){

                $user = User::findOrFail($manager_id);
                $sharer = $sharer->where('manager_id', $manager_id);
                $data['manager'] = $user->full_name;
            }

            if ($district_id) {
                $sharer = $sharer->where('district_id', $district_id);
                $data['district'] = District::find($district_id);
            }

            if (($upazilla_id)) {
                $sharer = $sharer->where('upazilla_id', $upazilla_id);
                $data['upazilla'] = Upazilla::find($upazilla_id);
            }

            if (($union_id)) {
                $sharer = $sharer->where('union_id', $union_id);
                $data['union'] = Union::find($union_id);
            }

            if (($area_id)) {
                $sharer = $sharer->where('area_id', $area_id);
                $data['area'] = Area::find($area_id);
            }

            $sharer = $sharer->pluck('id');


            $query = $query->whereHas('sale', function ($q) use ($sharer) {
                $q->whereIn('customer_id', $sharer)->orderBy('sales.customer_id');
            });
        }


        $query = $this->authCompany($query, $request);
        $query = $query->orderBy('date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('sale_id');


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
    //    $report_title = "Sale Report by ".ucfirst(human_words($type))." <br/> Date: ".$this->fromDate." to ".$this->toDate."  <br/> " ;
        $report_title = trans('common.sale_report_by')." ".trans('common.'.strtolower(human_words($type)))." <br/> ".trans('common.date').": ".$this->fromDate." to ".$this->toDate."  <br/> " ;
       $report_title = $data['manager']  ? $report_title." ".trans('common.manager').": ".$data['manager']."  <br/> " : $report_title;
       $report_title = $data['sharer'] ? $report_title.$data['sharer']."  <br/> " : $report_title;
        $report_title = isset($data['area']) ? $report_title.$data['area']->name."  <br/> " : $report_title;
        $report_title = isset($data['union']) ? $report_title." ".$data['union']->name : $report_title;
        $report_title = isset($data['upazilla']) ? $report_title.$data['upazilla']->name."  <br/> " : $report_title;
       $report_title = isset($data['district'])  ? $report_title." ".$data['district']->name : $report_title;

        $data['report_title'] = $report_title;

        $query = $query->with('sale');

        if ($request->type == "print" || $request->type == "download") {

            $data['sales'] = $query->get();
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.sales.print_sale_profit_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_sale_profit_report', $data);
                $file_name = file_name_generator($report_title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {


            $data['sales'] = $query->paginate(100)->appends(request()->query());
//            $data['sales']  = $query->get();
            return view('member.reports.sales.sale_profit_report', $data);
        }
    }

    public function sale_report(Request $request)
    {
       
        $inputs = $request->all();
        $query = new Sale();

        $query = $this->searchSaleDate($inputs, $query);
        $query = $query->orderBy('date');
        $query = $query->orderBy('id');

        $data['report_title'] = " Sale Report"." <br/> Date: ".$this->fromDate." to ".$this->toDate;

        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {

            $data['sales'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.sales.print_sale_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_sale_report', $data);
                $file_name = file_name_generator("Sale_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['sales'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.sales.sale_report', $data);
        }
    }


    public function sale_return_report(Request $request)
    {

        $inputs = $request->all();
        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->get()->pluck('name', 'id');
        $data['item'] = $data['sharer'] = null;
        $query = new SaleReturn();

        if (!empty($inputs['item_id'])) {
            $data['item'] = $inputs['item_id'];
            $query = $query->where('item_id', $inputs['item_id']);
        }

        if (!empty($inputs['customer_id'])) {
            $data['sharer'] = $inputs['customer_id'];
            $query = $query->whereHas('sales', function ($query) use ($inputs) {
                $query->where('customer_id', $inputs['customer_id']);
            });
        }

        $query = $this->searchSaleReturnDate($inputs, $query);
        $query = $query->orderBy('return_date');
        $query = $query->orderBy('item_id');
        $query = $query->orderBy('sale_id');
        $query = $this->authCompany($query, $request);


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('common.sale_return_report')."<br/> ".trans('common.date').": ".$this->fromDate." to ".$this->toDate ;

        if ($request->type == "print" || $request->type == "download") {
            $data['sales'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.sales.print_sale_return_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_sale_return_report', $data);
                $file_name = file_name_generator("Sale_Return_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            $data['sales'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.sales.sale-return-report', $data);
        }
    }


    public function daywise_total_sales(Request $request)
    {
        $inputs = $request->all();

        $sale_details = new SaleDetails();
        $sale_details = $this->searchSaleDate($inputs, $sale_details);

        $data['item'] = '';
        if (!empty($inputs['item_id'])) {
            $data['item'] = $inputs['item_id'];
            $sale_details = $sale_details->where('item_id', $inputs['item_id']);
        }

        $data['sale_details'] = $sale_details
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->groupBy('date')
            ->groupBy('item_id')
            ->orderBy('date')
            ->orderBy('item_id')
            ->get();


        $data['products'] = Item::get()->pluck('item_name', 'id');

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('common.total_sales_as_per_day')." <br/> ".trans('common.date').": ".$this->fromDate." to ".$this->toDate ;;


        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.sales.print_daywise_total_sales', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_daywise_total_sales', $data);
                $file_name = file_name_generator("Total_Sales_and_Purchases_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.reports.sales.daywise_total_sales', $data);
        }
    }




    private function searchSaleDate($inputs, $query){

        $fromDate = $toDate = '';
        if( !empty($inputs['from_date']) )
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if( !empty($inputs['to_date']) )
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if( empty($fromDate) && (!empty($toDate)) ) {
            $query = $query->whereDate('date','<=', $toDate);
        }elseif( (!empty($fromDate)) && empty($toDate) ) {
            $query = $query->whereDate('date','>=', $fromDate);
        }elseif ( $fromDate !='' && $toDate != '' ) {
            $query = $query->whereBetween('date', [$fromDate, $toDate]);
        }else{
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereDate('date', '>=', $fromDate);
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $query;

    }


    private function searchSaleReturnDate($inputs, $query){

        $fromDate = $toDate = '';
        if( !empty($inputs['from_date']) )
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if( !empty($inputs['to_date']) )
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if( empty($fromDate) && (!empty($toDate)) ) {
            $query = $query->whereDate('return_date','<=', $toDate);
        }elseif( (!empty($fromDate)) && empty($toDate) ) {
            $query = $query->whereDate('return_date','>=', $fromDate);
        }elseif ( $fromDate !='' && $toDate != '' ) {
            $query = $query->whereBetween('return_date', [$fromDate, $toDate]);
        }else{
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereDate('return_date', '>=', $fromDate);
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $query;

    }

}