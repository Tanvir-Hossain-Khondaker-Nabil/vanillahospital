<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['member.share_holders.index']) ? route('member.share_holders.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Share Holder',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Share Holder',
    'title'=>'List Of Share Holder',
    'heading' =>trans('common.list_of_shift'),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">
                    @if(\Auth::user()->can(['member.share_holders.create']))
                    <div class="box-header">
                        <a href="{{ route('member.share_holders.create') }}" class="btn btn-info"> <i class="fa fa-plus"> {{__('common.add_share_holder')}} </i></a>
                    </div>

                    @endif
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">


                                    <div class="box-body">
                                        <table id="vanilla-table1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#SL</th>
                                                    <th>Name</th>
                                                    <th>Father Name</th>
                                                    <th>Mother Name</th>
                                                    <th>Phone</th>
                                                    <th>Nominee Name</th>
                                                    <th>NID Number</th>
                                                    <th>Passport Number</th>
                                                    <th>Number of Share</th>
                                                    <th>Is Management</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($datas as $key => $list)
                                                    <tr>
                                                        {{-- {{dd($list)}} --}}
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $list->name }}</td>
                                                        <td>{{ $list->father_name }}</td>
                                                        <td>{{ $list->mother_name }}</td>
                                                        <td>{{ $list->phone }}</td>
                                                        <td>{{ $list->nominee }}</td>
                                                        <td>{{ $list->nid_number }}</td>
                                                        <td>{{ $list->passport_number }}</td>
                                                        <td>{{ $list->share_number }}</td>
                                                        <td>{{ $list->type == '1'? "Yes" : "No" }}</td>

                                                        <td>

                                                            @if (\Auth::user()->can(['member.share_holders.edit']))
                                                                <a class="btn btn-xs  btn-primary"
                                                                    href="{{ route('member.share_holders.edit', $list->id) }}">
                                                                    <i class="fa fa-edit" aria-hidden="true"
                                                                        title='Edit'></i>

                                                                </a>
                                                            @endif



                                                            @if (\Auth::user()->can(['member.share_holders.destroy']))
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-xs btn-danger delete-confirm"
                                                                    data-target="{{ route('member.share_holders.destroy', $list->id) }}">
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
                {{-- <p>ffffd</p> --}}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    {{-- {!! $dataTable->scripts() !!} --}}
@endpush
