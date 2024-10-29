<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.opd.discount.summary.list']) ? route('member.opd.discount.summary.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'OPD Discount Summary List',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OPD Discount Summary List',
    'title' => 'OPD Discount Summary List',
    'heading' => 'OPD Discount Summary List',
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">

@endpush

@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="py-5">
                                        <form action="{{ route('member.opd.discount.summary.search') }}" method="POST">
                                            @csrf
                                            <div class="card-body border-bottom row">


                                                <div class="col-lg-3 col-md-3 col-12">
                                                    <label>{{ __('From Date') }}</label>
                                                    <input autocomplete="off" id="from_date" name="from_date" type="text"
                                                        placeholder="{{ __('From Date') }}" class="form-control"
                                                        value="">
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-12">
                                                    <label>{{ __('To Date') }}</label>
                                                    <input autocomplete="off" id="to_date" name="to_date" type="text"
                                                        placeholder="{{ __('To Date') }}" class="form-control"
                                                        value="">
                                                </div>



                                                <div class="col-lg-2 col-md-3 col-12" style="margin-top: 22px">
                                                    <button type="submit" class="btn btn-success">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(function() {
            $('.select2').select2();

        });

        $('#from_date').datepicker({

            "format": 'yyyy-mm-dd',
            // "format": 'mm/dd/yyyy',

            "showTime": true,
            // "startDate": "+0d",
        });

        $('#to_date').datepicker({

            "format": 'yyyy-mm-dd',
            // "format": 'mm/dd/yyyy',
            "showTime": true,
            // "startDate": "+0d",
        });

        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });
            $("#vanilla-table3").DataTable({

                "searching": true,
            });

        });
    </script>


    </script>
@endpush
