<?php

namespace App\DataTables;

use App\Models\Membership;
use Yajra\DataTables\Services\DataTable;

class PackagesDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('time_period', function($row) {
                return $row->time_period.($row->time_period < 2 ? " Month":" Months") ;
            })
            ->editColumn('discount', function($row) {
                return $row->discount >0 ? $row->discount.( $row->discount_type == 'Percentage' ? "%":"(Fixed)") : "No Discount" ;
            })
            ->addColumn('action', function($membership) {
                return view('common._edit-button', ['model' => $membership, 'route' => 'admin.packages']);
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Membership::query();

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
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
            [
                'name' => 'display_text',
                'data' => 'display_text',
                'title' => 'Title',
            ],
            [
                'name' => 'price',
                'data' => 'price',
                'title' => 'Price (TK)',
            ],
            [
                'name' => 'time_period',
                'data' => 'time_period',
                'title' => 'Time Period',
            ],
            'discount',
            'total_users',
            'created_at',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Memberships_' . date('YmdHis');
    }
}
