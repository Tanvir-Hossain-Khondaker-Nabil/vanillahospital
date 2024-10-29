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

@include('common._error')
<input type="hidden" value="{{ $sharer_type }}" name="customer_type"/>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{__('common.full_name')}} <span class="text-red"> * </span> </label>
            {!! Form::text('name',old('name') ,['id'=>'name','class'=>'form-control','placeholder'=>trans('common.enter_full_name'), 'required']); !!}
        </div>
        <div class="form-group">
            <label for="email">{{__('common.email')}} </label>
            {!! Form::email('email',null,['id'=>'email','class'=>'form-control','placeholder'=>trans('common.enter_email')]); !!}
        </div>
        {{--<div class="form-group">--}}
            {{--<label for="manager_id">Manager </label>--}}
            {{--{!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="district_id">District </label>--}}
            {{--{!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>'Select District']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="union_id">Union </label>--}}
            {{--{!! Form::select('union_id', $unions, null,['id'=>'union_id','class'=>'form-control select2','placeholder'=>'Select Union']); !!}--}}
        {{--</div>--}}

        @if($sharer_type != "Broker")
        <div class="form-group">
            <label for="initial_balance">{{__('common.initial_balance')}}  </label>
            {!! Form::number('initial_balance',isset($model) ? $bf_balance ? $bf_balance->amount : 0 : null,['id'=>'initial_balance','class'=>'form-control input-number', 'step'=>"any" ,'placeholder'=>trans('common.enter_initital_balance')]); !!}

        </div>
        <div class="form-group">
            <label for="initial_balance"> {{__('common.balance')}}: <span class="text-red"> * </span>  </label><br/>
            {!! Form::radio('amount_type', 'credit', isset($model) && $bf_balance ? $bf_balance->transaction_type == 'cr' ? "checked" : null: null ) !!} {{ __('common.credited') }} <label class="text-red">*{{__('common.if_you_give_any_amount_then_it_will_be_credit')}}</label> <br/>
            {!! Form::radio('amount_type', 'debit', isset($model) && $bf_balance ? $bf_balance->transaction_type == 'dr' ? "checked" : null:  "checked" ) !!} {{ __('common.debited') }}
            <label class="text-red">*{{__("common.if_you_take_any_amount_then_it_will_be_debit")}}</label>
        </div>

        <div class="form-group">
            <?php if( isset($model)) { ?>
            {!! Form::checkbox('supplier', 1, null ,[ isset($model) && ($model->customer_type == 'both' || $model->customer_type == 'supplier') ? "checked='checked'" : '']) !!} {{ __('common.also_save_as_supplier') }} <br/>
            {!! Form::checkbox('customer', 1, null ,[ isset($model) && ($model->customer_type == 'both' || $model->customer_type == 'customer') ? "checked='checked'" : '']) !!} {{ __('common.also_save_as_customer') }}
            <?php }else{ ?>
            {{-- {!! Form::checkbox('both', 1, null ) !!} {{ __('Also Save as') }} {{ $sharer_type == "Supplier" ? "Customer" : "Supplier" }} --}}
            {!! Form::checkbox('both', 1, null ) !!}  {{ $sharer_type == "Supplier" ? trans('common.also_save_as_customer') : trans('common.also_save_as_supplier')}}

            <?php } ?>
        </div>
        @endif
        <div class="form-group">
            <label for="status">{{__('common.status')}} <span class="text-red"> * </span>  </label>
            {!! Form::select('status',['active'=>trans('common.active'), 'inactive'=>trans('common.inactive')], null,['id'=>'status','class'=>'form-control', 'required' ]); !!}
        </div>
</div>

<div class="col-md-6">
        <div class="form-group">
            <label for="phone">{{__('common.phone')}}  <span class="text-red"> * </span> </label>
            {!! Form::text('phone',null,['id'=>'phone','class'=>'form-control input-number','placeholder'=>trans('common.enter_phone_number'), 'required' ]); !!}
        </div>
        <div class="form-group">
            <label for="address" >{{__('common.address')}}   <span class="text-red"> * </span> </label>
            {!! Form::text('address',null,['id'=>'address','class'=>'form-control','placeholder'=>trans('common.enter_address'), 'required' ]); !!}
        </div>
        {{--<div class="form-group">--}}
            {{--<label for="division_id">Division </label>--}}
            {{--{!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Select Division']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="upazilla_id">Upazilla </label>--}}
            {{--{!! Form::select('upazilla_id', $upazillas, null,['id'=>'upazilla_id','class'=>'form-control select2','placeholder'=>'Select Upazilla']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="area_id">Area </label>--}}
            {{--{!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select Area']); !!}--}}
        {{--</div>--}}
    @if($sharer_type != "Broker")
        <div class="form-group">
            <label for="initial_balance">{{__('common.initial_balance_date')}}   </label>
            {!! Form::text('initial_date', isset($model) ? $bf_balance ? create_date_format($bf_balance->date, "/") : "" : null, ['id'=>'date','class'=>'form-control initial_date' ,'placeholder'=>trans('common.initial_balance_date'), 'autocomplete'=>'off']); !!}
        </div>
    @endif


        {{--<div class="form-group">--}}
            {{--{!! Form::checkbox('is_blacklist', 1, null, [ isset($model) && ($model->is_blacklist == 1) ? "checked='checked'" : ''] ) !!} <b> {{ __(' Is Blocked Party') }} </b>--}}

{{--            {!! Form::checkbox('account_head', 1, null ) !!} {{ __('Create Account Head') }}  <br/>--}}
{{--            {!! Form::checkbox('cash_bank', 1, null ) !!} {{ __('Create Cash and Bank Account') }}--}}
        {{--</div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="balance_limit"> Balance Limit  </label>--}}
{{--            {!! Form::number('balance_limit',null,['id'=>'balance_limit','class'=>'form-control input-number' ,'placeholder'=>'Enter Balance Limit']); !!}--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="purchase_limit"> Purchase Limit  </label>--}}
{{--            {!! Form::number('purchase_limit',null,['id'=>'purchase_limit','class'=>'form-control input-number' ,'placeholder'=>'Enter Purchase Balance Limit']); !!}--}}
{{--        </div>--}}

        @if($sharer_type == "customer")
            <div class="form-group">
                <label for="sale_limit"> {{__('common.sale_limit')}}  </label>
                {!! Form::number('sale_limit',null,['id'=>'sale_limit','class'=>'form-control input-number', 'step'=>"any" ,'placeholder'=>trans('common.enter_sale_balance_limit')]); !!}
            </div>
        @endif

        @if($sharer_type != "Broker")
        <div class="form-group">
            <label for="initial_balance">{{__('common.b_f_text')}}  </label>
            {!! Form::text('bf_text',isset($model) && $bf_balance ? $bf_balance->description  : null,['id'=>'bf_text','class'=>'form-control' ,'placeholder'=>trans('common.enter_b_f_text')]); !!}
        </div>
        @endif

        <div class="form-group">
            <label for=file> {{__('common.attach_photo')}}  </label>
            {{-- <input  name="photo" type="file" accept="image/*"/>
             --}}
         <input type="file" id='image' class="form-control" accept="image/*" name="photo"
             placeholder="Import image" onchange="getImagePreview(this)">
         <input type="hidden" id="front-image-url" value="">
         <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
         </div>
         
        </div>
        {{--<div class="form-group">--}}
            {{--<label for=file> Attach if File Available  </label>--}}
            {{--<input  name="file" type="file" accept="*"/>--}}
        {{--</div>--}}
</div>
{{--<div class="">--}}
    {{--<div class="form-group">--}}
        {{--<label class="col-md-12">Collected Files </label>--}}
        {{--@foreach($document_types as $key=>$value)--}}
        {{--<div class="col-md-4 mb-3">--}}
            {{--{!! Form::checkbox('submitted_documents[]', $key, null ,[ (isset($model) && in_array($key, $submitted_documents)) ? "checked=checked" : '']) !!} {{ $value }} <br/>--}}

        {{--</div>--}}
            {{--@endforeach--}}
    {{--</div>--}}
{{--</div>--}}



@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        // var date = new Date();
        $(function () {
            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();

        });
    </script>

    @include('common.area_script')

@endpush
