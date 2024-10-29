<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\StaffSupportDataTable;
use App\Models\Support;

class StaffSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StaffSupportDataTable $dataTable)
    {
        return $dataTable->render('member.staff_support.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data =Support::findOrFail($request->re_id);
        if($data){
            $data->update([
                'status'=> 'seen',
                'reply_status'=> 'yes'
            ]);
        }
        $inputs = $request->all();
        $inputs['company_id'] = \Auth::user()->company_id;
        $inputs['generated_id'] = \Auth::user()->id;
        $inputs['rep_id'] = $request->re_id;
        $inputs['message_status'] = 'reply';
        $inputs['title'] = $data->title;
        Support::create($inputs);

        $status = ['type' => 'success', 'message' => "Successfully Replied"];

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

        $data['reply']  =   Support::where('re_id',$id)->get();
        $data['data']   =   Support::findOrFail($id);
        // dd($data);
        return view('admin.staff_support.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function change_status_support_message(Request $request)
    {
        // dd($request->id);
        $data =Support::findOrFail($request->id);

        if($data){
            $data->update([
                'status'=> 'seen',
            ]);
        }
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
        //
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
}
