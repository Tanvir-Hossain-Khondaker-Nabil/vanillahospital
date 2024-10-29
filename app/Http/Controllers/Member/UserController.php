<?php

namespace App\Http\Controllers\Member;

use App\DataTables\MemberUsersDataTable;
use App\Http\Traits\VerifyUserTrait;
use App\Models\Company;
use App\Models\EmployeeInfo;
use App\Models\Role;
use App\Models\User;
use App\Models\Task;
use App\Models\EmployeeProject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierOrCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use VerifyUserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MemberUsersDataTable $dataTable)
    {
        return $dataTable->render('member.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['roles'] = Role::filter()->get()->pluck('display_name', 'id');
        $data['companies'] = Company::active()->authMember()->get()->pluck('company_name', 'id');
        $data['sharers'] = SupplierOrCustomer::active()->authMember()->get()->pluck('customer_details', 'id');
        return view('member.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules());

        $inputs = $request->all();
        $email = $request->email;
        $roles = $request->roles;
        $inputs['member_id'] = Auth::user()->member_id;
        $inputs['membership_id'] = Auth::user()->membership_id;
        $inputs['password'] = Hash::make($request->password);

        if (isset($request->company_id))
            $inputs['company_id'] = $request->company_id;
        else
            $inputs['company_id'] = Auth::user()->company_id;


        $inputs['verify_token'] = $verify_token = verify_token_generate();
        $inputs['status'] = $request->status ?? 'active';

//        DB::beginTransaction();
//
//        try{

        $inputs['support_pin'] = generate_pin();
        $saveUser = User::create($inputs);


//            if(Auth::user()->can(['super-admin'])){
//
//                $company = [];
//                $company['company_name'] = $inputs['company_name'];
//                $company['phone'] = $phone_number = phone_number_format($inputs['phone']);
//                $company['email'] = $inputs['email'];
//                $company['address'] = $inputs['address'];
//                $company['country_id'] = $inputs['country_id'];
//                $member['membership_id'] = $inputs['membership_id'];
//                $company['status'] = 'active';
//                $company['created_by'] = $saveUser->id;
//                $company['member_id'] = $inputs['member_id'];
//                $saveCompany = Company::create($company);
//
//                $saveUser->company_id = $saveCompany->id;
//            }else{
//
//                $saveUser->company_id = Auth::user()->company_id;
//            }


//            $saveUser->save();

        $assignRole = $saveUser->attachRoles($roles);

        if (isset($request->customer_id)) {
            $customer_id = $request->customer_id;
            $sharer = SupplierOrCustomer::find($customer_id);
            $sharer->user_id = $saveUser->id;
            $sharer->save();
        }

//            $this->sendVerifyToken($verify_token, $email, 'User');
        $status = ['type' => 'success', 'message' => "User Added Successfully"];

//        }catch (\Exception $e){
//
//            $status = ['type' => 'danger', 'message' => 'Unable to User Add'];
//            DB::rollBack();
//        }
//
//        DB::commit();

        return back()->with('status', $status);

    }

    private function getValidationRules($id = '')
    {
        $rules = [
            'full_name' => 'required',
            "roles" => "required",
        ];

        if (is_null($id)) {
            $rules['email'] = 'required|email|unique:users';
            $rules['phone'] = 'nullable|unique:users,phone';
            $rules['password'] = 'required';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $id;
            $rules['phone'] = 'nullable|unique:users,phone,' . $id;
        }

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $user = User::where('id', $id)->membersUser()->first();
        $data['companies'] = Company::active()->authMember()->get()->pluck('company_name', 'id');
        $data['sharers'] = SupplierOrCustomer::active()->authMember()->get()->pluck('customer_details', 'id');

        if (!$user) {
            $status = ['type' => 'danger', 'message' => 'User Not find'];
            return back()->with('status', $status);
        }

        $data['roles'] = Role::filter()->get()->pluck('display_name', 'id');
        $data['assignRole'] = $user->roles->pluck('id')->toArray();

        return view('member.users.edit', $data);
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
        $user = User::where('id', $id)->membersUser()->first();

        if (!$user) {
            $status = ['type' => 'danger', 'message' => 'User Not find'];
            return back()->with('status', $status);
        }

        $this->validate($request, $this->getValidationRules($id));

        $user->email = $email = $request->email;
        $user->full_name = $request->full_name;
        $user->phone = $request->phone;
        // $user->status = $request->status;

        if (isset($request->company_id))
            $user->company_id = $request->company_id;


        if (isset($request->new_password) && !empty($request->new_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $roles = $request->roles;

        DB::beginTransaction();

        try {

            $user->save();
            $assignRole = $user->roles()->sync($roles);

            $employeeInfo = EmployeeInfo::where('user_id', $user->id)->first();

            if($employeeInfo)
            {

            }

            if (isset($request->customer_id)) {
                $customer_id = $request->customer_id;
                $sharer = SupplierOrCustomer::find($customer_id);
                $sharer->user_id = $user->id;
                $sharer->save();
            }

            $status = ['type' => 'success', 'message' => 'User Updated Successfully'];

        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to User update'];
            DB::rollBack();
        }

        DB::commit();

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = User::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    /**
     * Show the form for change Password.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function change_password($id)
    {
        $data['model'] = $user = User::where('id', $id)->membersUser()->first();

        if (!$user) {
            $status = ['type' => 'danger', 'message' => 'User Not find'];
            return back()->with('status', $status);
        }

        return view('member.users.change_password', $data);
    }

    /**
     * Update the change password in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update_change_password(Request $request, $id)
    {

        if ($request->reset_pass != $request->confirm_reset_pass) {
            $status = ['type' => 'danger', 'message' => 'Password Not Matched'];
            return back()->with('status', $status);
        }

        $user = User::where('id', $id)->membersUser()->first();

        $rules = [
            'reset_pass' => 'required',
            'confirm_reset_pass' => 'required',
        ];

        $this->validate($request, $rules);


        if (!$user) {
            $status = ['type' => 'danger', 'message' => 'User Not find'];
            return back()->with('status', $status);
        }

        $user->password = Hash::make($request->reset_pass);
        $user->save();

        $status = ['type' => 'success', 'message' => 'User Updated Successfully'];

        return back()->with('status', $status);
    }

    public function set_users_company()
    {
        $data = [];
        $data['companies'] = Company::active()->authMember()->get()->pluck('company_name', 'id');

        $users_company = User::active()->membersUser()->authCompany();

        if (!Auth::user()->hasRole(['super-admin', 'developer'])) {
            $users_company = $users_company->systemUser();
        }

        $data['users'] = $users_company->get()->pluck('user_details', 'id');
        $data['users_company'] = $users_company->paginate(10);

        return view('member.users.company_assign', $data);
    }

    public function save_users_company(Request $request)
    {
        $rules = [
            'user_id' => 'required'
        ];

        if (Auth::user()->hasRole(['developer', 'super-admin']) == false) {
            $rules['company_id'] = 'required';
        }

        $this->validate($request, $rules);

        $user = User::membersUser()->where('id', $request->user_id)->first();
        $user->company_id = $request->company_id;
        $user->save();

        $status = ['type' => 'success', 'message' => 'Successfully User Add Company ' . $user->full_name . " " . $user->email];

        return back()->with('status', $status);
    }

}
