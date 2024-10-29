<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/28/2019
 * Time: 12:41 PM
 */

 $route =  \Auth::user()->can(['member.quote_company.index']) ? route('member.quote_company.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";
$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Client Company',
        'href' =>  $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Client Company',
    'title'=>'Edit Client Company',
    'heading' => 'Edit Client Company',
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
                    <h3 class="box-title">Update </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.client_company.update', $modal],  'method' => 'put']) !!}

                <div class="box-body">

                    @include('member.client_company._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update</button>
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


@push('scripts')

@endpush
