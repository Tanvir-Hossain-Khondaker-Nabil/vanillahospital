<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MembersDataTable;
use App\Http\Traits\SmsTrait;
use App\Models\Company;
use App\Models\Country;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Role;
use App\Models\User;
use App\Notifications\VerifyUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\VerifyUserTrait;

class MemberController extends Controller
{
    use VerifyUserTrait, SmsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MembersDataTable $dataTable)
    {
        return $dataTable->render('admin.members.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $packages = Membership::where('status', 'active')->get();
        $data['memberships'] = $packages->pluck('packages', 'id');
        $data['countries'] = Country::pluck('countryName', 'id');


        return view('admin.members.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, $this->getValidationRules());

        $member = array();
        $member['member_code'] = member_code_generate();
        $member['api_access_key'] = api_access_key_generate($request->full_name);
        $member['membership_id'] = $request->membership_id;
        $packages = Membership::find($request->membership_id);

        if($packages->time_period == 0)
        {
            $member['expire_date'] = Carbon::maxValue();
        }else{
            $member['expire_date'] = Carbon::now()->addMonths($packages->time_period);
        }
        $member['country_id'] = $request->country_id;
        $member['status'] = $request->status;

        DB::beginTransaction();

        try{
            $saveMember = Member::create($member);
//            $saveMember->save();


            // Set Member As a User
            $user = array();
            $user['full_name'] = $request->full_name;
            $user['email'] = $email = $request->email;
            $user['phone'] = $request->phone;
            $user['member_id'] = $saveMember->id;
            $user['membership_id'] = $request->membership_id;
            $user['verify_token'] = $verify_token = verify_token_generate();
            $user['status'] = $request->status;
            $saveUser = $saveMember->users()->create($user);
//            $saveUser->save();

            $company = [];
            $company['company_name'] = $request->company_name;
            $company['phone'] = $phone_number = phone_number_format($user['phone']);
            $company['email'] = $user['email'];
            $company['address'] = $request->address;
            $company['country_id'] = $member['country_id'];
            $company['membership_id'] = $member['membership_id'];
            $company['status'] = 'active';
            $company['member_id'] =  $user['member_id'];

            $saveCompany = Company::create($company);

            $saveCompany->member_id =  $user['member_id'];
            $saveCompany->save();

            $saveUser->company_id = $saveCompany->id;
            $saveUser->save();

            // Find Master Member Role
            $memberRole = Role::where('name', 'admin')->first();
            $assignRole = $saveUser->roles()->attach($memberRole->id);


            $msg = "Thanks for your registration in Hisabe.";
            $this->sendSMS($phone_number, $msg);
            $this->sendAdminSMS($saveUser->full_name, $phone_number);

//            $this->sendVerifyToken($verify_token, $email);

            $status = ['type' => 'success', 'message' => 'Member Added Successfully'];
            DB::commit();

        }catch (\Exception $e){

            DB::rollBack();
            $status = ['type' => 'danger', 'message' => 'Unable to create Member'];
        }



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
        $data['member'] = $member = Member::findOrFail($id);
        $data['user'] = $member->users()->first();
        $packages = Membership::where('status', 'active')->get();
        $data['memberships'] = $packages->pluck('packages', 'id');
        $data['countries'] = Country::pluck('countryName', 'id');

        return view('admin.members.edit', $data);
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

        $findMember = Member::findOrFail($id);
        $masterUser = User::where('member_id', $id)->first();
        $user_id = $findMember->users()->first()->id;

        $this->validate($request, $this->getValidationRules($user_id, $masterUser->company_id));

        $member = array();
        $member['membership_id'] = $request->membership_id;
        $packages = Membership::find($request->membership_id);


        if($packages->time_period == 0)
        {
            $member['expire_date'] = Carbon::maxValue();
        }else{
            $member['expire_date'] = Carbon::now()->addMonths($packages->time_period);
        }
        $member['status'] = $request->status;


        $saveMember = $findMember->update($member);


//            print_r($request->full_name); exit;
        // Set Member As a User
        $masterUser->full_name = $request->full_name;
        $masterUser->email = $request->email;
        $masterUser->phone = $request->phone;
        $masterUser->status = $request->status;
        $masterUser->save();


        $status = ['type' => 'success', 'message' => 'Member Updated Successfully'];

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


    private function getValidationRules($id='', $company_id = "")
    {

        if($company_id)
        {

            $rules = [
                'full_name' => 'required',
                'membership_id' => 'required',
            ];

        }else{
            $rules = [
                'full_name' => 'required',
                'membership_id' => 'required',
                'company_name' => 'required',
                'address' => 'required',
            ];

        }

        if (is_null($id)) {
            $rules['email'] = 'required|email|unique:users';
            $rules['phone'] = 'nullable|unique:users,phone';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $id;
            $rules['phone'] = 'nullable|unique:users,phone,' . $id;
        }

        return $rules;
    }
}
