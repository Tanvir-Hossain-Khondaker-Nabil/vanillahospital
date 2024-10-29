<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush


<div class="col-md-6">
    <div class="form-group">
        <label for="name">Region Name <span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>'Enter Name', 'required']); !!}
    </div>

    <div class="form-group">
        <label for="status">Select State  <span class="text-red"> * </span> </label>
        {!! Form::select('division_id',$divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
    </div>

    <div class="form-group">
        <label for="status">Status  </label>
        {!! Form::select('active_status',['1'=>'Active', '0'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>




@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $('.select2').select2();
    </script>

@endpush
