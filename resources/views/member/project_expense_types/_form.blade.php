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

    <div class="col-md-7">

        <div class="form-group">
            <label for="display_name">Display Name <span class="text-red"> * </span> </label>
            {!! Form::text('display_name',null,['id'=>'display_name','class'=>'form-control','placeholder'=>'Enter Display Name', 'required']); !!}
        </div>

        <div class="form-group">
            <label for="description">Description  </label>
            {!! Form::textarea('description',null,['id'=>'description','class'=>'form-control','placeholder'=>'Enter description']); !!}
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

@endpush
