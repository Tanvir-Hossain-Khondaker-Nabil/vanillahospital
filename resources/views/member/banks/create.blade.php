<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/21/2019
 * Time: 5:00 PM
 */

 $route =  \Auth::user()->can(['member.banks.index']) ? route('member.banks.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Bank',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Bank',
    'title'=>'Create Bank',
    'heading' => 'Create Bank',
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
                <!-- form start -->

                {!! Form::open(['route' => 'member.banks.store', 'method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.banks._form')

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


@push('scripts')

    <!-- CK Createor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKCreateor
            // instance, using default configuration.
            CKEDITOR.replace('note');
        })
    </script>
@endpush
