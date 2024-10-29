<?php
/**
 * Editd by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 2:35 PM
 */

if ($model[0]->model == "SaleDetail") {
    $loadingType = "Unload";
    $loadingText = "Unloaded";

} elseif ($model[0]->model == "PurchaseDetail") {
    $loadingType = "Load";
    $loadingText = "Loaded";

} elseif ($model[0]->model == "Damage") {
    $loadingType = "Damage";
    $loadingText = "Damaged";

} elseif ($model[0]->model == "Loss") {
    $loadingType = "Loss";
    $loadingText = "Lost";

} elseif ($model[0]->model == "Overflow") {
    $loadingType = "Overflow";
    $loadingText = "Overflow";

} else {
    $loadingType = "Transfer";
    $loadingText = "Transferred";

}

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'warehouse history',
        'href' => route('member.warehouse.history.index'),
    ],
    [
        'name' => $loadingType,
    ],
];

$data['data'] = [
    'name' => 'Warehouse ' . $loadingText,
    'title' => ' Warehouse ' . $loadingText,
    'heading' => 'Warehouse ' . $loadingText,
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">
        <div class="col-md-12">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">

                    <div class="col-md-8 pl-0">
                        <table class="table table-responsive table-striped mb-0">
                            <tr>
                                <th width="200px">Date</th>
                                <th>: {{ db_date_month_year_format($model[0]->date) }}</th>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <th>: {{ $model[0]->code }}</th>
                            </tr>
                            @if(in_array($model[0]->model, ["Transfer", "Damage", "Loss", "Overflow"]))

                                <tr>
                                    <th>From Warehouse</th>
                                    <th>: {{ $model[0]->fromWarehouse($model[0]->model_id)->title }}</th>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <th>:

                                        @php
                                            if($model[0]->model == "SaleDetail")
                                            {
                                                $type = 'label label-primary';
                                                $loadingType = "Unload";
                                            }elseif ($model[0]->model == "PurchaseDetail"){
                                                $loadingType = "Load";
                                                $type = 'label label-success';
                                            }elseif ($model[0]->model == "Damage"){
                                                $loadingType = "Damage/ Loss";
                                                $type = 'label label-danger';
                                            }elseif ($model[0]->model == "Loss"){
                                                $loadingType = "Damage/ Loss";
                                                $type = 'label label-danger';
                                            }elseif ($model[0]->model == "Overflow"){
                                                $loadingType = "Overflow";
                                                $type = 'label label-info';
                                            }else{
                                                $loadingType = "Transfer";
                                                $type = 'label label-default ';
                                            };

                                            echo "<label style='font-size: 100% !important;' class='".$type."'>".ucfirst($loadingType)."</label>";
                                        @endphp
                                    </th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Details</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">


                        <thead>
                        <tr>
                            <th width="50px" class="text-center">#SL</th>
                            <th>Product</th>
                            <th>Warehouse Title</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">{{$loadingType}} Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($model as $key=> $value)

                            <tr class="item-row">
                                <td class="text-center">
                                    {{ $key+1 }}
                                </td>
                                <td class="text-left">
                                    {{ $value->item->item_name }}
                                </td>
                                <td class="text-left" width="200px">
                                    {{ $value->warehouse->title }}
                                </td>
                                <td class="text-center" width="80px">
                                    {{ $value->item->unit }}
                                </td>
                                <td class="text-center" width="100px">
                                    {{ $value->qty }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
@endsection


