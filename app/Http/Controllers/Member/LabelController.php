<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Label;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $data = Label::where('label_type',$request->label_type)->get();
        return response()->json([
          'status'=>200,
          'data'=>$data,
        ]);
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
        //  dd($request->all());
           $inputs = $request->all();

           Label::create($inputs);

           $data = Label::all();
           return response()->json([
             'status'=>200,
             'data'=>$data,
           ]);
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

    }

    public function updateLabel(Request $request)
    {
    //   dd($request->id,$request->name);

        $data = Label::findOrFail($request->id);
        // dd($data);
        $data->update(['name'=>$request->name,'bg_color'=>$request->bg_color]);
        // return $this->index();
        return response()->json([
            'status'=>200,
          ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
     public function destroyLabel(Request $request)
    {
    //    dd($request->id);
     $data = Label::findOrFail($request->id);
     $data->delete();

     return response()->json([
        'status'=>200,
      ]);
    }
}