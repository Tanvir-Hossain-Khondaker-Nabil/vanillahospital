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
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush



<div class="col-md-7">


    @if(!\Auth::user()->hasRole(['user','project_manager']))
        <div class="form-group">
            <label for="status"> Employee <span class="text-red"> * </span> </label>
            <select class="form-control select2" required name="emp_id" id="emp_id">
                <option value=""> Select Employee</option>
                @foreach($employees as $value)
                    <option
                        value="{{$value->id}}" {{ isset($emp_leave) ? $emp_leave->emp_id == $value->id ? "selected" : "" :""  }} > {{ $value->employeeID." - ".$value->first_name." ".$value->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status"> Leave </label>
            <select class="form-control select2" name="leave_id" id="leave_id">
                <option value=""> Select Leave</option>
                @foreach($leaves as $value)
                    <option
                        value="{{$value->id}}" {{ isset($emp_leave) ? $emp_leave->leave_id == $value->id ? "selected" : "" :""  }}> {{ $value->title }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="form-group">
        <label for="name">Start Date <span class="text-red"> * </span> </label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            @if(isset($emp_leave))
                {!! Form::text('start_date', create_date_format($emp_leave->start_date,'/'),['id'=>'start_date','class'=>'form-control ','placeholder'=>'Enter start date', 'required', 'autocomplete'=>"off"]); !!}
            @else
                {!! Form::text('start_date', null,['id'=>'start_date','class'=>'form-control ','placeholder'=>'Enter start date', 'required', 'autocomplete'=>"off"]); !!}
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="name">End Date </label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            @if(isset($emp_leave) && $emp_leave->end_date != null)
                {!! Form::text('end_date', create_date_format($emp_leave->end_date,'/'),['id'=>'end_date','class'=>'form-control pull-right','placeholder'=>'Enter end date', 'autocomplete'=>"off"]); !!}
            @else
                {!! Form::text('end_date', null,['id'=>'end_date','class'=>'form-control ','placeholder'=>'Enter end date', 'autocomplete'=>"off"]); !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="display_name">Note </label>
        {!! Form::text('note', isset($emp_leave) ? $emp_leave->l_note : null,['id'=>'note','class'=>'form-control','placeholder'=>'Enter note']); !!}
    </div>

    @if(!\Auth::user()->hasRole(['user','project_manager']))
        <div class="form-group">
            <label for="display_name"> Accept Status </label>
            {!! Form::select('status',['3'=>'Requested','1'=>'Accepted', '2' =>"Canceled"],null,['id'=>'status','class'=>'form-control','required']); !!}
        </div>

    @endif



<div class="form-group">
    <label for="client_image">Attachment </label>
     {!! Form::file('attachment',null,['id'=>'attachment','class'=>'form-control']); !!}  <br/>

    @if(isset($emp_leave) && $emp_leave->attachment)
        <a target="_blank" class="btn btn-bitbucket" href="{{ $emp_leave->attachment ? $emp_leave->attached_file : 'javascript:void(0)' }}">
          <i class="fa fa-file"></i>  View file </a>
    @endif
</div>

</div>

@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

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



            $('.select2').select2();
        });

    </script>

@endpush

