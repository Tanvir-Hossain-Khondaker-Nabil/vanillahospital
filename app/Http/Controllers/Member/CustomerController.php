<?php

namespace App\Http\Controllers\Member;

use App\DataTables\DealerCustomersDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\User;
use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use App\DataTables\CustomersDataTable;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DealerCustomersDataTable $dataTable)
    {
        return $dataTable->render('member.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['divisions'] = Division::get()->pluck('display_name_bd','id');
        $data['districts'] = District::get()->pluck('display_name_bd','id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd','id');
        $data['unions'] = Union::get()->pluck( 'display_name_bd','id');
        $data['areas'] = Area::get()->pluck( 'display_name_bd','id');

        return view('member.customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $request['composite_unique'] = $this->checkSharerCompositeUnique($request);
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $customMessages = [
            'name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules(), $customMessages);
//        print_r($request->all()); exit;
        $inputs = $request->all();


        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $options['width'] = 100;
            $options['height'] = 100;
            $destinationPath = 'sharers';
            $upload = $this->fileUpload($file, $destinationPath, $options);
            $inputs['photo'] = $upload;
        }

        $sharer = Customer::create($inputs);

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
        $data['sharer'] = $sharer = Customer::where('id', $id)->authCompany()->authMember()->firstOrFail();


        /* if(!$sharer)
         {
             $status = ['type' => 'danger', 'message' => 'You don\'t have this Id Supplier/Customer' ];
             return redirect()->back()->with('status', $status);
         }*/

        return view('member.customers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $sharer = Customer::find($id);
        $data['divisions'] = Division::get()->pluck('display_name_bd','id');
        $data['districts'] = District::get()->pluck('display_name_bd','id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd','id');
        $data['unions'] = Union::get()->pluck( 'display_name_bd','id');
        $data['areas'] = Area::get()->pluck( 'display_name_bd','id');

        if(!$sharer)
        {
            $status = ['type' => 'danger', 'message' => 'Don\'t have any data'];
            return back()->with('status', $status);
        }

        return view('member.customers.edit', $data);
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
        $request['member_id'] = Auth::user()->member_id;

        $customMessages = [
            'name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules($id), $customMessages);

        $sharer =  Customer::find($id);

        $inputs = $request->all();

        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $options['width'] = 100;
            $options['height'] = 100;
            $destinationPath = 'sharers';
            $upload = $this->fileUpload($file, $destinationPath, $options);
            $inputs['photo'] = $upload;
        }


        $sharer->update($inputs);

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
        $modal = Customer::findOrFail($id);

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
            'file' => 'file|mimes:jpeg,jpg,png,pdf',
            'status' => 'required',
        ];


        if($id)
        {
            $rules['name'] = 'required|unique_with:customers,name, phone,company_id,member_id,'.$id;
            $rules['phone'] = 'required|unique:customers,phone,'.$id;
        }else{
//            $rules['composite_unique'] = [new SharerCompositeUnique()];

            $rules['name'] = 'required|unique_with:customers,name, phone,company_id,member_id';
            $rules['phone'] = 'required|unique:customers,phone';
        }

        return $rules;
    }



    private function checkSharerCompositeUnique($request)
    {
        $sharer = Customer::where('name', $request->name)
            ->where('phone', $request->phone)
            ->where('company_id', $request->company_id)
            ->where('union_id', $request->union_id)->first();

        if($sharer)
            $result = true;
        else
            $result = false;

        return $result;
    }



}
