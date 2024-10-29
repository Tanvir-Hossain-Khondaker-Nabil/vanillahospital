<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:31 PM
 */

 $route =  \Auth::user()->can(['member.quotation_sub_terms.index']) ? route('member.quotation_sub_terms.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quotation Sub Terms',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Add Quotation Sub Terms',
    'title'=> 'Add Quotation Sub Terms',
    'heading' => 'Add Quotation Sub Terms',
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
                    <h3 class="box-title"> Quotation Sub Terms</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.quotation_sub_terms.store','method' => 'POST', 'files'=>true, 'id'=>'quotation_sub_terms', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.quotation_terms.sub._form')

                    <div class="box-footer">
                        <div class="col-md-12">
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

