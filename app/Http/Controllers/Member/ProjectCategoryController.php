<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use App\Http\Traits\FileUploadTrait;
use App\DataTables\ProjectCategoryDataTable;
class ProjectCategoryController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectCategoryDataTable $dataTable)
    {
       return $dataTable->render('member.project_category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.project_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,$this->validationRules());

        $request['name'] = snake_case($request->display_name);
        $request['company_id'] = \Auth::user()->company_id;
        $request['created_by'] = \Auth::user()->id;
        $inputs = $request->all();



        ProjectCategory::create($inputs);

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
        $data['model'] = ProjectCategory::findOrFail($id);

        return view('member.project_category.edit',$data);
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

        $this->validate($request,$this->validationRules($id));

        $project_category = ProjectCategory::findOrFail($id);
        $request['updated_by'] = \Auth::user()->id;
        $inputs = $request->all();
        $inputs['name'] = snake_case($request->display_name);
        $project_category->update($inputs);

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
        {
            if($id==''){
                $rules = [

                    'display_name' => 'required|unique:project_categories,display_name',
                ];
            }else{
                $rules = [
                    'display_name' => 'required|unique:project_categories,display_name,'.$id,
                ];
            }

            return $rules;
        }
    }
}