<?php

namespace App\Http\Controllers\Member;

use App\DataTables\BrandDataTable;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BrandDataTable $dataTable)
    {
        return $dataTable->render('member.brands.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['slug'] = str_slug($request->name);
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        Brand::create($inputs);

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
        $data['model'] = Brand::findOrFail($id);

        return view('member.brands.edit',$data);
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
        $request['slug'] = str_slug($request->name);
        $area = Brand::findOrFail($id);
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
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
                'name' => 'required|unique:brands,name',
                'slug' => 'required|unique:brands,slug',
            ];
        }else{
            $rules = [
                'name' => 'required|unique:brands,name,'.$id,
                'slug' => 'required|unique:brands,slug,'.$id,
            ];
        }

        return $rules;
    }
}
