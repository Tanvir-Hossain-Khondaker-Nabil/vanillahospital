<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PermissionsDataTable;
use App\Models\Permission;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('admin.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['groups'] = Permission::groupBy('group_name')->pluck('group_name');

        return view('admin.permissions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $a = str_replace('-',' ', $request->name);
        $b = str_replace('_', ' ',$a );
        $c = str_replace('/', ' ',$b );

        $request['name'] = str_replace(' ', '_', strtolower($c));
        $request['display_name'] = ucfirst(str_replace('_', ' ',$c ));

        $this->validate($request, [
            'group_name' => 'required',
            'name' => 'required|unique:permissions,name',
            'display_name' => 'required',
        ]);

        $inputs = $request->all();

        Permission::create($inputs);

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
        $data['permission'] = Permission::findOrFail($id);
        $data['groups'] = Permission::groupBy('group_name')->pluck('group_name');

        return view('admin.permissions.edit', $data);
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
        $permissions = Permission::findOrFail($id);

//        $a = str_replace('-',' ', $request->name);
//        $b = str_replace('_', ' ',$a );
//        $c = str_replace('/', ' ',$b );
//
//        $request['name'] = str_replace(' ', '_', strtolower($c));
//        $request['display_name'] = ucfirst(str_replace('_', ' ',$c ));


        $this->validate($request, [
            'group_name' => 'required',
            'display_name' => 'required',
        ]);

        $inputs = $request->all();

        $permissions->update($inputs);

        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

        return back()->with('status', $status);
    }


    public function generate_permission()
    {
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as  $value) {

            $route = $value->uri();
            $name = $value->getName();
            $action = $value->getActionName();

            $checkMain = explode('.',$name);

            if(
                ($checkMain[0] == "member" || $checkMain[0] == "admin")
                && $checkMain[1] != 'signin' && $checkMain[1] != 'support_pin_login'
                &&  $checkMain[1] != 'memberships' && $checkMain[1] != 'packages'
                &&  $checkMain[1] != 'permissions' && $checkMain[1] != 'payment_methods'
                &&  $checkMain[1] != 'transaction_categories' && $checkMain[1] != 'members'
            )
            {
                $module = $checkMain[1];
                $action = isset($checkMain[2]) ? $checkMain[2] : "";

                if($action == "index"){
                    $action = "List/All";
                }elseif($action == "destroy"){
                    $action = "Delete";
                }
                $action = ucfirst(normal_writing_format($action));

                $inputs = [];
                $inputs['group_name'] = $module;
                $inputs['name'] = $name;
                $inputs['display_name'] = $action != "" ? $action : ucfirst(normal_writing_format($module));

//                if($module == "stock_reconcile")
//                    dd($inputs);


                Permission::firstOrCreate($inputs);
            }
        }


        $status = ['type' => 'success', 'message' => 'Successfully Updated.'];

        echo "Successfully Generated.";

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
}
