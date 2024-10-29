<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/29/2019
 * Time: 3:51 PM
 */


 $route =  \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Purchases',
        'href' => $route,
    ],
    [
        'name' => 'Product Serial',
    ],
];

$data['data'] = [
    'name' => 'Purchase Serial',
    'title'=> 'Purchase Serial',
    'heading' => 'Purchase Serial',
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')

    <div class="box box-default">

        @include('common._alert')

        <div class="box-header with-border">
            <h3 class="box-title"> Purchase Product Serial</h3>
        </div>

        {!! Form::open(['route' => 'member.purchase.item_serial_store','method' => 'POST', 'role'=>'form' ]) !!}


        <div class="box-body">
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <div class="col-md-6">
                            <label for="suppliers"> Item </label>
                            {!! Form::text('item_name', $item_name->item_name, [ 'class'=>'form-control','required', 'readonly']); !!}
                            {!! Form::hidden('item_id', $item_id, [ 'class'=>'form-control','required', 'readonly']); !!}
                        </div>
                        <div class="col-md-6">
                            <label for="suppliers"> Purchase ID </label>
                            {!! Form::text('purchase_id', $purchase_id, [ 'class'=>'form-control','required', 'readonly']); !!}
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">
                    <h4>Purchase  Item Serial</h4>

                    <table width="400px">

                        <thead>
                        <tr>
                            <th class="text-center">#SL</th>
                            <th>Product Serial</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i=0; $i<$item_count; $i++)
                            <tr class="item-row">

                                <td>{{ $i+1 }}</td>
                                <td  class=" my-2" width="300px">
                                    {!! Form::text('serial[]',null,['class'=>'form-control', 'required']); !!}
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>

                </div>

            </div>


            <div style="margin-top: 20px; margin-bottom: 20px" class="row pull-right">

                <div class="col-lg-12 col-md-12 ">
                    <table class="new-table-3">
                        <tr>
                            <td>
                                <button style="width: 100px" type="submit" class="btn btn-block btn-primary">Save </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            {!! Form::close() !!}


        </div>
    </div>




@endsection

