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
        <label for="name">Store  Name <span class="text-red"> * </span> </label>
        {!! Form::text('store_name',null,['id'=>'store_name','class'=>'form-control','placeholder'=>'Enter Store Name', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="name">Full Address <span class="text-red"> * </span> </label>
        {!! Form::text('full_address', null,['class'=>'form-control','placeholder'=>'Enter address', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="name">Road <span class="text-red"> * </span> </label>
        {!! Form::text('city', null,['id'=>'city','class'=>'form-control','placeholder'=>'Enter Road', 'required']); !!}
    </div>
    {{-- <div class="form-group">
        <label for="status">Select Region  <span class="text-red"> * </span> </label>
        {!! Form::select('region_id',$regions, null,['id'=>'region_id','class'=>'form-control select2 select-region-district','placeholder'=>'Please select','required']); !!}
    </div>
    <div class="form-group">
        <label for="status">Select Thana   <span class="text-red"> * </span>   </label>
        {!! Form::select('thana_id',$thanas, null,['id'=>'thana_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
    </div> --}}

    <div class="form-group">
        <label for="store_image">Store Image (Image must be JPG) </label>
        {!! Form::file('store_image', null,['id'=>'store_image', 'accept'=>'image/*' , 'class'=>'form-control' ]); !!}
    </div>
    @if(isset($model) && $model->store_image != '')
    <div class="form-group">
        <img src="{{ $model->store_image_path }}" style="width: 200px !important;"/>
    </div>
    @endif

</div>



<div class="col-md-6">

    <div class="form-group">
        <label for="name">Mobile Number <span class="text-red"> * </span> </label>
        {!! Form::text('mobile_no',null,['id'=>'mobile_no','class'=>'form-control','placeholder'=>'Enter Mobile Number', 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">Latitude <span class="text-red"> * </span> </label>
        {!! Form::text('latitude',null,['id'=>'latitude','class'=>'form-control','placeholder'=>'Enter latitude', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="name">Longitude <span class="text-red"> * </span> </label>
        {!! Form::text('longitude',null,['id'=>'longitude','class'=>'form-control','placeholder'=>'Enter longitude', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="status">Select City    <span class="text-red"> * </span>  </label>
        {!! Form::select('district_id',$districts, null,['id'=>'district_id','class'=>'form-control select2 select-district-thana','placeholder'=>'Please select']); !!}
    </div>
    <div class="form-group">
        <label for="status">Select Area    <span class="text-red"> * </span>  </label>
        {!! Form::select('area_id',$areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
    </div>
    <div class="form-group">
        <label for="status">Approval Status  </label>
        {!! Form::select('approval_status',[ 1 =>'Approved', 0 => 'Declined'], null,['id'=>'approval_status','class'=>'form-control', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="status">Status  </label>
        {!! Form::select('active_status',[ 1 =>'Active', 0 => 'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>


@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(function () {

            $('.select2').select2();

        });

    </script>

    @include('common.area_script')

@endpush
