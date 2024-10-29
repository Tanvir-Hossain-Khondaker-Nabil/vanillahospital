<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */
$route = \Auth::user()->can(['member.employee.index']) ? route('member.employee.index') : "#";
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Personal Info',
        'href' => $route,
    ],
    [
        'name' => 'Update',
    ],
];

$data['data'] = [
    'name' => 'Personal Info',
    'title' => 'Update Personal Info',
    'heading' => 'Update Personal Info',
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')
        @include('common._error')


        <!-- /.box-header -->
            <!-- form start -->

            {!! Form::model($model, ['route' => ['member.personal_info.update', $model],  'method' => 'put','files'=>'true','role'=>'form']) !!}


            @include('member.employee._employee_info_form')

            <!-- /.box-body -->

        {!! Form::close() !!}
        <!-- /.box -->
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

@endpush


