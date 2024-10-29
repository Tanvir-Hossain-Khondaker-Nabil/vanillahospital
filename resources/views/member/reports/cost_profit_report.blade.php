<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/25/2019
 * Time: 2:50 PM
 */

 $route = \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()) : '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => "Cost/Profit",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Cost/Profit",
    'title'=>''."Cost/Profit",
    'heading' => ''."Cost/Profit",
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                     <div class="col-lg-6">
                            <table class="table table-striped" id="dataTable">

                                <tbody>
                                <tr>
                                    <th>GL Class Name</th>
                                    <th class="text-right">Amount </th>
                                </tr>
                                @foreach($modal as $value)
                                    <tr>
                                        <td>{{ $value->gl_class_name }}</td>
                                        <td class="text-right">{{ create_money_format($value->total_amount)  }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
