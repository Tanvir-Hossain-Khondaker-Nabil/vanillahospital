<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\Specimen;
use App\Http\Controllers\Controller;

class SpecimenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  dd($request->to_date);

        //  dd(($request->from_date));
        //   $date = \Carbon\Carbon::parse($$request->to_date);

        //   dd($date->format('Y-m-d'));

        $query = Specimen::AuthCompany();
        $data['from_date'] = '';
        $data['to_date'] = '';
        if(@$request->from_date && @$request->to_date){
            // dd('1');
            $data['from_date'] = $request->from_date;
            $data['to_date'] =  $request->to_date;
            $query = $query->whereBetween('created_at', [$request->from_date, $request->to_date]) ;
        }

        if(@$request->from_date){
            // dd('dds');
            $data['from_date'] = $request->from_date;
            $query = $query->whereBetween('created_at', [$request->from_date, date("Y-m-d")]) ;
        }



        $data['specimen'] = $query->get();


        // $data['specimen'] = Specimen::AuthCompany()->get();

        return view('member.specimen.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.specimen.create');
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
         $check = Specimen::AuthCompany()->where('specimen',$request->specimen)->first();
         if(!$check){
            Specimen::create($inputs);
            $status = ['type' => 'success', 'message' => 'Successfully Added'];

         }else{
            $status = ['type' => 'error', 'message' => 'This data alreday exists'];

         }
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
        $data['model'] = Specimen::findOrFail($id);
        return view('member.specimen.edit', $data);
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
        $this->validate($request, $this->validationRules());

        $data = Specimen::findOrFail($id);
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
        $modal = Specimen::findOrFail($id);
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
            'specimen' => 'required',
        ];

        return $rules;

   }
}