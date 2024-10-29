<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\CustomerSave;
use App\Models\EmployeeAttendence;
use App\Models\EmployeeInfo;
use App\Models\Store;
use App\Models\StoreVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreApiController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $stores = Store::approved()->active()->orderBy('store_name','asc')->get();

        $response = [ 'status' =>'success',"stores" => $stores];

        return response($response);


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

        $validator = Validator::make($request->all(), $this->getValidationRules(), $customMessages);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all(), 'status' =>'failed'], 422);
        }

        $inputs = $request->all();
        $inputs['created_by'] = $request->user()->id;
        $inputs['company_id'] = $request->user()->company_id;



        if($request->hasFile('store_image'))
        {
            $image = $request->file('store_image');

            $upload = $this->fileUpload($image, '/store_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'failed', 'message' => 'Image Must be JPG'];

                return response($status,422);

            }

            $inputs['store_image'] = $upload;
        }

        $saveStore = Store::create($inputs);

        $customer  =  new CustomerSave();
        $sharer = $customer->create_store_customer($request, null, true);

        $saveStore->sharer_id = $sharer->id;
        $saveStore->save();


        $response = ["message" => "Store Added Successfully", 'status' =>'success'];

        return response($response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store = Store::where('id', $id)->first();

        if(!$store) {
            $status = ['status' => 'failed', 'message' => 'Store Not find'];

            return response($status, 422);
        }

        $response = ["store" => $store, 'status' =>'success'];

        return response($response);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $id = $request->store_id;

        $store =  Store::where('id', $id)->first();

        if(!$store)
        {
            $status = ['type' => 'danger', 'message' => 'Store Not find'];
            return response($status, 422);
        }

        $customMessages = [
            'store_name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $validator = Validator::make($request->all(), $this->getValidationRules($id), $customMessages);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all(), 'status' =>'failed'], 422);
        }


        $inputs = $request->all();

        if($request->hasFile('store_image'))
        {
            $image = $request->file('store_image');

            $upload = $this->fileUpload($image, '/store_image/', null);

            if (!$upload)
            {
                $status = ['type' => 'failed', 'message' => 'Image Must be JPG'];

                return response($status, 422);
            }

            $inputs['store_image'] = $upload;
        }else{
            unset($inputs['store_image']);
        }

        unset($inputs['store_id']);

        $store->update($inputs);

        $customer  =  new CustomerSave();
        $customer->create_store_customer($request, $store->sharer_id, true);


        $status = ['type' => 'success', 'message' => 'Store Updated Successfully'];


        return response($status);

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
            "latitude" => "required",
            "longitude" => "required",
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


    public function storeVisit(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $rules = [
          'latitude'  => 'required',
          'longitude'  => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all(), 'status' =>'failed'], 422);
        }

        $distance = 200; // user input distance

//          To convert to miles, multiply by 3971.
//          To convert to kilometers, multiply by 6373.
//          To convert to meters, multiply by 6373000.
//          To convert to feet, multiply by (3971 * 5280) 20914080.

        $sql = "SELECT ROUND(6373000 * acos (cos ( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( latitude ) ))) AS distance,stores.* FROM stores HAVING distance <= $distance  order by distance asc";

        $distance = DB::select($sql);



        if(count($distance)>0)
        {
            $store_id = $distance[0]->id;

            $auth_id = Auth::id();
            $employee = EmployeeInfo::where('user_id', $auth_id)->first();

            $storeVisit = new StoreVisit();
            $storeVisit->store_id = $store_id;
            $storeVisit->employee_id = $employee->id;
            $storeVisit->visit_date = date("Y-m-d");
            $storeVisit->visit_time = date("h:i:s ");
            $storeVisit->save();

            $attend = EmployeeAttendence::where('visit_date', date("Y-m-d"))
                ->where('employee_id', $auth_id)->first();

            if(!$attend)
            {
                $emp_attend = new EmployeeAttendence();
                $emp_attend->employee_id = $employee->id;
                $emp_attend->visit_date = date("Y-m-d");
                $emp_attend->in_time = date("h:i:s ");
                $emp_attend->save();
            }


            return response($distance);

        }else{

            $status = ['type' => 'failed', 'message' => 'No Store Found'];

            return response($status,422);
        }



    }

}
