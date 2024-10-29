<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/7/2019
 * Time: 6:01 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('admin.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sales',
        'href' => route('member.sales.index'),
    ],
    [
        'name' => 'Sales Due',
    ],
];

$data['data'] = [
    'name' => 'Sale Due',
    'title'=>'List Of Sale Due',
    'heading' => trans('common.list_of_sale_due'),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.sale_due_list')}}</h3>
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
                                        <th> {{__('common.sale_code')}}</th>
                                        <th> {{__('common.total_amount')}}</th>
                                        <th> {{__('common.due_amount')}}</th>
                                        <th> {{__('common.manage')}}</th>
                                    </tr>
                                    @foreach($modal as $key=>$value)
                                        <tr>
                                            <td> {{ $key+1 }} </td>
                                            <td> {{ $value->sale_code }} </td>
                                            <td> {{ $value->total_price }} </td>
                                            <td> {{ $value->due }} </td>
                                            <td>
                                                <a href="{{ route('member.sales.due_payment', $value->id) }}" class="btn btn-success btn-sm"> <i class="fa fa-money"></i> {{__('common.pay_due')}}</a>
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

