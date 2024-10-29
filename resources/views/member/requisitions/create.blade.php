<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */

 $route =  \Auth::user()->can(['member.requisition.index']) ? route('member.requisition.index') : "#";
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
    'name' => 'Add Requisition',
    'title'=> 'Add Requisition',
    'heading' => 'Add Requisition',
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
                    <h3 class="box-title"> Requisition</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.requisition.store','method' => 'POST', 'files'=>true, 'id'=>'requisition_form', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.requisitions._form')

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

