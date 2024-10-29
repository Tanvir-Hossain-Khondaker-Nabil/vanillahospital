<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClientCompany;
use App\DataTables\ClientCompanyDataTable;
class ClientCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientCompanyDataTable $dataTable)
    {
        return $dataTable->render('member.client_company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.client_company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $this->validate($request, $this->rules());

        $inputs['company_id'] = \Auth::user()->company_id;
        $inputs['type'] = 'project';
        ClientCompany::create($inputs);

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
        $data['modal'] = ClientCompany::findorFail($id);

        return view('member.client_company.edit', $data);
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
        $terms = ClientCompany::find($id);
        $inputs = $request->all();
        $inputs['type'] = 'project';
        $this->validate($request, $this->rules($id));
        $terms->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

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
        $company = ClientCompany::findOrFail($id);
        $company->delete();

        return response()->json([
          'data' =>[
            'message'=> 'Successfully Deleted',
          ]
        ],200);
    }

    private function rules($id='')
    {
            $rules = [
                'address_1' => 'required',
                'company_name' => 'required',
                'designation' => 'required',
                'contact_no' => 'required',
            ];


        return $rules;
    }
}
