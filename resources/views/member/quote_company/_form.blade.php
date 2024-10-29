<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:55 PM
 */
?>


@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush


    <div class="col-md-6">

        <div class="form-group  {{ $errors->has('company_name') ? 'has-error' : '' }} ">
            <label for="name">Company Name<span class="text-red"> * </span> </label>
            {!! Form::text('company_name', null, ['id'=>'company_name','class'=>'form-control','placeholder'=>'Enter Company Name', 'required']); !!}
        </div>

        <div class="form-group  {{ $errors->has('address_1') ? 'has-error' : '' }} ">
            <label for="name">Address Line 1 <span class="text-red"> * </span> </label>
            {!! Form::text('address_1', null, ['id'=>'address_1','class'=>'form-control','placeholder'=>'Enter Address ', 'required']); !!}
        </div>


        <div class="form-group  {{ $errors->has('contact_no') ? 'has-error' : '' }} ">
            <label for="name">Contact No  <span class="text-red"> * </span></label>
            {!! Form::text('contact_no', null, ['id'=>'contact_no','class'=>'form-control','placeholder'=>'Enter contact no', 'required']); !!}
        </div>


    </div>
    <div class="col-md-6">

        <div class="form-group  {{ $errors->has('designation') ? 'has-error' : '' }} ">
            <label for="name">Designation <span class="text-red"> * </span> </label>
            {!! Form::text('designation', null, ['id'=>'designation','class'=>'form-control','placeholder'=>'Enter designation', 'required']); !!}
        </div>


        <div class="form-group  ">
            <label for="name">Address Line 2 </label>
            {!! Form::text('address_2', null, ['id'=>'address_2','class'=>'form-control','placeholder'=>'Enter Address ']); !!}
        </div>

        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">Select Status  </label>
            {!! Form::select('status', ['1'=>'Active', '0'=>'Inactive'] , null,['id'=>'status','class'=>'form-control']); !!}
        </div>
    </div>



@push('scripts')

   <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">

        // var date = new Date();
        $(function () {

            $('.select2').select2();

        });
    </script>

@endpush


