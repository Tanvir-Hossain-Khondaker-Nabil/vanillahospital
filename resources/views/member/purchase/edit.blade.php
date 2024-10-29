<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */




 $route =  \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Purchases',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Edit Purchase',
    'title'=> 'Edit Purchase',
    'heading' => trans('common.edit_purchase'),
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12 purchase-desktop">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> {{__('common.purchase')}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.purchase.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

                <div class="box-body">

                    @include('member.purchase._edit_form')

                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="submit" class="btn btn-primary">{{__('common.update')}}</button>
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


