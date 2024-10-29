<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')

    <link rel="stylesheet"
          href="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet"
          href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

@endpush


<div class="col-md-6">
    <div class="form-group">
        <label for="name">{{__('common.title')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('title',null,['id'=>'title','class'=>'form-control','placeholder'=>'Enter  Title', 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('common.start_date')}}   <span class="text-red"> * </span> </label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            @if(isset($model))
                {!! Form::text('start_date', create_date_format($model->start_date,'/'),['id'=>'start_date','class'=>'form-control ','placeholder'=>trans('common.start_date'), 'required', 'autocomplete'=>"off"]); !!}
            @else
                {!! Form::text('start_date', null,['id'=>'start_date','class'=>'form-control ','placeholder'=>trans('common.start_date'), 'required', 'autocomplete'=>"off"]); !!}
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="name">{{__('common.end_date')}} </label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            @if(isset($model) && $model->end_date != null)
                {!! Form::text('end_date', create_date_format($model->end_date,'/'),['id'=>'end_date','class'=>'form-control pull-right','placeholder'=>trans('common.end_date'), 'autocomplete'=>"off"]); !!}
            @else
                {!! Form::text('end_date', null,['id'=>'end_date','class'=>'form-control ','placeholder'=>trans('common.end_date'), 'autocomplete'=>"off"]); !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="status">{{__('common.type')}}   <span class="text-red"> * </span></label>
        {!! Form::select('type',['govt'=>'Govt Holiday', 'calender'=>'Holiday'], null,['id'=>'type','class'=>'form-control', 'required']); !!}
    </div>
</div>


@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

            $('#end_date').datepicker({
                "format": 'mm/dd/yyyy',
                "startDate": '-0d',
                "autoclose": true
            });

            $('#start_date').change( function () {
                var arr = $(this).val().split("/");
                var date = new Date(arr[2]+"-"+arr[0]+"-"+arr[1]);


                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                var minDate = new Date(y, m, d);

                $("#end_date").datepicker("setStartDate", minDate);
            });


            $('#start_date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "startDate": '-0d',
                "todayHighlight": true,
                "autoclose": true
            });

        });

    </script>

@endpush



