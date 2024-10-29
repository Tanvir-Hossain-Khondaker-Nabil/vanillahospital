<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Auth;
use App\DataTables\HolidayDataTable;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HolidayDataTable $dataTable)
    {
      return $dataTable->render('member.holiday.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.holiday.create');
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

        $inputs['start_date'] =  db_date_format($request->start_date);
        if(isset($request->end_date))
            $inputs['end_date'] =  db_date_format($request->end_date);

        Holiday::create($inputs);

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
        $data['model'] = Holiday::findOrFail($id);

        return view('member.holiday.edit',$data);
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
        $this->validate($request, $this->validationRules($id));

        $holiDay = Holiday::findOrFail($id);

        $inputs = $request->all();
        $inputs['start_date'] =  db_date_format($request->start_date);

        if(isset($request->end_date))
            $inputs['end_date'] =  db_date_format($request->end_date);

        $holiDay->update($inputs);

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
        //
    }

    private function validationRules($id=""){

        $rules = [
            'start_date' => 'required',
            'type' => 'required',
        ];

        if (is_null($id)) {
            $rules['title'] = 'required|unique:holidays,title';
        } else {
            $rules['title'] = 'required|unique:holidays,title,' . $id;
        }


        return $rules;
    }
}
