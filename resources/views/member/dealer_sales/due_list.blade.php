<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/7/2019
 * Time: 6:01 PM
 */


 $route =  \Auth::user()->can(['member.dealer_sales.index']) ? route('member.dealer_sales.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Dealer sales',
        'href' => $route,
    ],
    [
        'name' => 'Dealer Sales Due',
    ],
];

$data['data'] = [
    'name' => 'Dealer Sale Due',
    'title'=>'List Of Dealer Sale Due',
    'heading' => 'List Of Dealer Sale Due',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Sale Due List</h3>
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
                                        <th> Sale Code</th>
                                        <th> Total Amount</th>
                                        <th> Due Amount</th>
                                        <th> Manage</th>
                                    </tr>
                                    @foreach($modal as $key=>$value)
                                        <tr>
                                            <td> {{ $key+1 }} </td>
                                            <td> {{ $value->sale_code }} </td>
                                            <td> {{ $value->total_price }} </td>
                                            <td> {{ $value->due }} </td>
                                            <td>
                                                <a href="{{ route('member.dealer_sales.due_payment', $value->id) }}" class="btn btn-success btn-sm"> <i class="fa fa-money"></i> Pay Due</a>
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

