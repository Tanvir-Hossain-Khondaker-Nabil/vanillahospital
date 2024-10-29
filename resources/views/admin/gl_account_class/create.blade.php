<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */
 $route =  \Auth::user()->can(['admin.gl_account_class.index']) ? route('admin.gl_account_class.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'GL Account Classes',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'GL Account Classes',
    'title'=>'Create GL Account Class',
    'heading' => 'Create GL Account Class',
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
                <h3 class="box-title">Create GL Account Class</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'admin.gl_account_class.store', 'method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('admin.gl_account_class._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
