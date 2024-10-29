<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')

<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush



<div class="col-md-7">

    @if(!\Auth::user()->hasRole(['user']))
    <div class="form-group">
        <label for="status"> Employee  <span class="text-red"> * </span> </label>
        <select class="form-control select2" required name="employee_id" id="employee_id" >
            <option value=""> Select Employee</option>
            @foreach($employees as $value)
                <option value="{{$value->id}}"  > {{ $value->employeeID." - ".$value->first_name." ".$value->last_name }}</option>
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
            {!! Form::text('start_date', null,['id'=>'start_date','class'=>'form-control ','placeholder'=>'Enter start date', 'required', 'autocomplete'=>"off"]); !!}
        </div>
    </div>
    <div class="form-group">
        <label for="name">End Date  </label>
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
            </div>
            {!! Form::text('end_date', null,['id'=>'end_date','class'=>'form-control pull-right','placeholder'=>'Enter end date', 'autocomplete'=>"off"]); !!}
        </div>
    </div>
    <div class="form-group">
        <label for="display_name">Note<span class="text-red"> * </span> </label>
        {!! Form::text('note',null,['id'=>'note','class'=>'form-control','placeholder'=>'Enter note']); !!}
    </div>

    <div class="form-group">
        <label for="display_name"> Accept Status </label>
        {!! Form::select('status',['0'=>'Requested','1'=>'Accepted'],null,['id'=>'status','class'=>'form-control','required']); !!}
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

      $('#end_date').datepicker();
      $('#start_date').datepicker();

      $('.select2').select2();
  });

</script>

@endpush

