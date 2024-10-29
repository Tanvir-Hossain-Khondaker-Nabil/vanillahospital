<?php

namespace App\Http\Controllers\Member;

use App\DataTables\BankDataTable;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BankDataTable $dataTable)
    {
        return $dataTable->render('member.banks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.banks.create');
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
            'display_name' => 'required|unique:banks,display_name',
//            'short_name' => 'required|unique:banks,short_name'
        ];

        $this->validate($request, $rules);

        $inputs = $request->all();

        $bank = Bank::create($inputs);

        if($bank)
            $status = ['type' => 'success', 'message' => 'Successfully Added.'];
        else
            $status = ['type' => 'danger', 'message' => 'Unable to Add Bank'];

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
        $data['modal'] = Bank::findOrFail($id);

        return view('member.banks.edit', $data);
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
            'display_name' => 'required|unique:banks,display_name,'.$id,
//            'short_name' => 'required|unique:banks,short_name,'.$id
        ];

        $this->validate($request, $rules);

        $inputs = $request->all();

        $bank = Bank::findOrFail($id);
        $bank = $bank->update($inputs);

        if($bank)
            $status = ['type' => 'success', 'message' => 'Successfully Updated.'];
        else
            $status = ['type' => 'danger', 'message' => 'Unable to Add Bank'];

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
