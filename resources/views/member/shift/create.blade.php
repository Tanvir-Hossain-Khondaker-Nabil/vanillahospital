<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.shift.index']) ? route('member.shift.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Shift',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Shift',
    'title'=>'Create Shift',
    'heading' =>trans('common.shift'),
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
                <h3 class="box-title"> {{__('common.create_shift')}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'member.shift.store','method' => 'POST','files'=>'true','role'=>'form' ]) !!}

            <div class="box-body">

                @include('member.shift._form')

                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">{{__('common.submit')}} </button>
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


