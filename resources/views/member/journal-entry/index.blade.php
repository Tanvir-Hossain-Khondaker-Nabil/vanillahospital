<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/24/2019
 * Time: 12:28 PM
 */


 $route =  \Auth::user()->can(['member.journal_entry.index']) ? route('member.journal_entry.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Journal Entry',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Journal Entry',
    'title'=>'List Of Journal Entry',
    'heading' =>trans('common.list_of_journal_entry'),
];

?>
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">

                <div class="box-body">

                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 search-transaction">
                           {!! Form::open(['route' => 'member.journal_entry.index','method' => 'GET', 'role'=>'form' ]) !!}

                        <div class="col-md-3">
                            <label>{{__('common.transaction_code')}}</label>
                            <input class="form-control" name="transaction_code" value="{{ isset($transaction_code) ? $transaction_code : ''  }}" type="text"/>

                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.transaction_date')}}</label>
                            <input class="form-control" name="date" id="date" value="{{ isset($date) ? $date : ''  }}" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.transaction_from')}}</label>
                            {!! Form::select('from_account_type_id', $accounts, isset($from_account_type_id) ? $from_account_type_id : '',['id'=>'from_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_account_from')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.transaction_to')}}</label>
                            {!! Form::select('to_account_type_id', $accounts, isset($to_account_type_id) ? $to_account_type_id : '',['id'=>'to_account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_account_to')]); !!}
                        </div>
                        <div class="col-md-3">
                            {{-- <input class="btn btn-info btn-search" value="Search" type="submit"/> --}}
                            <button class="btn btn-info btn-search" type="submit">{{__('common.search')}}</button>
                            <a class="btn btn-primary btn-search" href="{{ route(Route::current()->getName()) }}"> <i class="fa fa-refresh"></i> </a>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">

                            <table class="table table-responsive table-striped">
                                <tbody>
                                <tr>
                                    <th>{{__('common.transaction_date')}}</th>
                                    <th>{{__('common.transaction_code')}}</th>
                                    <th>{{__('common.transaction_from')}}</th>
                                    <th> {{__('common.debit')}} </th>
                                    <th>{{__('common.credit')}} </th>
                                    <th>{{__('common.entry_by')}}</th>
                                    <th> {{__('common.manage')}} </th>
                                </tr>
                                @php
                                    $lastCode = '';
                                @endphp
                                @foreach($modal as $value)
                                    <tr>
                                        <td>{{ db_date_month_year_format($value->date) }}</td>
                                        <td>{{ $value->transaction_code }}</td>
                                        <td>{{ human_words($value->title) }}</td>
                                        <td class="text-right">{{ $value->transaction_type=='dr' ? create_money_format($value->total_amount) : create_money_format(0) }}</td>
                                        <td class="text-right">{{ $value->transaction_type=='cr' ? create_money_format($value->total_amount) : create_money_format(0)  }}</td>
                                        <td>{{ human_words($value->created_user) }}</td>
                                        @if($value->transaction_code != $lastCode)
                                            <td rowspan="{{ $value->transaction_code != $lastCode ? 2 : ''}}">
                                                <a class="btn btn-xs btn-info btn-trans-show" href="{{ route('member.journal_entry.show', $value->transaction_code) }}"><i class="fa fa-eye"></i></a>
                                            </td>
                                        @endif
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
            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
        });
    </script>
@endpush
