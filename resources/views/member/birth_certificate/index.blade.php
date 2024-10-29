<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.birth_certificate.index']) ? route('member.birth_certificate.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Birth Certificate',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Birth Certificate',
    'title' => 'List Of Birth Certificate',
    'heading' => 'List Of Birth Certificate',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
<style>
    /* width */
    ::-webkit-scrollbar {
        height: 6px !important;
        width: 0px !important;

    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #dbdbdb !important;
        border-radius: 10px !important;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #6AA0BF !important;
        border-radius: 10px !important;
        padding: 0 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #6AA0BF !important;
    }

</style>
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                        <div class="box-header">
                            <div class="box-header">
                                @if (\Auth::user()->can(['member.doctors.create']))
                                    <a href="{{ route('member.birth_certificate.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Birth Certificate</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive w-100">
                            <table id="vanilla-table1" class="table text-nowrap table-bordered table-hover overflow-visible">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Newborn Id No</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Date And Time Of Birth</th>
                                        <th>Mother's Id No</th>
                                        <th>Mother's Name</th>
                                        <th>Mother's Nationality</th>
                                        <th>Mother's Religion</th>
                                        <th>Father's Id No</th>
                                        <th>Father's Name </th>
                                        <th>Father's Nationality</th>
                                        <th>Father's Religion</th>
                                        <th>Present Address</th>
                                        <th>Permanent Address</th>

                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($birth_certificates as $key => $list)
                                        <tr>
                                            <td>{{ $list->serial_no }}</td>
                                            <td>{{ $list->newborn_id_no }}</td>
                                            
                                            <td>{{ $list->name }}</td>
                                            <td>{{ $list->sex }}</td>
                                            <td>{{ $list->date_and_time_of_birth }}</td>
                                            <td>{{ $list->mother_s_id_no }}</td>
                                            <td>{{ $list->mother_s_name }}</td>
                                            <td>{{ $list->mother_s_nationality }}</td>
                                            <td>{{ $list->mother_s_religion }}</td>
                                            <td>{{ $list->father_s_id_no }}</td>
                                            <td>{{ $list->father_s_name }}</td>
                                            <td>{{ $list->father_s_nationality }}</td>
                                            <td>{{ $list->father_s_religion }}</td>
                                            <td>{{ $list->present_address }}</td>
                                            <td>{{ $list->permanent_address }}</td>




                                            <td>
                                                @if(\Auth::user()->can(['member.doctors.edit']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.birth_certificate.edit',$list->id) }}"><i
                                                        class="fa fa-edit" title='Edit'></i>
                                                    </a>


                                                @endif


                                                {{-- @if(\Auth::user()->can(['member.doctor_comission.show']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.birth_certificate.show',$list->id) }}"><i
                                                        class="fa fa-eye" title='Comission Show'></i>
                                                    </a>


                                                @endif --}}

                                                    @if(\Auth::user()->can(['member.doctors.destroy']))

                                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.birth_certificate.destroy', $list->id) }}">
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


@endpush
