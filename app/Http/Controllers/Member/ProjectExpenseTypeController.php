<?php

namespace App\Http\Controllers\Member;

use App\DataTables\ProjectExpenseTypesDataTable;
use App\Models\ProjectExpenseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectExpenseTypesDataTable $dataTable)
    {
        return $dataTable->render('member.project_expense_types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.project_expense_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $a = str_replace('-',' ', $request->display_name);
        $b = str_replace('_', ' ',$a );
        $c = str_replace('/', ' ',$b );

        $request['name'] = str_replace(' ', '_', strtolower($c));
        $request['company_id'] = Auth::user()->company_id;
        $request['created_by'] = Auth::user()->id;

        $this->validate($request, [
            'name' => 'required|unique:project_expense_types,name',
            'display_name' => 'required',
        ]);

        $inputs = $request->all();

        ProjectExpenseType::create($inputs);

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
        $data['project_expense_types'] = ProjectExpenseType::findOrFail($id);

        return view('member.project_expense_types.edit', $data);
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
        $permissions = ProjectExpenseType::findOrFail($id);

//        $a = str_replace('-',' ', $request->name);
//        $b = str_replace('_', ' ',$a );
//        $c = str_replace('/', ' ',$b );
//
//        $request['name'] = str_replace(' ', '_', strtolower($c));
//        $request['display_name'] = ucfirst(str_replace('_', ' ',$c ));


        $this->validate($request, [
            'display_name' => 'required',
        ]);

        $inputs = $request->all();

        $permissions->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = ProjectExpenseType::findOrFail($id);
        $project->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully Deleted',
            ]
        ], 200);
    }


}
