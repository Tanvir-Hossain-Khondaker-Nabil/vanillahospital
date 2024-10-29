<?php

namespace App\DataTables;

use App\Models\VehicleDetail;
use Yajra\DataTables\Services\DataTable;

class BookingDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->editColumn('ipd_patient_info_registration_id', function($list){
                if (@$list->ipd_patient_info_registration_id)
                                       return "IPD Patient";
                                        elseif (@$list->outdoor_registration_id)
                                        return "Outdoor Patient";
                                        else
                                        return "Normal Patient";
            })
            ->editColumn('driver_id', function($list){
                return @$list->driver->name;
            })
            ->editColumn('vehicle_info_id', function($list){
                return $list->vehicleInfo->model_no;
            })
            ->editColumn('vehicle_schedule_id', function($list){
                return "(". @$list->vehicleSchedule->start_time." - ". @$list->vehicleSchedule->end_time .")";
            })
            ->editColumn('price', function($list){
                return @$list->vehicleSchedule->price;
            })
            ->addColumn('action', function($list) {
                return view('common._all-button', ['model' => $list, 'route' => 'member.vehicle_detail']);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Area $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = VehicleDetail::authCompany();

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
            'ipd_patient_info_registration_id'=>['title' => 'Type Of Patient'],
            'invoice_number',
            'driver_id'=>['title' => 'Driver Name'],
            'vehicle_info_id'=>['title' => 'Amdulance Name'],
            'patient_name',
            'patient_email',
            'patient_phone_one'=>['title' => 'Mobile Number'],
            'patient_phone_two'=>['title' => 'Mobile Number'],
            'vehicle_schedule_id'=>['title' => 'Schedule'],
            'price',
            'subtotal',
            'paid',
            'due',
            'gender',
            'age',
            'date_of_birth',
            'blood_group',
            'start_date_and_time'=>['title' => 'Date'],
            'patient_address'=>['title' => 'Address'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Area_' . date('YmdHis');
    }
}
