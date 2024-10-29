<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/29/2019
 * Time: 2:57 PM
 */

 $route =  \Auth::user()->can(['member.items.print_barcode_form']) ? route('member.items.print_barcode_form') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Barcode',
        'href' => $route,
    ],
    [
        'name' => 'Product Barcode Print',
    ],
];

$data['data'] = [
    'name' => 'Product Barcode Print',
    'title'=> 'Product Barcode Print',
    'heading' => 'Product Barcode Print',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <style type="text/css">

        .per-barcode{
            margin-right: 25px;
            width: 120px;
            height: 90px;
            text-align: center;
            float: left;
            margin-bottom: 20px;
        }

        .per-barcode div{
            text-align: center !important;
            margin-left: 7px;
        }
        .per-barcode h4{
            margin: 5px 0;
            font-size: 15px;
        }
        .per-barcode p{
            text-align: center;
            margin: 5px 0;
            font-size: 12px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Barcode</h3>
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url."item_id=".$item->id."&print_qty=".$print_qty.'&' }}type=print" class="pull-right btn btn-sm btn-primary" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        @for($i=1; $i<=$print_qty; $i++)
                            <div class="per-barcode">
                                {{--                <p>{{ $company_name }}</p>--}}
                                <p>{{ $item->item_name }}</p>
                                <p>@php print_r($barcode) @endphp</p>
                                <p>{{ $item->productCode }}</p>
                                <h4>{{ "TK ".$item->price }}</h4>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

