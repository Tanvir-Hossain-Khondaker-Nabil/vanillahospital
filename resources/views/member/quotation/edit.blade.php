<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */




 $route =  \Auth::user()->can(['member.quotations.index']) ? route('member.quotations.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quotation',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Edit quotation',
    'title'=> 'Edit quotation',
    'heading' => 'Edit quotation',
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
                    <h3 class="box-title"> quotation</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.quotations.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

                <div class="box-body">

                    @include('member.quotation._edit_form')

                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>


    @include('common._model_add_product')

@endsection


