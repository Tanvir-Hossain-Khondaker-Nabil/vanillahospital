<?php

namespace App\Http\Controllers\Member;


use App\Http\Traits\FileUploadTrait;
use App\Models\Country;
use App\Models\Department;
use App\Models\District;
use App\Models\Division;
use App\Models\DocumentType;
use App\Models\EmployeeSubmittedDocument;
use App\Models\Region;
use App\Models\Shift;
use App\Models\Thana;
use App\Models\Area;
use App\Models\Designation;
use App\Models\EmployeeInfo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeePersonalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data['model'] = $employee = EmployeeInfo::with('user')->find($id);

        if (!$employee) {
            $status = ['type' => 'danger', 'message' => 'Personal Info Not find'];
            return back()->with('status', $status);
        }

        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['departments'] = Department::where('active_status', 1)->get();
        $data['designations'] = Designation::where('active_status', 1)->get();
        $data['divisions'] = Division::active()->get()->pluck('name', 'id');
        $data['regions'] = Region::active()->get()->pluck('name', 'id');
        $data['districts'] = District::active()->get()->pluck('name', 'id');
        $data['thanas'] = Thana::active()->get()->pluck('name', 'id');
        $data['areas'] = Area::active()->get()->pluck('name', 'id');

        $data['submitted_documents'] = $employee->attached_file->pluck('document_type_id')->toArray();

        return view('member.employee.employee_info_edit', $data);
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
    //   dd($request->all());
        $employeeInfo = EmployeeInfo::where('id', $id)->first();
        $user = User::where('id', $employeeInfo->user_id)->first();

        if (!$employeeInfo) {
            $status = ['type' => 'danger', 'message' => 'Personal Info Not find'];
            return back()->with('status', $status);
        }

        $this->validate($request, $this->getValidationRules($request->designation_id, $user->id));

//        $user->email = $email = $request->email;
        $user->full_name = $request->first_name . " " . $request->last_name;
//        $user->phone = $request->phone;
        // $user->status = $request->status;

//        DB::beginTransaction();
//
//        try{

        $user->save();


//        $employeeInfo->employeeID = $request->employeeID;
//        $employeeInfo->phone2 = $request->phone;
//        $employeeInfo->nid = $request->nid;
//        $employeeInfo->salary = $request->salary;
//        $employeeInfo->commission = $request->commission;
//        $employeeInfo->join_date = db_date_format($request->join_date);
//        $employeeInfo->designation_id = $request->designation_id;


        $employeeInfo->first_name = $request->first_name;
        $employeeInfo->last_name = $request->last_name;
        $employeeInfo->address = $request->present_address;
        $employeeInfo->address2 = $request->permanent_address;
        $employeeInfo->dob = db_date_format($request->dob);
        $employeeInfo->region_id = $request->region_id;
        $employeeInfo->area_id = $request->area_id;
        $employeeInfo->thana_id = $request->thana_id;
        $employeeInfo->district_id = $request->district_id;
        $employeeInfo->division_id = $request->division_id;


        $employeeInfo->nationality = $request->nationality;
        $employeeInfo->insurance_company = $request->insurance_company;
        $employeeInfo->insurance_number = $request->insurance_number;

//        $employeeInfo->pr_number = $request->pr_number;
//        $employeeInfo->department_id = $request->department_id;
//        $employeeInfo->shift_id = $request->shift_id;
//        $employeeInfo->salary_system = $request->salary_system;
//        $employeeInfo->weekend_accept = $request->weekend_accept;
//        $employeeInfo->passport_number = $request->passport_number;
//        $employeeInfo->passport_expire = db_date_format($request->passport_expire);
//        $employeeInfo->visa_expire = db_date_format($request->visa_expire);
//        $employeeInfo->pr_expire = db_date_format($request->pr_expire);
//        $employeeInfo->diving_license = $request->diving_license;

        $employeeInfo->save();

