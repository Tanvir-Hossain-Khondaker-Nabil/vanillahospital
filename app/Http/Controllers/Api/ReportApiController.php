<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesRequisition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportApiController extends Controller
{
    public function sale_requisition_report(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $query  = SalesRequisition::where('is_sale', 0)->authCompany()
            ->where('sales_requisitions.created_by',Auth::user()->id)
            ->where('requisition_request_by', 1)
            ->latest()
            ->with(['dealer', 'store']);


        if($month)
            $query = $query->whereMonth('date', $month);

        if($year)
            $query = $query->whereYear('date', $year);


         $data['sale_requisitions'] = $query->get();

        return response($data);
    }
}
