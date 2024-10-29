<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestGroup;
use App\Models\Doctor;
use App\Models\DoctorComission;
use App\Models\DoctorComissionTest;


class DoctorComissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //  dd($request->all());
        $data['test_group'] = TestGroup::AuthCompany()->where('status', 'active')->get()->pluck('title', 'id')->toArray();

        //   dd($data);
        $data['doctor_id'] = $request->doct_id;

        $data['doctor'] = Doctor::AuthCompany()->where('id', $request->doct_id)->first();
        $data['comissions'] = DoctorComission::AuthCompany()->where('doctor_id', $request->doct_id)->with(['doctor', 'comission', 'testGroup'])->get();
        return view('member.doctor_comission.create', $data);
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

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;

        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;

        foreach($request->group_ids as $key=> $item){
           $inputs['comission_type'] = $request->com_type[$key];
           $inputs['amount'] = $request->com_amnt[$key];
           $inputs['partiality'] = $request->partiality[$key]?? 0;
           $inputs['test_group_id'] = $request->group_ids[$key];
           $inputs['doctor_id'] = $request->doctor_id;
            $data_status = DoctorComission::create($inputs);

            if ($data_status) {
                foreach ($request->test_name[$key] as $k => $val) {
                    // dd($request->all(),$k,$val);
                    $input['sub_test_group_id'] =  $val;
                    $input['doctor_comission_id'] = $data_status->id;
                    DoctorComissionTest::create($input);
                }
            }
        }

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
        $data['doctor'] = Doctor::AuthCompany()->where('id', $id)->first();
        $data['comissions'] = DoctorComission::AuthCompany()->where('doctor_id', $id)->with(['doctor', 'comission', 'testGroup'])->get();

        // dd($id,$data);
        return view('member.doctor_comission.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['data'] = DoctorComissionTest::where('id', $id)->with(['doctorComission', 'subTestGroup'])->first();
        // dd($data);

        return view('member.doctor_comission.edit', $data);
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
        dd($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = DoctorComissionTest::findOrFail($id);

        $modal->delete();

        return back();

        // return response()->json([
        //     'data' => [
        //         'message' => 'Successfully deleted'
        //     ]
        // ], 200);
    }

    public function checkDoctorComission(Request $request)
    {
        // dd($request->all());
        $sub_test_id = $request->sub_test_id;
        $data = DoctorComission::AuthCompany()->where('doctor_id', $request->doctor_id)
            ->where('test_group_id', $request->test_id)
            ->with(['comission' => function ($query) use ($sub_test_id) {
                $query->where('sub_test_group_id', $sub_test_id);
            }])->first();

        // dd($data);
        // $modal = DoctorComissionTest::findOrFail($id);

        // $modal->delete();

        // return back();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted',
                'data' => $data,
            ]
        ], 200);
    }
}