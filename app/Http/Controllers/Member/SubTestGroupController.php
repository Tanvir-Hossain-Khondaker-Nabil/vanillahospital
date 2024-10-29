<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestGroup;
use App\Models\SubTestGroup;
use App\DataTables\SubTestGroupDataTable;
use App\Http\Traits\CompanyInfoTrait;

class SubTestGroupController extends Controller
{
    use CompanyInfoTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $data['sub_test_groups'] = SubTestGroup::AuthCompany()->with('testGroup')->get();

    //     return view('member.sub_test_group.index',$data);
    // }

    public function index(SubTestGroupDataTable $dataTable)
    {
        // $data = HospitalService::authCompany()->get();
        // dd($data);
        return $dataTable->render('member.sub_test_group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['test_groups'] = TestGroup::AuthCompany()->where('status','active')->get()->pluck('title','id');

        return view('member.sub_test_group.create',$data);

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
        SubTestGroup::create($inputs);

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
        //
    }
    public function subTestPrint(Request $request)
    {
        $ids = explode(",", $request->selected_ids);

       $data['sub_test_groups'] = SubTestGroup::authCompany()->whereIn('id',$ids)->with('testGroup')->get();
       $data = $this->company($data);
       return view('member.sub_test_group.print_sub_test_group',$data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = SubTestGroup::findOrFail($id);
        $data['test_groups'] = TestGroup::AuthCompany()->where('status','active')->get()->pluck('title','id');

        return view('member.sub_test_group.edit',$data);
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
        $data = SubTestGroup::findOrFail($id);
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
        $modal = SubTestGroup::findOrFail($id);
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
            'test_group_id' => 'required',
            'price' => 'required',
        ];

        return $rules;

   }

   public function fetchSubTestGroup()
    {
        $data['models'] = SubTestGroup::AuthCompany()->with('testGroup')->get();

        return view('member.pathology.index',$data);
    }

  
}
