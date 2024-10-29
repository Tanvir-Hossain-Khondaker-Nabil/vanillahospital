<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/19/2023
 * Time: 12:41 PM
 */


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
        'name' => "Load Not Done",
    ],
];

$data['data'] = [
    'name' => 'Warehouse Load Not Done' ,
    'title' => ' Warehouse  Load Not Done',
    'heading' => 'Warehouse  Load Not Done',
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">
        <div class="col-md-12">

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Product List which Load not Complete</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">


                        <thead>
                        <tr>
                            <th width="50px" class="text-center">#SL</th>
                            <th  width="100px"class="text-center">Purchase ID</th>
                            <th>Product</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($loads as $key=> $value)

                            <tr class="item-row">
                                <td class="text-center">
                                    {{ $key+1 }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('member.purchase.show', $value->purchase_id) }}"  > {{ $value->purchase_id }} </a>
                                </td>
                                <td class="text-left">
                                    {{ $value->item->item_name }}
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

@push("scripts")

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {
            $('table').dataTable();
        });

    </script>

@endpush




