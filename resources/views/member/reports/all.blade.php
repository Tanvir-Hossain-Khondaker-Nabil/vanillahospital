<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/17/2019
 * Time: 11:45 AM
 *
 *
 * Deposit and Income are same
 */
 $route = \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => $title,
        'href' => $route
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => $title,
    'title'=>'List Of '.$title,
    'heading' =>trans('common.list_of').' '.trans("common.".strtolower(str_replace(' ', '_', $title))),
];

?>
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row small-text">
        <div class="col-xs-12">
            {!! Form::open(['route' => Route::current()->getName(),'method' => 'GET', 'role'=>'form' ]) !!}
                @include('member.reports.search')
            {!! Form::close() !!}

            <div class="box">
                <div class="box-body">
                    {{--<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 ">--}}
                        {{--@foreach($inputs as $value)--}}
                            {{--<label class="label label-primary"> {{ $value }}</label>--}}
                            {{--@endforeach--}}
                    {{--</div>--}}
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12  search-transaction">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped" id="dataTable">
                                <thead>
                                <tr>
                                    <td colspan="6" class="text-right label-primary">
                                        {{--<a href="{{ route('member.report.all_report_list', 'pdf') }}" class="btn btn-sm btn-warning"><i class="fa fa-file-pdf-o"> pdf</i></a>--}}
                                        {{--<a href="{{ route('member.report.all_income_list', 'csv') }}" class="btn btn-sm btn-info"><i class="fa fa-file-excel-o"></i> Excel</a>--}}
                                        {{--<a href="{{ route('member.report.all_income_list', 'download') }}" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>--}}
                                        <a  id="btn-print" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" class="btn btn-sm btn-info"><i class="fa fa-print"></i>{{__('common.print')}} </a>
                                        <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" class="btn btn-sm btn-success"><i class="fa fa-download"></i>{{__('common.download')}} </a>
                                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}} </a>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>{{__('common.transaction_date')}} </th>
                                        <th>{{__('common.transaction_code')}} </th>
                                        <th>{{__('common.transaction_from')}}</th>
                                        <th>{{__('common.debit')}}</th>
                                        <th>{{__('common.credit')}}</th>
                                        <th>{{__('common.entry_by')}}</th>

                                    </tr>
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

            {{--$('#dataTable').DataTable({--}}
                {{--processing: true,--}}
                {{--serverSide: true,--}}
                {{--ajax: "{{ route('member.report.all_income_datatable') }}",--}}
                {{--columns: [--}}
                    {{--{data: 'date_format', name: 'transactions.date'},--}}
                    {{--{data: 'transaction_code', name: 'transactions.transaction_code'}--}}
                {{--],--}}
                {{--initComplete: function () {--}}
                    {{--this.api().columns().every(function () {--}}
                        {{--var column = this;--}}
                        {{--var input = document.createElement("input");--}}
                        {{--$(input).appendTo($(column.footer()).empty())--}}
                            {{--.on('change', function () {--}}
                                {{--column.search($(this).val(), false, false, true).draw();--}}
                            {{--});--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}
        });
    </script>
@endpush
