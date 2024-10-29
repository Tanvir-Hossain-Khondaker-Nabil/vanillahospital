<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush
<div class="col-md-6">
    <div class="form-group">
        <label for="name">State Name <span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>'Enter  Name', 'required']); !!}
    </div>
    <div class="form-group division">
        <label for="status">Country <span class="text-red"> </span> </label>
        {!! Form::select('country_id', $countries, null, [
            'class' => 'form-control select2 select-region',
            'placeholder' => 'Please select',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="status">Status  </label>
        {!! Form::select('active_status',['1'=>'Active', '0'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>


@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $('.select2').select2();

        });

    </script>


@endpush
