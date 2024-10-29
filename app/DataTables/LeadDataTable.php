<?php

namespace App\DataTables;

use App\Models\Labeling;
use App\Models\Lead;

use Yajra\DataTables\Services\DataTable;

class LeadDataTable extends DataTable
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
            ->editColumn('client', function ($model) {

                return $model->client ? $model->client->name : "";
            })
            ->editColumn('leadCategory', function ($model) {

                return $model->leadCategory ? $model->leadCategory->display_name : "";
            })
            ->editColumn('clientCompany', function ($model) {

                return $model->clientCompany ? $model->clientCompany->company_name : "";
            })
            ->editColumn('leadStatus', function ($model) {

                return $model->leadStatus ? $model->leadStatus->last()->lead_status : "";

            })
            ->editColumn('labeling', function ($model) {
                $label = '';
                $labeling = Labeling::where('modal', 'Lead')->where('modal_id', $model->id)->get();
                foreach ($labeling as $val) {

                    $label .= "<div class='form-group ml-2'><span style='background-color:" . $val->label->bg_color . "' class='mt-2 badge large p-2'
                    data-color='#83c340'>" . $val->label->name . "</span>
                    </div>";
                }

                return $label;

            })
            ->editColumn('changeStatus', function ($model) {
                // $lead = 'lead';
            //     return "<select class='form-control change-lead-status' data-id='" . $model->id . "' name='lead_status' onchange='changeStatus(this)'>
            //     <option value=''>Change Status</option>
            //     <option value='Qualified'>Qualified</option>
            //     <option value='Discussion'>Discussion</option>
            //     <option value='Negotiation'>Negotiation</option>
            //     <option value='Won'>Won</option>
            //     <option value='Lost'>Lost</option>
            //     <option value='Canceled'>Canceled</option>
            //   </select>";
            return "<button data-id='" . $model->id . "' data-leadStatus='" . $model->status . "' name='lead_status' class='change-lead-status btn btn-xs btn-success' onclick='changeStatus(this)'>Change Status</button>";
            })
            ->addColumn('action', function ($model) {
                return view('common._all_action-button', ['model' => $model, 'route' => 'member.lead']);
            })
            ->rawColumns(['labeling', 'changeStatus', 'client', 'leadStatus', 'clientCompany', 'leadCategory', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Lead::authCompany()->with(['client', 'leadStatus', 'labeling', 'clientCompany', 'leadCategory']);

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
            'title',
            'client' => ['data' => 'client', 'name' => 'client.name', 'title' => 'Client Name'],
            ['data' => 'leadCategory', 'name' => 'leadCategory.display_name', 'title' => 'Category'],
            ['data' => 'clientCompany', 'name' => 'clientCompany.company_name', 'title' => 'Company Name'],
            ['data' => 'leadStatus', 'name' => 'leadStatus.lead_status', 'title' => 'Status'],
            ['data' => 'labeling', 'title' => 'Label', 'searchable' => false, 'orderable' => false],
            'changeStatus' => ['title' => 'Change Status','searchable'=>false, 'orderable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Lead_' . date('YmdHis');
    }
}