<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

/*
 * TODO: Fiscal year date Edit is not fixed yet
 */
?>

@push('styles')

<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush



<div class="col-md-7">
    <div class="form-group">
        <label for="name">Fiscal Year <span class="text-red"> * </span> </label>
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
            </div>
            {!! Form::text('fiscal_year', null,['id'=>'fiscal_year','class'=>'form-control pull-right','placeholder'=>'Enter Fiscal Year Range', 'required']); !!}
        </div>
    </div>
{{--    <div class="form-group">--}}
{{--        <label for="display_name">Title<span class="text-red"> * </span> </label>--}}
{{--        {!! Form::text('title',null,['id'=>'title','class'=>'form-control','placeholder'=>'Enter Title', 'required']); !!}--}}
{{--    </div>--}}

    <div class="form-group">
        <label for="display_name">Status </label>
        {!! Form::select('status',['active'=>'Active','inactive'=>'Inactive'],null,['id'=>'status','class'=>'form-control','required']); !!}
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

    $('#fiscal_year').daterangepicker();

      $('.select2').select2();
  });

</script>

@endpush

