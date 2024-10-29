<?php

namespace App\Http\Controllers\Member;

use App\DataTables\CategoriesDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('member.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::all()->pluck('display_name', 'id');
        return view('member.categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['name'] = snake_case($request->display_name);
      
        $inputs = $request->all();

        $this->validate($request, $this->rules());

        if($request->hasFile('category_image'))
        {
            $image = $request->file('category_image');

            $upload = $this->fileUpload($image, '/category_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => trans('common.image_must_be').' JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $inputs['category_image'] = $upload;
        }

        Category::create($inputs);

        $status = ['type' => 'success', 'message' => trans('common.successfully_added')];

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
        $data['modal'] = Category::findorFail($id);
        $data['categories'] = Category::all()->pluck('display_name', 'id');

        return view('member.categories.edit', $data);
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
        $category = Category::find($id);
        $request['name'] = snake_case($request->display_name);
        $inputs = $request->all();

        $this->validate($request, $this->rules($id));

        if($request->hasFile('category_image'))
        {
            $image = $request->file('category_image');

            $upload = $this->fileUpload($image, '/category_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => trans('common.image_must_be').' JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $inputs['category_image'] = $upload;
        }

        $category->update($inputs);

        $status = ['type' => 'success', 'message' => trans('common.successfully_updated')];

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
        $modal = Category::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);
    }

    private function rules($id='')
    {
        if($id==''){
            $rules = [
                'name' => 'required|unique:categories,name',
                'display_name' => 'required|unique:categories,display_name',
            ];
        }else{
            $rules = [
                'name' => 'required|unique:categories,name,'.$id,
                'display_name' => 'required|unique:categories,display_name,'.$id,
            ];
        }

        return $rules;
    }
}