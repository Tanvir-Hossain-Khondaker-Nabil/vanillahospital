<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/20/2019
 * Time: 4:15 PM
 */

 $route =  \Auth::user()->can(['member.users.set_users_company']) ? route('member.users.set_users_company') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Company  Setting',
        'href' => $route,
    ],

    [
        'name' => 'Set Users Company',
    ],
];

$data['data'] = [
    'name' => 'Set Users Company Setting',
    'title' => 'Set Users Company ',
    'heading' => 'Set Users Company',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')
        @include('common._error')

        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Set Users Company </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.users.save_users_company', 'method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="user_id">User </label>
                            {!! Form::select('user_id',$users,null,['id'=>'user_id','class'=>'form-control select2','placeholder'=>'Select User']); !!}
                        </div>
                        <div class="form-group">
                            <label for="company_id">Company </label>
                            {!! Form::select('company_id',$companies,null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success form-group"> Save</button>
                    </div>
                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}


            <!-- /.box -->
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Users Assigned Company </h3>
                </div>

                <div class="box-body">
                    <div class="col-md-12">
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>#SL</th>
                                <th>User Name</th>
                                <th>Assign Company</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users_company as $key=>$value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->user_details }}</td>
                                    <td>{{ $value->company ? $value->company->company_name : '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tr>
                                <td class="text-right" colspan="4">{{ $users_company->links() }}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(function () {
            $('.select2').select2()
        });

    </script>

@endpush
