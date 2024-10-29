<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 4/16/2023
 * Time: 3:54 PM
 */

$route = \Auth::user()->can(['member.quotations.index']) ? route('member.quotations.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quotation',
        'href' => $route,
    ],

    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Create Quotation',
    'title' => 'Create Quotation',
    'heading' => ' Quotation',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

            @include('common._alert')

            @include('common._error')
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Create Quotation</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open([
                    'route' => 'member.quotations.save-others-transaction',
                    'method' => 'POST',
                    'files' => true,
                    'id' => 'requisition_form',
                    'role' => 'form',
                ]) !!}

                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Quotation REF <span class="text-red"> * </span> </label>
                            {!! Form::select('quotation_id', $quotations, null, [
                                'id' => 'quotation_id',
                                'class' => 'form-control select2',
                                'placeholder' => 'Please select',
                                'required',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            <label for="item_name">Transaction Code <span class="text-red"> * </span> </label>
                            {!! Form::text('transaction_code', null, [
                                'id' => 'transaction_code',
                                'class' => 'form-control',
                                'placeholder' => 'Enter transaction code',
                                'required',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            <label>Transaction Type (Given/Taken) <span class="text-red"> * </span> </label>
                            {!! Form::select('transaction_type', ['cr' => 'Given', 'dr' => 'Take Back'], null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter transaction type',
                                'required',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            <label for="item_name">Note </label>
                            {!! Form::text('note', null, ['id' => 'note', 'class' => 'form-control', 'placeholder' => 'Enter Note']) !!}
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12 text-left">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>


    <script type="text/javascript">
        $(function() {

            $('.select2').select2();
        });
    </script>
@endpush
