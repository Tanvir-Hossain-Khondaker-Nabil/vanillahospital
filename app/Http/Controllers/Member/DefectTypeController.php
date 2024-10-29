<?php

namespace App\Http\Controllers\Member;

use App\DataTables\DefectTypeDataTable;
use App\Models\DefectType;
use App\Models\Serviceing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DefectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DefectTypeDataTable $dataTable)
    {
        return $dataTable->render('member.defect_types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.defect_types.create');
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
        $inputs['company_id'] = Auth::user()->company_id;
        DefectType::create($inputs);

        $status = ['type' => 'success', 'message' => trans('common.successfully_added')];
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
        $data['model'] = DefectType::findOrFail($id);

        return view('member.defect_types.edit',$data);
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
        $area = DefectType::findOrFail($id);
        $this->validate($request, $this->validationRules($id));

        $inputs = $request->all();
        $inputs['company_id'] = Auth::user()->company_id;
        $area->update($inputs);

        $status = ['type' => 'success', 'message' => trans('common.successfully_updated')];
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


    private function validationRules($id = ""){

        if($id==''){
            $rules = [
                'name' => 'required|unique:defect_types,name',
            ];
        }else{
            $rules = [
                'name' => 'required|unique:defect_types,name,'.$id,
            ];
        }

        return $rules;
    }
}
