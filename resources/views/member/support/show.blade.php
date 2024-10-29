<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */
$route = \Auth::user()->can(['member.support.index']) ? route('member.support.index') : "#";
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Support',
        'href' => route('member.support.index') ,
    ],
    [
        'name' => 'Message',
    ],
];

$data['data'] = [
    'name' => 'Support',
    'title' => 'Support Message',
    'heading' => 'Support Message',
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
                    <h3 class="box-title">Read Message </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="px-5">
                    <div class="row">
                        <div class="col-md-12 px-0">

                            <div class="mailbox-read-info">
                                <h3>{{ $data->title }}</h3>
                                <h5>From: {{ $data->sender->employee->employee_name_id }}

                                    <span class="mailbox-read-time pull-right">{{ \Carbon\Carbon::parse($data->created_at)->toDayDateTimeString() }}</span></h5>
                            </div>

                            <div class="mailbox-read-message">
                                {!! $data->message !!}
                            </div>

                        </div>
                        @if (count($reply)> 0)
                            <div class="col-md-12 pb-5 px-2">
                                <h4 class="mt-3"> <i class="fa fa-reply"></i> Reply Message: </h4>
                                @foreach ($reply as $rply)
                                    <div class="" style="border-top: 1px solid #eee">
                                        <p class="justify-text"> {!! $rply->message !!} </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-', 'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList']];
            CKEDITOR.replace('description',
                {toolbar: 'MA'});
        });
    </script>

@endpush
