<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/28/2019
 * Time: 12:41 PM
 */

 $route =  \Auth::user()->can(['member.project.index']) ? route('member.project.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Project',
    'title'=>'Edit Project',
    'heading' => 'Edit Project',
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

                {!! Form::model($modal, ['route' => ['member.project.update', $modal],  'files'=>true, 'method' => 'put']) !!}

                <div class="box-body">

                    @include('member.project._form')

                </div>

                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>

                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
            @include('member.project.add_client')
        </div>
    </div>
@endsection


@push('scripts')

@endpush
