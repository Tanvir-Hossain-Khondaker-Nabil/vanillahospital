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


    <div class="col-md-7">

        <div class="form-group  {{ $errors->has('short_tag') ? 'has-error' : '' }} ">
            <label for="name">Short Text <span class="text-red"> * </span> </label>
            {!! Form::text('short_tag', null, ['id'=>'short_tag','class'=>'form-control','placeholder'=>'Enter Short Text', 'required']); !!}
        </div>

        <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }} ">
            <label for="name">Title <span class="text-red"> * </span> </label>
            {!! Form::textarea('name', null, ['id'=>'name','class'=>'form-control','placeholder'=>'Enter Name', 'required']); !!}
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


