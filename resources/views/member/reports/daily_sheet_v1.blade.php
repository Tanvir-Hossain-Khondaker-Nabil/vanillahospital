<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/3/2019
 * Time: 12:46 PM
 */

 $route = \Auth::user()->can(['member.report.daily_sheet']) ? route('member.report.daily_sheet'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sheets',
        'href' => $route,
    ],
    [
        'name' => 'Daily Sheet Report',
    ],
];

$data['data'] = [
    'name' => 'Daily Sheet Report',
    'title'=> 'Daily Sheet Report',
    'heading' => 'Daily Sheet Report',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <style>
        .table {
             margin-bottom: 0px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

            {!! Form::open(['route' => 'member.report.daily_sheet','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label> Transaction Type </label>
                            <select class="form-control" name="transaction_method">
                                <option value=""> Select </option>
                                <option value="Purchases"> Purchase</option>
                                <option value="Sales"> Sales</option>
                                <option value="Income">Income</option>
                                <option value="Expense">Expense</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label> From Date </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> To Date</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="Search" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                {{--<div class="box-body">--}}

                {{----}}
                {{--</div>--}}

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Daily Sheet Report</h3>
                    <a class="btn btn-sm  btn-primary pull-right" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                    <a class="btn btn-sm  btn-success pull-right btn-print" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=single_print"> <i class="fa fa-print"></i> Single Page Print</a>
                </div>

                <div class="box-body">
                    <div class="col-lg-6" style="padding-left: 0;padding-right: 0;">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th colspan="3" class="text-center border-right-1"> ------- Credit -------</th>
                            </tr>
                            <tr>
                                <th>Vo. No</th>
                                <th>Particulars</th>
                                {{--                                <th>L.P. No</th>--}}
                                <th class="border-right-1 text-right">Taka</th>
                            </tr>

                            @php
                                $total_credit = $last_transaction  = 0;
                            @endphp
                            @foreach($credits as $key=>$value)
                                @if($value->sale_id > 0 && ($value->account_name == "Accounts Receivable" || $value->account_name == "Cash"))
                                    @php
                                        $sales = \App\Models\Sale::find($value->sale_id);
                                        $total = $sale_print = 0;
                                        $account_name = $value->account_name;
                                        $sharer_name = $value->sharer_name;
                                        $value_transaction_id = $value->transaction_id;
                                        $credit = $value->credit;
                                    @endphp
                                    <tr>
                                        <td>{{ $value->transaction_id }}</td>
                                        <td width="350px">
                                            @if($last_transaction==$value_transaction_id )
                                                {{ $sharer_name." (".$value->account_name.")" }}
                                            @else
                                                <table class="table table-bordered">
                                                    @foreach($sales->sale_details as $key=>$value)
                                                        <tr>
                                                            <td>{{$value->item->item_name}}</td>
                                                            <td>{{$value->qty.$value->item->unit." X ".$value->price}}</td>
                                                            <td class="text-right">= {{create_money_format($value->total_price)}}</td>
                                                        </tr>
                                                        @php
                                                            $total += $value->total_price;
                                                        @endphp
                                                        @if ($loop->last && $key>0)
                                                            <tr>
                                                                <th class="text-right font-italic" colspan="3">{{create_money_format($total)}}</th>
                                                            </tr>
                                                        @endif

                                                    @endforeach

                                                </table>
                                                ( {{ " SALES & ".$account_name." (".$sharer_name.")" }})
                                                @php
                                                    $sale_print++;
                                                @endphp

                                            @endif

                                        </td>
                                        {{-- <td>{{ $value->account_type_id }}</td>--}}
                                        <td  class="border-right-1 text-right">
                                            {{ create_money_format( $credit) }}
                                        </td>
                                    </tr>

                                    @php
                                        $last_transaction = $value_transaction_id;
                                        $total_credit +=  $credit;
                                    @endphp
                                @else
                                    <tr>
                                        <td>{{ $value->transaction_id }}</td>
                                        <td>{{ ($value->account_name == "Accounts Payable" ? " PURCHASES & ".$value->account_name : $value->account_name)." (".$value->sharer_name.")" }}</td>
                                        {{--                                        <td>{{ $value->account_type_id }}</td>--}}
                                        <td class="border-right-1 text-right">
                                            {{create_money_format($value->credit)}}
                                        </td>
                                    </tr>
                                    @php
                                        $last_transaction = $value->transaction_id;
                                        $total_credit += $value->credit;
                                    @endphp
                                @endif

                            @endforeach

                            <tr>
                                <th colspan="2" class="text-right"> Total</th>
                                <th colspan="1" class="text-right border-dual">{{ create_money_format($total_credit) }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6"  style="padding-left:  0;">

                        <table class="table table-striped" id="dataTable">
                            <tbody>
                                <tr>
                                    <th colspan="3" class="text-center border-right-1"> -------- Debit --------</th>
                                </tr>
                                <tr>
                                    <th>Vo. No</th>
                                    <th>Particulars</th>
    {{--                                <th>L.P. No</th>--}}
                                    <th class="border-right-1 text-right">Taka</th>
                                </tr>
                            @php
                                $total_debit = $last_transaction = 0;
                            @endphp
                            @foreach($debits as $key=>$value)

                                @php
                                    $v_account = $value->account_name;
                                @endphp

                                @if($value->purchase_id>0 && ($value->account_name == 'Accounts Payable' || $value->account_name == "Cash"))
                                    @php
                                        $purchases = \App\Models\Purchase::find($value->purchase_id);
                                        $total = $purchase_print = 0;
                                        $account_name = $value->account_name;
                                        $value_transaction_id = $value->transaction_id;
                                        $sharer_name = $value->sharer_name;
                                        $debit = $value->debit;
                                    @endphp
                                <tr>

                                    <td>{{ $value->transaction_id }}</td>
                                    <td width="350px">
                                        @if($last_transaction==$value_transaction_id )
                                            {{ $sharer_name." (".$value->account_name.")" }}
                                        @else
                                            <table class="table table-bordered">
                                                @foreach($purchases->purchase_details as $key=>$value)
                                                    <tr>
                                                        <td>{{$value->item->item_name}}</td>
                                                        <td>{{$value->qty.$value->item->unit." X ".$value->price}}</td>
                                                        <td class="text-right">= {{create_money_format($value->qty*$value->price)}}</td>
                                                    </tr>
                                                    @php
                                                        $total += $value->qty*$value->price;
                                                    @endphp
                                                    @if ($loop->last && $key>0)
                                                        <tr>
                                                            <th class="text-right font-italic" colspan="3">{{create_money_format($total)}}</th>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </table>
                                           ( {{ " PURCHASES & ".$account_name." (".$sharer_name.")"  }} )
                                            @php
                                                $purchase_print++;
                                            @endphp
                                        @endif
                                    </td>
                                    {{--                                        <td>{{ $value->account_type_id }}</td>--}}
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($debit) }}
                                    </td>
                                </tr>

                                    @php
                                        $last_transaction = $value_transaction_id;
                                        $total_debit += $debit;
                                    @endphp

                                @else

                                    <tr>
                                        <td>{{ $value->transaction_id }}</td>
                                        <td>{{ ($value->account_name == "Accounts Receivable" ? " SALES & ".$value->account_name : $value->account_name)." (".$value->sharer_name.")" }}</td>
                                        {{--                                    <td>{{ $value->account_type_id }}</td>--}}
                                        <td class="border-right-1 text-right">
                                            {{create_money_format($value->debit)}}
                                        </td>
                                    </tr>

                                    @php
                                        $last_transaction = $value->transaction_id;
                                        $total_debit += $value->debit;
                                    @endphp

                                @endif

                                @php
                                    $last_account = $v_account;
                                @endphp
                            @endforeach
                                <tr>
                                    <th colspan="2" class="text-right"> Total</th>
                                    <th colspan="1" class="text-right border-dual">{{ create_money_format($total_debit) }}</th>
                                </tr>
                            </tbody>
                        </table>

                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
        });
    </script>
@endpush
