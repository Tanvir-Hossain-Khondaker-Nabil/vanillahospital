<?php

namespace App\Http\Controllers\Member;

use App\DataTables\ChequeEntryDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Models\Bank;
use App\Models\ChequeEntry;
use App\Models\SupplierOrCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChequeEntryController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ChequeEntryDataTable $dataTable)
    {

        return $dataTable->render('member.cheque_entry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['sharers'] = SupplierOrCustomer::authMember()->get()->pluck('name', 'id');
        $data['banks'] = Bank::authMember()->displayNameAsc()->get()->pluck('display_name', 'id');
        return view('member.cheque_entry.create', $data);
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

        $inputs = [];
        $inputs = $request->all();
        $sharer = SupplierOrCustomer::find($request->sharer_id);
        $inputs['payer_name'] = $sharer->name;
        $inputs['issue_status'] = 'pending';
        $inputs['giving_date'] = db_date_format($request->giving_date);
        $inputs['issue_date'] = db_date_format($request->issue_date);


        if($request->hasFile('file'))
        {
            $image = $request->file('file');

            $upload = $this->fileUpload($image, '/cheque_issues/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $inputs['file'] = $upload;
        }

        ChequeEntry::create($inputs);

        $status = ['type' => 'success', 'message' =>trans('common.successfully_added')];

        return redirect()->route('member.cheque_entries.index')->with('status', $status);
    }


    public function chequeTodaysQueue()
    {
        $data['cheque_list'] = ChequeEntry::where('issue_date', '<=',Carbon::today())->where('issue_status','!=','completed')->get();
        $data['issue_statuses'] = [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cheque_bounce' => 'Cheque Bounce',
            'canceled' => 'Canceled'
        ];
        return view('member.cheque_entry.today_cheque_list', $data);
    }

    public function changeChequeStatus(Request $request)
    {
        $cheque = ChequeEntry::find($request->id);
        if($request->status == 'cheque_bounce')
        {
            $cheque->issue_status = 'pending';
            $cheque->attempted_count = $cheque->attempted_count+1;
            $cheque->save();
        }else{
            $cheque->issue_status = $request->status;
            $cheque->attempted_count = $cheque->attempted_count+1;
            $cheque->save();
        }

        return response()->json([
            'data' => [
                'message' =>trans('common.successfully_deleted')
            ]
        ], 200);
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
        $data['model'] = ChequeEntry::findOrFail($id);
        $data['sharers'] = SupplierOrCustomer::authMember()->get()->pluck('name', 'id');
        $data['banks'] = Bank::authMember()->displayNameAsc()->get()->pluck('display_name', 'id');

        return view('member.cheque_entry.edit', $data);
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
        $this->validate($request, $this->getValidationRules($id));

        $updateEntry = ChequeEntry::findorFail($id);
        $inputs = [];
        $inputs = $request->all();
        $sharer = SupplierOrCustomer::find($request->sharer_id);
        $inputs['payer_name'] = $sharer->name;
        $inputs['giving_date'] = db_date_format($request->giving_date);
        $inputs['issue_date'] = db_date_format($request->issue_date);


        if($request->hasFile('file'))
        {
            $image = $request->file('file');

            $upload = $this->fileUpload($image, '/cheque_issues/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' =>trans('common.image_must_be').'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $inputs['file'] = $upload;
        }

        $updateEntry->update($inputs);

        $status = ['type' => 'success', 'message' =>trans('common.successfully_updated')];

        return redirect()->route('member.cheque_entries.index')->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = ChequeEntry::findOrFail($id);

        if($modal->issue_status == "pending")
        {
            $modal->delete();

            return response()->json([
                'data' => [
                    'message' => 'Successfully deleted'
                ]
            ], 200);

        }else{

            return response()->json([
                'data' => [
                    'message' => "You can\'t delete this because It\'s Issue Status already changed"
                ]
            ], 400);
        }
    }


    private function getValidationRules($id='')
    {
        $rules = [
            'sharer_id' => 'required',
            'bank_id' => 'required',
            'issue_date' => 'required',
            'giving_date' => 'required',
            'amount' => 'required|numeric',
        ];

        if($id)
        {
            $rules['cheque_no'] = 'required|unique:cheque_entries,cheque_no,'.$id;
        }else{

            $rules['cheque_no'] = 'required|unique:cheque_entries,cheque_no';
        }

        return $rules;
    }

}