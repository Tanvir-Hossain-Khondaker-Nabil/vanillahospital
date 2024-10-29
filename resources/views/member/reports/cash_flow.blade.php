<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/4/2019
 * Time: 12.07 PM
 */

 $route = \Auth::user()->can(['member.report.cash_flow']) ? route('member.report.cash_flow'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'List',
        'href' => $route,
    ],
    [
        'name' => 'Cash Flow Statement',
    ],
];

$data['data'] = [
    'name' => "Cash Flow",
    'title'=> 'Cash Flow Statement',
    'heading' => 'Cash Flow Statement',
];

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

                {!! Form::open(['route' => ['member.report.balance_sheet'],'method' => 'GET', 'role'=>'form' ]) !!}

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
{{--                        <div class="col-md-2">--}}
{{--                            <label> Year </label>--}}
{{--                            <input class="form-control year" name="year" value="" autocomplete="off"/>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-2">--}}
{{--                            <label> From Date </label>--}}
{{--                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-2">--}}
{{--                            <label> To Date</label>--}}
{{--                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>--}}
{{--                        </div>--}}
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

                <div class="box-body">


{{--                    <div class="modal fade" id="inventories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">--}}
{{--                        <div class="modal-dialog" role="document">--}}
{{--                            <div class="modal-content">--}}
{{--                                <div class="modal-header">--}}
{{--                                    <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format( $inventory_no = $balance_sheet_key++) }} Inventories</h4>--}}
{{--                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                        <span aria-hidden="true">&times;</span>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                                <div class="modal-body">--}}

{{--                                    <table class="table">--}}
{{--                                        @php--}}
{{--                                            $last_unit = '';--}}
{{--                                            $category = '';--}}
{{--                                            $total_inventory = $total_per_category = 0;--}}
{{--                                        @endphp--}}
{{--                                        @foreach($inventories as $key=>$value)--}}
{{--                                            @php--}}
{{--                                                $qty = $value->opening_stock+$value->purchase_qty-$value->purchase_return_qty-$value->sale_qty+$value->sale_return_qty;--}}
{{--                                            @endphp--}}

{{--                                            @if( !empty($category) && $category != $value->item->category->display_name)--}}
{{--                                                <tr>--}}
{{--                                                    <td colspan="2">Total {{ $category }}</td>--}}
{{--                                                    <td class="single-line text-right"> {{ create_money_format($total_per_category) }} </td>--}}
{{--                                                </tr>--}}
{{--                                            @endif--}}
{{--                                            @if( $key == 0 || $category != $value->item->category->display_name)--}}
{{--                                                @php--}}
{{--                                                    $total_per_category = 0;--}}
{{--                                                @endphp--}}
{{--                                                <tr>--}}
{{--                                                    <th>{{ $value->item->category->display_name }}</th>--}}
{{--                                                    <td class="text-right single-line"> Quantity (in {{ $value->item->unit }})</td>--}}
{{--                                                </tr>--}}
{{--                                            @endif--}}

{{--                                            <tr>--}}
{{--                                                <td>{{ $value->product_name }}</td>--}}
{{--                                                <td class="text-right padding-right-50">{{ $qty }}</td>--}}
{{--                                                <td class="text-right">{{ create_money_format($qty*$value->item_price) }}</td>--}}
{{--                                            </tr>--}}

{{--                                            @php--}}
{{--                                                $last_unit = $value->item->unit;--}}
{{--                                                $category = $value->item->category->display_name;--}}
{{--                                                $total_inventory += $qty*$value->item_price;--}}
{{--                                                $total_per_category += $qty*$value->item_price;--}}
{{--                                            @endphp--}}

{{--                                            @if(count($inventories) == $key+1)--}}
{{--                                                <tr>--}}
{{--                                                    <td colspan="2">Total {{ $value->item->category->display_name }}</td>--}}
{{--                                                    <td class="single-line text-right"> {{ create_money_format($total_per_category) }} </td>--}}
{{--                                                </tr>--}}
{{--                                                <tr>--}}
{{--                                                    <th colspan="2">Total Inventory</th>--}}
{{--                                                    <th class="dual-line text-right"> {{ create_money_format($total_inventory) }} </th>--}}
{{--                                                </tr>--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </table>--}}

{{--                                </div>--}}
{{--                                <div class="modal-footer">--}}
{{--                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Modal -->--}}
{{--                    <div class="modal fade" id="sales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">--}}
{{--                        <div class="modal-dialog" role="document">--}}
{{--                            <div class="modal-content">--}}
{{--                                <div class="modal-header">--}}
{{--                                    <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($sale_no = $balance_sheet_key++) }} Sales</h4>--}}
{{--                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                        <span aria-hidden="true">&times;</span>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                                <div class="modal-body">--}}

