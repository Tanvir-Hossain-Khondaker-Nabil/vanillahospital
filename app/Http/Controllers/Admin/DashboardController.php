<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Member\ProjectController;
use App\Models\AttendanceMaster;
use App\Models\Client;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmpLeave;
use App\Models\EmployeeInfo;
use App\Models\EmployeeProject;
use App\Models\Item;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Shift;
use App\Models\Support;
use App\Models\Task;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\TaskEmployeeStatus;
use App\Models\TaskStatusHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        $menuActive = \Session::get('sidebar_menu', '');

        $view_set = request()->get('view_item');

        if ($menuActive == 'accounts') {
            return $this->account_dashboard();
        } else if ($menuActive == 'inventory') {

            if($view_set == "normal")
                return $this->inventory_dashboard();
            else
                return $this->inventory_dashboard_latest();

        } else if ($menuActive == 'warehouse') {
            return $this->warehouse_dashboard();
        } else if ($menuActive == 'requisition') {
            return $this->requisition_dashboard();
        } else if ($menuActive == 'quotation') {
            return $this->quotation_dashboard();
        } else if ($menuActive == 'project') {
            return $this->project_dashboard();
        } else if ($menuActive == 'reports') {
            return $this->reports_dashboard();
        } else if ($menuActive == 'procurement') {
            return $this->procurement_dashboard();
        } else if ($menuActive == 'hr') {

            if($view_set == "normal")
                return $this->hr_dashboard();
            else
                return $this->hr_dashboard_latest();

        } else if ($menuActive == 'settings') {
            return $this->settings_dashboard();
        }else {
            return view('admin.dashboard.index');
        }
    }

    public function account_dashboard()
    {
        $data['total_products']  = Item::authCompany()->count();
        $data['total_purchases'] = Purchase::authCompany()->count();
        $data['total_sales']     = Sale::authCompany()->count();
        $data['total_users']     = User::membersUser()->active()->count();

        $data['total_transport_cost'] = Purchase::authCompany()->sum('transport_cost');
        $data['total_unload_cost']    = Purchase::authCompany()->sum('unload_cost');

        $data['total_purchase_amount'] = Purchase::authCompany()->sum('total_amount');
        $data['total_sales_amount']    = Sale::authCompany()->sum('grand_total');
        $data['today_sales_amount']    = Sale::authCompany()->whereDate('date', Carbon::today())->sum('grand_total');
        $data['today_purchase_amount'] = Purchase::authCompany()->whereDate('date', Carbon::today())->sum('total_amount');

        $data['today_due']          = Sale::authCompany()->whereDate('date', Carbon::today())->sum('due');
        $data['total_due']          = Sale::authCompany()->sum('due');
        $data['today_out_standing'] = Purchase::authCompany()->whereDate('date', Carbon::today())->sum('due_amount');
        $data['total_out_standing'] = Purchase::authCompany()->sum('due_amount');

        $data['transfers']      = Transactions::authCompany()->onlyTransfer()->offset(0)->limit(10)->select('date', 'transaction_code', 'amount')->latest()->get();
        $data['total_transfer'] = Transactions::authCompany()->onlyTransfer()->sum('amount');

        $data['total_income']  = Transactions::authCompany()->whereIn('transaction_method', ['Income', 'Sales'])->sum('amount');
        $data['total_expense'] = Transactions::authCompany()->whereIn('transaction_method', ['Expense', 'Purchases'])->sum('amount');
        $data['today_income']  = Transactions::authCompany()->whereIn('transaction_method', ['Income', 'Sales'])->whereDate('date', Carbon::today())->sum('amount');
        $data['today_expense'] = Transactions::authCompany()->whereIn('transaction_method', ['Expense', 'Purchases'])->whereDate('date', Carbon::today())->sum('amount');

        return view('admin.dashboard.accounts', $data);
    }

    public function inventory_dashboard()
    {
        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::today()->format('m');
        $lastMonth = Carbon::today()->subMonth()->format('m');

        $data['total_products'] = Item::authCompany()->count();
        $data['total_purchases'] = Purchase::authCompany()->count();
        $data['total_sales'] = Sale::authCompany()->count();
        $data['total_users'] = User::membersUser()->active()->count();

        $data['total_transport_cost'] = Purchase::authCompany()->sum('transport_cost');
        $data['total_unload_cost'] = Purchase::authCompany()->sum('unload_cost');

        $data['total_purchase_amount'] = Purchase::authCompany()->sum('total_amount');
        $data['total_sales_amount'] = Sale::authCompany()->sum('grand_total');
        $data['this_month_sales'] = Sale::whereMonth('date', $thisMonth)->authCompany()->sum('grand_total');
        $data['last_month_sales'] = Sale::whereMonth('date', $lastMonth)->authCompany()->sum('grand_total');
        $data['today_sales_amount'] = Sale::authCompany()->whereDate('date', $today)->sum('grand_total');
        $data['today_purchase_amount'] = Purchase::authCompany()->whereDate('date', $today)->sum('total_amount');

        $data['today_due'] = Sale::authCompany()->whereDate('date', $today)->sum('due');
        $data['total_due'] = Sale::authCompany()->sum('due');
        $data['today_out_standing'] = Purchase::authCompany()->whereDate('date', $today)->sum('due_amount');
        $data['total_out_standing'] = Purchase::authCompany()->sum('due_amount');


        return view('admin.dashboard.inventory', $data);
    }
    public function inventory_dashboard_latest()
    {
        $today     = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::today()->format('m');
        $lastMonth = Carbon::today()->subMonth()->format('m');

        $data['total_products'] = Item::authCompany()->count();
        $data['total_purchases'] = Purchase::authCompany()->count();
        $data['total_sales'] = Sale::authCompany()->count();
        $data['total_users'] = User::membersUser()->active()->count();

        $data['total_transport_cost'] = Purchase::authCompany()->sum('transport_cost');
        $data['total_unload_cost'] = Purchase::authCompany()->sum('unload_cost');

        $data['total_purchase_amount'] = Purchase::authCompany()->sum('total_amount');
        $data['total_sales_amount'] = Sale::authCompany()->sum('grand_total');
        $data['this_month_sales'] = Sale::whereMonth('date', $thisMonth)->authCompany()->sum('grand_total');
        $data['last_month_sales'] = Sale::whereMonth('date', $lastMonth)->authCompany()->sum('grand_total');
        $data['today_sales_amount'] = Sale::authCompany()->whereDate('date', $today)->sum('grand_total');
        $data['today_purchase_amount'] = Purchase::authCompany()->whereDate('date', $today)->sum('total_amount');


        $oneYear = Carbon::now()->subMonths(12);
        $Sales = Sale::where('date', '>=', $oneYear)
            ->select(
                DB::raw('DATE_FORMAT(date, "%b") as month'),
                DB::raw('SUM(grand_total) as grand_total')
            )
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('date', 'asc') // Order by month in ascending order
            ->get();

        $data['monthlySaleAmount'] = json_encode($Sales->pluck('grand_total')->toArray());
        $data['saleMonthly'] = json_encode($Sales->pluck('month')->toArray());


        $SaleDetailWithAmount = SaleDetails::join('items', 'item_id', '=', 'items.id')
            ->select(
                'items.item_name',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(total_price) as total_price')
            )
            ->groupBy('items.item_name')
            ->orderBy('item_name', 'asc') // Order by month in ascending order
            ->get();


        $data['ProductSaleAmount'] = json_encode($SaleDetailWithAmount->pluck('total_price')->toArray());
        $data['ProductSaleQty'] = json_encode($SaleDetailWithAmount->pluck('total_qty')->toArray());
        $data['saleProduct'] = json_encode($SaleDetailWithAmount->pluck('item_name')->toArray());

        $oneYearAgo = Carbon::now()->subMonths(12);
        $lastOneYearSaleDetails = SaleDetails::join('items', 'item_id', '=', 'items.id')
            ->where('date', '>=', $oneYearAgo)
            ->select(
                'items.item_name',
                DB::raw('SUM(qty) as total_qty')
            )
            ->groupBy('items.item_name')
            ->orderBy('item_name', 'asc') // Order by month in ascending order
            ->get();

        $data['lastOneYearSaleQty'] = json_encode($lastOneYearSaleDetails->pluck('total_qty')->toArray());
        $data['lastOneYearSaleProduct'] = json_encode($lastOneYearSaleDetails->pluck('item_name')->toArray());

        $oneYearAgo = Carbon::now()->subMonths(1);
        $lastOneMonthSaleDetails = SaleDetails::join('items', 'item_id', '=', 'items.id')
            ->where('date', '>=', $oneYearAgo)
            ->select(
                'items.item_name',
                DB::raw('SUM(qty) as total_qty')
            )
            ->groupBy('items.item_name')
            ->orderBy('item_name', 'asc') // Order by month in ascending order
            ->get();

        $data['lastOneMonthSaleQty'] = json_encode($lastOneMonthSaleDetails->pluck('total_qty')->toArray());
        $data['lastOneMonthSaleProduct'] = json_encode($lastOneMonthSaleDetails->pluck('item_name')->toArray());


        $oneYearAgo = Carbon::now()->subMonths(5);
        $SaleDetails = SaleDetails::join('items', 'item_id', '=', 'items.id')
            ->where('date', '>=', $oneYearAgo)
            ->select(
                'item_id',
                'items.item_name',
                DB::raw('DATE_FORMAT(date, "%b") as month'),
                DB::raw('SUM(qty) as total_qty')
            )
            ->groupBy('items.item_name', DB::raw('MONTH(date)'))
            ->orderBy('item_name', 'asc') // Order by month in ascending order
            ->get()
            ->toArray();


        // Initialize an empty array to store the converted data
        $convertedData = [];

        // Group the data by item_name
        $groupedData = [];
        $monthly = [];
        foreach ($SaleDetails as $item) {
            $itemName = $item["item_name"];
            if (!isset($groupedData[$itemName])) {
                $groupedData[$itemName] = [];
            }
            $groupedData[$itemName][] = $item["total_qty"];
            $monthly[] = $item["month"];
        }

        // Convert the grouped data to the desired format
        foreach ($groupedData as $itemName => $totalQtyArray) {
            $convertedItem = [
                "name" => $itemName,
                "data" => $totalQtyArray
            ];
            $convertedData[] = json_encode($convertedItem);
        }

        $data['monthlySale'] = json_encode($convertedData);
        $data['monthly'] = json_encode(array_unique($monthly));

        return view('admin.dashboard.inventory_latest', $data);
    }

    public function warehouse_dashboard()
    {

        $data = [];
        $data['total_products'] = Item::active()->count();
        $data['total_warehouses'] = Warehouse::count();
        $data['active_warehouses'] = Warehouse::active()->count();
        $data['inactive_warehouses'] = Warehouse::where('active_status',0)->count();

        return view('admin.dashboard.warehouse', $data);
    }

    public function requisition_dashboard()
    {

        $data = [];
        $data['total_quotations'] = Quotation::count();
        return view('admin.dashboard.requisition', $data);
    }

    public function quotation_dashboard()
    {

        $data = [];
        $data['total_quotations'] = Quotation::count();
        return view('admin.dashboard.quotation', $data);
    }

    public function settings_dashboard()
    {

        $data = [];

        $data['employee_joins'] = EmployeeInfo::whereMonth('join_date',"<=", date("m"))->limit(10)->latest()->get();
        $data['employee_dobs'] = EmployeeInfo::whereMonth('dob',">=", date("m"))->latest()->get();
        $data['visa_expires'] = EmployeeInfo::whereMonth('visa_expire',"<=", date("m"))->latest()->get();
        $data['passport_expires'] = EmployeeInfo::whereMonth('passport_expire',"<=", date("m"))->latest()->get();

        return view('admin.dashboard.settings', $data);
    }

    public function project_dashboard()
    {
        $project_id = request()->get('project_id');

        if($project_id != "" && $project_id>0)
        {
            $date = request()->get('date');
            $projectData = new ProjectController();
            $data = $projectData->show($project_id, true, $date);

        }else{
            $data = [];
            $data['total_projects'] = Project::count();
            $data['active_projects'] = Project::where('status','active')->count();
            $data['inactive_projects'] = Project::where('status','inactive')->count();

            $data['total_leads'] = Lead::count();

            $data['total_clients'] = Client::count();
            $data['active_clients'] = Client::where('status', "!=" ,'inactive')->count();
            $data['inactive_clients'] = Client::where('status','inactive')->count();

            $data['total_tasks'] = TaskEmployeeStatus::count();
            $data['complete_tasks'] = TaskEmployeeStatus::where('status','done')->count();
            $data['review_tasks'] = TaskEmployeeStatus::where('status','review')->count();
            $data['pending_tasks'] = TaskEmployeeStatus::where('status','in_progress')->count();
            $data['to_do_tasks'] = TaskEmployeeStatus::where('status','to_do')->count();
        }

        $data['projects'] = Project::where('status', 'active')->pluck('project','id')->toArray();

        return view('admin.dashboard.project', $data);

    }

    public function reports_dashboard()
    {

        $data = [];
        return redirect()->route('member.report.list');
    }

    public function procurement_dashboard()
    {

        $data = [];
        return view('admin.dashboard.procurement', $data);
    }

    public function hr_dashboard_latest()
    {
        $today =  date("Y-m-d");
        $data = [];

        $data['on_leaves'] = EmpLeave::where(function($query)use($today){
            return $query
                ->where('start_date', '=', $today)
                ->orwhere('end_date', '>=',$today);
        })->where('status', 1)->count();

        $data['next_attends'] = EmpLeave::where(function($query)use($today){
            return $query
                ->where('start_date', '=', $today)
                ->orwhere('end_date', '=',$today);
        })->where('status', 1)->count();


        $data['total_present'] = AttendanceMaster::where('attend_date', $today)->count();
        $data['total_shifts'] = Shift::count();
        $data['total_department'] = Department::count();
        $data['total_designation'] = Designation::count();
        $data['unread_messages'] = Support::where('reply_status',0)->count();

        $employee_joins = EmployeeInfo::whereMonth('join_date', date("m"));
        $employee_dobs = EmployeeInfo::whereMonth('dob',">=", date("m"));
        $visa_expires = EmployeeInfo::whereDate('visa_expire',"<=", $today);
        $passport_expires = EmployeeInfo::whereDate('passport_expire',"<=", $today);

        $data['show_month'] = date("m");
        $data['show_year'] = date("Y");
        $data['count_employee_joins'] = $employee_joins->count();
        $data['count_employee_dobs'] = $employee_dobs->count();
        $data['count_visa_expires'] = $visa_expires->count();
        $data['count_passport_expires'] = $passport_expires->count();

        $data['employee_joins'] = $employee_joins->limit(6)->get();
        $data['employee_dobs'] = $employee_dobs->limit(6)->get();
        $data['visa_expires'] = $visa_expires->limit(6)->get();
        $data['passport_expires'] = $passport_expires->limit(6)->get();

        $data['total_employee'] = EmployeeInfo::whereIn('user_id', User::active()->pluck('id')->toArray())->count();
        $data['inactive_employee'] = EmployeeInfo::whereIn('user_id', User::where('status', "inactive")->pluck('id')->toArray())->count();


        $employee_departments = EmployeeInfo::select(DB::raw('departments.id, departments.name, COUNT(*) AS count'))
            ->join('departments', 'departments.id', '=', 'employee_info.department_id')
            ->groupBy('departments.id')->get()->toArray();;

        $employeeSalaryRanges = EmployeeInfo::selectRaw('CASE
                    WHEN salary BETWEEN 0 AND 3000 THEN "<€3000"
                    WHEN salary BETWEEN 3001 AND 6000 THEN "€3001-€6000"
                    WHEN salary BETWEEN 6001 AND 10000 THEN "€6001-€10000"
                    ELSE "€10001<"
                END as salary_range, COUNT(*) as count')
            ->groupBy(DB::raw('CASE
                    WHEN salary BETWEEN 0 AND 3000 THEN "<€3000"
                    WHEN salary BETWEEN 3001 AND 6000 THEN "€3001-€6000"
                    WHEN salary BETWEEN 6001 AND 10000 THEN "€6001-€10000"
                    ELSE "€10001<"
                END'))
            ->orderBy('salary_range', 'desc') // Order by salary_range in ascending order
            ->get()
            ->toArray();


        $employeeCounts = EmployeeInfo::selectRaw('YEAR(join_date) as x, COUNT(*) as y')
            ->groupBy(DB::raw('YEAR(join_date)'))
            ->get()->toArray();

        $salary_system = EmployeeInfo::selectRaw('salary_system, COUNT(*) as total_employees')
            ->groupBy('salary_system')
            ->get()->toArray();

        $employee_departments = employeeInfoUnsetByArrayMap($employee_departments);
        $employeeSalaryRanges = employeeInfoUnsetByArrayMap($employeeSalaryRanges);
        $employeeCounts = employeeInfoUnsetByArrayMap($employeeCounts);
        $salarySystem = employeeInfoUnsetByArrayMap($salary_system);


        $data['employeeSalaryRanges'] = ($employeeSalaryRanges);
        $data['employeeDepartments'] = ($employee_departments);
        $data['employeeJoinYearCount'] = json_encode(collect($employeeCounts)->toJson());
        $data['employeeSalarySystemCount'] = ($salarySystem);


        return view('admin.dashboard.hr_latest', $data);
    }

    public function hr_dashboard()
    {

        $today =  date("Y-m-d");
        $data = [];
        $data['on_leaves'] = EmpLeave::where(function($query)use($today){
            return $query
                ->where('start_date', '=', $today)
                ->orwhere('end_date', '>=',$today);
        })->where('status', 1)->count();

        $data['next_attends'] = EmpLeave::where(function($query)use($today){
            return $query
                ->where('start_date', '=', $today)
                ->orwhere('end_date', '=',$today);
        })->where('status', 1)->count();


        $data['total_present'] = AttendanceMaster::where('attend_date', $today)->count();
        $data['total_shifts'] = Shift::count();
        $data['total_department'] = Department::count();
        $data['total_designation'] = Designation::count();
        $data['unread_messages'] = Support::where('reply_status',0)->count();

        $employee_joins = EmployeeInfo::whereMonth('join_date', date("m"));
        $employee_dobs = EmployeeInfo::whereMonth('dob',">=", date("m"));
        $visa_expires = EmployeeInfo::whereDate('visa_expire',"<=", $today);
        $passport_expires = EmployeeInfo::whereDate('passport_expire',"<=", $today);

        $data['show_month'] = date("m");
        $data['show_year'] = date("Y");
        $data['count_employee_joins'] = $employee_joins->count();
        $data['count_employee_dobs'] = $employee_dobs->count();
        $data['count_visa_expires'] = $visa_expires->count();
        $data['count_passport_expires'] = $passport_expires->count();

        $data['employee_joins'] = $employee_joins->limit(6)->get();
        $data['employee_dobs'] = $employee_dobs->limit(6)->get();
        $data['visa_expires'] = $visa_expires->limit(6)->get();
        $data['passport_expires'] = $passport_expires->limit(6)->get();

        $data['total_employee'] = EmployeeInfo::whereIn('user_id', User::active()->pluck('id')->toArray())->count();
        $data['inactive_employee'] = EmployeeInfo::whereIn('user_id', User::where('status', "inactive")->pluck('id')->toArray())->count();

        return view('admin.dashboard.hr', $data);
    }

    public function month_based_joined(Request $request){

        $month = $request->month;
        $year = $request->year;
        $target = $request->target;


        $date = Carbon::create($year, $month, 1, 0);
        if($target == "next")
        {
            $show_month = $date->addMonth();
        }else{
            $show_month = $date->subMonth();
        }

        $data['show_month'] = $get_month = Carbon::parse($show_month)->format('m');
        $data['show_year'] = $get_year = Carbon::parse($show_month)->format('Y');
        $data['show_month_name'] = Carbon::parse($show_month)->format('F').", ".$get_year;

        $employee_joins = EmployeeInfo::whereMonth('join_date', $get_month)->whereYear('join_date', $get_year);
        $data['count_employee_joins'] = $employee_joins->count();

        $data['employee_joins'] = $employee_joins->limit(6)->get();

        $result['html'] = View::make('admin.dashboard.hr_common_joined', $data)->render();

        header('Content-Type: application/json');

        echo json_encode($result);
    }


}
