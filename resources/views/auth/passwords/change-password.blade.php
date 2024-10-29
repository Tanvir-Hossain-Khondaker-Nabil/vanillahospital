<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2020
 * Time: 12:56 PM
 */

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Change Password',
        'href' => route('member.users.index'),
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Change Password',
    'title' => 'Change Password',
    'heading' => trans('common.change_password'),
];


?>
@extends('layouts.back-end.master', $data)

@section('contents')

    @if(session('success'))
        <!-- If password successfully show message -->
        <div class="row">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif


    @include('common._alert')
    {!! Form::open(['method' => 'post', 'route' => ['auth.change_password']]) !!}
    <!-- If no success message in flash session show change password form  -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('current_password', trans('common.current_password'), ['class' => 'control-label']) !!}
                    {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('current_password'))
                        <p class="help-block">
                            {{ $errors->first('current_password') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('new_password', trans('common.new_password'), ['class' => 'control-label']) !!}
                    {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('new_password'))
                        <p class="help-block text-red">
                            {{ $errors->first('new_password') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('new_password_confirmation', trans('common.confirm_password'), ['class' => 'control-label']) !!}
                    {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('new_password_confirmation'))
                        <p class="help-block">
                            {{ $errors->first('new_password_confirmation') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {!! Form::submit(trans('common.save'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@stop
