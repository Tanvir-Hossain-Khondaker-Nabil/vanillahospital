<?php

namespace App\Http\Controllers\member;

use Illuminate\Http\Request;
use App\Models\HospitalService;
use App\Http\Controllers\Controller;
use App\Marketing_officer;
use App\Marketing_officer_commission;

class MarketingOfficerCommissionController extends Controller
{

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //  dd($request->all());
        $data['services'] = HospitalService::AuthCompany()->where('status', 'active')->get()->pluck('title', 'id')->toArray();

        //   dd($data);
        $data['marketing_officer_id'] = $request->marketing_officer_id;

        $data['marketingOfficer'] = Marketing_officer::AuthCompany()->where('id', $request->marketing_officer_id)->first();
        $data['comissions'] = Marketing_officer_commission::AuthCompany()->where('marketing_officer_id', $request->marketing_officer_id)->with('service')->get();
        // dd($data);
        return view('member.marketing_officer_commission.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $user_id = auth()->user()->id;
        $company_id = auth()->user()->company_id;
        $name = auth()->user()->full_name;

        $inputs['operator_id'] = $user_id;
        $inputs['operator_name'] = $name;
        $inputs['company_id'] = $company_id;
        $inputs['marketing_officer_id'] = $request->marketing_officer_id;

        foreach ($request->commission_type as $key => $item) {
            $inputs['commission_type'] = $item;
            $inputs['commission_amount'] = $request->commission_amount[$key];
            // dd($key);
            foreach ($request->service[$key] as $service) {
                $inputs['hospital_services_id'] = $service;
                $commissin =  Marketing_officer_commission::where([['marketing_officer_id', $request->marketing_officer_id], ['hospital_services_id', $service]])->first();

                if ($commissin == null) {
                    Marketing_officer_commission::create($inputs);
                    $status = ['type' => 'success', 'message' => 'Successfully Added'];
                } else {
                    $commissin->update($inputs);
                    $status = ['type' => 'success', 'message' => 'Successfully Updated'];
                }
            }
            // $key++;
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
        $data['doctor'] = Marketing_officer::AuthCompany()->where('id', $id)->first();
        $data['comissions'] = DoctorComission::AuthCompany()->where('doctor_id', $id)->with(['doctor', 'comission', 'testGroup'])->get();

        // dd($id,$data);
        return view('member.marketing_officer.index', $data);
    }


    public function destroy($id)
    {
        $modal = Marketing_officer_commission::findOrFail($id);
        $marketing_officer_id = $modal->marketing_officer_id;
        $modal->delete();

        return response()->json([
            'data' => [
                "url" => route('member.marketing_officer_commissions.create').'?marketing_officer_id='.$marketing_officer_id,
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);
    }
}
