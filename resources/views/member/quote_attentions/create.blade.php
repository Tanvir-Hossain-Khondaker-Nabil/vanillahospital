<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:31 PM
 */

 $route =  \Auth::user()->can(['member.quote_attentions.index']) ? route('member.quote_attentions.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quote Attention',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Add Quote Attention',
    'title'=> 'Add Quote Attention',
    'heading' => 'Add Quote Attention',
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
                    <h3 class="box-title"> Quote Attention</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.quote_attentions.store','method' => 'POST', 'files'=>true, 'id'=>'quote_attentions', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.quote_attentions._form')

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

