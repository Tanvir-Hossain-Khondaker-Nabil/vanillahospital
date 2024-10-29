<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 4:16 PM
 */

$route = \Auth::user()->can(['member.project_expenses.index']) ? route('member.project_expenses.index') : "#";
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project Expenses',
        'href' => $route,
    ],
    [
        'name' => $project_expenses->code,
    ],
];

$data['data'] = [
    'name' => 'Project Expense : ' . $project_expenses->code,
    'title' => 'Code: ' . $project_expenses->code,
    'heading' => 'Project Expense: ' . $project_expenses->code,
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <a class="btn btn-xs btn-primary"
                       href="{{ route('member.project_expenses.show', $project_expenses->id) }}?based=print" id="btn-print"><i
                            class="fa fa-print"></i> Print</a>

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['super-admin', 'admin']))
                        <a href="{{ route('member.project_expenses.edit',  $project_expenses->id) }}"
                           class="btn btn-xs btn-success">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm"
                           data-target="{{route('member.project_expenses.destroy', $project_expenses->id)}}">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">

                <div class="box-body">

                    <div class="row">
                        <div class="col-xs-4 text-left">
                            @if( isset($company_logo))
                                <img src="{{ $company_logo }}" width="100px;"/>
                            @endif
                        </div>
{{--                        <div class="col-xs-4 text-center py-5">--}}
{{--                            @php print_r($barcode) @endphp<br>--}}
{{--                        </div>--}}
                        <div class="col-xs-8 text-right company-info">
                            <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
                            <div class="info">
                                <p>{{ $company_address }} </p>
                                <p>{{ $company_city.($company_country ? ", ".$company_country : "") }}</p>
                                <p>Phone : {{ $company_phone }}</p>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- title row -->

                    <!-- info row -->
                    <div style="margin-bottom: 10px; " class="row invoice-info">

                        <div class="col-md-8 p-5">
                            <table class="bill-info w-100">
                                <tr>
                                    <th style="width: 70px !important;">Date:</th>
                                    <td>{{ $project_expenses->date_format }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">Code:</th>
                                    <td>{{ $project_expenses->code }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">Project:</th>
                                    <td>{{ $project_expenses->project->project }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">Created By:</th>
                                    <td>
                                        {{ $project_expenses->createdBy->uc_full_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">Transaction Code:</th>
                                    <td>
                                        @if(Auth::user()->can(['member.general_ledger.show']))
                                            <a href="{{ route('member.general_ledger.show', $project_expenses->transaction->transaction_code) }}" target="_blank"> {{ $project_expenses->transaction->transaction_code }} </a>
                                        @else
                                        {{ $project_expenses->transaction->transaction_code }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.row -->


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">

                            <table  width="400px" class="table table-responsive table-bordered margin-top-30 float-right">
                                <tbody>
                                <tr>
                                    <th>#SL</th>
                                    <th>Expense Name</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                                @php $total = 0; @endphp

                                @foreach($project_expenses->projectExpenseDetails as $key => $value)

                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->projectExpenseType->display_name }}</td>
                                        <td class="text-right">{{ create_money_format($value->amount) }}</td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <th class="text-right" colspan="2"> Total</th>
                                        <th class="text-right"> {{ create_money_format($project_expenses->total_amount) }} </th>
                                    </tr>
                                </tbody>

                            </table>
{{--                            <table class="margin-top-30" style="width: 700px; float: left;">--}}
{{--                                <tr>--}}
{{--                                    <th> Notes:</th>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        @php print_r($project_expenses->notation) @endphp--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
