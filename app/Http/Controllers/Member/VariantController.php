<?php

namespace App\Http\Controllers\Member;

use App\DataTables\VariantDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Nexmo\Client\Exception\Validation;

class VariantController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VariantDataTable $dataTable)
    {
        return $dataTable->render('member.variants.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.variants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $inputs = [];
        $inputs['title'] = $request->title;
        $inputs['status'] = $request->status;
        $inputs['company_id'] = \Auth::user()->company_id;
        $inputs['created_by'] = \Auth::user()->id;
        $variant = Variant::create($inputs);

        $names = $request->name;

        foreach ($names as $key => $value)
        {
            $data = [];
            $data['variant_id'] = $variant->id;
            $data['name'] = $value;
            $data['company_id'] = \Auth::user()->company_id;
            $data['created_by'] = \Auth::user()->id;

            VariantValue::create($data);
        }

//        if($request->hasFile('image'))
//        {
//            $image = $request->file('image');
//
//            $upload = $this->fileUpload($image, '/image/', null);
//
//            if (!$upload)
//            {
//                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
//                return back()->with('status', $status);
//            }
//            $inputs['image'] = $upload;
//        }


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
        $data['modal'] = Variant::findorFail($id);

        return view('member.variants.edit', $data);
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
        $this->validate($request, $this->rules($id));

        $variant = Variant::authCompany()->findOrFail($id);

        $inputs = [];
        $inputs['title'] = $request->title;
        $inputs['status'] = $request->status;
        $variant->update($inputs);

        $names = $request->name;
        $name_ids = $request->name_id;

        VariantValue::whereNotIn('id', $name_ids)->where('variant_id', $variant->id)->delete();

        foreach ($names as $key => $value)
        {
            $variantValue = false;
            if(isset($name_ids[$key]))
                $variantValue = VariantValue::findOrFail($name_ids[$key]);

            $data = [];
            $data['variant_id'] = $variant->id;
            $data['name'] = $value;
            $data['company_id'] = \Auth::user()->company_id;
            $data['created_by'] = \Auth::user()->id;

            if(!$variantValue)
                VariantValue::create($data);
            else
                $variantValue->update($data);

        }


//        if($request->hasFile('image'))
//        {
//            $image = $request->file('image');
//
//            $upload = $this->fileUpload($image, '/image/', null);
//
//            if (!$upload)
//            {
//                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
//                return back()->with('status', $status);
//            }
//            $inputs['image'] = $upload;
//        }



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
        $modal = Variant::findOrFail($id);

        if($modal->items->count()>0)
        {
            return response()->json([
                'data' => [
                    'message' => 'Delete not Possible , you already have Items for this variant'
                ]
            ], 400);
        }


        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    private function rules($id='')
    {
        if($id==''){
            $rules = [
                'title' => 'required|unique:variants,title',
            ];
        }else{
            $rules = [
                'title' => 'required|unique:variants,title,'.$id,
            ];
        }

        $rules["name"]    = "required|array|min:1";
        $rules["name.*"]  = "required";

        return $rules;
    }
}
