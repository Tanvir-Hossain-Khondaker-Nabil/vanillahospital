<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/2/2019
 * Time: 12:28 PM
 */

 $route =  \Auth::user()->can(['member.transaction.index']) ? route('member.transaction.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Transaction',
        'href' => $route,
    ],
    [
        'name' => "Completed Transaction",
    ],
];

$data['data'] = [
    'name' => 'Completed Transaction',
    'title'=>'Completed Transaction',
    'heading' => 'Completed Transaction',
];
?>


@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">

                <div class="box-body">

                    <div class="text-center store-trans">
                        <a href="{{ route('member.general_ledger.show', $status['transaction_code']) }}" class="btn btn-primary"> View the GL Postings for this Payment </a>
                        <br/>
                        <a href="{{ route('member.transaction.create', 'Payment') }}" class="btn btn-success"><i class="fa fa-long-arrow-right"></i> New Payment / Expense</a>
                        <br/>
                        <a href="{{ route('member.transaction.create', 'Deposit') }}" class="btn btn-success"><i class="fa fa-long-arrow-right"></i> New Deposit / Received</a>
{{--                        <br/>--}}
{{--                        <a href="{{ route('member.transaction.create', 'Expense') }}" class="btn btn-info"><i class="fa fa-long-arrow-left"></i> New Expense</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
