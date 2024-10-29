<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use Illuminate\Support\Facades\Auth;
use App\DataTables\FiscalYearDataTable;



class FiscalYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(FiscalYearDataTable $dataTable)
    {
       return $dataTable->render('member.fiscal_year.index');
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('member.fiscal_year.create');
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
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $fiscal_year=explode('-', $request['fiscal_year']);

        $request['start_date']=db_date_format($fiscal_year[0]);
        $request['end_date']=db_date_format($fiscal_year[1]);
        $request['title'] = "FY (".formatted_date_string($request['start_date'])."-".formatted_date_string($request['end_date']).")";

        $this->validate($request, $rules, $customMessages);

        $inputs = $request->all();

        FiscalYear::create($inputs);

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
        $data['fiscal_year'] = FiscalYear::findOrFail($id);
        return view('member.fiscal_year.edit', $data);
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
        /*
         * TODO: Fiscal Year update will be check later
         */
        $fiscal = FiscalYear::findOrFail($id);

        $fiscal_year=explode('-', $request['fiscal_year']);

        $request['start_date']=db_date_format($fiscal_year[0]);
        $request['end_date']=db_date_format($fiscal_year[1]);
        $request['title'] = "FY (".formatted_date_string($request['start_date'])."-".formatted_date_string($request['end_date']).")";


        $inputs = $request->all();

        $fiscal->update($inputs);

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
        $modal = FiscalYear::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }
}
