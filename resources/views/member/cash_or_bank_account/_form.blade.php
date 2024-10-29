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

        @include('common._alert')

    <div class="col-md-6">
        <div class="form-group">
            <label for="title">{{__('common.account_title')}} <span class="text-red"> * </span> </label>
            {!! Form::text('title',null,['id'=>'title','class'=>'form-control','placeholder'=>trans('common.enter_title'), 'required']); !!}
        </div>
        <div class="form-group">
            <label for="account_type"> {{__('common.gl_account_code')}} <span class="text-red"> * </span>  </label>
            {!! Form::select('account_type_id', $account_types, null,['id'=>'account_type','class'=>'form-control select2','placeholder'=>trans('common.please_select_gl_account'), 'required' ]); !!}
        </div>
{{--        <div class="form-group">--}}
{{--            <label for="bank_charge_account_id">Bank Charge Account <span class="text-red"> * </span>  </label>--}}
{{--            {!! Form::select('bank_charge_account_id', $account_types, null,['id'=>'bank_charge_account_id','class'=>'form-control select2','placeholder'=>'Please GL Account', 'required' ]); !!}--}}
{{--        </div>--}}
        <div class="form-group">
            <label for="description">{{__('common.description')}}  </label>
            {!! Form::textarea('description',null,['id'=>'description','class'=>'form-control','rows'=>'7', 'placeholder'=>trans('common.enter_description')]); !!}
        </div>

        <div class="form-group">
            <label for="initial_balance">{{__('common.initial_balance')}}  </label>
            {!! Form::number('initial_balance',null,['id'=>'initial_balance', 'step'=>"any",'class'=>'form-control input-number','placeholder'=>trans('common.enter_initital_balance')]); !!}
        </div>
        <div class="form-group">
            <label for="status">{{__('common.status')}} <span class="text-red"> * </span>  </label>
            {!! Form::select('status',['active'=>trans('common.active'), 'inactive'=>trans('common.inactive')], null,['id'=>'status','class'=>'form-control','required' ]); !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">{{__('common.contact_person')}} <span class="text-red"> * </span> </label>
            {!! Form::text('contact_person',null,['id'=>'contact_person','class'=>'form-control','placeholder'=>trans('common.enter_contact_person'), 'required']); !!}
        </div>
        <div class="form-group">
            <label for="phone">{{__('common.phone')}}  <span class="text-red"> * </span> </label>
            {!! Form::text('phone',null,['id'=>'phone','class'=>'form-control input-number','placeholder'=>trans('common.enter_phone_number'), 'required']); !!}
        </div>
        <div class="form-group">
            <label for="account_number">{{__('common.account_number')}}  </label>
            {!! Form::text('account_number',null,['id'=>'account_number','class'=>'form-control input-number','placeholder'=>trans('common.enter_account_number')]); !!}
        </div>
        <div class="form-group">
            <label for="internet_banking_url">{{__('common.internet_banking_url')}} </label>
            {!! Form::url('internet_banking_url',null,['id'=>'internet_banking_url','class'=>'form-control','placeholder'=>trans('common.enter_internet_banking_url')]); !!}
        </div>
        <div class="form-group">
            <label for="initial_balance"> {{__('common.balance')}}: <span class="text-red"> * </span>  </label><br/>
            {!! Form::radio('amount_type', 'credit', isset($model) && $model->transaction_type == "cr" ? "checked=checked" : "" ) !!} {{ __('common.credited') }}
            <label class="text-red">*{{__('common.if_you_give_any_amount_then_it_will_be_credit')}} </label> <br/>
            {!! Form::radio('amount_type', 'debit', isset($model) && $model->transaction_type == "dr" ? "checked=checked" : "" ) !!} {{ __('common.debited') }}
            <label class="text-red">*{{__('common.if_you_take_any_amount_then_it_will_be_debit')}} </label>
        </div>
        <div class="form-group">
            <label for="initial_balance">{{__('common.initial_balance_date')}}  </label>
            {!! Form::text('initial_date',isset($model) ? create_date_format(db_date_month_year_format($model->date), '/') : null,['id'=>'date','class'=>'form-control initial_date' ,'placeholder'=>trans('common.initial_balance_date'), 'autocomplete'=>'off']); !!}
        </div>
        <div class="form-group">
            <label for="initial_balance">{{__('common.b_f_text')}}  </label>
            {!! Form::text('bf_text',null,['id'=>'bf_text','class'=>'form-control' ,'placeholder'=>trans('common.enter_b_f_text')]); !!}
        </div>
    </div>


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
        });
    </script>
    @endpush

