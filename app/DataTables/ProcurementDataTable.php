<?php

namespace App\DataTables;

use App\Models\Procurement;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class ProcurementDataTable extends DataTable
{
    public $count = 1;
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('id', function(){
                return $this->count++;
            })
            ->editColumn('status', function($modal){
                $status = $modal->status == 0 ? 'Pending' : ($modal->status == 1 ? 'Approved' : 'Not Approved');
                return "<span class='badge'>{$status}</span>";
            })
            ->editColumn('updated_at', function($modal){
                $html = "<ul>";
                foreach ($modal->procurement_details as $key => $value) {
                    $html .= "<li>{$value->item->item_name}</li>";
                }
                $html .= "</ul>";
                return $html;
            })
            ->editColumn('created_at', function($modal){
                $html = "<ul>";
                foreach ($modal->procurement_details as $key => $value) {
                    $html .= "<li>{$value->department->name}</li>";
                }
                $html .= "</ul>";
                return $html;
            })
            ->editColumn('month', function($modal){
                $months = array_map(fn($month) => Carbon::create(null, $month)->format('F'), range(1, 12));
                return $months[$modal->month];
            })
            ->addColumn('action', function($modal) {
                $button = "";
                if(Route::currentRouteName() == 'member.procurements.budget_approve_list'){
                    $list = json_encode($modal);
                    if($modal->status == 1)
                        $button = "<a data-full='{$modal}' href='javascript:void(0)' onclick='onPreview(this,{$modal->id})' class='btn btn-xs btn-info ajax-show' data-target=''>
                                        <i class='fa fa-info-circle'></i>
                                    </a>";

                    
                        $button .= "<a href='javascript:void(0)' onclick='onNotApproved(this,{$modal->id})' class='btn btn-xs btn-danger mx-2' data-target=''>
                                        <i class='fa fa-times'></i>
                                    </a>";
                    return $button;
                }else{
                    if($modal->status == 0)
                    $button = "<a href='javascript:void(0)' onclick='onApproved(this,{$modal->id})' class='btn btn-xs btn-info ajax-show' data-target=''>
                                    <i class='fa fa-info-circle'></i>
                                </a>";
                    return view('common._action-button', ['model' => $modal, 'record' => $button, 'route' => 'member.procurements']);
                }
                
            })
            ->rawColumns(['action','status','updated_at','created_at'])
            ->make(true);
    }

    
    public function query()
    {
        if(Route::currentRouteName() == 'member.procurements.budget_approve_list'){
            $query = Procurement::authCompany()->where('status',1)->latest()->with('procurement_details')
            ->whereHas('procurement_details',function($q){
                $q->where('status',1);
            });
            
        }
        elseif (Route::currentRouteName() == 'member.procurements.budget_not_approve_list') {
            $query = Procurement::authCompany()->where('status',3)->latest()->with('procurement_details');
        }
        else{
            $query = Procurement::authCompany()->latest()->with('procurement_details');
        }
        

        return $this->applyScopes($query);
    }

    
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    
    protected function getColumns()
    {
        return [
            ['name' => 'id', 'data' => 'id',  'title' => "#" ],
            ['name' => 'updated_at', 'data' => 'updated_at',  'title' => "Product Name" ],
            ['name' => 'created_at', 'data' => 'created_at',  'title' => "Department Name" ],
            'month',
            'year',
            'status',
        ];
    }

    
    protected function filename()
    {
        return 'Procurement_' . date('YmdHis');
    }
}
