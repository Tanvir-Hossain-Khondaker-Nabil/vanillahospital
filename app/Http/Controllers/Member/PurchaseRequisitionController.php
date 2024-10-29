<?php

namespace App\Http\Controllers\Member;

use App\DataTables\PurchaseFormRequisitionDataTable;
use App\DataTables\PurchaseRequisitionDataTable;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\Requisition;
use App\Models\SupplierOrCustomer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseRequisitionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseRequisitionDataTable $dataTable)
    {
        return $dataTable->render('member.purchase.requisition_purchase_list');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requisitions(PurchaseFormRequisitionDataTable $dataTable)
    {
        return $dataTable->render('member.purchase.requisition_purchase_list');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function purchase_requisition($id)
    {
        $lastId = Purchase::latest()->pluck('id')->first();
        $data['is_requisition'] = 1;
        $data['modal'] = $purchase = Requisition::findOrfail($id);

//        if(count($purchase->requisition_details())<1)
//        {
//
//            $status = ['type' => 'danger', 'message' => 'No Data Found'];
//            return back()->with('status', $status);
//        }


        $data['memo_no'] = $data['chalan_no'] = memo_generate("MC-", $lastId+1);
        $data['suppliers'] = SupplierOrCustomer::onlySuppliers()->authCompany()->latest()->pluck('name', 'id');
        $data['products'] = Item::authCompany()->latest()->pluck('item_name', 'id');
        $data['purchase_products'] = Item::whereNotIn('id', $purchase->requisition_details()->pluck('item_id')->toArray())->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        return view('member.purchase.purchase_requisition', $data);
    }
}
