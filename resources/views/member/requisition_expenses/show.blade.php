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
        'name' => 'Requisition Expenses',
        'href' => $route,
    ],
    [
        'name' => $requisition_expenses->code,
    ],
];

$data['data'] = [
    'name' => 'Requisition Expense : ' . $requisition_expenses->code,
    'title' => 'Code: ' . $requisition_expenses->code,
    'heading' =>trans('common.requisition_expenses'). ': ' . $requisition_expenses->code,
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush


@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <a class="btn btn-xs btn-primary"
                       href="{{ route('member.requisition_expenses.show', $requisition_expenses->id) }}?based=print" id="btn-print"><i
                            class="fa fa-print"></i>{{__('common.print')}}  </a>

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['super-admin', 'admin']))
                        <a href="{{ route('member.requisition_expenses.edit',  $requisition_expenses->id) }}"
                           class="btn btn-xs btn-success">
                            <i class="fa fa-pencil"></i>{{__('common.Edit')}}
                        </a>
                        <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm"
                           data-target="{{route('member.requisition_expenses.destroy', $requisition_expenses->id)}}">
                            <i class="fa fa-trash"></i>{{__('common.Delete')}}
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

                        <div class="col-md-6 p-5">
                            <table class="bill-info w-100">
                                <tr>
                                    <th style="width: 70px !important;">{{__('common.date')}} :</th>
                                    <td>{{ $requisition_expenses->date_format }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">{{__('common.code')}}:</th>
                                    <td>{{ $requisition_expenses->code }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">{{__('common.employee')}} :</th>
                                    <td>{{ $requisition_expenses->employee->employee_name_id }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 70px !important;">{{__('common.created_by')}} :</th>
                                    <td>
                                        {{ $requisition_expenses->createdBy->uc_full_name }}
                                    </td>
                                </tr>

                                @if($requisition_expenses->transaction_id)
                                <tr>
                                    <th style="width: 70px !important;">{{__('common.transaction_code')}} :</th>
                                    <td>
                                        @if(Auth::user()->can(['member.general_ledger.show']))
                                            <a href="{{ route('member.general_ledger.show', $requisition_expenses->transaction->transaction_code) }}" target="_blank"> {{ $requisition_expenses->transaction->transaction_code }} </a>
                                        @else
                                        {{ $requisition_expenses->transaction->transaction_code }}
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <!-- /.row -->


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">

                            <table  class="table table-responsive table-bordered margin-top-30 float-right">
                                <tbody>
                                <tr>
                                    <th style="width: 50px;"  class="text-center">#{{__('common.serial')}}</th>
                                    <th> {{__('common.expense_name')}} </th>
                                    <th class="text-right"> {{__('common.amount')}} </th>
                                </tr>
                                @php $total = 0; @endphp

                                @foreach($requisition_expenses->expenseDetails as $key => $value)

                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td>{{ $value->expenseType->display_name }}</td>
                                        <td class="text-right">{{ create_money_format($value->amount) }}</td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <th class="text-right" colspan="2"> {{__('common.total')}} </th>
                                        <th class="text-right"> {{ create_money_format($requisition_expenses->total_amount) }} </th>
                                    </tr>
                                </tbody>

                            </table>


                             @if(isset($requisition_expenses))
                            @if(!$requisition_expenses->transaction_id)
                                    @if(!Auth::user()->hasRole(['user']) && Auth::user()->can('member.requisition_expenses.approved'))

                                    {!! Form::open(['route' => 'member.requisition_expenses.approved', 'method' => 'POST', 'role'=>'form' ]) !!}

                                <table class="margin-top-30" style="width: 400px; float: right;">
                                    <tr>
                                        <th>
                                            <h4> {{__('common.accept_requisition_and_create_transaction')}}:</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>

                                            <div class="form-group">
                                                <label for="inputPassword" > {{__('common.date')}}</label>
                                                <input type="hidden" name="requisition_expense_id" value="{{$requisition_expenses->id}}" required>
                                                {!! Form::text('date',null,['id'=>'date','class'=>'form-control','autocomplete'=>"off",'required']); !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group ">
                                                <label for="inputPassword" >{{__('common.expense_from_account')}} </label>
                                                <select class="form-control select2"  name="account_id" required>
                                                    <option value="">{{__('common.select_cash_and_bank')}} </option>
                                                    @foreach($accounts as $value)
                                                        <option value="{{ $value->id }}" > {{ $value->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">
                                            <button  class="btn btn-primary" type="submit"> {{__('common.approve_transaction')}} </button>
                                        </td>
                                    </tr>
                                </table>
                                    {!! Form::close() !!}
                                        @endif

                            @else
                                <div class="px-0 col-md-12 text-right">
                                    <a href="javascript:void(0)" class="btn btn-success">
                                        Transaction Approved
                                    </a>
                                </div>
                            @endif
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(function () {

            $('.select2').select2();

            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            var today = moment().format('MM/DD/YYYY');
            $('#date').datepicker('setDate', today);
            $('#date').datepicker('update');
            $('.date').datepicker('setDate', today);
        });

    </script>

@endpush
