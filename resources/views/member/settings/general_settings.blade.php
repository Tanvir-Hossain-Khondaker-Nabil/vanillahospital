<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/8/2019
 * Time: 12:14 PM
 */
 $route =  \Auth::user()->can(['member.users.index']) ? route('member.users.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'General Settings',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'General Settings',
    'title'=>'General Settings',
    'heading' => 'General Settings',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
    <style type="text/css">
        .select2.select2-container.select2-container--default.select2-container--focus, .select2.select2-container.select2-container--default.select2-container--below, .select2.select2-container.select2-container--default {
            width: 100% !important;
        }
    </style>
@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')


        <!-- general form elements -->
            <div class="box box-primary">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#general_setting" data-toggle="tab">General Setting</a></li>
                        <li ><a href="#all_settings" data-toggle="tab">Settings</a></li>
                        <li ><a href="#cash_setup" data-toggle="tab">Cash Configuration Setup</a></li>
                        <li ><a href="#print_page_system" data-toggle="tab">Print Page Setup</a></li>
                        <li ><a href="#backup_db" data-toggle="tab">DB Backup</a></li>

                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="general_setting">
                            <div class="box-body">
                                <table class="table table-responsive table-striped">

                                    <tr>
                                        <th> Member Code</th>
                                        <td> {{ $memberInfo->member_code }}</td>
                                    </tr>
                                    <tr>
                                        <th> Expire Date</th>
                                        <td> {{ formatted_date_string($memberInfo->expire_date) }}</td>
                                    </tr>
                                    <tr>
                                        <th> API Access Token</th>
                                        <td>{{ $memberInfo->api_access_key }} </td>
                                    </tr>
                                </table>


                            </div>

                        </div>
                        <div class=" tab-pane" id="print_page_system">
                            @include('member.settings.print_page_system')
                        </div>
                        <div class=" tab-pane" id="cash_setup">
                            @include('member.settings.cash_configuration')
                        </div>
                        <div class=" tab-pane" id="all_settings">
                            @include('member.settings.all_settings')
                        </div>
                        <div class=" tab-pane" id="backup_db">
                            @include('member.settings.backup_db')
                        </div>
                    </div>
                </div>


                <!-- /.box -->
            </div>


        </div>
    </div>
@endsection

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(function () {
            $('.select2').select2()
        });

    </script>

@endpush
