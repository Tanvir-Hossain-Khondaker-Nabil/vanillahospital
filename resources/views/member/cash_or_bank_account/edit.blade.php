<?php
/**
 * Created by PhpStorm.
 * Cash or Bank Account: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */


 $route =  \Auth::user()->can(['member.cash_or_bank_accounts.index']) ? route('member.cash_or_bank_accounts.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Cash & Bank  Accounts',
        'href' =>  $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Cash & Bank  Accounts',
    'title'=>'Edit Cash & Bank  Account',
    'heading' => trans('common.edit_cash_and_bank_account'),
];

?>
@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._error')

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.edit_cash_and_bank_account')}} </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($model, ['route' => ['member.cash_or_bank_accounts.update', $model],  'method' => 'put']) !!}

                <div class="box-body">

                    @include('member.cash_or_bank_account._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">{{__('common.edit_cash_and_bank_account')}}</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection



@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
        });
    </script>
@endpush
