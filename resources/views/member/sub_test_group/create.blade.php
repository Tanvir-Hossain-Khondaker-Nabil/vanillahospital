<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.sub_test_group.index']) ? route('member.sub_test_group.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sub Test Group',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Sub Test Group',
    'title'=>'Create Sub Test Group',
    'heading' => 'Sub Test Group',
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
                <h3 class="box-title">Create Sub Test Group</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'member.sub_test_group.store','method' => 'POST','files'=>'true','role'=>'form' ]) !!}

            <div class="box-body">

                @include('member.sub_test_group._form')

            </div>

            <div class="box-footer">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection


