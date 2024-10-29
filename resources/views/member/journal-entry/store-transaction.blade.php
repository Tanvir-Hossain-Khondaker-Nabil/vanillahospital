<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/2/2019
 * Time: 12:28 PM
 */
 $route =  \Auth::user()->can(['member.general_ledger.index']) ? route('member.general_ledger.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'General Ledger',
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
                        <a href="{{ route('member.journal_entry.show', $status['transaction_code']) }}" class="btn btn-primary"> View the GL Postings for this Payment </a>
                        <br/>
                        <a href="{{ route('member.journal_entry.create') }}" class="btn btn-success"><i class="fa fa-exchange"></i> New Journal Entry</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
