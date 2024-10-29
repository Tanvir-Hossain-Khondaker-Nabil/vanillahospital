<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/16/2019
 * Time: 4:14 PM
 */

 $route = \Auth::user()->can(['member.general_ledger.index']) ? route('member.general_ledger.index'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'All Transaction',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'All Transaction',
    'title'=>'List Of All Transaction',
    'heading' => 'List Of All Transaction',
];

?>
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row small-text">
        <div class="col-xs-12">

            {!! Form::open(['route' => 'member.report.all_transaction','method' => 'GET', 'role'=>'form' ]) !!}
                @include('member.reports.search')
            {!! Form::close() !!}
            <div class="box">

                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">

                            <table class="table table-responsive table-striped">
                                <thead>
                                    <tr>
                                        <td colspan="6" class="text-right label-primary">
                                            {{--<a href="{{ route('member.report.all_transaction_list', 'pdf') }}" class="btn btn-sm btn-warning"><i class="fa fa-file-pdf-o"> PDF</i></a>--}}
                                            {{--<a href="{{ route('member.report.all_transaction_list', 'csv') }}" class="btn btn-sm btn-info"><i class="fa fa-file-excel-o"></i> Excel</a>--}}
                                            {{--<a href="{{ route('member.report.all_transaction_list', 'download') }}" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>--}}
                                            <a  id="btn-print" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" class="btn btn-sm btn-info"><i class="fa fa-print"></i> Print</a>
                                            <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>
                                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>Transaction Date</th>
                                    <th>Transaction Code</th>
                                    <th>Transaction From</th>
                                    <th>Debit </th>
                                    <th>Credit </th>
                                    <th>Entry By</th>
                                </tr>
                                @php
                                    $lastCode = '';
                                @endphp
                                @foreach($modal as $value)
                                    <tr>
                                        <td>{{ db_date_month_year_format($value->date) }}</td>
                                        <td>{{ $value->transaction_code }}</td>
                                        <td>{{ $value->account_type_name }}</td>
                                        <td class="text-right">{{ $value->transaction_type=='dr' ? create_money_format($value->amount) : create_money_format(0) }}</td>
                                        <td class="text-right">{{ $value->transaction_type=='cr' ? create_money_format($value->amount) : create_money_format(0)  }}</td>
                                        <td>{{ human_words($value->created_user) }}</td>
                                    </tr>
                                    @php
                                        $lastCode = $value->transaction_code;
                                    @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right"> {{ $modal->links() }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
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
