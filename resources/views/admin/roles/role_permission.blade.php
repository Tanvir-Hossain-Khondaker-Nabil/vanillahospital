<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/16/2023
 * Time: 12:37 PM
 */
$route = \Auth::user()->can(['admin.roles.index']) ? route('admin.roles.index') : '#';
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Role Permission',
        'href' => $route,
    ],

    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Roles',
    'title' => 'Create Role Permission ',
    'heading' => 'Create Role Permission ',
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
                    <h3 class="box-title">Create Role</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'admin.roles.store', 'method' => 'POST', 'role' => 'form']) !!}

                <div class="box-body">

                    <div class="col-md-12">
                        <table class="table table-responsive table-striped">
                            <tr>
                                <th> Permission/Role </th>

                            </tr>
                        </table>
                    </div>

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


@push('scripts')
    <script>
        $(function() {

        })
    </script>
@endpush
