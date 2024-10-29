<?php

namespace App\Http\Controllers\Member;

use App\DataTables\QuoteAttentionDataTable;
use App\Models\QuotationCompany;
use App\Models\QuoteAttention;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuotationCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuoteAttentionDataTable $dataTable)
    {
        return $dataTable->render('member.quote_attentions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['quotationers'] = QuotationCompany::active()->pluck('company_name', 'id');
        return view('member.quote_attentions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $this->validate($request, $this->rules());

        $inputs['company_id'] = Auth::user()->company_id;
        QuoteAttention::create($inputs);

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
        $data['quotationers'] = QuotationCompany::active()->pluck('company_name', 'id');
        $data['modal'] = QuoteAttention::findorFail($id);

        return view('member.quote_attentions.edit', $data);
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
        $terms = QuoteAttention::find($id);
        $inputs = $request->all();

        $inputs['company_id'] = Auth::user()->company_id;
        $this->validate($request, $this->rules($id));
        $terms->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

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
        $modal = QuoteAttention::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    private function rules($id='')
    {
        $rules = [
            'department' => 'required',
            'name' => 'required',
            'designation' => 'required',
        ];


        return $rules;
    }
}
