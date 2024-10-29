<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/7/2019
 * Time: 5:22 PM
 */


 $route =  \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

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
        'name' => 'Purchases Due',
    ],
];

$data['data'] = [
    'name' => 'Purchase Due',
    'title'=>'List Of Purchase Due',
    'heading' => trans('purchase.list_of_purchase_due'),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('purchase.purchase_due_list')}}</h3>
                </div>

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <div class="col-lg-12">
                                <table class="table table-striped" id="dataTable">

                                    <tbody>
                                    <tr>
                                        <th> #SL</th>
                                        <th> {{__('common.purchase')}} ID</th>
                                        <th> {{(__('common.memo_no'))}}</th>
                                        <th> {{__('common.total_amount')}}</th>
                                        <th> {{__('common.due_amount')}}</th>
                                        <th> {{__('common.manage')}}</th>
                                    </tr>
                                    @foreach($modal as $key=>$value)
                                        <tr>
                                            <td> {{ $key+1 }} </td>
                                            <td> {{ $value->id }} </td>
                                            <td> {{ $value->memo_no }} </td>
                                            <td> {{ $value->total_amount }} </td>
                                            <td> {{ $value->due_amount }} </td>
                                            <td>
                                                <a href="{{ route('member.purchase.due_payment', $value->id) }}" class="btn btn-success btn-sm"> <i class="fa fa-money"></i> {{__('common.pay_due')}}</a>
                                            </td>
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
        </div>
    </div>
@endsection

