<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UnitsDataTable;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitsDataTable $dataTable)
    {
        return $dataTable->render('admin.units.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UnitsDataTable $dataTable)
    {
        return $dataTable->render('admin.units.create');
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
            'name' => 'required|unique:units,name',
            'display_name' => 'required|unique:units,display_name',
            'slug' => 'required|unique:units,slug'
        ];

        $request['display_name'] = ucfirst($request->name);
        $request['slug'] = snake_case($request->name);
        $this->validate($request, $rules);
        $inputs = $request->all();

        Unit::create($inputs);

        $status = ['type' => 'success', 'message' => trans('common.successfully_added')];

        return redirect()->route('admin.units.index')->with('status', $status);
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
        $data['modal'] = Unit::findOrFail($id);

        return view('admin.units.edit', $data);
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
            'name' => 'required|unique:units,name,'.$id,
            'display_name' => 'required|unique:units,display_name,'.$id,
            'slug' => 'required|unique:units,slug,'.$id
        ];

        $request['display_name'] = ucfirst($request->name);
        $request['slug'] = snake_case($request->name);
        $this->validate($request, $rules);

        $inputs = $request->all();

        $unit = Unit::findOrFail($id);
        $unit->update($inputs);

        $status = ['type' => 'success', 'message' => trans('common.successfully_updated')];

        return redirect()->route('admin.units.index')->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = Unit::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);
    }
}
