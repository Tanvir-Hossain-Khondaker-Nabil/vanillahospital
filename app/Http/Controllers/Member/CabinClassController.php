<?php

namespace App\Http\Controllers\Member;

use App\Models\Room;
use App\Models\CabinClass;
use App\Models\CabinSubClass;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CabinClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::where('id', auth()->user()->company_id)->get();
        $cabinClass = CabinClass::with("subClass",'room','company')->where('status', 1)->get();
        // dd($cabinClass);

        $cabinSubClass = CabinSubClass::where('status', 1)->get();
        return view("member.cabin.index", compact('cabinClass', 'company', 'cabinSubClass'));
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
        $status = $request->status;
        if ($status == 1) {
            $cabinClass = new CabinClass();
            $cabinClass->company_id = $request->company_id;
            $cabinClass->title = $request->cabin_class;
            $cabinClass->save();
            $status = ['type' => 'success', 'message' => 'Cabin Class Added Successfully'];
            session()->flash('status', $status);
            // session()->flash('success', "Cabin Class Added Successfully");
        } elseif ($status == 2) {
            // dd($request->all());
            $cabinSubClass = new CabinSubClass();
            $cabinSubClass->company_id = $request->company_id;
            $cabinSubClass->cabin_class_id = $request->cabin_class_id;
            $cabinSubClass->title = $request->subClassTitle;
            $cabinSubClass->save();
            $status = ['type' => 'success', 'message' => 'Cabin Sub Class Added Successfully'];
            session()->flash('status', $status);
            // session()->flash('success', "Cabin Sub Class Added Successfully");
        } elseif ($status == 3) {
            // dd($request->all());
            $room = new Room();
            $room->company_id = $request->company_id;
            $room->cabin_class_id = $request->cabin_class_id;
            $room->cabin_sub_class_id = $request->cabin_sub_class_id;
            $room->title = $request->room_title;
            $room->price = $request->price;
            $room->seat_capacity = $request->seat_capacity;
            $room->save();
            $status = ['type' => 'success', 'message' => 'Cabin Room Added Successfully'];
            session()->flash('status', $status);
            // session()->flash('success', "Cabin Room Added Successfully");
        }
        return redirect()->back();
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
    public function cabinsummary()
    {
        $cabinClass = CabinClass::with("subClass",'room','company')->where('status', 1)->get();

        return view("member.cabin.show", compact('cabinClass'));
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
    public function getCabinClass(Request $request)
    {
        $id = $request->id;
        $cabinClass = CabinClass::find($id);
        return response()->json(['data'=>$cabinClass]);
    }
    public function updateCabinSubClass(Request $request)
    {
        $id = $request->id;
        $cabinSubClass = CabinSubClass::find($id);
        return response()->json(['data'=>$cabinSubClass]);
    }
    public function updateRoom(Request $request)
    {
        $id = $request->id;
        $room = Room::find($id);
        return response()->json(['data'=>$room]);
    }
    public function cabinUpdate(Request $request)
    {
        // dd($request->all());
        $status = $request->status;
        if ($status == 1) {
            $cabinClass = CabinClass::find($request->cabin_class_id);
            $cabinClass->company_id = $request->company_id;
            $cabinClass->title = $request->cabin_class;
            $cabinClass->status = $request->status1;
            $cabinClass->save();
            $status = ['type' => 'success', 'message' => 'Cabin Class Updated Successfully'];
            session()->flash('status', $status);
        } elseif ($status == 2) {
            // dd($request->all());
            $cabinSubClass = CabinSubClass::find($request->cabin_sub_class_id);
            $cabinSubClass->company_id = $request->company_id;
            $cabinSubClass->cabin_class_id = $request->cabin_class_id;
            $cabinSubClass->title = $request->subClassTitle;
            $cabinSubClass->status = $request->status1;
            $cabinSubClass->save();
            $status = ['type' => 'success', 'message' => 'Cabin Sub Class Updated Successfully'];
            session()->flash('status', $status);
            // session()->flash('success', "Cabin Sub Class Updated Successfully");
        } elseif ($status == 3) {
            // dd($request->all());
            $room = Room::find($request->room_id);
            // $room->company_id = $request->company_id;
            // $room->cabin_class_id = $request->cabin_class_id;
            $room->cabin_sub_class_id = $request->cabin_sub_class_id;
            $room->title = $request->room_title;
            $room->price = $request->price;
            $room->seat_capacity = $request->seat_capacity;
            $room->status = $request->status1;
            $room->save();
            $status = ['type' => 'success', 'message' => 'Cabin Room Updated Successfully'];
            session()->flash('status', $status);
            // session()->flash('success', "Cabin Room Updated Successfully");
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $cabinClass = CabinClass::find($id);
        $cabinClass->delete();
        return response()->json([
            'data' => [
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);
    }
    public function getCabin(Request $request)
    {
        $company_id = $request->company_id;
        if ($request->status == 1) {
            $cabins = CabinClass::where([["company_id", $company_id], ['status', 1]])->get();
            $content = '<option value="0">Select Cabin Class Title</option>';
            foreach ($cabins as $cabin) {
                $content .= "<option value='" . $cabin->id . "'>" . $cabin->title . "</option>";
            }
        } else {
            $cabin_class_id = $request->cabin_class_id;
            $cabins = CabinSubClass::where([["company_id", $company_id], ['cabin_class_id', $cabin_class_id], ['status', 1]])->get();
            // dd( $cabins);
            $content = '<option value="0">Select Cabin Sub Class Title</option>';
            foreach ($cabins as $cabin) {
                $content .= "<option value='" . $cabin->id . "'>" . $cabin->title . "</option>";
            }
        }
        return $content;
    }
}
