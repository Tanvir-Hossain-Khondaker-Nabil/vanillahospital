<?php

namespace App\Http\Controllers\Member;

use App\DataTables\BankBranchDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankBranch;

class BankBranchController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BankBranchDataTable $dataTable)
    {
        return $dataTable->render('member.bank_branch.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['banks'] = Bank::where('status', 'active')->pluck('display_name', 'id')->toArray();

        return view('member.bank_branch.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'branch_name' => 'required',
            'bank_id' => 'required'
        ];

        $this->validate($request, $rules);

        $inputs = $request->all();

        $bank = BankBranch::create($inputs);

        if($bank)
            $status = ['type' => 'success', 'message' => 'Successfully Added.'];
        else
            $status = ['type' => 'danger', 'message' => 'Unable to Add Bank Branch'];

        return back()->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = BankBranch::findOrFail($id);
        $data['banks'] = Bank::where('status', 'active')->pluck('display_name', 'id')->toArray();

        return view('member.bank_branch.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'branch_name' => 'required',
            'bank_id' => 'required'
        ];

        $this->validate($request, $rules);

        $inputs = $request->all();

        $bank = BankBranch::findOrFail($id);
        $bank = $bank->update($inputs);

        if($bank)
            $status = ['type' => 'success', 'message' => 'Successfully Updated.'];
        else
            $status = ['type' => 'danger', 'message' => 'Unable to Add Bank Branch'];

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
