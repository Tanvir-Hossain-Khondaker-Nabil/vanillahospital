<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Shift;
use App\DataTables\ShiftDataTable;
class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShiftDataTable $dataTable)
    {
        return $dataTable->render('member.shift.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.shift.create');
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

        // $inputs['time_in'] = \Carbon\Carbon::parse($request->time_in)->format("g:i A");
        // $inputs['time_out'] = \Carbon\Carbon::parse($request->time_out)->format("g:i A");

        // dd($request->all());
        $inputs = $request->all();
        $inputs['time_in'] = \Carbon\Carbon::parse($request->time_in)->toTimeString();
        $inputs['time_out'] = \Carbon\Carbon::parse($request->time_out)->toTimeString();
        $inputs['late'] = \Carbon\Carbon::parse($request->late)->toTimeString();

        // $inputs['shift_type'] = $request->shift_type;
        $inputs['company_id'] = Auth::user()->company_id;


        Shift::create($inputs);

        $status = ['type'=> 'success', 'message'=> 'Successfully Added.'];

        return back()->with('status',$status);
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
        $data['model'] = Shift::findOrFail($id);

        return view('member.shift.edit',$data);
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

        $inputs = $request->all();
        $inputs['time_in'] = \Carbon\Carbon::parse($request->time_in)->toTimeString();
        $inputs['time_out'] = \Carbon\Carbon::parse($request->time_out)->toTimeString();
        $inputs['late'] = \Carbon\Carbon::parse($request->late)->toTimeString();


        $shift = Shift::findOrFail($id);

        $shift->update($inputs);


        $status = ['type'=> 'success', 'message'=> 'Successfully Updated.'];

        return back()->with('status',$status);
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


        $rules =[
            'time_in' => 'required',
            'time_out' => 'after:time_in|required',
            'late' => 'after:time_in|required',
        ];


        return $rules;
    }
}
