<?php

namespace App\Http\Controllers\Member;

use App\DataTables\DivisionDataTable;
use App\Models\Division;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DivisionDataTable $dataTable)
    {
        return $dataTable->render('member.divisions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::get()->pluck('countryName', 'id');
        return view('member.divisions.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        Division::create($inputs);

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
        $data['model'] = Division::findOrFail($id);

        return view('member.divisions.edit',$data);
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
        $area = Division::findOrFail($id);
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
        $modal = Division::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }


    private function validationRules(){

        $rules = [
            'name' => 'required|unique:divisions,name'
        ];

        return $rules;
    }
}