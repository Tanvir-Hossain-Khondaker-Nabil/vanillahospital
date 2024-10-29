<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";
if( $sharer_type =='Supplier')
{
    $route =  \Auth::user()->can(['member.sharer.supplier_list']) ? route('member.sharer.supplier_list') : "#";
}else{
    $route =  \Auth::user()->can(['member.sharer.customer_list']) ? route('member.sharer.customer_list') : "#";

}

$type = ($sharer_type == 'Both' ? trans('common.supplier_and_customer') : '');

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => $type,
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => $type,
    'title'=>'Edit '.$type,
    'heading' => trans('common.update').' '.$type,
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
                    <h3 class="box-title">{{__('common.update')}} </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($model, ['route' => ['member.sharer.update', $model],  'method' => 'put', 'files'=>true]) !!}

                <div class="box-body">

                    @include('member.suppliers_or_customers._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">{{__('common.update')}}</button>
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
