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
        <label for="name">Area Name<span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>'Enter Area Name', 'required']); !!}
    </div>


    <div class="form-group">
        <label for="status">Select State  <span class="text-red"> * </span> </label>
        {!! Form::select('division_id',$divisions, null,['id'=>'division_id','class'=>'form-control select2 select-region','placeholder'=>'Please select']); !!}
    </div>
    {{-- <div class="form-group">
        <label for="status">Select Region  <span class="text-red"> * </span> </label>
        {!! Form::select('region_id',$regions, null,['id'=>'region_id','class'=>'form-control select2 select-region-district','placeholder'=>'Please select']); !!}
    </div> --}}
    <div class="form-group">
        <label for="status">Select City  <span class="text-red"> * </span> </label>
        {!! Form::select('district_id',$districts, null,['id'=>'district_id','class'=>'form-control select2 select-district-thana','placeholder'=>'Please select']); !!}
    </div>

    {{-- <div class="form-group">
        <label for="status">Select Thana  <span class="text-red"> * </span> </label>
        {!! Form::select('thana_id',$thanas, null,['id'=>'thana_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
    </div> --}}

    <div class="form-group">
        <label for="status">Status  </label>
        {!! Form::select('status',['active'=>'Active', 'inactive'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>

    @push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

          $(function () {

            $('.select2').select2()
        });

    </script>

        @include('common.area_script')

@endpush

