<?php

namespace App\Http\Controllers\member;

use App\Marketing_officer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\DataTables\MarketingOfficerDataTable;

class MarketingOfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MarketingOfficerDataTable $dataTable)
    {
        // $data = Marketing_officer::authCompany()->get();
        // dd($data);
        return $dataTable->render('member.marketing_officer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.marketing_officer.create');
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

        $user_id = auth()->user()->id;
        $company_id = auth()->user()->company_id;
        $name = auth()->user()->full_name;

        if($request->file('image')){
            $file = $request->file('image');
            $filename = time() . '.' . $request->file('image')->extension();
            $filePath = public_path() . '/uploads/marketing_officer/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
        }
        // $inputs = $request->all();
        $inputs['operator_id'] = $user_id;
        $inputs['operator_name'] = $name;
        $inputs['company_id'] = $company_id;
        $inputs['name'] = $request->name;
        $inputs['designation'] = $request->designation;
        $inputs['contact_no'] = $request->contact_no;
        $inputs['description'] = $request->description;
        $inputs['address'] = $request->address;

        $lead = Marketing_officer::create($inputs);

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
        $data['model'] = Marketing_officer::findOrFail($id);
        return view('member.marketing_officer.edit', $data);
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

        $data = Marketing_officer::findOrFail($id);
        $user_id = auth()->user()->id;
        $name = auth()->user()->full_name;
        $inputs = $request->all();
        $inputs['image'] = $data->image;
        // dd($request->all());
        if($request->file('image')){
            $path = public_path() . '/uploads/marketing_officer/'.$data->image;
            if(file_exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            $filename = time() . '.' . $request->file('image')->extension();
            $filePath = public_path() . '/uploads/marketing_officer/';
            $file->move($filePath, $filename);
            $inputs['image'] = $filename;
            // dd($inputs);
        }
        $inputs['operator_id'] = $user_id;
        $inputs['operator_name'] = $name;
        // dd($inputs);
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
        $modal = Marketing_officer::findOrFail($id);
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
            'name' => 'required',
            'contact_no' => 'required',
            'designation' => 'required',
        ];

        return $rules;
    }


}
