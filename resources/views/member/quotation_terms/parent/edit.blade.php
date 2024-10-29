<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/28/2019
 * Time: 12:41 PM
 */
 $route =  \Auth::user()->can(['member.quotation_terms.index']) ? route('member.quotation_terms.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quotation Terms',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Quotation Terms',
    'title'=>'Edit Quotation Terms',
    'heading' => 'Edit Quotation Terms',
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

                {!! Form::model($modal, ['route' => ['member.quotation_terms.update', $modal],  'method' => 'put']) !!}

                <div class="box-body">

                    @include('member.quotation_terms.parent._form')

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
