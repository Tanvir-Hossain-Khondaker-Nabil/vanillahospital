<?php

namespace App\Http\Controllers\Member;

use App\DataTables\ProcurementDataTable;
use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\ProcurementDetail;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    
    public function index(ProcurementDataTable $dataTable)
    {
        return $dataTable->render('member.procurement.index');
    }

    
    public function create()
    {
        $data['departments'] = Department::authCompany()->active()->pluck('name','id');
        $data['items']  = Item::where('company_id',auth()->user()->company_id)->where('status','active')->get();
        $data['months'] = array_map(fn($month) => Carbon::create(null, $month)->format('F'), range(1, 12));
        $data['years']  = array_map(fn($year) => $year + 2022, range(1, 50));
        return view('member.procurement.create',$data);
    }

   
    public function store(Request $request)
    {
       
        $status = ['type' => 'error', 'message' => 'Something went wrong!'];
        $years  = array_map(fn($year) => $year + 2022, range(1, 50));
        if(count($request->item_id) > 0){
            $pr = Procurement::where('year',$years[$request->year])->where('month',$request->month)->where('company_id',auth()->user()->company_id)->first();
            if(!$pr)
                $pr = Procurement::create([
                    "year"       => $years[$request->year],
                    "month"      => $request->month,
                    "user_id"    => auth()->user()->id,
                    "company_id" => auth()->user()->company_id,
                ]);
            else{
                $pr->update(["status" => 0]);
            }
            foreach ($request->item_id as $key => $value) {
                ProcurementDetail::create([
                    "procurement_id" => $pr->id,
                    "item_id"        => $value,
                    "qty"            => $request->qty[$key],
                    "amount"         => $request->total_amount[$key],
                    "user_id"        => auth()->user()->id,
                    "company_id"     => auth()->user()->company_id,
                    "department_id"  => $request->deparment_id[$key],
                ]);
            }
            $status = ['type' => 'success', 'message' => 'Successfully Created.'];
        }
        return back()->with('status', $status);
     

        
    }

    
    public function show(Procurement $procurement)
    {
        //
    }

   
    public function edit(Procurement $procurement)
    {
        
        $data['departments'] = Department::authCompany()->active()->pluck('name','id');
        $data['items']  = Item::where('company_id',auth()->user()->company_id)->where('status','active')->get();
        $data['months'] = array_map(fn($month) => Carbon::create(null, $month)->format('F'), range(1, 12));
        $data['years']  = array_map(fn($year) => $year + 2022, range(1, 50));
        $data['procurement'] = $procurement;
        return view('member.procurement.edit', $data);
    }

    
    public function update(Request $request, Procurement $procurement)
    {
        
        $status = ['type' => 'error', 'message' => 'Something went wrong!'];
        $years  = array_map(fn($year) => $year + 2022, range(1, 50));
        if(count($request->item_id) > 0){
            $procurement->procurement_details->where('status',0)->each->delete();
            $procurement->update(["status" => 0]);
            $pr = Procurement::where('year',$years[$request->year])->where('month',$request->month)->where('company_id',auth()->user()->company_id)->first();
            if(!$pr)
                $procurement->update([
                    "year"       => $years[$request->year],
                    "month"      => $request->month,
                    "user_id"    => auth()->user()->id,
                    "company_id" => auth()->user()->company_id,
                ]);
            
            foreach ($request->item_id as $key => $value) {
                ProcurementDetail::create([
                    "procurement_id" => $procurement->id,
                    "item_id"        => $value,
                    "qty"            => $request->qty[$key],
                    "amount"         => $request->total_amount[$key],
                    "user_id"        => auth()->user()->id,
                    "company_id"     => auth()->user()->company_id,
                    "department_id"  => $request->deparment_id[$key],
                ]);
            }
            $status = ['type' => 'success', 'message' => 'Successfully Updated.'];
        }
        return redirect()->route('member.procurements.index')->with('status', $status);
    }

    
    public function destroy(Procurement $procurement)
    {
        $procurement->delete();
        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function approve(Request $request){
        $pr = Procurement::find($request->id);
        if($pr){
            $pr->update(["status" => 1]);
            $requisition = Requisition::create([
                'date'          => now(),
                'total_price'   => $pr->procurement_details->where('status',0)->sum('amount'),
                'member_id'     => auth()->user()->member->id,
                'company_id'    => auth()->user()->company_id,
                'created_by'    => auth()->user()->id,
                'procurement_id'=> $pr->id
            ]);
            foreach ($pr->procurement_details->where('status',0) as $key => $value) { 
                RequisitionDetail::create([
                    "requisition_id" => $requisition->id,
                    "item_id"        => $value->item_id,
                    "qty"            => $value->qty,
                    "unit"           => $value->item->unit,
                    "price"          => $value->amount/$value->qty,
                    "total_price"    => $value->amount,
                ]);
            }
            $pr->procurement_details->where('status',0)->each->update(["status" => 1]);
            
            return response()->json([
                'data' => [
                    'message' => 'Successfully Approved'
                ]
            ], 200);
        }
        return response()->json([
            'data' => [
                'message' => 'Sorry! Something went wrong.'
            ]
        ], 200);
    }
    public function not_approve(Request $request){
        $pr = Procurement::find($request->id);
        if($pr){
            $pr->update(["status" => 3]);
            return response()->json([
                'data' => [
                    'message' => 'Successfully Approved Cancel'
                ]
            ], 200);
        }
        return response()->json([
            'data' => [
                'message' => 'Sorry! Something went wrong.'
            ]
        ], 200);
    }
    public function approve_list(ProcurementDataTable $dataTable)
    {
        return $dataTable->render('member.procurement.approved_list');
    }
    public function not_approve_list(ProcurementDataTable $dataTable)
    {
        return $dataTable->render('member.procurement.approved_list');
    }
}
