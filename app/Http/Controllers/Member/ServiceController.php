<?php

namespace App\Http\Controllers\Member;

use App\DataTables\ServiceDataTable;
use App\Models\Serviceing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('member.services.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.services.create');
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
        Serviceing::create($inputs);

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
        $data['model'] = Serviceing::findOrFail($id);

        return view('member.services.edit',$data);
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
        $area = Serviceing::findOrFail($id);
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
                'title' => 'required|unique:services,title',
            ];
        }else{
            $rules = [
                'title' => 'required|unique:services,title,'.$id,
            ];
        }

        return $rules;
    }
}
