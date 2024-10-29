<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\ProjectExpenseDetail;
use App\Models\ProjectExpenseType;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectExpenseReportController  extends BaseReportController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function project_expense_report(Request $request){

        $data['projects'] = Project::authCompany()->pluck('project', 'id')->toArray();
        $data['project_expense_types'] = ProjectExpenseType::authCompany()->pluck('display_name', 'id')->toArray();

        $inputs = $request->all();
        $query = new ProjectExpenseDetail();
        $project_id = $request->project_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $expense_type_id = $request->expense_type_id;

        if($expense_type_id>0)
        {
            $query = $query->where('project_expense_type_id', $expense_type_id);
        }

        if($project_id>0)
        {
            $query = $query->whereHas('project_expense', function ($q) use ($project_id) {
                $q->where('project_id', $project_id);
            });
        }

        $query = $this->searchExpenseDate($inputs, $query);

        $data['full_url'] = $request->fullUrl();

        $title = "Project Expense Details Report";
        $data['report_title'] = $title." <br/> Date:".db_date_month_year_format(date("Y-m-d"));

//        $query = $this->authCompany($query, $request);

        $data['project_expenses'] = $query->get();

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.project_expenses.print', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.project_expenses.print', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.reports.project_expenses.report', $data);
        }


    }

    public function project_profit_report(Request $request){

        $data['projects'] = Project::authCompany()->pluck('project', 'id')->toArray();

        $inputs = $request->all();
        $query = new ProjectExpense();
        $project_id = $request->project_id;

        $data['project'] = null;
        if($project_id>0)
        {
            $data['project'] = Project::find($project_id);
            $query = $query->where('project_id', $project_id);
        }

        $data['full_url'] = $request->fullUrl();

        $title = "Project Profit Report";
        $data['report_title'] = $title." <br/> Date:".db_date_month_year_format(date("Y-m-d"));

//        $query = $this->authCompany($query, $request);

        $data['project_expenses'] = $query->get();

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.project_expenses.print', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.project_expenses.print', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.reports.project_expenses.report', $data);
        }


    }

    private function searchExpenseDate($inputs, $query){

        $fromDate = $toDate = '';
        if( !empty($inputs['from_date']) )
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if( !empty($inputs['to_date']) )
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if( empty($fromDate) && (!empty($toDate)) ) {
            $query = $query->whereHas('project_expense', function ($q) use ($toDate) {
                $q->whereDate('date','<=', $toDate);
            });
        }elseif( (!empty($fromDate)) && empty($toDate) ) {
            $query = $query->whereHas('project_expense', function ($q) use ($fromDate) {
                $q->whereDate('date','>=', $fromDate);
            });
        }elseif ( $fromDate !='' && $toDate != '' ) {
            $query = $query->whereHas('project_expense', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('date', [$fromDate, $toDate]);
            });
        }else{
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereHas('project_expense', function ($q) use ($fromDate) {
                $q->whereDate('date','>=', $fromDate);
            });
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $query;

    }


}
