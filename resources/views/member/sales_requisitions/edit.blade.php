<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */
 $route =  \Auth::user()->can(['member.sales_requisitions.index']) ? route('member.sales_requisitions.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sales Requisitions',
        'href' => $route,
    ],

    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Edit sales requisitions',
    'title'=> 'Edit sales requisitions',
    'heading' => 'Edit sales requisitions',
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> sales requisitions</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.sales_requisitions.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

                <div class="box-body">

                    @include('member.sales_requisitions._edit_form')

                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="submit" class="btn btn-primary">Update</button>
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


