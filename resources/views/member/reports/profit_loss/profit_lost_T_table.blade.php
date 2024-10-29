<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */

 $route = \Auth::user()->can(['member.report.lost_profit']) ? route('member.report.lost_profit') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => $route,
    ],
    [
        'name' => 'Profit And Loss Report',
    ],
];

$data['data'] = [
    'name' => "Profit And Loss",
    'title'=> 'Profit And Loss',
    'heading' => 'Profit And Loss ',
];

$balance_sheet_key = 1;
$sale_no  = $purchase_no = $inventory_no = 0;
?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            @include('common._alert')

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

                {!! Form::open(['route' => ['member.report.lost_profit'],'method' => 'GET', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  Select Company </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>  Fiscal Year </label>
                            {!! Form::select('fiscal_year', $fiscal_year, null ,['class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>
                        <div class="col-md-2">
                            <label> Year </label>
                            <input class="form-control year" name="year" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                            <label> From Date </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                            <label> To Date</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3 margin-top-23">
                                <input type="checkbox" name="t_based_view" value="1" {{ $t_based_view ? "checked" : "" }}/>
                                <label> T Based View </label>
                            </div>

                        </div>
                        <div class="col-md-2 margin-top-23">
                            <label></label>
                            <input class="btn btn-sm btn-info" value="Search" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">

                @include('member.reports.print_title_btn')

                <div class="box-header with-border">
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=full_pl" class="btn btn-info  btn-sm  pull-right" id="btn-print"> <i class="fa fa-print"></i> Print Profit Loss Details </a>
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download_full_pl" class="btn btn-default  btn-sm  pull-right  mr-3"> <i class="fa fa-download"></i> Download Profit Loss Details</a>
                </div>

                <div class="box-body profit_lost">


                   @include('member.reports.common._modal_profit')

                    <div class="col-lg-12 text-center">
                        <h3>{!!  $report_title !!} </h3>
                    </div>

                    <div class="col-lg-6">
                        <table class="table table-striped" id="dataTable">
                            <tbody>

                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="2">2. Less: Cost of Goods Sold</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40">A. Opening Stock</td>
                                <td class="text-right">{{ create_money_format($openingStock) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#purchases">B. Purchase </a></td>
                                <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase ">Total(A+B)</th>
                                <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases) }}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#inventories">C. Closing Stock</a></td>
                                <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " >Total(A+B-C)</th>
                                <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases-$total_inventory) }}</th>
                            </tr>

                            @php
                                $total_cost_of_sold = $openingStock+$total_purchases-$total_inventory+$cost_of_sold_balance;
                            @endphp
                            @foreach($cost_of_sold_items as $key => $value)
                                <tr>
                                    <td class="padding-left-40">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                                @php
                                    $total_cost_of_sold += $value['balance'];
                                @endphp
                            @endforeach
                            <tr>
                                <th class="padding-left-40 text-uppercase" >Cost of Goods Sold</th>
                                <th class="single-line text-right">{{ create_money_format($total_cost_of_sold) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="2">4. Less: Administrative and general Expenses</th>
                            </tr>
                            @foreach($expenses as $key => $value)
                                <tr>
                                    <td  class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class=" padding-left-40 text-uppercase " >Total Expenses</th>
                                <th class=" text-right">{{ create_money_format($total_expenses) }}</th>
                            </tr>
                            <tr>
                                <th class="border-top-1 text-uppercase ">Total</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_cost_of_sold+$total_expenses) }}</th>
                            </tr>
                            </tbody>
                        </table>



                    </div>
                    <div class="col-lg-6">
                        <table class="table table-striped" id="dataTable">
                            <tbody>
                                    <tr>
                                        <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                        <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                                    </tr>
                                    <tr>
                                        <th class="text-uppercase" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sales">1. Sales  </a></th>
                                        <th class="text-right">{{ create_money_format( $total_sales) }}</th>
                                    </tr>

                            <tr>


                                <th class="text-uppercase" colspan="2">5. Income</th>
                            </tr>
                            @foreach($incomes as $key => $value)
                                <tr>
                                    <td  class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="padding-left-40 text-uppercase ">Total</th>
                                <th class=" single-line text-right">{{ create_money_format($total_incomes) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1"> Net {{ $net_profit<0? "Loss":"Profit" }} </th>
                                <th class=" border-top-1 text-right">{{ create_money_format( $profit = $net_profit<0 ? $net_profit*(-1) :$net_profit ) }}</th>
                            </tr>

                            <tr>
                                <th class="border-top-1 text-uppercase ">Total</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_sales+$profit+$total_incomes) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1"> Gross Profit (1-2)</th>
                                <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
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
                @if($set_company_fiscal_year)
            var $setDate = new Date( '{{ str_replace("-", "/", $set_company_fiscal_year->start_date) }}' );
            var today = new Date($setDate.getFullYear(), $setDate.getMonth(), $setDate.getDate(), 0, 0, 0, 0);
            @endif

            $('.year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                endDate: '+0d',
                setDate: today
            });

            $('.date').change( function (e) {
                $('.date').attr('required', true);
            });


            $(".account_type_view").click( function (e) {
                e.preventDefault();

                var $view = $(this).data('id');
                // alert($view);
                $("#"+$view).css('display', 'block');
            });
        });
    </script>
@endpush

