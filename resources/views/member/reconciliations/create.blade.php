<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 11/14/2019
 * Time: 4:53 PM
 */

 $route =  \Auth::user()->can(['member.reconciliation.index']) ? route('member.reconciliation.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reconciliation',
        'href' => $route,
    ],
    [
        'name' => $type,
    ],
];

$data['data'] = [
    'name' => $type.' Reconciliation ',
    'title'=> $type.' Reconciliation ',
    'heading' => $type.' Reconciliation ',
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
                    <h3 class="box-title">{{ $type.' Reconciliation ' }}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.reconciliation.store','method' => 'POST', 'files'=>true, 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.reconciliations._form')

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




