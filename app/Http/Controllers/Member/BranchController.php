<?php

namespace App\Http\Controllers\Member;

use App\DataTables\BranchDataTable;
use App\Models\Branch;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BranchDataTable $dataTable)
    {
        return $dataTable->render('member.branches.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::all()->pluck('countryName', 'id');

        return view('member.branches.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        $inputs['name'] = human_words($inputs['display_name']);
        Branch::create($inputs);


        $status = ['type' => 'success', 'message' => 'Successfully Added.'];

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
        $data['model'] = Branch::findOrFail($id);
        $data['countries'] = Country::all()->pluck('countryName', 'id');

        return view('member.branches.edit',$data);
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
        $company = Branch::findOrFail($id);
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        $inputs['name'] = human_words($inputs['display_name']);
        $company->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated'];

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


    private function validationRules(){

        $rules = [
            'display_name' => 'required',
            'phone' => 'required',
        ];

        return $rules;
    }
}
