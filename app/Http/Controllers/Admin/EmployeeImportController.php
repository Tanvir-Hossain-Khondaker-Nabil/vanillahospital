<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmployeeInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeImportController extends Controller
{

    public function create(){

        return view('admin.import.create');
    }

    public function store(Request $request){

        $this->validate($request, [
            'import_file'  => 'required|mimes:xls,xlsx'
        ]);

        if($request->hasFile('import_file'))
        {
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {

                $data = $reader->toArray();

                $employee = [];
                $employee['employeeID'] = generate_employee_id(1, 1);

                foreach ($data as $key => $row) {
                    $employee = [];
                    $employee['item_name'] = ucfirst($row['product_name']);
                    $employee['unit'] = $row['unit'];
                    $employee['category_id'] = 1;
                    $employee['member_id'] = Auth::user()->member_id;
                    $employee['company_id'] = Auth::user()->company_id;
                    $employee['created_by'] = Auth::user()->id;
                    $employee['price'] = $row['price'];
                    $employee['status'] = 'active';

                    EmployeeInfo::create($employee);
                }

            });

            $status = ['type' => 'success', 'message' => 'Employee import Successfully'];

        }else{

            $status = ['type' => 'danger', 'message' => 'Unable to import product'];

        }


        return back()->with('status', $status);
    }


    /*
     * product Import Sample
     */
    public function sample(){

        return response()->download(public_path('sample_excel/employee.xlsx'));
    }

}
