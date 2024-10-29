<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 11/25/2019
 * Time: 3:36 PM
 */


 $route =  \Auth::user()->can(['member.reconciliation.index']) ? route('member.reconciliation.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reconciliation',
        'href' => $route,
    ],
    [
        'name' => "Completed Reconciliation",
    ],
];

$data['data'] = [
    'name' => 'Completed Reconciliation',
    'title'=>'Completed Reconciliation',
    'heading' => 'Completed Reconciliation',
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
                        <a href="{{ route('member.reconciliation.show', $transaction_code) }}" class="btn btn-primary"> View this Payment </a>
                        <br/>
                        <a href="{{ route('member.reconciliation.create', 'supplier') }}" class="btn btn-success"><i class="fa fa-long-arrow-right"></i> Supplier Reconciliation</a>
                        <br/>
                        <a href="{{ route('member.reconciliation.create', 'bank') }}" class="btn btn-success"><i class="fa fa-long-arrow-right"></i> Bank Reconciliation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