{{--                                    <table class="table">--}}
{{--                                        --}}{{--                                                    <tr>--}}
{{--                                        --}}{{--                                                        <th colspan="3"></th>--}}
{{--                                        --}}{{--                                                    </tr>--}}

{{--                                        @foreach($sale_details as $key=>$value)--}}
{{--                                            <tr>--}}
{{--                                                <td colspan="2">{{ $value->item->item_name }}</td>--}}
{{--                                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            @php--}}
{{--                                                $total_sales += $value->sum_total_price;--}}
{{--                                            @endphp--}}
{{--                                        @endforeach--}}
{{--                                        <tr>--}}
{{--                                            <th colspan="2">Total Sales</th>--}}
{{--                                            <th class="dual-line text-right">{{ create_money_format( $total_sales) }}</th>--}}
{{--                                        </tr>--}}
{{--                                    </table>--}}

{{--                                </div>--}}
{{--                                <div class="modal-footer">--}}
{{--                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Modal -->--}}
{{--                    <div class="modal fade" id="purchases" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">--}}
{{--                        <div class="modal-dialog" role="document">--}}
{{--                            <div class="modal-content">--}}
{{--                                <div class="modal-header">--}}
{{--                                    <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($purchase_no = $balance_sheet_key++) }} Purchases</h4>--}}
{{--                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                        <span aria-hidden="true">&times;</span>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                                <div class="modal-body">--}}

{{--                                    <table class="table">--}}

{{--                                        @foreach($purchase_details as $key=>$value)--}}
{{--                                            <tr>--}}
{{--                                                <td colspan="2">{{ $value->item->item_name }}</td>--}}
{{--                                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            @php--}}
{{--                                                $total_purchases += $value->sum_total_price;--}}
{{--                                            @endphp--}}
{{--                                        @endforeach--}}
{{--                                        <tr>--}}
{{--                                            <th colspan="2">Total Purchases</th>--}}
{{--                                            <th class="dual-line text-right">{{ create_money_format( $total_purchases) }}</th>--}}
{{--                                        </tr>--}}
{{--                                    </table>--}}

{{--                                </div>--}}
{{--                                <div class="modal-footer">--}}
{{--                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">
                            <thead>

                            <tr>
                                <th colspan="3" style="border: none !important; padding-bottom: 20px;" class="text-center">
                                    <h3>{{ $report_title }}</h3>
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td class="text-uppercase report-head-tag width-100 border-1 "> Details</td>
                                <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Amount</td>
                            </tr>
                            <tr>
                                <th class="text-capitalize" colspan="3">A. Cash flow from Operating Activities </th>
                            </tr>
                            <tr>
                                <th class="text-capitalize" colspan="3">B. Cash flow from Investing Activities </th>
                            </tr>
                            <tr>
                                <th class="text-capitalize" colspan="3">C. Cash flow from Financing Activities </th>
                            </tr>
                            <tr>
                                <th class="text-capitalize" colspan="2"> Net Increase/Decrease in Cash & Cash equivalent </th>
                                <th class="text-capitalize" >  </th>
                            </tr>
                            <tr>
                                <th class="text-capitalize" colspan="2"> Cash & Cash equivalent Balance begining of the period </th>
                                <th class="text-capitalize" >  </th>
                            </tr>
                            <tr>
                                <th class="text-capitalize" colspan="2"> Cash & Cash equivalent Balance end of the period </th>
                                <th class="text-capitalize"> </th>
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


