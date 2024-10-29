<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\TestGroup;
use App\Models\SubTestGroup;
use App\Models\Specimen;
use App\Models\DoctorComission;
use App\Http\Controllers\Controller;

class TestGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['test_group'] = TestGroup::AuthCompany()->with('specimen')->get();
        //  dd($data);
        return view('member.test_group.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['specimen'] = Specimen::AuthCompany()->get()->pluck('specimen','id');
        // dd($data);
        return view('member.test_group.create',$data);
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
        TestGroup::create($inputs);

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
        $data['title']= TestGroup::AuthCompany()->where('id',$id)->value('title');
        // dd($data);
        $data['sub_test_groups']= SubTestGroup::AuthCompany()->where('test_group_id',$id)->with('testGroup')->get();
        return view('member.test_group.show',$data);
    }

    public function fetchSubTest(Request $request)
    {

      $test_data = DoctorComission::AuthCompany()->where('doctor_id',$request->doctor_id)
                                                    ->where('test_group_id',$request->id)
                                                     ->with(['comission'])->first();

        $check_test = [];
         if(@$test_data->comission){
            $check_test = $test_data->comission->pluck('sub_test_group_id')->toArray();
         }

        $data = SubTestGroup::AuthCompany()->where('test_group_id',$request->id)->with('testGroup')->get();

        return response()->json([
            'data'=> $data,
            'check_test'=> $check_test,

        ]);
    }


    public function fetchSubTestByTestId(Request $request)
    {

        $data = SubTestGroup::AuthCompany()->where('test_group_id',$request->id)->with('testGroup')->get();

        return response()->json([
            'data'=> $data,

        ]);
    }

    public function fetchTestBySpecimen(Request $request)
    {

        $data = TestGroup::AuthCompany()->where('specimen_id',$request->id)->get();

        return response()->json([
            'data'=> $data,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['specimen'] = Specimen::AuthCompany()->get()->pluck('specimen','id');
        $data['model'] = TestGroup::findOrFail($id);
        return view('member.test_group.edit',$data);
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

        $data = TestGroup::findOrFail($id);
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
        $modal = TestGroup::findOrFail($id);
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
            'title' => 'required',
            'specimen_id' => 'required',
        ];

        return $rules;


  }
}