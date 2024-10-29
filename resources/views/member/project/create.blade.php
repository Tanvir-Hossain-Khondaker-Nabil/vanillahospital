<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:31 PM
 */
$route = \Auth::user()->can(['member.project.index']) ? route('member.project.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

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
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Add Project',
    'title' => 'Add Project',
    'heading' => 'Add Project',
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
                    <h3 class="box-title"> Project</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open([
                    'route' => 'member.project.store',
                    'method' => 'POST',
                    'files' => true,
                    'id' => 'project',
                    'class' => 'image-upload',
                    'role' => 'form',
                ]) !!}

                <div class="box-body">

                    @include('member.project._form')


                </div>

                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <!-- /.box-body -->

                {!! Form::close() !!}
                <!-- /.box -->
            </div>

            @include('member.project.add_client')

        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

        <!-- Date range picker -->
        <script type="text/javascript">
            // var date = new Date();


            $(function() {

                $('.select2').select2();
            });
        </script>
    @endpush
@endsection
