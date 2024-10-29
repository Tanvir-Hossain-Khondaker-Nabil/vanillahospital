<?php

namespace App\Http\Controllers\Member;

use App\DataTables\DistrictDataTable;
use App\Models\District;
use App\Models\Division;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DistrictDataTable $dataTable)
    {
        return $dataTable->render('member.districts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['regions'] = Region::active()->get()->pluck('name', 'id');

        return view('member.districts.create', $data);
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
        District::create($inputs);

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
        $data['model'] = District::findOrFail($id);

        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['regions'] = Region::active()->get()->pluck('name', 'id');

        return view('member.districts.edit',$data);
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
        $area = District::findOrFail($id);
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        $area->update($inputs);

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
            'name' => 'required'
        ];

        return $rules;
    }
}
