<?php

namespace App\Http\Controllers\Member;

use App\DataTables\StoreDataTable;
use App\Http\Services\CustomerSave;
use App\Http\Traits\FileUploadTrait;
use App\Models\District;
use App\Models\Region;
use App\Models\Thana;
use App\Models\Area;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    use FileUploadTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StoreDataTable $dataTable)
    {
        return $dataTable->render('member.store.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['regions'] = Region::active()->get()->pluck('name','id');
        $data['districts'] = District::active()->get()->pluck('name','id');
        $data['thanas'] = Thana::active()->get()->pluck('name','id');
        $data['areas'] = Area::active()->get()->pluck('name','id');

        return view('member.store.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customMessages = [
            'store_name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules(), $customMessages);

        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        $inputs['company_id'] = Auth::user()->company_id;

        if($request->approval_status)
        {
            $inputs['approval_date'] = Carbon::today();
        }


        if($request->hasFile('store_image'))
        {
            $image = $request->file('store_image');

            $upload = $this->fileUpload($image, '/store_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG'];

                return back()->with('status', $status)->withInput();

            }

            $inputs['store_image'] = $upload;
        }

        $saveStore = Store::create($inputs);

        $customer  =  new CustomerSave();
        $sharer = $customer->create_store_customer($request);

        $saveStore->sharer_id = $sharer->id;
        $saveStore->save();

        $status = ['type' => 'success', 'message' => "Store Added Successfully"];


        return back()->with('status', $status);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $user = Store::where('id', $id)->first();

        if(!$user) {
            $status = ['type' => 'danger', 'message' => 'Store Not find'];
            return back()->with('status', $status);
        }

        $data['regions'] = Region::active()->get()->pluck('name','id');
        $data['districts'] = District::active()->get()->pluck('name','id');
        $data['thanas'] = Thana::active()->get()->pluck('name','id');
        $data['areas'] = Area::active()->get()->pluck('name','id');

        return view('member.store.edit', $data);
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
        $store =  Store::where('id', $id)->first();

        if(!$store)
        {
            $status = ['type' => 'danger', 'message' => 'Store Not find'];
            return back()->with('status', $status);
        }

        $customMessages = [
            'store_name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules($id), $customMessages);

        DB::beginTransaction();
        try{

            $inputs = $request->all();

            if($request->approval_status)
            {
                $inputs['approval_date'] = Carbon::today();
            }


            if($request->hasFile('store_image'))
            {
                $image = $request->file('store_image');

                $upload = $this->fileUpload($image, '/store_image/', null);

                if (!$upload)
                {
                    $status = ['type' => 'danger', 'message' => 'Image Must be JPG'];
                    return back()->with('status', $status);
                }

                $inputs['store_image'] = $upload;
            }else{
                unset($inputs['store_image']);
            }


            $store->update($inputs);


            $customer  =  new CustomerSave();
            $customer->create_store_customer($request, $store->sharer_id);


            $status = ['type' => 'success', 'message' => 'Store Updated Successfully'];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to Store update'];
            DB::rollBack();
        }

        DB::commit();

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
        $modal = Store::findOrFail($id);
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
            "full_address" => "required",
            "city" => "required",
            "region_id" => "required",
            "district_id" => "required",
            "thana_id" => "required",
            "area_id" => "required",
        ];

        if($id=='') {
            $rules['store_name'] = 'required|unique_with:stores,store_name,mobile_no,company_id,region_id,district_id,thana_id,area_id';
            $rules['mobile_no'] = "required|unique:stores,mobile_no";
        }else{
            $rules['store_name'] = 'required|unique_with:stores,store_name,mobile_no,company_id,region_id,district_id,thana_id,area_id,'.$id;
            $rules['mobile_no'] = "required|unique:stores,mobile_no,".$id;
        }

        return $rules;
    }

}
