<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */

 $route =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Dashboard',
        'href' => $route,
    ],
    ];
$data['data'] = [
    'name' => 'Dashboard',
    'title'=>'Dashboard',
    'heading' => trans('dashboard.procurement_dashboard'),
];
?>
@extends('layouts.back-end.master', $data)


@push('styles')

    @include('admin.dashboard.style')

@endpush

@section('contents')

    @if (session('set_expired_days'))
        <div class="alert alert-danger alert-out text-left">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-exclamation-circle"></i> {{__('common.system_access_expired')}}</h4>
            <span class="text-capitalize">{{__('common.your_access_expired_soon')}}. {{ session('set_expired_days') }} {{__('common.days_remaining')}}. {{__('common.please_contact_and_pay_soon')}}. </span>
        </div>
    @endif

    @include('common._alert')

    @if(config('pos.feature'))

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $total_products }}</h3>

                <p>{{ __('dashboard.total_product') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('member.items.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $total_purchases }}</h3>

                <p>{{ __('dashboard.total_purchase') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('member.purchase.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $total_sales }} </h3>

                <p>{{ __('dashboard.total_sale') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('member.sales.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $total_users }}</h3>

                <p>{{ __('dashboard.total_user') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('member.users.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

    <!-- Info boxes -->
    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_purchase_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_purchase_amount )}} {{ getCurrencySymbol() }}
</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_purchase_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_purchase_amount) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>


        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-shopping-bag"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_sales_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_sales_amount) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-shopping-basket"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_Sales_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_sales_amount) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-lime-active"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_due') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_due) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-maroon"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_due') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_due) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-lime-active"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_out_standing') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_out_standing) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-navy"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_out_standing') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_out_standing) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>

@endif

    @if(config('accounts-system.feature'))
<div class="row">

        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-fuchsia"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">Today Income (CR)</span>
                    <span class="info-box-number">{{ create_money_format($today_income) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-fuchsia"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">Total Income (CR)</span>
                    <span class="info-box-number">{{ create_money_format($total_income) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue-grey"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">Today Expense (Dr)</span>
                    <span class="info-box-number">{{ create_money_format($today_expense )}} {{ getCurrencySymbol() }}
</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue-grey"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">Total Expense (Dr)</span>
                    <span class="info-box-number">{{ create_money_format($total_expense )}} {{ getCurrencySymbol() }}
</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endif

    <div class="row">


        <div class="col-md-6">

            @if(config('accounts-system.feature'))
            <!-- Bar chart -->
            {{--             <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart-o"></i>

                    <h3 class="box-title">{{ __('dashboard.bar_chart') }} </h3>

                <div class="box-tools pull-right">--}}
{{--                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
{{--                        </button>--}}
{{--                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
{{--                    </div>--}}
{{--                 </div>
                <div class="box-body">
                    <div id="bar-chart" style="height: 300px;"></div>
                </div>
                <!-- /.box-body-->
            </div>--}}
            <!-- /.box -->
            @endif

            @if(config('pos.feature'))
            {{--              <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('dashboard.purchases_vs_sales') }}  </h3>

                <div class="box-tools pull-right">--}}
{{--                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
{{--                        </button>--}}
{{--                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
{{--                    </div>--}}
{{--                  </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </div>

            <div class="col-md-6">

                <!-- Donut chart -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-bar-chart-o"></i>

                        <h3 class="box-title"> {{ __('dashboard.transport_vs_unload_cost') }} </h3>

                   <div class="box-tools pull-right">--}}
{{--                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
{{--                            </button>--}}
{{--                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
{{--                        </div>
                    </div>
                    <div class="box-body">
                        <div id="donut-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body-->
                </div>
            </div> --}}
        @endif
    </div>

@endsection

@push('scripts')

    @include('admin.dashboard.scripts')


    <script>
        $(function () {

            var $total_transport_cost = "{{ $total_transport_cost > 0 ?  $total_transport_cost : 0}}";
            var $total_unload_cost = "{{ $total_unload_cost > 0 ?  $total_unload_cost : 0 }}";
            var $total_purchase_amount = "{{ $total_purchase_amount > 0 ?  $total_purchase_amount : 0}}";
            var $total_sales_amount = "{{ $total_sales_amount > 0 ?  $total_sales_amount : 0 }}";
            var $total_transfer = "{{ $total_transfer > 0 ?  $total_transfer : 0 }}";
            var $total_income = "{{ $total_income > 0 ?  $total_income : 0 }}";
            var $total_expense = "{{ $total_expense > 0 ?  $total_expense : 0 }}";

            /*
             * BAR CHART
             * ---------
             */

            var bar_data = {
                data : [
                    [' Transfer', $total_transfer],
                    [' Income', $total_income],
                    [' Expense', $total_expense],
                    ['Unload Cost', $total_unload_cost],
                    ['Transport Cost', $total_transport_cost],
                    [' Purchase ', $total_purchase_amount],
                    [' Sales ', $total_sales_amount]
                ],
                color: '#3c8dbc'
            };
            $.plot('#bar-chart', [bar_data], {
                grid  : {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor  : '#f3f3f3'
                },
                series: {
                    bars: {
                        show    : true,
                        barWidth: 0.50,
                        align   : 'center'
                    }
                },
                xaxis : {
                    mode      : 'categories',
                    tickLength: 0
                }
            });
            /* END BAR CHART */

            /*
             * DONUT CHART
             * -----------
             */


            var donutData = [
                { label: 'Transport Cost', data: $total_transport_cost, color: '#3c8dbc' },
                { label: 'Unload Cost', data: $total_unload_cost, color: '#0073b7' },
                // { label: 'Series4', data: 50, color: '#00c0ef' }
            ];
            $.plot('#donut-chart', donutData, {
                series: {
                    pie: {
                        show       : true,
                        radius     : 1,
                        innerRadius: 0.5,
                        label      : {
                            show     : true,
                            radius   : 2 / 3,
                            formatter: labelFormatter,
                            threshold: 0.1
                        }

                    }
                },
                legend: {
                    show: false
                }
            });
            /*
             * END DONUT CHART
             */

            /*
    * LINE CHART
    * ----------
    */
            //LINE randomly generated data

            var sin = [], cos = [];
            for (var i = 0; i < 10; i += 0.5) {
                sin.push([i, Math.sin(i)]);
                cos.push([i, Math.cos(i)])
            }
            var line_data1 = {
                data : sin,
                color: '#3c8dbc'
            };
            var line_data2 = {
                data : cos,
                color: '#00c0ef'
            };
            $.plot('#double-line-chart', [line_data1, line_data2], {
                grid  : {
                    hoverable  : true,
                    borderColor: '#f3f3f3',
                    borderWidth: 1,
                    tickColor  : '#f3f3f3'
                },
                series: {
                    shadowSize: 0,
                    lines     : {
                        show: true
                    },
                    points    : {
                        show: true
                    }
                },
                lines : {
                    fill : false,
                    color: ['#3c8dbc', '#f56954']
                },
                yaxis : {
                    show: true
                },
                xaxis : {
                    show: true
                }
            });


            //Initialize tooltip on hover
            $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
                position: 'absolute',
                display : 'none',
                opacity : 0.8
            }).appendTo('body');

            $('#double-line-chart').bind('plothover', function (event, pos, item) {

                if (item) {
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    $('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
                        .css({ top: item.pageY + 5, left: item.pageX + 5 })
                        .fadeIn(200)
                } else {
                    $('#line-chart-tooltip').hide()
                }

            });
            /* END LINE CHART */

            {{--console.log($transfers);--}}
            {{--var $data_set = [];--}}
            {{--$.each($transfers, function( index, value ) {--}}
            {{--    $data_set[index] = [];--}}
            {{--    $data_set[index]['transaction_code'] = value.date_format+" "+value.transaction_code;--}}
            {{--    $data_set[index]['amount'] = value.amount;--}}
            {{--});--}}

            {{--console.log($data_set);--}}
            {{--// LINE CHART--}}
            {{--var line = new Morris.Line({--}}
            {{--    element: 'line-chart',--}}
            {{--    resize: true,--}}
            {{--    data: $data_set,--}}
            {{--    xkey: 'transaction_code',--}}
            {{--    ykeys: ['amount'],--}}
            {{--    labels: ['Item 1'],--}}
            {{--    lineColors: ['#3c8dbc'],--}}
            {{--    hideHover: 'auto'--}}
            {{--});--}}

            //DONUT CHART


            var donut = new Morris.Donut({
                element: 'sales-chart',
                resize: true,
                colors: ["#3c8dbc", "#f56954"],
                data: [
                    {label: "Purchase", value: $total_purchase_amount},
                    {label: "Sales", value: $total_sales_amount},
                    // {label: "Mail-Order Sales", value: 20}
                ],
                hideHover: 'auto'
            });


        });


        /*
         * Custom Label formatter
         * ----------------------
         */
        function labelFormatter(label, series) {
            return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
                + label
                + '<br>'
                + Math.round(series.percent) + '%</div>'
        }
    </script>


@endpush
