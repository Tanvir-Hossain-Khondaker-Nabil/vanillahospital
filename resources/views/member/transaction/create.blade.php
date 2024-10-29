<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:31 PM
 */

 $route =  \Auth::user()->can(['member.transaction.index']) ? route('member.transaction.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Transaction',
        'href' => $route,
    ],
    [
        'name' => $type,
    ],
];

$data['data'] = [
    'name' => $type,
    'title'=> $type,
    'heading' => __("common.transaction")." :" .__("common.".strtolower($type)).($type == "Payment" ? "/". __('common.expense') : "/". __('common.received')),
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
{{--                <div class="box-header with-border">--}}
{{--                    <h3 class="box-title">{{ $type }}</h3>--}}
{{--                </div>--}}
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.transaction.store','method' => 'POST', 'files'=>true, 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.transaction._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                             <button type="submit" class="btn btn-primary">{{__('common.submit')}}</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>

    @include('member.transaction._account_type_add');
@endsection

