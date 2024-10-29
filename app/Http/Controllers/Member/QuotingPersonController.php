<?php

namespace App\Http\Controllers\Member;

use App\DataTables\QuotingDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Models\Quoting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuotingPersonController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuotingDataTable $dataTable)
    {
        return $dataTable->render('member.quoting.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.quoting.create');
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


        if($request->hasFile('seal'))
        {
            $image = $request->file('seal');

            $upload = $this->fileUpload($image, '/seal/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $inputs['seal'] = $upload;
        }

        Quoting::create($inputs);

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
        $data['modal'] = Quoting::findorFail($id);

        return view('member.quoting.edit', $data);
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
        $terms = Quoting::find($id);
        $inputs = $request->all();

        $inputs['company_id'] = Auth::user()->company_id;
        $this->validate($request, $this->rules($id));


        if($request->hasFile('seal'))
        {
            $image = $request->file('seal');

            $upload = $this->fileUpload($image, '/seal/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $inputs['seal'] = $upload;
        }

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
        $modal = Quoting::findOrFail($id);
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
            'name' => 'required',
            'designation' => 'required',
            'contact' => 'required',
        ];

        return $rules;
    }
}
