<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.defect_types.index']) ? route('member.defect_types.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'defect types',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Defect type',
    'title'=>'Create Defect type',
    'heading' => trans('common.create_defect_type'),
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
                <h3 class="box-title">{{__('common.create_defect_type')}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'member.defect_types.store','method' => 'POST','files'=>'true','role'=>'form' ]) !!}

            <div class="box-body">

                @include('member.defect_types._form')

                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">{{__('common.submit')}}</button>
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


