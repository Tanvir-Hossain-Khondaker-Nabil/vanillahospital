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
        <label for="name">Branch Name<span class="text-red"> * </span> </label>
        {!! Form::text('display_name',null,['id'=>'display_name','class'=>'form-control','placeholder'=>'Enter Branch Name', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="phone">Phone No<span class="text-red"> * </span> </label>
        {!! Form::text('phone',null,['id'=>'phone','class'=>'form-control','placeholder'=>'Enter Phone No', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="phone">Email<span class="text-red"> * </span> </label>
        {!! Form::text('email',null,['id'=>'email','class'=>'form-control ','placeholder'=>'Enter Email', 'required']); !!}
    </div>


    <div class="form-group">
        <label for="display_name">Address <span class="text-red"> * </span> </label>
        {!! Form::textarea('address',null,['id'=>'address','class'=>'form-control','placeholder'=>'Enter Address',  'rows'=>"2"]); !!}
    </div>
    <div class="form-group">
        <label for="city">City  </label>
        {!! Form::text('city',null,['id'=>'city','class'=>'form-control','placeholder'=>'Enter City']); !!}
    </div>
    <div class="form-group">
        <label for="display_name">Country <span class="text-red"> * </span> </label>
        {!! Form::select('country_id',$countries,null,['id'=>'country','class'=>'form-control select2','placeholder'=>'Select Country','required']); !!}
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


@endpush

