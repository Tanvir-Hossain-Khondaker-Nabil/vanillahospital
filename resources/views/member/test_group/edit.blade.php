<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.test_group.index']) ? route('member.test_group.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Test Group',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Test Group',
    'title'=>'Edit Test Group',
    'heading' => 'Edit Test Group',
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

            {!! Form::model($model, ['route' => ['member.test_group.update', $model], 'files'=>'true', 'method' => 'put']) !!}

            <div class="box-body">

                @include('member.test_group._form')

            </div>


            <div class="box-footer">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection


@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

@endpush


