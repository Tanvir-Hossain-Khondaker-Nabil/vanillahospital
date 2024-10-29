<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.specimen.index']) ? route('member.specimen.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Specimen',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Specimen',
    'title' => 'List Of Specimen',
    'heading' => 'List Of Specimen',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                        <div class="box-header">
                            <div class="box-header">
                                @if (\Auth::user()->can(['member.specimen.create']))
                                    <a href="{{ route('member.specimen.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Specimen</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="py-3">
                                <form  action="{{ route('member.specimen.index') }}" method="GET">
                                    @csrf
                                    <div class="card-body border-bottom row">
                                        <div class="col-lg-3 col-md-3 col-12">
                                            <label>{{ __('From Date') }}</label>
                                            <input autocomplete="off" id="from_date" name="from_date" type="text" placeholder="{{ __('From Date') }}" class="form-control"
                                                value="{{ $from_date }}">
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-12">
                                            <label>{{ __('To Date') }}</label>
                                            <input autocomplete="off" id="to_date" name="to_date" type="text" placeholder="{{ __('To Date') }}" class="form-control"
                                                value="{{ $to_date}}">
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-12" style="margin-top: 22px">
                                            <button type="submit" class="btn btn-success">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Specimen</th>
                                        <th>Created At</th>

                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($specimen as $key => $list)
                                        <tr>

                                            <td>{{ ++$key }}</td>
                                            <td>{{ $list->specimen }}</td>
                                            <td>{{ $list->created_at }}</td>



                                            <td>
                                                @if(\Auth::user()->can(['member.specimen.edit']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.specimen.edit',$list->id) }}"><i
                                                        class="fa fa-edit" title='Edit'></i>
                                                    </a>

                                                    @endif

                                                    @if(\Auth::user()->can(['member.specimen.destroy']))

                                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.specimen.destroy', $list->id) }}">
                                                        <i class="fa fa-times"></i>
                                                    </a>

                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>

$('#from_date').datepicker({
            //   format: 'L',
            //    minDate: new Date(),
                //  "minDate": new Date(),
                // "setDate": new Date(),
              "format": 'yyyy-mm-dd',
                // "endDate": "+0d",
                // "todayHighlight": true,
                // "autoclose": true
                "showTime": true,
                // "startDate": "+0d",
            });

            $('#to_date').datepicker({
            //   format: 'L',
            //    minDate: new Date(),
                //  "minDate": new Date(),
                // "setDate": new Date(),
                "format": 'yyyy-mm-dd',
                // "endDate": "+0d",
                // "todayHighlight": true,
                // "autoclose": true
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

        });
    </script>


    </script>
@endpush
