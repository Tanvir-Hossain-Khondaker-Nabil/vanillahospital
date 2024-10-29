<?php

namespace App\Http\Controllers\Member;

use App\Http\Services\PaymentMethodIntegrate;
use App\Http\Traits\FileUploadTrait;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Weekend;
use App\Services\CompanyFeature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\FiscalYear;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\DataTables\CompanyDataTable;

class CompanyController extends Controller
{
    use FileUploadTrait;

        /*
         * TODO: Company Controller fully functional check in later.
         */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CompanyDataTable $dataTable)
    {
        return $dataTable->render('member.companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::all()->pluck('countryName', 'id');

        $data['fiscal_year'] = FiscalYear::active()->authMember()->get()->pluck('fiscal_year_details','id');
        // "<pre>";print_r($data['fiscal_year']);die();
        return view('member.companies.create',$data);
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

        $inputs = $request->all();

        // dd($inputs);

        if ($request->hasFile('logo')) {
             $id_photo = $request->file('logo');
    //         $destinationPath='storage/company_logo';
    //         $ext=$id_photo->getClientOriginalExtension();
    //         $file_name=file_name_generator().'.'.$ext;
    //         $upload =$id_photo->move($destinationPath,$file_name);

                $upload = $this->fileUpload($id_photo, '/company_logo/', null);

                if (!$upload)
                {
                    $status = ['type' => 'danger', 'message' => 'Image Must be JPG'];
                    return back()->with('status', $status)->withInput();
                }
                $inputs['logo'] = $upload;

         }

        if ($request->hasFile('report_head_image')) {
             $id_photo = $request->file('report_head_image');

                $upload = $this->fileUpload($id_photo, '/company_report_head/', null);

                if (!$upload)
                {
                    $status = ['type' => 'danger', 'message' => 'Image Must be JPG'];
                    return back()->with('status', $status)->withInput();
                }
                $inputs['report_head_image'] = $upload;

         }

     Company::create($inputs);


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
        $data['model'] = Company::findOrFail($id);
        $data['countries'] = Country::all()->pluck('countryName', 'id');

        $data['fiscal_year'] = FiscalYear::active()->authMember()->get()->pluck('fiscal_year_details','id');

        return view('member.companies.edit',$data);
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
        $company = Company::findOrFail($id);
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();
        // dd($inputs);
        if ($request->hasFile('logo')) {
            $id_photo = $request->file('logo');

            $upload1 = $this->fileUpload($id_photo, '/company_logo/', null);

            if (!$upload1)
            {
                $status = ['type' => 'danger', 'message' => 'Company logo Image Must be JPG'];
                return back()->with('status', $status);
            }
                $inputs['logo'] = $upload1;

            }

            if ($request->hasFile('report_head_image')) {

                $id_photo = $request->file('report_head_image');

                $upload2 = $this->fileUpload($id_photo, '/company_report_head/', null);

                if (!$upload2)
                {
                    $status = ['type' => 'danger', 'message' => 'company report head Image Must be JPG'];
                    return back()->with('status', $status)->withInput();
                }

                $inputs['report_head_image'] = $upload2;

            }

            if ($request->hasFile('pad_header_image')) {

                $id_photo = $request->file('pad_header_image');

                $upload3 = $this->fileUpload($id_photo, '/company_report_head/', null);

                if (!$upload3)
                {
                    $status = ['type' => 'danger', 'message' => 'Pad header Image Must be JPG'];
                    return back()->with('status', $status)->withInput();
                }

                $inputs['pad_header_image'] = $upload3;

            }

            if ($request->hasFile('pad_footer_image')) {

                $id_photo = $request->file('pad_footer_image');

                $upload4 = $this->fileUpload($id_photo, '/company_report_head/', null);

                if (!$upload4)
                {
                    $status = ['type' => 'danger', 'message' => 'Pad footer Image Must be JPG'];
                    return back()->with('status', $status)->withInput();
                }

                $inputs['pad_footer_image'] = $upload4;

            }

            if($inputs['quote_ref'] == "")
                $inputs['quote_ref'] = get_quote_ref($company->company_name);

            if(isset($request->weekend))
            {
                $week = $request->weekend;
                $daysOfWeek = get_daysOfWeek();

                Weekend::where('company_id', $company->id)
                    ->whereNull('month')->whereNull('employee_id')->delete();

                foreach ($week as $value)
                {
                    $weekend = [];
                    $weekend['name'] = $value;
                    $weekend['company_id'] = $company->id;
                    Weekend::create($weekend);
                }

            }
            // dd($inputs);
            $company->update($inputs);

            $status = ['type' => 'success', 'message' => 'Successfully Updated'];

        return back()->with('status', $status);
    }

    public function feature()
    {
        $features = new CompanyFeature();
        $data['features'] = $features->options();
        if(Company::count()>1)
        {
            $data['companies'] = Company::all()->pluck('company_name', 'id');

        }else{
            $data['companies'] = [];
        }
        $data['activeFeatures'] = Setting::all()->pluck('key')->toArray();

        return view('member.companies.company_features',$data);
    }

    public function feature_store(Request $request)
    {
        $rules = [
            'features' => 'required',
        ];

        $this->validate($request, $rules);

        if(Company::count()==1)
        {
            $company_id = 1;
        }else{
            $company_id = $request->company_id;
        }

        $features = new CompanyFeature();
        $features = $features->options();
        array_push($features,'all_features');
        Setting::whereIn('key', $features)->delete();

        foreach ($request->features as $value)
        {
            $data = [];
            $data['company_id'] = $company_id;
            $data['key'] = $value;
            $data['value'] = true;

            Setting::create($data);

            if($value == "pos")
            {
                $posPayment = new PaymentMethodIntegrate();
                $posPayment->posPaymentMethodSave();
            }
        }

//        Artisan::call('migrate');

//        Artisan::call('db:seed', ['--class' => 'CreateNewPackage']);

        $status = ['type' => 'success', 'message' => 'Successfully Set Features'];

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


    private function validationRules(){

        $rules = [
            'company_name' => 'required',
            'phone' => 'required',
            'email' => 'required'
        ];

        return $rules;
    }
}