<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/24/2019
 * Time: 2:53 PM
 */

?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.transaction_search')}} </h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            @if(Auth::user()->hasRole(['super-admin', 'developer']))
                <div class="col-md-4">
                    <label>{{__('common.select_company')}}    </label>
                    {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('select_company'), 'required']); !!}
                </div>
            @endif
            <div class="col-md-4">
                <label>{{__('common.transaction_code')}} </label>
                <input class="form-control" name="transaction_code" value="{{ isset($transaction_code) ? $transaction_code : ''  }}" type="text"/>
            </div>

            <div class="col-md-4">
                <label>{{__('common.accounts_head')}}  </label>
                {!! Form::select('head_account_type_id', $accounts, isset($head_account_type_id) ? $head_account_type_id : '',['id'=>'head_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
            </div>
        </div>
        <!-- /.row -->
    </div>

</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.date_wise_search')}}</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="col-md-4">
                <label>{{__('common.from_date')}} ({{__('common.transaction')}})</label>
                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
            </div>
            <div class="col-md-4">
                <label>{{__('common.to_date')}} ({{__('common.transaction')}})</label>
                <input class="form-control date" name="to_date" value="" autocomplete="off"/>
            </div>
            <div class="col-md-4">
                <label>{{__('common.accounts_head')}}   </label>
                {!! Form::select('d_head_account_type_id', $accounts, isset($d_head_account_type_id) ? $d_head_account_type_id : '',['id'=>'d_head_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
            </div>

        </div>
        <!-- /.row -->
    </div>

</div>


<div class="col-md-4">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{__('common.group_wise_search')}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">

                <div class="col-md-12">
                    <label>{{__('common.accounts_group')}}   </label>
                    {!! Form::select('group_account_type_id', $account_groups, isset($group_account_type_id) ? $group_account_type_id : '',['id'=>'group_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                </div>

            </div>
            <!-- /.row -->
        </div>

    </div>
</div>
<div class="col-md-4">

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.head_wise_search')}}</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                <label>{{__('common.accounts_head')}}   </label>
                {!! Form::select('head_account_type_id', $accounts, isset($head_account_type_id) ? $head_account_type_id : '',['id'=>'head_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
            </div>

        </div>
        <!-- /.row -->
    </div>

</div>
</div>

<div class="col-md-4">
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.sub_head_wise_search')}}</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                <label>{{__('common.accounts_sub_head')}}  </label>
                {!! Form::select('sub_head_account_type_id', $account_sub_heads, isset($sub_head_account_type_id) ? $sub_head_account_type_id : '',['id'=>'sub_head_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
            </div>

        </div>
        <!-- /.row -->
    </div>

</div>
</div>

<div class="box-body">

    <div class="col-md-3">
        <input class="btn btn-info" value="{{__('common.search')}}"" type="submit"/>
    </div>
</div>
