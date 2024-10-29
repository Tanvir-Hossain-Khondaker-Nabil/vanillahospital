<?php
/**
 * Created by PhpStorm.
 * {{ $sharer_type }}: R-Creation
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
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Cash & Bank  Accounts',
    'title'=>'Create Cash & Bank  Account',
    'heading' => trans('common.create_cash_and_bank_account'),
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
                <h3 class="box-title">{{__('common.create')}} </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'member.cash_or_bank_accounts.store', 'method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.cash_or_bank_account._form')

                </div>

            <div class="box-footer">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">{{__("common.submit")}}</button>
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
            $('#bank_charge_account_id').val(20).trigger('change');
        });
    </script>
@endpush
