<?php

namespace App\Http\Controllers\Member;

use App\Models\EmployeeInfo;
use App\Models\PaidCommission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{
    public function sale_commission_paid()
    {
        $data['employees'] = EmployeeInfo::get()->pluck('employee_name_id', 'id');

        return view('member.commission.sale_commission_paid', $data);
    }

    public function save_sale_commission_paid(Request $request)
    {

        $this->validate($request, [
            'employee_id' =>'required',
            'paid_amount' =>'required|lte:due_amount',
            'due_amount' =>'required',
        ]);

        $paid_commission = new PaidCommission();
        $paid_commission->amount = $request->paid_amount;
        $paid_commission->employee_id = $request->employee_id;
        $paid_commission->status = 1;
        $paid_commission->paid_notes = $request->paid_notes;
        $paid_commission->paid_date = Carbon::today();
        $paid_commission->paid_time = date('h:i:s');
        $paid_commission->save();

        $status = ['type' => 'success', 'message' => 'Successfully Sale Commission Paid'];

        return back()->with('status', $status);
    }
}
