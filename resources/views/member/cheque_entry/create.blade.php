<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/21/2019
 * Time: 5:00 PM
 */

 $route =  \Auth::user()->can(['member.cheque_entries.index']) ? route('member.cheque_entries.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Cheque Entry',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Cheque Entry',
    'title'=>'Create Cheque Entry',
    'heading' =>trans('common.create_cheque_entry'),
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
                    <h3 class="box-title"> {{__('common.create_cheque_entry')}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.cheque_entries.store','method' => 'POST', 'files'=>true, 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.cheque_entry._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">{{__('common.submit')}} </button>
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
