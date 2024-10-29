<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/1/2023
 * Time: 12:40 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Process',
//        'href' => route('member.banks.index'),
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Process',
    'title'=>'Attendance Process',
    'heading' =>trans('common.attendance_process'),
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

                <div class="box-body">

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="box p-5" style="border: 0; box-shadow: 0px -1px 9px 9px rgba(0,0,0,0.1)">
                            <div class="gifImg text-center"><img  src="{{ asset('public/img/hdadrmia.gif') }}" alt=""  class="w-75 text-center"></div>
                            <div class="">
                                {!! Form::open(['route' => 'member.store.process-attendances', 'method' => 'POST', 'role'=>'form' ]) !!}
                                    <input type="text" class="form-control date" name="attend_date" placeholder="{{__('common.enter_date')}}" required="" autocomplete="off" style="height: 50px; font-size: 22px">
                                    <input type="submit" value="{{__('common.process')}}" class="mt-2 btn btn-primary">
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- /.box -->
                </div>
            </div>
        </div>
        </div>
@endsection

@push('scripts')

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

            $('.date').datepicker({
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
        });

    </script>

@endpush

