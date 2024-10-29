<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeInfo;
use App\Models\Chat;
class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['employee'] = EmployeeInfo::get();
        return view('member.chats.index',$data);
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
        //
    }

    public function save_message_from_admin(Request $request)
    {
        // return ($request->all());
       $date_time = date('Y-m-d H:i:s');
       $date_time_array = explode(" ",$date_time);

          $inputs = $request->all();
          $inputs['user_id'] = \Auth::user()->id;
          $inputs['date'] =  $date_time_array[0];
          $inputs['time'] =  $date_time_array[1];
        $data =   Chat::create($inputs);

        if($data){
            return response()->json([
                'status'=>200
            ]);
        }else{
            return response()->json([
                'status'=>400
            ]);
        }

    }

    public function show_message_from_admin (Request $request){
        $user_id = \Auth::user()->id;
        $employee_id = $request->employee_id;
        $data = Chat::where('user_id',$user_id)->where('employee_id',$employee_id)->get();

        if($data){
            return response()->json([
                'status'=>200,
                'data'=>$data,
            ]);
        }else{
            return response()->json([
                'status'=>400
            ]);
        }
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
        //
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