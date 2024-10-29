<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')

    {{-- <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
     --}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.css" integrity="sha512-BB0bszal4NXOgRP9MYCyVA0NNK2k1Rhr+8klY17rj4OhwTmqdPUQibKUDeHesYtXl7Ma2+tqC6c7FzYuHhw94g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush


<div class="col-md-6">

    {{-- <div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true">
        <input type="text" class="form-control" value="09:32">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-time"></span>
        </span>
    </div> --}}

    <div class="form-group ">
        <label for="name">{{__('common.time_in')}} <span class="text-red"> * </span> </label>

        {!! Form::text('time_in',null,['id'=>'time_in','class'=>'form-control input-group clockpicker', 'data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('common.time_in'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('common.time_out')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('time_out',null,['id'=>'time_out','class'=>'form-control input-group clockpicker','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('common.time_out'), 'required']); !!}
    </div>
    <div class="form-group">
        <label for="name">{{__('common.late_after_time')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('late',null,['id'=>'late','class'=>'form-control input-group clockpicker','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('common.late_after_time'), 'required']); !!}
    </div>


    <div class="form-group">
        <label for="name"> {{__('common.shift_type')}}  <span class="text-red"> * </span> </label>
        {!! Form::select('shift_type',['0'=>'Day','1'=>'Night'], null,['id'=>'shift_type','class'=>'form-control', 'required']); !!}
        {{--{!! Form::select('shift_type',['Morning'=>'Morning', 'Day'=>'Day','Night'=>'Night'], null,['id'=>'shift_type','class'=>'form-control', 'required']); !!}--}}
    </div>




</div>
@push('scripts')
    <!-- Select2 -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js" integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
     $('.clockpicker').clockpicker({
        twelvehour: true,
        donetext: "Done",
        autoclose: false,
     })
     .find('input').change(function(){

    });
    </script>

@endpush

