<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client, LeadCategory, ClientCompany, Label, Labeling, Lead, LeadStatus};
use App\DataTables\LeadDataTable;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeadDataTable $dataTable)
    {
        //    $data = Lead::authCompany()->with(['client','leadStatus','labeling','clientCompany','leadCategory'])->get();
        // //    dd($data[2]->leadStatus[0]->lead_status);
        //    dd($data);

        return $dataTable->render('member.lead.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['client'] = Client::all()->pluck('name', 'id');
        $data['lead_category'] = LeadCategory::all()->pluck('display_name', 'id');
        $data['company'] = ClientCompany::where('type', 'project')->pluck('company_name', 'id');
        $data['label'] = Label::where('label_type', 'lead')->pluck('name', 'id');
        $data['lead_status'] = Lead::get_statuses();
        // dd($data);
        return view('member.lead.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, $this->validationRules());

        $user_id = \Auth::user()->id;
        $company_id = \Auth::user()->company_id;


        $lead = Lead::latest()->first();
        $lastId = $lead ? $lead->id + 1 : 1;

        $inputs = $request->all();
        $inputs['lead_code'] = code_generate($lastId, $user_id, "L");
        $inputs['created_by'] = $user_id;
        $inputs['company_id'] = $company_id;
        $lead = Lead::create($inputs);

        if ($lead) {

            foreach ($request->label_id as $id) {
                $label = Label::findOrFail($id);
                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $lead->id;
                $labelingInputs['modal'] = 'Lead';
                $labelingInputs['created_by'] = $user_id;
                $labelingInputs['company_id'] = $company_id;
                Labeling::create($labelingInputs);

            }

            $leadStatusInput['lead_id'] = $lead->id;
            $leadStatusInput['lead_status'] = $request->lead_status;
            $leadStatusInput['created_by'] = \Auth::user()->id;
            $leadStatusInput['company_id'] = \Auth::user()->company_id;
            LeadStatus::create($leadStatusInput);
        }
        $status = ['type' => 'success', 'message' => 'Successfully Added'];

        return back()->with('status', $status);

    }

    public function validationRules($id = '')
    {

//        if($id==''){
//            $rules = [
//                'name' => 'required|unique:leads,name',
//                'lead_category_id' => 'required',
//            ];
//        }else{
        $rules = [
            'title' => 'required',
            'lead_category_id' => 'required',
        ];
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
        // dd($id);
        $data['lead'] = $dd = Lead::where('id', $id)->with('leadStatus', 'client', 'clientCompany', 'leadCategory')->first();
        //   dd($dd);
        //   dd($dd->client->name);
        return view('member.lead.show', $data);
    }

    public function showLead(Request $request)
    {
        // return $request->all();
        $data = Lead::where('id', $request->id)->with('client')->first();

        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = Lead::findOrFail($id);
        $data['client'] = Client::all()->pluck('name', 'id');
        $data['lead_category'] = LeadCategory::all()->pluck('display_name', 'id');
        $data['company'] = ClientCompany::where('type', 'project')->pluck('company_name', 'id');
        $data['label'] = Label::where('label_type', 'lead')->pluck('name', 'id');
        $data['labeling'] = Labeling::where('modal', 'Lead')->where('modal_id', $id)
            ->pluck('label_id')->toArray();
        $data['lead_status'] = Lead::get_statuses();

        return view('member.lead.edit', $data);
    }

    public function editLeadStatus(Request $request)
    {
        $leadData = LeadStatus::where('lead_id',$request->lead_id)->latest()->first();

        $leadStatusInput['lead_id'] = $request->lead_id;
        $leadStatusInput['comment'] = $request->comment ?? '';
        $leadStatusInput['lead_status'] = $request->lead_status ?? $leadData->lead_status;
        $leadStatusInput['created_by'] = \Auth::user()->id;
        $leadStatusInput['company_id'] = \Auth::user()->company_id;
        // dd($request->all());
        LeadStatus::create($leadStatusInput);

        return response()->json([
            'status' => 200,
        ]);
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

        $this->validate($request, $this->validationRules());

        $lead = Lead::findOrFail($id);

        $user_id = \Auth::user()->id;

        $inputs = $request->all();
        $inputs['lead_code'] = code_generate($id, $user_id, "L");
        $inputs['updated_by'] = \Auth::user()->id;

        $leadUpdate = $lead->update($inputs);

        if ($leadUpdate && isset($request->label_id)) {

            Labeling::where('modal', 'Lead')->where('modal_id', $lead->id)->delete();

            foreach ($request->label_id as $id) {

                $label = Label::findOrFail($id);

                $labelingInputs['label_id'] = $label->id;
                $labelingInputs['modal_id'] = $lead->id;
                $labelingInputs['modal'] = 'Lead';
                $labelingInputs['created_by'] = \Auth::user()->id;
                $labelingInputs['company_id'] = \Auth::user()->company_id;

                Labeling::create($labelingInputs);

            }

            $leadStatusInput['lead_id'] = $lead->id;
            $leadStatusInput['lead_status'] = $request->lead_status;
            $leadStatusInput['created_by'] = \Auth::user()->id;
            $leadStatusInput['company_id'] = \Auth::user()->company_id;

            LeadStatus::create($leadStatusInput);
        }

        $status = ['type' => 'success', 'message' => 'Successfully Updated'];

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
        //
    }
}