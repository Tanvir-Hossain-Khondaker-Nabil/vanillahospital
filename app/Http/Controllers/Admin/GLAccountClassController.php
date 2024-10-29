<?php

namespace App\Http\Controllers\Admin;

use App\Models\GLAccountClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GLAccountClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data['gl_account_classes'] = GLAccountClass::paginate(10);

        return view('admin.gl_account_class.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['class_type'] = $this->class_types();
        return view('admin.gl_account_class.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        GLAccountClass::create($request->all());

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
        $data['modal'] = GLAccountClass::find($id);

        if(!$data['modal']){
            $status = ['type' => 'danger', 'message' => 'Unable to Find data.'];
            return back()->with('status', $status);
        }

        $data['class_type'] = $this->class_types();

        return view('admin.gl_account_class.edit', $data);
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
        $gl_account_class = GLAccountClass::find($id);
        $this->validate($request, $this->rules($id));

        $gl_account_class->update($request->all());

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
        $modal = GLAccountClass::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    private function class_types()
    {
        $value = [
            'Assets' => 'Assets',
            'Liabilities' => 'Liabilities',
            'Income' => 'Income',
            'Expense' => 'Expense',
            'Equity' => 'Equity',
            'Cost of Goods Sold' => 'Cost of Goods Sold',
        ];

        return $value;
    }

    private function rules($id='')
    {
        if(is_null($id))
        {
            $data =  [
                'name' => 'required|unique:gl_account_classes,name',
                'class_type' => 'required'
            ];
        }else{
            $data =  [
                'name' => 'required|unique:gl_account_classes,name,'.$id,
                'class_type' => 'required'
            ];
        }

        return $data;
    }
}