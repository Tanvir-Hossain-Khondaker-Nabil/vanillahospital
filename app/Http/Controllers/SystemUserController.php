<?php

namespace App\Http\Controllers;

use App\DataTables\SystemUsersDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Item;

class SystemUserController extends Controller
{

    public function create()
    {
        return view('user-db.check_system_users');
    }

    public function index(Request $request, SystemUsersDataTable $dataTable)
    {
        if($request->password == "#isebi@2023")
        {
            return $dataTable->render('system-users.list');
        }
        else{
            Session::flash('type', 'danger');
            Session::flash('message', 'Your Password Not Match');

            return redirect()->back();
        }
    }
    
    public function show($id)
    {
        
        
    }

}
