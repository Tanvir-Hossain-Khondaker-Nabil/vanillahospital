<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.driver.index']) ? route('member.driver.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Driver',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Driver',
    'title' => 'List Of Driver',
    'heading' => 'List Of Driver',
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
                                    <a href="{{ route('member.driver.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Driver</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table id="vanilla-table1" class="table text-nowrap table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>First Mobile Number</th>
                                        <th>Second Mobile Number</th>
                                        <th>Driving License No</th>
                                        <th>Status</th>
                                        <th>Address</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($drivers as $key => $list)
                                        @php
                                            $driver_count = App\Models\VehicleDetail::where('driver_id',$list->id)->count();
                                        @endphp
                                        <tr>
                                            <td>{{ ++$key }}</td>                                            
                                            <td>{{ $list->name }}</td>
                                            <td>{{ $list->email }}</td>
                                            <td>{{ $list->phone_one }}</td>
                                            <td>{{ $list->phone_two }}</td>
                                            <td>{{ $list->driving_license_no }}</td>
                                            <td>
                                                <label class="label {{@$list->status == 1 ? 'label-primary' : 'label-warning'}}  ">{{@$list->status == 1 ? 'Active' : 'Inactive'}}</label>
                                            </td>
                                            <td>{{ $list->address }}</td>                                            
                                            <td>
                                                @if(\Auth::user()->can(['member.doctors.edit']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.driver.edit',$list->id) }}"><i
                                                        class="fa fa-edit" title='Edit'></i>
                                                    </a>


                                                @endif


                                                {{-- @if(\Auth::user()->can(['member.doctor_comission.show']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.driver.show',$list->id) }}"><i
                                                        class="fa fa-eye" title='Comission Show'></i>
                                                    </a>


                                                @endif --}}
                                                @if ($driver_count < 1) 
                                                    @if(\Auth::user()->can(['member.doctors.destroy']))

                                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.driver.destroy', $list->id) }}">
                                                        <i class="fa fa-times"></i>
                                                    </a>

                                                    @endif
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
