<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 5/3/2023
 * Time: 12:42 PM
 */

namespace App\Http\Services;


use App\Models\DealerSale;
use App\Models\Designation;
use App\Models\EmployeeInfo;
use App\Models\Sale;
use App\Models\SaleCommission;
use Carbon\Carbon;

class SaleCommissionGenerate
{

    public $employeeId;
    public $employee;
    public $saleId;
    public $requisitionId;
    public $saler;
    public $sale;
    public $deptHead;

    public function __construct($sale_id, $employeeId, $requisitionId, $saler = 'DealerSale')
    {
        $this->employeeId = $employeeId;
        $this->saleId = $sale_id;
        $this->requisitionId = $requisitionId;
        $this->saler=$saler ;
    }


    public function checkEmployeeById()
    {
        $employee = EmployeeInfo::where('user_id',$this->employeeId)->first();

        if($employee->designation_id)
        {
           $this->checkHeadOfDesignation($employee->designation_id);
        }

        return $employee;

    }


    public function checkHeadOfDesignation($designation_id)
    {
        $headDesignation = Designation::find($designation_id);


        if($headDesignation->parent_designation_id)
        {
            $this->deptHead[] = $headDesignation->parent_designation_id;
            $this->checkHeadOfDesignation($headDesignation->parent_designation_id);
        }

    }



    public function sale_commission_generate()
    {
        if($this->saler == "DealerSale")
        {
            $sale = new DealerSale();
        }else{
            $sale = new Sale();
        }

        $sale = $sale->where('id', $this->saleId)->first();

        $this->sale = $sale;

        $employee = $this->checkEmployeeById();
        $this->employee = $employee;

        $this->saveCommission($employee);

        $this->departmentLevel($employee);


    }

    private function  saveCommission($employee)
    {

        $saleCommission = new SaleCommission();
        $saleCommission->employee_id = $employee->id;
        $saleCommission->sale_id = $this->saleId;
        $saleCommission->requisition_id = $this->requisitionId;
        $saleCommission->commission_percentage = $employee->commission;
        $saleCommission->commission_amount = ($this->sale->total_price*$employee->commission)/100;
        $saleCommission->total_sales_amount = $this->sale->total_price;
        $saleCommission->status = 0;
        $saleCommission->generate_date = Carbon::today();
        $saleCommission->generate_time = date("h:i:s ");
        $saleCommission->save();

    }

    public function departmentLevel($employeeID)
    {

        foreach ($this->deptHead as $value)
        {
            $dept =  Designation::find($value);

            $employee =  $this->checkEmployeeByArea($dept->id, $dept->commission_area);

            if($employee)
                $this->saveCommission($employee);

//            switch ($dept->name){
//
//                case "Sales Manager":
//                    $employee =  $this->saleManager($thana_id, $dept->id);
//                    $this->saveCommission($employee);
//                    break;
//
//                case "Area Manager":
//                    $employee = $this->areaManager($district_id, $dept->id);
//                    $this->saveCommission($employee);
//                    break;
//
//                case "Region Manager":
//                    $employee = $this->regionManager($region_id, $dept->id);
//                    $this->saveCommission($employee);
//                    break;
//
//                case "General Manager":
//                    $employee = $this->generalManager($dept->id);
//                    $this->saveCommission($employee);
//                    break;
//            }


//            switch ($dept->commission_area){

//                case "thana":
//                    $this->saveCommission($employee);
//                    break;
//
//                case "district":
//                    $employee = $this->areaManager($district_id, $dept->id);
//                    $this->saveCommission($employee);
//                    break;
//
//                case "region":
//                    $employee = $this->regionManager($region_id, $dept->id);
//                    $this->saveCommission($employee);
//                    break;
//
//                case "General Manager":
//                    $employee = $this->generalManager($dept->id);
//                    $this->saveCommission($employee);
//                    break;
//            }


        }
    }


    public function checkEmployeeByArea($dept, $tag_area)
    {
        $employee = EmployeeInfo::where('designation_id', $dept);

        if($tag_area == "area"){

            $employee = $employee->where('area_id', $this->employee->area_id);

        }else if($tag_area == "region") {

            $employee = $employee->where('region_id', $this->employee->region_id);

        }else if($tag_area == "district") {

            $employee = $employee->where('district_id', $this->employee->district_id);

        }else if($tag_area == "thana") {

            $employee = $employee->where('thana_id', $this->employee->thana_id);

        }else if($tag_area == "division") {

            $employee = $employee->where('division_id', $this->employee->division_id);
        }

        $employee =   $employee->first();


        return $employee;
    }


    public function saleManager($area_id, $dept)
    {
        $employee = EmployeeInfo::where('thana_id',$area_id)
            ->where('designation_id', $dept)
            ->first();

        return $employee;
    }


    public function areaManager($area_id, $dept)
    {
        $employee = EmployeeInfo::where('district_id',$area_id)
            ->where('designation_id', $dept)
            ->first();

        return $employee;
    }


    public function regionManager($area_id, $dept)
    {
        $employee = EmployeeInfo::where('region_id',$area_id)
            ->where('designation_id', $dept)
            ->first();

        return $employee;
    }

    public function generalManager($dept)
    {
        $employee = EmployeeInfo::where('designation_id', $dept)
            ->first();

        return $employee;
    }



}