//
//        $submitted_documents = $request->submitted_documents ?? [];
//        $submitted_files = $request->submitted_files ?? [];
//
//        if (count($submitted_documents) > 0) {
//            foreach ($submitted_documents as $value) {
//
//                $file = "";
//                if (isset($submitted_files[$value])) {
//
//                    $destinationPath = 'files';
//                    $upload = $this->fileUploadWithDetails($submitted_files[$value], $destinationPath);
//                    $file = $upload['file_store_path'] . "/" . $upload['file_name'];
//                }
//
//                $hasFile = EmployeeSubmittedDocument::where('employee_id', $employeeInfo->id)->where('document_type_id', $value)->first();
//
//                if ($hasFile && $file != "" && $submitted_files[$value] != "") {
//                    EmployeeSubmittedDocument::where('employee_id', $employeeInfo->id)->where('document_type_id', $value)->update(['attached' => $file]);
//
//                } elseif ($file != "" && $submitted_files[$value] != "") {
//                    $submitted_document = new EmployeeSubmittedDocument();
//                    $submitted_document->employee_id = $employeeInfo->id;
//                    $submitted_document->document_type_id = $value;
//                    $submitted_document->attached = $file;
//                    $submitted_document->save();
//                }
//
//
//            }
//        }


        $status = ['type' => 'success', 'message' => 'Personal Info Updated Successfully'];

//        }catch (\Exception $e){
//
//            $status = ['type' => 'danger', 'message' => 'Unable to Employee update'];
//            DB::rollBack();
//        }
//
//        DB::commit();

        return back()->with('status', $status);

    }
    private function getValidationRules($designation_id, $id = '')
    {

        $designation = Designation::find($designation_id);
        $commission_area = $designation->commission_area;


        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required|date_format:m/d/Y|',
            'permanent_address' => 'required',
            'present_address' => 'required',
//            'designation_id' => 'required',
//            'join_date' => 'required|date_format:m/d/Y',
//            'passport_expire' => 'nullable|date_format:m/d/Y',
//            'visa_expire' => 'nullable|date_format:m/d/Y',
//            'pr_expire' => 'nullable|date_format:m/d/Y',
//            'nid' => 'required|min:8|max:18',
//            'passport_number' => 'nullable|min:7|max:12',
//            'diving_license' => 'nullable|min:7|max:12',
//            'pr_number' => 'nullable|min:7|max:12',
//            'employeeID' => 'nullable|numeric|digits_between:5,10',
//            "roles" => "required",
//            "commission" => "required",
//            "salary" => "required",
        ];

//        if (is_null($id)) {
//            $rules['email'] = 'required|email|unique:users';
//            $rules['phone'] = 'nullable|min:8|max:12|unique:users,phone';
//            $rules['password'] = 'required';
//        } else {
//            $rules['email'] = 'required|email|unique:users,email,' . $id;
//            $rules['phone'] = 'nullable|min:8|max:12|unique:users,phone,' . $id;
//        }

//        if ($commission_area == "division") {
//
//            $rules['division_id'] = 'required';
//
//        } else if ($commission_area == "region") {
//
//            $rules['division_id'] = 'required';
//            $rules['region_id'] = 'required';
//
//        } else if ($commission_area == "area") {
//
//            $rules['division_id'] = 'required';
//            $rules['region_id'] = 'required';
//            $rules['district_id'] = 'required';
//            $rules['thana_id'] = 'required';
//            $rules['area_id'] = 'required';
//
//        } else if ($commission_area == "thana") {
//
//            $rules['division_id'] = 'required';
//            $rules['region_id'] = 'required';
//            $rules['district_id'] = 'required';
//            $rules['thana_id'] = 'required';
//
//        } else if ($commission_area == "district") {
//
//            $rules['division_id'] = 'required';
//            $rules['region_id'] = 'required';
//            $rules['district_id'] = 'required';
//
//        }


        return $rules;
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
}