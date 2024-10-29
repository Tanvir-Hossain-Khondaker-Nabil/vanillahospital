<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\HospitalService;
use App\Http\Controllers\Controller;
use App\DataTables\HospitalServiceDataTable;

class HospitalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HospitalServiceDataTable $dataTable)
    {
        // $data = HospitalService::authCompany()->get();
        // dd($data);
        return $dataTable->render('member.hospital_service.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.hospital_service.create');
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

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;


        $inputs = $request->all();

        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;
        $lead = HospitalService::create($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Added'];

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
        $data['model'] = HospitalService::findOrFail($id);
        return view('member.hospital_service.edit', $data);
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
        // dd($request->all());
        $this->validate($request, $this->validationRules());

        $data = HospitalService::findOrFail($id);
        $user_id = \Auth::user()->id;
        $inputs = $request->all();

        $inputs['updated_by'] = $user_id;
        $data->update($inputs);

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
        $modal = HospitalService::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function validationRules($id = '')
    {

        $rules = [
            'title' => 'required',
            'price' => 'required',
            'comission' => 'required',
        ];

        return $rules;
    }
}