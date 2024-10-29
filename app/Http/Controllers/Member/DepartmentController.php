<?php

namespace App\Http\Controllers\Member;
use App\DataTables\DepartmentDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Auth;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DepartmentDataTable $dataTable)
    {

       return $dataTable->render('member.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
   
        return view('member.department.create');
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

        Department::create($inputs);

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
        $data['model'] = Department::findOrFail($id);

        return view('member.department.edit',$data);
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
        $department = Department::findOrFail($id);
        $this->validate($request, $this->validationRules($id));

        $inputs = $request->all();
        $department->update($inputs);

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

        $modal = Department::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    private function validationRules($id=""){

        if (is_null($id)) {
            $rules['name'] = 'required|unique:departments';
        } else {
            $rules['name'] = 'required|unique:departments,name,' . $id;
        }

        return $rules;
    }
}