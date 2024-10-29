<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class MemberUsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('company_name', function ($user){
                return $user->company_id>0 ? $user->company->company_name : '';
            })
            ->addColumn('roles', function ($user){
                return $user->roles ? $user->roles->pluck('display_name')->toArray() : '';
            })
            ->addColumn('action', function($user) {
                return view('common._action-button', ['model' => $user, 'route' => 'member.users']);
            })
            ->addColumn('change_password', function($user) {
                return  '<a href="'.route('member.users.change_password', $user->id).'" class="btn btn-xs btn-warning">
                            <i class="fa fa-key"></i>
                        </a>';
            })
            ->addColumn('company_manage', function($user) {
                return $user->company_id ? '<a href="'.route('member.company.edit', $user->company_id).'" class="btn btn-xs btn-info">
                            <i class="fa fa-edit"></i>
                        </a>' : "";
            })
            ->rawColumns(['change_password','company_manage', 'action'])
            ->make(true);
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = User::membersUser()->authCompany();

        if(!Auth::user()->hasRole(['super-admin']))
            $query = $query->systemUser();

        $query = $query->latest()->with(['roles']);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'full_name',
            'email',
            'phone',
            'company_name',
            'roles',
            'company_manage',
            'change_password'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MemberUsers_' . date('YmdHis');
    }
}
