<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */

 $route =  \Auth::user()->can(['member.sales_requisitions.index']) ? route('member.sales_requisitions.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Requisitions',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Add Sales Requisitions',
    'title'=> 'Add Sales Requisitions',
    'heading' => 'Add Sales Requisitions',
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
                    <h3 class="box-title"> Sales Requisitions</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.sales_requisitions.store','method' => 'POST', 'files'=>true, 'id'=>'requisition_form', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.sales_requisitions._form')

                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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

