<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/21/2019
 * Time: 2:53 PM
 */

 $route =  \Auth::user()->can(['member.account_types.index']) ? route('member.account_types.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'GL Accounts',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'GL Account Groups',
    'title'=>'List Of GL Account Groups',
    'heading' => 'List Of GL Account Groups',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    <div class="box-header">
                        <a href="{{ route('admin.account_types.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add GL Account Groups </i></a>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-striped table-responsive">
                                <tr>
                                    <th>#SL</th>
                                    <th>Display Name</th>
                                    <th>Status</th>
                                </tr>
                                @foreach($account_types as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->display_name }}</td>
                                        <td>{{ ucfirst($value->status) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="3">
                                        {{ $account_types->links() }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
