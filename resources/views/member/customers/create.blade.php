<?php
/**
 * Created by PhpStorm.
 * {{ $sharer_type }}: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.customers.index']) ? route('member.customers.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$sharer_type = "Shopkeeper";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => $sharer_type,
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => $sharer_type,
    'title'=>'Create '.$sharer_type,
    'heading' => 'Create '.$sharer_type,
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
                <h3 class="box-title">Create {{ $sharer_type }}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'member.customers.store', 'method' => 'POST', 'role'=>'form', 'files'=>true ]) !!}

                <div class="box-body">

                    @include('member.customers._form')

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
