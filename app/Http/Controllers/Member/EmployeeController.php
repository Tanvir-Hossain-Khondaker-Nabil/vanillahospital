<?php

namespace App\Http\Controllers\Member;

use App\DataTables\EmployeeDataTable;
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
use App\Models\Bank;
use App\Models\BankBranch;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeDataTable $dataTable)
    {
        // dd('dssa');
        $data['departments'] = Department::authCompany()->where('active_status', 1)->get();
        $data['designations'] = Designation::authCompany()->where('active_status', 1)->get();
        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['join_years'] = EmployeeInfo::get_join_years();
        $data['dob_years'] = EmployeeInfo::get_dob_years();

        return $dataTable->render('member.employee.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['departments'] = Department::authCompany()->where('active_status', 1)->get();
//        $data['designations'] = Designation::authCompany()->where('active_status', 1)->get();
        $data['designations'] = [];
        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['divisions'] = [];
        $data['districts'] = [];
        $data['areas'] = [];
        $data['regions'] = Region::active()->get()->pluck('name', 'id');
        $data['thanas'] = Thana::active()->get()->pluck('name', 'id');
        $data['banks'] = Bank::where('status', 'active')->get()->pluck('display_name', 'id');
        $data['bank_branches'] = BankBranch::where('status', 'active')->get()->pluck('branch_name', 'id');

//        $data['roles'] = Role::filter()->get()->pluck('display_name', 'id');
        $data['document_types'] = DocumentType::active()->get()->pluck('name', 'id');
        $data['shifts'] = Shift::get();
        // dd($data);
        return view('member.employee.create', $data);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $d_count = EmployeeInfo::where('designation_id', $request->designation_id)
            ->whereMonth('created_at', date("m"))
            ->whereYear('created_at', date('Y'))
            ->count();

        if (!isset($request->employeeID) && empty($request->employeeID))
            $request['employeeID'] = generate_employee_id($d_count, $request->designation_id);

        $this->validate($request, $this->getValidationRules($request->designation_id));

        $inputs = [];
        $inputs['email'] = $email = $request->email;
        $inputs['full_name'] = $request->first_name . " " . $request->last_name;
        $inputs['phone'] = $request->phone;
        $roles = $request->roles ?? Role::where('name', 'User')->pluck('id')->toArray();
        $inputs['member_id'] = Auth::user()->member_id;
        $inputs['membership_id'] = Auth::user()->membership_id;
        $inputs['password'] = Hash::make($request->password);

        if (isset($request->company_id))
            $inputs['company_id'] = $request->company_id;
        else
            $inputs['company_id'] = Auth::user()->company_id;


        $inputs['verify_token'] = $verify_token = verify_token_generate();
        $inputs['status'] = $request->status ?? 'active';

        $submitted_documents = $request->submitted_documents ?? [];
        $submitted_files = $request->submitted_files ?? [];


        DB::beginTransaction();

        try {

            $inputs['support_pin'] = generate_pin();
            $saveUser = User::create($inputs);


            if (isset($roles))
                $saveUser->attachRoles($roles);

//          $employee = EmployeeInfo::latest()->first();


            $employeeInfo = new EmployeeInfo();
            $employeeInfo->employeeID = $request->employeeID;
            $employeeInfo->first_name = $request->first_name;
            $employeeInfo->last_name = $request->last_name;
            $employeeInfo->phone2 = $request->phone;
            $employeeInfo->address = $request->present_address;
            $employeeInfo->address2 = $request->permanent_address;
            $employeeInfo->nid = $request->nid;
            $employeeInfo->salary = $request->salary;
            $employeeInfo->commission = $request->commission;
            $employeeInfo->dob = db_date_format($request->dob);
            $employeeInfo->join_date = db_date_format($request->join_date);
            $employeeInfo->company_id = Auth::user()->company_id;
            $employeeInfo->designation_id = $request->designation_id;
            $employeeInfo->region_id = $request->region_id;
            $employeeInfo->area_id = $request->area_id;
            $employeeInfo->thana_id = $request->thana_id;
            $employeeInfo->district_id = $request->district_id;
            $employeeInfo->division_id = $request->division_id;
            $employeeInfo->bank_id = $request->bank_id;
            $employeeInfo->bank_branch_id = $request->bank_branch_id;

            $employeeInfo->nationality = $request->nationality;
            $employeeInfo->department_id = $request->department_id;
            $employeeInfo->shift_id = $request->shift_id;
            $employeeInfo->salary_system = $request->salary_system;
            $employeeInfo->weekend_accept = $request->weekend_accept;
            $employeeInfo->passport_number = $request->passport_number;
            $employeeInfo->passport_expire = db_date_format($request->passport_expire);
            $employeeInfo->visa_expire = db_date_format($request->visa_expire);
            $employeeInfo->pr_expire = db_date_format($request->pr_expire);
            $employeeInfo->diving_license = $request->diving_license;
            $employeeInfo->insurance_company = $request->insurance_company;
            $employeeInfo->insurance_number = $request->insurance_number;
            $employeeInfo->driving_expire = $request->driving_expire;
            $employeeInfo->pr_number = $request->pr_number;
            $employeeInfo->bank_account = $request->bank_account;
            $employeeInfo->bank_payment_type = $request->bank_payment_type;

            $employeeInfo->user_id = $saveUser->id;
            $employeeInfo->save();

            if (count($submitted_files) > 0) {
                foreach ($submitted_files as $key => $value) {

                    $key= str_replace("'", "", $key);

                    $document = $employeeInfo->getDocumentID($key);

                    $file = "";
                    $destinationPath = 'files';
                    $upload = $this->fileUploadWithDetails($value, $destinationPath);
                    $file = $upload['file_store_path'] . "/" . $upload['file_name'];

                    if ($file) {

                        $submitted_document = new EmployeeSubmittedDocument();
                        $submitted_document->employee_id = $employeeInfo->id;
                        $submitted_document->document_type_id = $document->id;
                        $submitted_document->attached = $file;
                        $submitted_document->save();

                    }
                }
            }


            $status = ['type' => 'success', 'message' => "Employee Created Successfully"];

        } catch (\Exception $e) {

            //  dd($e);
            $status = ['type' => 'danger', 'message' => 'Unable to User Add'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == "danger") {
            return back()->withInput()->with('status', $status);
        } else {
            return back()->with('status', $status);
        }

    }

    private function getValidationRules($designation_id, $id = '', $employeeID = '')
    {

        $designation = Designation::find($designation_id);
        $commission_area = $designation->commission_area;


        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'designation_id' => 'required',
            'join_date' => 'required|date_format:m/d/Y',
            'dob' => 'required|date_format:m/d/Y|',
            'passport_expire' => 'nullable|date_format:m/d/Y',
            'visa_expire' => 'nullable|date_format:m/d/Y',
            'pr_expire' => 'nullable|date_format:m/d/Y',
            'nid' => 'required|min:8|max:18',
            'passport_number' => 'nullable|min:7|max:12',
            'diving_license' => 'nullable|min:7|max:12',
            'pr_number' => 'nullable|min:7|max:12',
            'permanent_address' => 'required',
            'present_address' => 'required',
//            "roles" => "required",
            "commission" => "required",
            "salary" => "required",
        ];

        if (is_null($id)) {
            $rules['email'] = 'required|email|unique:users';
            $rules['phone'] = 'nullable|min:8|max:12|unique:users,phone';
            $rules['employeeID'] = 'nullable|numeric|digits_between:5,10|unique:employee_info,employeeID';
            $rules['password'] = 'required';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $id;
            $rules['phone'] = 'nullable|min:8|max:12|unique:users,phone,' . $id;
            $rules['employeeID'] = 'nullable|numeric|digits_between:5,10|unique:employee_info,employeeID,'.$employeeID;
        }

//        if ($commission_area == "division") {
//
//            $rules['division_id'] = 'required';
//
//        } else if ($commission_area == "region") {
//
//            $rules['division_id'] = 'required';
////            $rules['region_id'] = 'required';
//
//        } else if ($commission_area == "area") {
//
//            $rules['division_id'] = 'required';
////            $rules['region_id'] = 'required';
//            $rules['district_id'] = 'required';
////            $rules['thana_id'] = 'required';
//            $rules['area_id'] = 'required';
//
//        } else if ($commission_area == "thana") {
//
//            $rules['division_id'] = 'required';
////            $rules['region_id'] = 'required';
//            $rules['district_id'] = 'required';
////            $rules['thana_id'] = 'required';
//
//        } else if ($commission_area == "district") {
//
//            $rules['division_id'] = 'required';
////            $rules['region_id'] = 'required';
//            $rules['district_id'] = 'required';
//
//        }


        return $rules;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $data['employee'] = $employee = EmployeeInfo::with('user')->find($id);

        if (!$employee) {
            $status = ['type' => 'danger', 'message' => 'Employee Not find'];
            return back()->with('status', $status);
        }

        return view('member.employee.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $employee = EmployeeInfo::with('user')->find($id);
//        $user =  User::where('id', $employee->user_id)->first();

//        dd($employee);

        if (!$employee) {
            $status = ['type' => 'danger', 'message' => 'Employee Not find'];
            return back()->with('status', $status);
        }

        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['departments'] = Department::where('active_status', 1)->get();
        $data['banks'] = Bank::where('status', 'active')->get()->pluck('display_name', 'id');
        $data['bank_branches'] = BankBranch::where('bank_id', $employee->bank_id)->where('status', 'active')->get()->pluck('branch_name', 'id');
        $data['designations'] = Designation::where('department_id', $employee->department_id)
            ->where('active_status', 1)->get();
        $data['divisions'] = Division::where('country_id', $employee->nationality)->active()
            ->get()->pluck('name', 'id');

//        $data['regions'] = Region::active()->get()->pluck('name', 'id');
        $data['districts'] = District::where('division_id', $employee->division_id)->active()
            ->get()->pluck('name', 'id');

//        $data['thanas'] = Thana::active()->get()->pluck('name', 'id');
        $data['areas'] = Area::where('district_id', $employee->district_id)->active()
            ->get()->pluck('name', 'id');

        $data['document_types'] = DocumentType::active()->get()->pluck('name', 'id');
        $data['shifts'] = Shift::get();

        $data['submitted_documents'] = $employee->attached_file->pluck('document_type_id')->toArray();

//        $data['roles'] = Role::filter()->get()->pluck('display_name', 'id');

//        $data['assignRole'] = $employee->user->roles->pluck('id')->toArray();

        return view('member.employee.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        $d_count = EmployeeInfo::where('designation_id', $request->designation_id)
//        ->whereMonth('created_at', date("m"))
//        ->whereYear('created_at', date('Y'))
//        ->count();

        $employeeInfo = EmployeeInfo::where('id', $id)->first();
        $user = User::where('id', $employeeInfo->user_id)->first();

        if (!$employeeInfo) {
            $status = ['type' => 'danger', 'message' => 'Employee Not find'];
            return back()->with('status', $status);
        }

//        if (!isset($request->employeeID) && empty($request->employeeID))
//            $request['employeeID'] = generate_employee_id($d_count, $request->designation_id);

        $this->validate($request, $this->getValidationRules($request->designation_id, $user->id, $id));

        $user->email = $email = $request->email;
        $user->full_name = $request->first_name . " " . $request->last_name;
        $user->phone = $request->phone;
        // $user->status = $request->status;


//        $roles = $request->roles;

        DB::beginTransaction();

        try {

            $user->save();
//            $assignRole = $user->roles()->sync($roles);


            $employeeInfo->employeeID = $request->employeeID;
            $employeeInfo->first_name = $request->first_name;
            $employeeInfo->last_name = $request->last_name;
            $employeeInfo->phone2 = $request->phone;
            $employeeInfo->address = $request->present_address;
            $employeeInfo->address2 = $request->permanent_address;
            $employeeInfo->nid = $request->nid;
            $employeeInfo->salary = $request->salary;
            $employeeInfo->commission = $request->commission;
            $employeeInfo->dob = db_date_format($request->dob);
            $employeeInfo->join_date = db_date_format($request->join_date);
            $employeeInfo->designation_id = $request->designation_id;
            $employeeInfo->region_id = $request->region_id;
            $employeeInfo->area_id = $request->area_id;
            $employeeInfo->thana_id = $request->thana_id;
            $employeeInfo->district_id = $request->district_id;
            $employeeInfo->division_id = $request->division_id;
            $employeeInfo->bank_id = $request->bank_id;
            $employeeInfo->bank_branch_id = $request->bank_branch_id;


            $employeeInfo->nationality = $request->nationality;
            $employeeInfo->department_id = $request->department_id;
            $employeeInfo->shift_id = $request->shift_id;
            $employeeInfo->salary_system = $request->salary_system;
            $employeeInfo->weekend_accept = $request->weekend_accept;
            $employeeInfo->passport_number = $request->passport_number;
            $employeeInfo->passport_expire = db_date_format($request->passport_expire);
            $employeeInfo->visa_expire = db_date_format($request->visa_expire);
            $employeeInfo->pr_expire = db_date_format($request->pr_expire);
            $employeeInfo->diving_license = $request->diving_license;
            $employeeInfo->insurance_company = $request->insurance_company;
            $employeeInfo->insurance_number = $request->insurance_number;
            $employeeInfo->driving_expire = $request->driving_expire;
            $employeeInfo->pr_number = $request->pr_number;
            $employeeInfo->bank_account = $request->bank_account;
            $employeeInfo->bank_payment_type = $request->bank_payment_type;

            $employeeInfo->save();


            $submitted_documents = $request->submitted_documents ?? [];
            $submitted_files = $request->submitted_files ?? [];



            if (count($submitted_files) > 0) {
                foreach ($submitted_files as $key => $fileValue) {


                    $key= str_replace("'", "", $key);

                    $document = $employeeInfo->getDocumentID($key);

                    $file = "";
                    $destinationPath = 'files';
                    $upload = $this->fileUploadWithDetails($fileValue, $destinationPath);
                    $file = $upload['file_store_path'] . "/" . $upload['file_name'];

                    $hasFile = EmployeeSubmittedDocument::where('employee_id', $employeeInfo->id)->where('document_type_id', $document->id)->first();

                    if ($hasFile && $file != "" && $fileValue != "") {
                        EmployeeSubmittedDocument::where('employee_id', $employeeInfo->id)->where('document_type_id', $document->id)->update(['attached' => $file]);

                    } elseif ($file != "" && $fileValue != "") {
                        $submitted_document = new EmployeeSubmittedDocument();
                        $submitted_document->employee_id = $employeeInfo->id;
                        $submitted_document->document_type_id = $document->id;
                        $submitted_document->attached = $file;
                        $submitted_document->save();
                    }
                }
            }



            $status = ['type' => 'success', 'message' => 'Employee Updated Successfully'];

        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to Employee update'];
            DB::rollBack();
        }

        DB::commit();

        return back()->with('status', $status);
    }

    public function destroy($id)
    {
        $modal = EmployeeInfo::findOrFail($id);
        User::find($modal->user_id)->delete();
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    public function showDesignationByDeptId(Request $request){

           $designations = Designation::authCompany()->where('active_status', 1)
               ->where('department_id', $request->department_id)->get();

           return response()->json([
               'data'=>$designations,
               'status'=>200,
           ]);
   }


   public function showStateByCountryId(Request $request){

       // dd($request->department_id);

          $divisions = Division::where('country_id', $request->country_id)->where('active_status',1)->get();
          return response()->json([
              'data'=>$divisions,
              'status'=>200,
          ]);
  }

  public function showCityByStateId(Request $request){

       // dd($request->department_id);

          $districts = District::where('division_id', $request->division_id)->where('active_status',1)->get();
          return response()->json([
              'data'=>$districts,
              'status'=>200,
          ]);
  }

  public function showAreaByCityId(Request $request){

       // dd($request->department_id);

          $areas = Area::authCompany()->where('district_id', $request->district_id)->where('status','active')->get();
          return response()->json([
              'data'=>$areas,
              'status'=>200,
          ]);

 }
}