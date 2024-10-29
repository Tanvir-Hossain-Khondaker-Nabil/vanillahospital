<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 4:16 PM
 */

 $route =  \Auth::user()->can(['member.requisition.index']) ? route('member.requisition.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Requisitions',
        'href' => $route,
    ],
    [
        'name' => $requisition['id'],
    ],
];

$data['data'] = [
    'name' => 'Requisition Memo no: '.$requisition['id'],
    'title'=>'Memo no: '.$requisition['id'],
    'heading' => 'Requisition Memo No: '.$requisition['id'],
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <a href="{{ route('member.sales_requisitions.create') }}" class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> Add Sale Requisition</a>

                    <a class="btn btn-xs btn-primary" href="{{ route('member.sales_requisitions.print_requisition', $requisition->id) }}" id="btn-print"><i class="fa fa-print"></i> Print</a>

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['super-admin', 'admin']))
                    <a href="{{ route('member.sales_requisitions.edit',  $requisition->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <a href="javascript:void(0);"  class="btn btn-xs btn-danger delete-confirm" data-target="{{route('member.sales_requisitions.destroy', $requisition->id)}}">
                        <i class="fa fa-trash"></i> Delete
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
                        <div class="col-xs-4 text-center py-5">
                            @php print_r($barcode) @endphp<br>
                        </div>
                        <div class="col-xs-4 text-right company-info">
                            <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
                            <div class="info">
                                <p >{{ $company_address }} </p>
                                <p >{{ $company_city.($company_country ? ", ".$company_country : "") }}</p>
                                <p >Phone : {{ $company_phone }}</p>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- title row -->

                    <!-- info row -->
                    <div style="margin-bottom: 10px; " class="row invoice-info">

                        <div class="col-md-4 p-5">
                            <table class="bill-info w-100">
                                <tr >
                                    <th >Date: </th>
                                    <td >{{ $requisition->date_format }}</td>
                                </tr>
                                <tr >
                                    <th >Requisition No:</th>
                                    <td >{{ $requisition->id }}</td>
                                </tr>
                                <tr >
                                    <th >Customer Name</th>
                                    <td >
                                        @if($requisition->creator->sharer)
                                            {{ $requisition->creator->sharer->name }} <br/>
                                            {{ $requisition->creator->sharer->phone }}<br/>
                                            {{ $requisition->creator->sharer->address }}
                                        @else
                                            {{ $requisition->creator->full_name }} <br/>
                                            {{ $requisition->creator->phone }}<br/>
                                            {{ $requisition->creator->email }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.row -->


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">

                            <table class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        {{--<th>Carton</th>--}}
                                        {{--<th>Free</th>--}}
                                        {{--<th>Total Qty</th>--}}
                                        <th>Price</th>
                                        <th class="text-center">Total Price</th>
                                    </tr>
                                    @php $total = 0; @endphp

                                    @foreach($requisition->sales_requisition_details as $value)

                                    <tr>
                                        <td>{{ $value->item->item_name }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>{{ $value->unit }}</td>
                                        <td> {{ $value->qty }}</td>
                                        {{--<td> {{ $value->carton }}</td>--}}
                                        {{--<td> {{ $value->free }}</td>--}}
                                        {{--<td> {{ $value->qty+$value->free }}</td>--}}
                                        <td> {{ create_money_format($value->price) }}</td>
                                        <td class="text-right" >{{ create_money_format($value->total_price) }}</td>
                                    </tr>
                                        @php
                                            $total += $value->total_price;
                                        @endphp
                                    @endforeach
                                </tbody>

                            </table>
                            <table class="margin-top-30" style="width: 700px; float: left;">
                                <tr>
                                    <th> Notes: </th>
                                </tr>
                                <tr>
                                    <td>
                                        @php print_r($requisition->notation) @endphp
                                    </td>
                                </tr>
                            </table>
                            <table class=" margin-top-30 pull-right" width="400px">

                                <tr>
                                    <th class="text-right" colspan="5"> Sub Total</th>
                                    <th class="text-right" > {{ create_money_format($total) }} </th>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
