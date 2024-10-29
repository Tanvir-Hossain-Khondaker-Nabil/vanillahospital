<?php

namespace App\Http\Controllers\Member;

use App\DataTables\QuotationTermDataTable;
use App\Models\QuotationTerm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuotationTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuotationTermDataTable $dataTable)
    {
        return $dataTable->render('member.quotation_terms.parent.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.quotation_terms.parent.create');
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
        QuotationTerm::create($inputs);

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
        $data['modal'] = QuotationTerm::findorFail($id);

        return view('member.quotation_terms.parent.edit', $data);
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
        $terms = QuotationTerm::find($id);
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
        $modal = QuotationTerm::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    private function rules($id='')
    {
        if($id==''){
            $rules = [
                'name' => 'required',
                'short_tag' => 'required|unique:quotation_terms,short_tag',
            ];
        }else{
            $rules = [
                'name' => 'required',
                'short_tag' => 'required|unique:quotation_terms,short_tag,'.$id,
            ];
        }

        return $rules;
    }
}
