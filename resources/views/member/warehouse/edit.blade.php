<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

$route =  \Auth::user()->can(['member.warehouse.index']) ? route('member.warehouse.index') : "#";
$home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => " Warehouse",
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => " Warehouse",
    'title'=>'Edit '." Warehouse",
    'heading' => 'Update '." Warehouse",
];

?>
@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

            @include('common._alert')

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Update </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($model, ['route' => ['member.warehouse.update', $model],  'method' => 'put', 'files'=>true]) !!}

                <div class="box-body">

                    @include('member.warehouse._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection
