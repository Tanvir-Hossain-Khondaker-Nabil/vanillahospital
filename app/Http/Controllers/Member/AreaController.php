<?php

namespace App\Http\Controllers\Member;

use App\DataTables\AreaDataTable;
use App\Models\District;
use App\Models\Division;
use App\Models\Region;
use App\Models\Thana;
use App\Models\Area;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AreaDataTable $dataTable)
    {
        return $dataTable->render('member.areas.index');
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
        $data['districts'] = [];
        $data['thanas'] = [];

        return view('member.areas.create', $data);
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
        Area::create($inputs);

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
        $data['model'] = $area = Area::findOrFail($id);

        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['regions'] = Region::active()->get()->pluck('name', 'id');
        $data['districts'] = District::where('region_id', $area->region_id)->active()->get()->pluck('name', 'id');
        $data['thanas'] = Thana::where('district_id', $area->district_id)->active()->get()->pluck('name', 'id');

        return view('member.areas.edit',$data);
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
        $area = Area::findOrFail($id);
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
        $modal = Area::findOrFail($id);
        $modal->update(['status'=>'inactive']);

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
