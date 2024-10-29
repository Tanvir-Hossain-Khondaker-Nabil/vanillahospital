<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */
$route = \Auth::user()->can(['admin.staff_support.index']) ? route('admin.staff_support.index') : "#";
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Support',
        'href' => $route,
    ],
    [
        'name' => 'Message',
    ],
];

$data['data'] = [
    'name' => 'Supports',
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

                            @if (count($reply)==0)
                                {!! Form::open(['route' => 'admin.staff_support.store', 'method' => 'POST', 'role'=>'form' ]) !!}

                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="description">Reply Message <span class="text-red"> * </span>
                                            </label>
                                            {!! Form::textarea('message',null,['id'=>'description','class'=>'form-control','placeholder'=>'Enter Message']); !!}
                                        </div>
                                        <input type="hidden" id="re_id" name="re_id" value={{$data->id}}>
                                    </div>
                                    <div class="box-footer">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-reply"></i>
                                                Reply
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </div>
                        @if (count($reply)> 0)
                            <div class="col-md-12 pb-5 px-2">
                                <h4 class="mt-3"> <i class="fa fa-reply"></i> Reply Message: </h4>
                                @foreach ($reply as $rply)
                                    <div class="" style="border-top: 1px solid #eee">
                                        <p class="justify-text"> {!!$rply->message!!} </p>
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

        $(function () {
            let id = $("#re_id").val();
            console.log('id is', id)
            $.ajax({
                url: "{{ route('admin.change_status_support_message') }}",
                method: 'GET',
                data: {
                    'id': id
                },
                success: function (data) {
                    // console.log('data is',data)
                    // e.nextElementSibling.nextElementSibling.innerHTML = data

                }
            });
        });

    </script>

@endpush
