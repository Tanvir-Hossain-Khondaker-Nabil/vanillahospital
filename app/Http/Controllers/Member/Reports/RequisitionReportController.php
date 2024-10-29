<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Area;
use App\Models\Company;
use App\Models\District;
use App\Models\Division;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SupplierOrCustomer;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SalesRequisition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequisitionReportController extends BaseReportController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function requistion_report( Request $request)
    {

        $inputs = $request->all();
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['dealers'] = SupplierOrCustomer::onlyCustomers()->whereNotNull('user_id')->get()->pluck('name', 'user_id');
        $data['products'] = Item::get()->pluck('item_name', 'id');
        
        
        // $data['divisions'] = Division::get()->pluck('display_name_bd', 'id');
        // $data['districts'] = District::get()->pluck('display_name_bd', 'id');
        // $data['upazillas'] = Upazilla::get()->pluck('display_name_bd', 'id');
        // $data['unions'] = Union::get()->pluck('display_name_bd', 'id');
        // $data['areas'] = Area::get()->pluck('name', 'id');

      

        // dd($qry);

        // $query = DB::table('items')
                // ->whereExists(function($query)
                // {
                //     $query->select(DB::raw('SUM(requisition_details.total_price) As p_req_total_price'))
                //         ->from('requisition_details')
                //         ->whereRaw('requisition_details.item_id = items.id');
                // });
        // ->join('requisition_details',  'items.id', '=', 'requisition_details.item_id');
        // ->join('purchase_details',  'items.id', '=', 'purchase_details.item_id')
        // ->join('sales_details',  'items.id', '=', 'sales_details.item_id')
        // ->join('sales_requisition_details',  'items.id', '=', 'sales_requisition_details.item_id');
        
        // $query = $query->select('items.item_name', DB::raw('SUM(requisition_details.total_price) As p_req_total_price')); 
        // $query = $query->groupBy('items.id')->get(); 

        // dd($query);
        $query = new Item();
        $data['item'] = null;
        $condition = "";

        
        if (isset($request->company_id)) {
            $company_id = $request->company_id;
            $condition = "where company_id=".$company_id;
        
        }else if (isset(Auth::user()->company_id) && Auth::user()->company_id != null) {
            $company_id = Auth::user()->company_id;
            $condition = "where company_id=".$company_id;
        }

        if (!empty($inputs['product_id'])) {
            $data['item'] = $inputs['product_id'];
            $condition = "and items.id=".$data['item'];
        }

        $data['dealer'] = $conditionDealer = '';
        if (!empty($inputs['dealer_id'])) {
            $user = User::findOrFail($inputs['dealer_id']);
            $data['dealer'] = $user->name;
            $conditionDealer = " and sales_requisitions.dealer_id=".$inputs['dealer_id'];
    
        }

        $conditionDate = $this->searchRequistionDate($inputs);

        $data['manager'] = '';

        // if ( !empty($inputs['division_id']) || !empty($inputs['district_id']) || !empty($inputs['upazilla_id']) || !empty($inputs['union_id']) || !empty($inputs['area_id'])) {

        //     $division_id = isset($inputs['division_id']) ? $inputs['division_id'] : '';
        //     $district_id = isset($inputs['district_id']) ? $inputs['district_id'] : '';
        //     $upazilla_id = isset($inputs['upazilla_id']) ? $inputs['upazilla_id'] : '';
        //     $union_id = isset($inputs['union_id']) ? $inputs['union_id'] : '';
        //     $area_id = isset($inputs['area_id']) ? $inputs['area_id'] : '';


        //     $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->active();

        //     if ($district_id) {
        //         $sharer = $sharer->where('district_id', $district_id);
        //         $data['district'] = District::find($district_id);
        //     }

        //     if (($upazilla_id)) {
        //         $sharer = $sharer->where('upazilla_id', $upazilla_id);
        //         $data['upazilla'] = Upazilla::find($upazilla_id);
        //     }

        //     if (($union_id)) {
        //         $sharer = $sharer->where('union_id', $union_id);
        //         $data['union'] = Union::find($union_id);
        //     }

        //     if (($area_id)) {
        //         $sharer = $sharer->where('area_id', $area_id);
        //         $data['area'] = Area::find($area_id);
        //     }

        //     $sharer = $sharer->pluck('id');


        //     $query = $query->whereHas('sale', function ($q) use ($sharer) {
        //         $q->whereIn('customer_id', $sharer)->orderBy('sales.customer_id');
        //     });
        // }


        $query = DB::select("select items.id ,
                    items.item_name, p_req_total, p_total, s_req_total, s_total
                from  items 
                left  join (select item_id,sum(requisition_details.total_price) as p_req_total  
                            from requisition_details join requisitions on requisition_details.requisition_id = requisitions.id where $conditionDate
                            group by item_id ) as a 
                on  items.id=a.item_id
                left join ( select item_id,sum(total_price) as p_total  
                            from purchase_details
                            where requisition_detail_id is not null and  $conditionDate
                            group by item_id ) as b 
                on  items.id=b.item_id
                left  join (select item_id,sum(sales_requisition_details.total_price) as s_req_total  
                            from sales_requisition_details join sales_requisitions on sales_requisition_details.sales_requisition_id = sales_requisitions.id where   $conditionDate $conditionDealer
                            group by item_id ) as c 
                on  items.id=c.item_id
                left join ( select item_id,sum(total_price) as s_total  
                            from sales_details  
                            where sale_requisition_details_id is not null and  $conditionDate
                            group by item_id ) as d 
                on  items.id=d.item_id $condition");

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $report_title = "Requistion Summary Report  <br/> Date: ".$this->fromDate." to ".$this->toDate." <br/> " ;
        $report_title = $data['manager']  ? $report_title." Manager: ".$data['manager']." <br/> " : $report_title;
        $report_title = $data['dealer'] ? $report_title.$data['dealer']." <br/> " : $report_title;
        $report_title = isset($data['area']) ? $report_title.$data['area']->name." <br/> " : $report_title;
        $report_title = isset($data['union']) ? $report_title." ".$data['union']->name : $report_title;
        $report_title = isset($data['upazilla']) ? $report_title.$data['upazilla']->name." <br/> " : $report_title;
        $report_title = isset($data['district'])  ? $report_title." ".$data['district']->name : $report_title;

        $data['report_title'] = $report_title;

        if ($request->type == "print" || $request->type == "download") {

            $data['modal'] = $query;
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.requisition.print_summary_requisition_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.requisition.print_summary_requisition_report', $data);
                $file_name = file_name_generator($report_title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            $data['modal'] = $query;
            return view('member.reports.requisition.summary_requisition_report', $data);
        }
    }


    public function requisition_by_salesman(Request $request){

        $inputs = $request->all();
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $data['salesmanList'] = User::whereHas('roles', function($query){
                $query->where('name', 'sales_man');
        })->get()->pluck('user_details', 'id');


        $query = new SalesRequisition();
        $query = $this->authCompany($query, $request, 'sales_requisitions');
        $query = $this->searchReqModelDate($inputs, $query);

        $data['salesman'] =  '';
        if (!empty($inputs['user_id'])) {
            $user = User::findOrFail($inputs['user_id']);
            $data['salesman'] = $user;
            $query = $query->where('created_by', $user->id);
        }

        $data['modal'] = $query->with('sales')->get();

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $report_title = "Requisition Report by Salesman<br/> Date: ".$this->fromDate." to ".$this->toDate." <br/> " ;
 
        $data['report_title'] = $report_title;

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {

                return View('member.reports.requisition.print_salesman_requisition_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.requisition.print_salesman_requisition_report', $data);
                $file_name = file_name_generator($report_title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.reports.requisition.salesman_requisition_report', $data);
        }
    }

    private function searchReqModelDate($inputs, $query)
    {

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if (empty($fromDate) && (!empty($toDate))) {
            $query = $query->whereDate('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {
            $query = $query->whereDate('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {
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

    private function searchRequistionDate($inputs){

        $fromDate = $toDate = '';
        if( !empty($inputs['from_date']) )
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if( !empty($inputs['to_date']) )
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        
        $condition = "";
        if( empty($fromDate) && (!empty($toDate)) ) {

            $condition = "date <= '".$toDate."'";
            // $condition = ($condition ? " and " : "")."sale_details.date <= '".$toDate."'";
            // $condition = ($condition ? " and " : "")."purchase_details.date <= '".$toDate."'";
            // $condition = ($condition ? " and " : "")."purchase_details.date <= '".$toDate."'";

        }elseif( (!empty($fromDate)) && empty($toDate) ) {

            $condition = "date >= '".$fromDate."'";

        }elseif ( $fromDate !='' && $toDate != '' ) {

            $condition = "date between '".$fromDate."' and '".$toDate."'";

        }else{
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            
            $condition = "date >= '".$fromDate."'";
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $condition;

    }

}
