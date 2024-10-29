<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */
 $route =  \Auth::user()->can(['member.company.index']) ? route('member.company.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Company',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Company',
    'title'=>'Edit Company',
    'heading' => 'Edit Company',
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

            {!! Form::model($model, ['route' => ['member.company.update', $model],  'method' => 'put','files'=>'true','role'=>'form']) !!}

            <div class="box-body">

                @include('member.companies._form')

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


@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

@endpush


