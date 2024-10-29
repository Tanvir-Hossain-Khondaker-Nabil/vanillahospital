<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RolesDataTable;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('admin.roles.index');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['rolePermissions'] = [];
        $data['permissions'] = Permission::active()->orderBy('group_name','asc')
            ->orderBy('display_name','asc')->get();

        return view('admin.roles.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|unique:roles,name',
            'display_name' => 'required|unique:roles,display_name',
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        $this->validate($request, $rules, $customMessages);

        $inputs = $request->all();

        $role = Role::create($inputs);

        $role->perms()->sync($request->input('permissions'));

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
        $data['role'] = $role = Role::findOrFail($id);

        $data['permissions'] = Permission::active()->orderBy('group_name','asc')
                                        ->orderBy('display_name','asc')->get();
        $data['rolePermissions'] = $role->perms()->pluck('id')->toArray();

        return view('admin.roles.edit', $data);
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
        $roles = Role::findOrFail($id);

        $inputs = $request->all();

        $roles->update($inputs);

        $roles->perms()->sync($request->input('permissions'));

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
        $modal = Role::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }


    public function role_permission()
    {
        $data['roles'] =  Role::get();
        $data['permissions'] = Permission::get();


    }
}
