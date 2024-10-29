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
                <label for="supplier_id"> {{__('common.customer')}} / {{__('common.supplier_name')}}<span class="text-red">*</span> </label>
                {!! Form::select('sharer_id', $sharers, null,['id'=>'supplier_id', 'class'=>'form-control select2','required', 'placeholder'=>trans('common.select_name')]); !!}
            </div>
            <div class="form-group">
                <label for="date">{{__('common.received_date')}}   <span class="text-red">*</span> </label>
                {!! Form::text('giving_date', isset($model) ? create_date_format($model->giving_date, '/') :null, [ 'class'=>'form-control date','required']); !!}
            </div>
            <div class="form-group">
                <label for="date">{{__('common.cheque_no')}}    <span class="text-red">*</span> </label>
                {!! Form::number('cheque_no', null,['id'=>'amount', 'class'=>'form-control','required']); !!}
            </div>
            <div class="form-group">
                <label for="date">{{__('common.notes')}}  ({{__('common.optional')}} )</label>
                {!! Form::text('note', null,['id'=>'notes', 'class'=>'form-control']); !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="bank_id">{{__('common.bank_name')}}   <span class="text-red">*</span> </label>
                {!! Form::select('bank_id', $banks, null,['id'=>'bank_id', 'class'=>'form-control select2','required', 'placeholder'=>trans('common.select_bank_name')]); !!}
            </div>
            <div class="form-group">
                <label for="date">{{__('common.placing_date')}}   <span class="text-red">*</span> </label>
                {!! Form::text('issue_date', isset($model) ? create_date_format($model->issue_date, '/') : null, ['class'=>'form-control date','required']); !!}
            </div>
            <div class="form-group">
                <label for="date"> {{__('common.amount')}}    <span class="text-red">*</span> </label>
                {!! Form::number('amount', null,['id'=>'amount', 'class'=>'form-control','required']); !!}
            </div>
            <div class="form-group">
                <label for="date"> {{__('common.file')}}  /  {{__('common.cheque_image')}}  ( {{__('common.optional')}} ) </label>
                {{-- {!! Form::file('file', null,['id'=>'file']); !!} --}}

            <input type="file" id='file' class="form-control" accept="image/*" name="file"
                placeholder="Import image" onchange="getImagePreview(this)">
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
            </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $(".date").datepicker();
        });
    </script>
@endpush

