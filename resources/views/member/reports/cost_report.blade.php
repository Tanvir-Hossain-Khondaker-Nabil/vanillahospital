<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 6/16/2019
 * Time: 11:41 AM
 */

 $route = \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Purchases',
        'href' => $route,
    ],
    [
        'name' => ($cost_type == "unload" ? 'Unload' : 'Transport').' Cost Report',
    ],
];

$data['data'] = [
    'name' => ($cost_type == "unload" ? 'Unload' : 'Transport').' '.trans('common.cost_report'),
    'title'=> ($cost_type == "unload" ? 'Unload' : 'Transport').' '.trans('common.cost_report'),
    'heading' => ($cost_type == "unload" ? trans('common.unload') : trans('common.transport')).' '.trans('common.cost_report'),
];

?>

@extends('layouts.back-end.master', $data)


@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.search')}}</h3>
                </div>

            {!! Form::open(['route' => ['member.report.cost', $cost_type],'method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        @if(Auth::user()->hasRole(['super-admin', 'admin', 'developer']))
                            <div class="col-md-4">
                                <label>{{__('common.select_company')}}    </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company'), 'required']); !!}
                            </div>
                            <div class="col-md-4">
                                <label>{{__('common.branch_name')}}   </label>
                                {!! Form::select('branch_id', $branches, null,['id'=>'branch_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label>{{__('common.memo_no')}}   </label>
                            <input class="form-control" name="memo_no" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-4">
                            <label>{{__('common.chalan_no')}}   </label>
                            <input class="form-control" name="chalan" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-4">
                            <label>{{__('common.from_date')}} </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-4">
                            <label> {{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-4 margin-top-23">
                            <label></label>
                            {{-- <input class="btn btn-info" value="Search" type="submit"/> --}}
                            <button class="btn btn-info" type="submit">{{__('common.search')}}</button>
{{--                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>--}}

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
                    <h3 class="box-title">{{ $cost_type == "unload" ? trans('common.unload') : trans('common.transport') }} {{__('common.cost_report')}}</h3>
                    <a href="{{ $full_url}}type=print" class="btn btn-sm btn-primary  pull-right" id="btn-print"> <i class="fa fa-print"></i>{{__('common.print')}}   </a>
                    <a href="{{ $full_url}}type=download" class="btn btn-sm btn-success  pull-right"> <i class="fa fa-download"></i>{{__('common.download')}}    </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>{{__('common.id')}}</th>
                                <th>{{__('common.memo_no')}}</th>
                                <th>{{__('common.chalan_no')}}</th>

                                <th> {{ $cost_type == "unload" ? trans('common.unload') : trans('common.transport') }} {{__('common.cost')}} </th>
                                <th>{{__('common.date')}} </th>
                            </tr>
                            @foreach($modal as $key=>$value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->memo_no }}</td>
                                    <td>{{ $value->chalan }}</td>
                                    <td class="text-center">
                                        {{ $cost_type == "unload" ? create_money_format($value->unload_cost) : create_money_format($value->transport_cost) }}
                                    </td>
                                    <td>{{ db_date_month_year_format($value->date) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{ $modal->links() }}
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
