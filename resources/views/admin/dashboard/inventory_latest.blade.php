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
    'heading' => trans('dashboard.inventory_dashboard'),
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


    <div class="row">

        <div class="col-md-12">

            <div class="box">
                <div class="box-body pb-0">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12 px-0 text-right pb-4">

                                <a class="btn btn-success btn-sm"
                                   href="{{ url()->current() }}?view_item=graphical">{{ __('dashboard.graphical_analysis') }} </a>

                                <a class="btn btn-info btn-sm"
                                   href="{{ url()->current() }}?view_item=normal">{{ __('dashboard.normal_analysis') }}</a>
                            </div>


                            <div class="col-md-6">
                                <div id="chart" class="col-md-6"></div>
                                <div id="last_month_chart" class="col-md-6"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="monthly_chart"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="last_month_product_Sale_chart"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="product_Sale_chart"></div>
                            </div>
                            <div class="col-md-12">
                                <div id="daily_sale_chart"></div>
                            </div>
                            <div class="col-md-12">
                                <div id="sale_chart"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')

    @include('admin.dashboard.scripts')


    <script>
        $(function () {

            var CurrencySymbol = "{{ getCurrencySymbol() }}";

            var monthlySale = @json($monthlySale);
            var monthly = @json($monthly);

            monthlySale = JSON.parse(monthlySale);
            monthly = JSON.parse(monthly);


            var result = Object.keys(monthlySale).map((key) =>JSON.parse(monthlySale[key]));
            var monthlyresult = Object.keys(monthly).map((key) => monthly[key]);

            var options = {
                series: [],
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                title: {
                    text: 'Ajax Example',
                },
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    type: 'category',
                    tickPlacement: 'on',
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                }
            };

            // var chart = new ApexCharts(document.querySelector("#daily_sale_chart"), options);
            // chart.render();
            var ProductSaleAmount = @json($ProductSaleAmount);
            var ProductSaleQty = @json($ProductSaleQty);
            var saleProduct = @json($saleProduct);

            ProductSaleAmount = JSON.parse(ProductSaleAmount);
            ProductSaleQty = JSON.parse(ProductSaleQty);
            saleProduct = JSON.parse(saleProduct);

            var options = {
                series: [{
                    // name: 'Total Price',
                    name: "{{ __('dashboard.total_price') }}",
                    type: 'column',
                    data: ProductSaleAmount
                }, {
                    // name: 'Total Qty',
                    name: "{{ __('dashboard.total_qty') }}",
                    type: 'line',
                    data: ProductSaleQty
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    width: [0, 4]
                },
                title: {
                    // text: 'Sale Trends By Product'
                    text: "{{ __('dashboard.sale_trends_by_product') }}"
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [1]
                },
                labels: saleProduct,
                xaxis: {
                    title: {
                        // text: 'Products'
                        text: "{{ __('dashboard.products') }}"
                    }
                },
                yaxis: [{
                    title: {
                        // text: 'Total Price',
                        text: "{{ __('dashboard.total_price') }}",
                    },

                }, {
                    opposite: true,
                    title: {
                        // text: 'Total Qty'
                        text: "{{ __('dashboard.total_qty') }}"
                    }
                }]
            };
            //
            //
            // var options = {
            //     series: [{
            //         data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
            //     }],
            //     chart: {
            //         type: 'bar',
            //         height: 350,
            //         toolbar: {
            //             show: false
            //         }
            //     },
            //     plotOptions: {
            //         bar: {
            //             borderRadius: 4,
            //             borderRadiusApplication: 'end',
            //             horizontal: true,
            //         }
            //     },
            //     title: {
            //         text: 'Sales by Product ',
            //     },
            //     dataLabels: {
            //         enabled: false
            //     },
            //     xaxis: {
            //         categories: monthlyresult,
            //     }
            // };

            var chart = new ApexCharts(document.querySelector("#daily_sale_chart"), options);
            chart.render();

            // axios({
            //     method: 'GET',
            //     url: 'http://my-json-server.typicode.com/apexcharts/apexcharts.js/yearly',
            // }).then(function(response) {
            //     chart.updateSeries([{
            //         name: 'Sales',
            //         data: response.data
            //     }])
            // });

            var monthlySaleAmount = @json($monthlySaleAmount);
            var saleMonthly = @json($saleMonthly);

            monthlySaleAmount = JSON.parse(monthlySaleAmount);
            saleMonthly = JSON.parse(saleMonthly);

            var options = {
                series: [{
                    // name: "Sales",
                    name: "{{ __('dashboard.sales') }}",
                    data: monthlySaleAmount
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    // text: 'Sale Trends by Month',
                    text: "{{ __('dashboard.sale_trends_by_month') }}",
                    align: 'left'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: saleMonthly,
                }
            };

            var chart = new ApexCharts(document.querySelector("#monthly_chart"), options);
            chart.render();


            var options = {
                series: [{{$this_month_sales}}],
                chart: {
                    height: 250,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: "#E91E63",
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '16px',
                                color: "#E91E63",
                                formatter: function (val) {
                                    return CurrencySymbol+(val/1000) + "K";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                stroke: {
                    dashArray: 4
                },
                // labels: ['This Month Sales'],
                labels: ["{{ __('dashboard.this_month_sales') }}"],
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            var options = {
                series: [{{$last_month_sales}}],
                chart: {
                    height: 250,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                color: undefined,
                                offsetY: 120
                            },
                            value: {
                                offsetY: 76,
                                fontSize: '16px',
                                color: undefined,
                                formatter: function (val) {
                                    return CurrencySymbol + (val/1000) + "K";
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                stroke: {
                    dashArray: 4
                },
                // labels: ['Last Month Sales'],
                labels: ["{{ __('dashboard.last_month_sales') }}"],
            };

            var chart = new ApexCharts(document.querySelector("#last_month_chart"), options);
            chart.render();
;


            var options = {
                series: result,
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                title: {
                    // text: 'Product Trends by Month',
                    text: "{{ __('dashboard.product_trends_by_month') }}",
                    align: 'left'
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories:  monthlyresult,
                },
                yaxis: {
                    title: {
                        // text: 'Total Quantity'
                        text: "{{ __('dashboard.total_qty') }}"
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#sale_chart"), options);
            chart.render();


            var lastOneYearSaleQty = @json($lastOneYearSaleQty);
            var lastOneYearSaleProduct = @json($lastOneYearSaleProduct);

            lastOneYearSaleQty = JSON.parse(lastOneYearSaleQty);
            lastOneYearSaleProduct = JSON.parse(lastOneYearSaleProduct);

            var options = {
                labels: lastOneYearSaleProduct,
                series: lastOneYearSaleQty,
                chart: {
                    type: 'polarArea',
                },
                stroke: {
                    colors: ['#fff']
                },
                title: {
                    // text: 'Sales Product in Last One Year',
                    text: "{{ __('dashboard.sale_product_in_one_year') }}",
                    align: 'left'
                },
                fill: {
                    opacity: 0.8
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#product_Sale_chart"), options);
            chart.render();

            var lastOneMonthSaleQty = @json($lastOneMonthSaleQty);
            var lastOneMonthSaleProduct = @json($lastOneMonthSaleProduct);

            lastOneMonthSaleQty = JSON.parse(lastOneMonthSaleQty);
            lastOneMonthSaleProduct = JSON.parse(lastOneMonthSaleProduct);

            var options = {
                labels: lastOneMonthSaleProduct,
                series: lastOneMonthSaleQty,
                chart: {
                    type: 'polarArea',
                },
                stroke: {
                    colors: ['#fff']
                },
                title: {
                    // text: 'Sales Product in Last 30 Days',
                    text: "{{ __('dashboard.sale_product_in_30_days') }}",
                    align: 'left'
                },
                fill: {
                    opacity: 0.8
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#last_month_product_Sale_chart"), options);
            chart.render();

        });


    </script>


@endpush
