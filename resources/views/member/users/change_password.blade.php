<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.users.index']) ? route('member.users.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Users',
        'href' => $route,
    ],

    [
        'name' => 'Change',
    ],
];

$data['data'] = [
    'name' => 'Users',
    'title' => 'Change Password',
    'heading' => 'Change Password',
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
                    <h3 class="box-title">Change Password</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($model, ['route' => ['member.users.update_change_password', $model],  'method' => 'post']) !!}

                <div class="box-body">

                    @include('common._error')

                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="full_name">Full Name </label>
                            {!! Form::text('full_name',null,['id'=>'full_name','class'=>'form-control','placeholder'=>'Enter Full Name', 'required', 'disabled']); !!}
                        </div>
                        <div class="form-group">
                            <label for="email">Email </label>
                            {!! Form::email('email',null,['id'=>'email','class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Enter Email', 'required', 'disabled']); !!}
                        </div>
                        <div class="form-group">
                            <label for="password">Change Password </label>
                            <input type="password" class="form-control " onfocus="this.value=''" value=""
                                   autocomplete="new-password" name="reset_pass" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <label for="password">Confirm Password </label>
                            <input type="password" class="form-control " onfocus="this.value=''" value=""
                                   autocomplete="new-password" name="confirm_reset_pass" placeholder="Password">
                        </div>


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

    <!-- CK Createor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKCreateor
            // instance, using default configuration.
            CKEDITOR.replace('description');
        })
    </script>
@endpush
