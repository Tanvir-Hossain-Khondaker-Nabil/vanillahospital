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

    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{__('category.display_name')}} <span class="text-red"> * </span> </label>
            {!! Form::text('display_name', null, ['id'=>'display_name','class'=>'form-control','placeholder'=>'Enter Name', 'required']); !!}
        </div>


        <div class="form-group">
            <label for="parent_id">{{__('common.gl_account_parent_group')}}  </label>
            {!! Form::select('parent_id', $accounts, null,['id'=>'parent_id','class'=>'form-control select2','placeholder'=>trans('common.enter_gl_account_group_name')]); !!}
        </div>

        <div class="form-group">
            <label for="initial_balance">{{__('common.initial_balance')}}  </label>
            {!! Form::number('initial_balance',null,['id'=>'initial_balance','class'=>'form-control input-number', 'step'=>"any",'placeholder'=>trans('common.enter_initital_balance')]); !!}
        </div>
        <div class="form-group">
            <label for="initial_balance">{{__('common.initial_balance_date')}}  </label>
            {!! Form::text('initial_date',isset($modal) ? create_date_format(db_date_month_year_format($modal->date), '/') : null,['id'=>'date','class'=>'form-control initial_date' ,'placeholder'=>trans('common.initial_balance_date'), 'autocomplete'=>'off']); !!}
        </div>
        <div class="form-group">
            <label for="status">{{__('common.status')}} <span class="text-red"> * </span>  </label>
            {!! Form::select('status',['active'=>trans('common.active'), 'inactive'=>trans('common.inactive')], null,['id'=>'status','class'=>'form-control', 'required']); !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{__('common.name')}} <span class="text-red"> * </span> </label>
            {!! Form::text('name', null, ['id'=>'name','class'=>'form-control','placeholder'=>trans('common.enter_name'),  isset($modal) ? 'readonly': 'required']); !!}
        </div>
        <div class="form-group">
            <label for="class_id">{{__('common.gl_account_class')}} </label>
            {!! Form::select('class_id', $account_groups, null,['id'=>'class_id','class'=>'form-control select2','placeholder'=>trans("common.enter_gl_account_class")]); !!}
        </div>
        <div class="form-group">
            <label for="initial_balance"> {{__('common.balance')}}: <span class="text-red"> * </span>  </label><br/>
            {!! Form::radio('amount_type', 'credit', isset($modal) && $modal->transaction_type == "cr" ? "checked=checked" : "" ) !!} {{ __('common.credited_amount') }}
            <br/>
            {!! Form::radio('amount_type', 'debit', isset($modal) && $modal->transaction_type == "dr" ? "checked=checked" : "" ) !!} {{ __('common.debited_amount') }}
        </div>

        <div class="form-group">
            <label for="initial_balance">{{__('common.b_f_text')}}  </label>
            {!! Form::text('bf_text',null,['id'=>'bf_text','class'=>'form-control' ,'placeholder'=>trans('common.enter_b_f_text')]); !!}
        </div>
    </div>

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        // var date = new Date();
        $(function () {

            $('.select2').select2();
        });

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
