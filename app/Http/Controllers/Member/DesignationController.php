<?php

namespace App\Http\Controllers\Member;

use App\DataTables\DesignationDataTable;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DesignationDataTable $dataTable)
    {
        return $dataTable->render('member.designation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['designations'] = Designation::get()->pluck('name', 'id');
        $data['departments'] = Department::get()->pluck('name', 'id');
        // dd($data);
        return view('member.designation.create', $data);
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
        $inputs['commission_percentage'] = $request->commission_percentage ?? 0;
        Designation::create($inputs);

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
        $data['model'] = Designation::findOrFail($id);

        $data['designations'] = Designation::where('id','!=', $id)->get()->pluck('name', 'id');
        $data['departments'] = Department::get()->pluck('name', 'id');
        return view('member.designation.edit',$data);
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
        $area = Designation::findOrFail($id);
        $this->validate($request, $this->validationRules($id));

        $inputs = $request->all();
        $inputs['commission_percentage'] = $request->commission_percentage ?? 0;
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


    private function validationRules($id=""){

        if (is_null($id)) {
            $rules['name'] = 'required|unique:designations';
        } else {
            $rules['name'] = 'required|unique:designations,name,' . $id;
        }

        return $rules;
    }


}
