<?php

namespace App\Http\Controllers\Member;

use App\DataTables\WarehouseDataTable;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\FileUploadTrait;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\Area;
use App\Models\Thana;
use App\Models\Region;
use App\Models\District;
use App\Models\Division;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{

    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WarehouseDataTable $dataTable)
    {
        return $dataTable->render('member.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['divisions'] = Division::get()->pluck('display_name_bd','id');
        $data['regions'] = Region::active()->get()->pluck('name','id');
        $data['districts'] = District::get()->pluck('display_name_bd','id');
        $data['thanas'] = Thana::get()->pluck('name','id');
//        $data['unions'] = Union::get()->pluck( 'display_name_bd','id');
        $data['areas'] = Area::get()->pluck( 'display_name_bd','id');

        return view('member.warehouse.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['company_id'] = Auth::user()->company_id;

        $customMessages = [
            'title.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules(), $customMessages);

        $inputs = $request->all();


        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $options['width'] = 100;
            $options['height'] = 100;
            $destinationPath = 'featured_image';
            $upload = $this->fileUpload($file, $destinationPath, $options);
            $inputs['featured_image'] = $upload;
        }

        $inputs['gallery_images'] = "";

        if (isset($request->gallery_image) && count($request->gallery_image)>0) {


            $files = $request->gallery_image;
            $upload = "";
            for ($i=0; $i<count($files); $i++)
            {
                $options['width'] = 100;
                $options['height'] = 100;
                $destinationPath = 'gallery_images';
                $file = $this->fileUpload($files[$i], $destinationPath, $options);
                $upload .= $upload == "" ? $file : ", ".$file;
            }

            $inputs['gallery_images'] = $upload;
        }

        unset($inputs['gallery_image']);


        $warehouse = Warehouse::create($inputs);
        $short = strtolower(substr($inputs['title'], 0, 2));
        $inputs['shortcode'] = $short.$warehouse->id;
        $warehouse->update($inputs);


        $status = ['type' => 'success', 'message' => ' Added Successfully'];



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
        $data['warehouse'] = $warehouse = Warehouse::where('id', $id)->authCompany()->firstOrFail();

        if(!$warehouse)
         {
             $status = ['type' => 'danger', 'message' => 'You don\'t have this Warehouse' ];
             return redirect()->back()->with('status', $status);
         }

        return view('member.warehouse.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $warehouse = Warehouse::find($id);
        $data['divisions'] = Division::get()->pluck('display_name_bd','id');
        $data['regions'] = Region::active()->get()->pluck('name','id');
        $data['districts'] = District::get()->pluck('display_name_bd','id');
        $data['thanas'] = Thana::get()->pluck('name','id');
//        $data['unions'] = Union::get()->pluck( 'display_name_bd','id');
        $data['areas'] = Area::get()->pluck( 'display_name_bd','id');

        if(!$warehouse)
        {
            $status = ['type' => 'danger', 'message' => 'Don\'t have any data'];
            return back()->with('status', $status);
        }

        return view('member.warehouse.edit', $data);
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
        $request['company_id'] = Auth::user()->company_id;

        $customMessages = [
            'title.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules($id), $customMessages);

        $warehouse =  Warehouse::find($id);

        $inputs = $request->all();

        $support = ['jpg', 'png', 'jpeg', 'gif'];

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $destinationPath = 'featured_image';

            $upload = $this->fileUpload($file, $destinationPath, null, $support);
            $inputs['featured_image'] = $upload;
        }

        if (isset($request->gallery_image) && count($request->gallery_image)>0) {


            $files = $request->gallery_image;
            $upload = "";
            for ($i=0; $i<count($files); $i++)
            {
                $destinationPath = 'gallery_images';
                $file = $this->fileUpload($files[$i], $destinationPath, null, $support);
                $upload .= $upload == "" ? $file : ", ".$file;
            }

            $inputs['gallery_images'] = $upload;
        }

        unset($inputs['gallery_image']);

        $short = strtolower(substr($inputs['title'], 0, 2));
        $inputs['shortcode'] = $short.$warehouse->id;
        $warehouse->update($inputs);


        $status = ['type' => 'success', 'message' => ' updated Successfully'];


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
        $modal = Warehouse::findOrFail($id);

        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);

    }

    private function getValidationRules($id='')
    {

        $rules = [
            'featured_image' => 'file|mimes:jpeg,jpg,png',
            'active_status' => 'required',
        ];


        if($id)
        {
            $rules['title'] = 'required|unique_with:warehouses,title, mobile,company_id,'.$id;
            $rules['mobile'] = 'required|unique:warehouses,mobile,'.$id;
        }else{
//            $rules['composite_unique'] = [new SharerCompositeUnique()];

            $rules['title'] = 'required|unique_with:warehouses,title, mobile,company_id';
            $rules['mobile'] = 'required|unique:warehouses,mobile';
        }

        return $rules;
    }



    private function checkWarehouseCompositeUnique($request)
    {
        $warehouse = Warehouse::where('title', $request->name)
            ->where('mobile', $request->mobile)
            ->where('company_id', $request->company_id)
            ->first();

        if($warehouse)
            $result = true;
        else
            $result = false;

        return $result;
    }


}
