<?php

namespace App\Http\Controllers\Member;

use App\DataTables\RegionDataTable;
use App\Models\Region;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(RegionDataTable $dataTable)
    {
        return $dataTable->render('member.regions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        return view('member.regions.create', $data);
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
        Region::create($inputs);

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
        $data['model'] = Region::findOrFail($id);
        $data['divisions'] = Division::active()->get()->pluck('name', 'id');

        return view('member.regions.edit',$data);
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
        $area = Region::findOrFail($id);
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
        $modal = Region::findOrFail($id);
        $modal->update(['active_status'=>0]);

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }


    private function validationRules(){

        $rules = [
            'name' => 'required'
        ];

        return $rules;
    }
}
