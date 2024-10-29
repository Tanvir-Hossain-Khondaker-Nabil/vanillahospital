<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/21/2019
 * Time: 5:01 PM
 */
?>


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

<div class="box">
    <div class="box-body">
        <div class="col-md-6">
            <div class="form-group">
                <label for="date"> Branch Name </label>
                {!! Form::text('branch_name', null,['id'=>'branch_name', 'class'=>'form-control','required']); !!}
            </div>

            <div class="form-group">
                <label for="display_name">Bank <span class="text-red"> * </span> </label>
                {!! Form::select('bank_id',$banks,null,['id'=>'bank_id','class'=>'form-control select2','placeholder'=>'Select bank', 'required']); !!}
            </div>
            <div class="form-group">
                <label for="bank_id"> Status </label>
                {!! Form::select('status', [ 'active'=>'Active', 'inactive'=>'inactive'], null,['id'=>'status', 'class'=>'form-control']); !!}
            </div>
        </div>
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
