<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.doctor_comission.index']) ? route('member.doctor_comission.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Doctor',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Doctor Comission',
    'title' => 'List Of Doctor Comission',
    'heading' => 'List Of Doctor Comission',
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
                                @if (\Auth::user()->can(['member.doctor_comission.create']))
                                    <a href="{{ route('member.doctors.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Doctor Comission</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="box box-primary">
                                <div class="box-body box-profile pt-4 pb-0">
                                    <div class="col-md-3">

                                        <img style="height: 100px" class="profile-user-img img-responsive img-circle" src="{{ $doctor->image == null ? asset("/public/adminLTE/dist/img/avatar5.png") : asset('/public/uploads/doctor/'.$doctor->image)}}" alt="User profile picture">

                                    </div>
                                    <div class="col-md-9">
                                        <table class="table table-responsive table-striped">

                                            <tr>
                                                <th>{{__('common.name')}} </th>
                                                <td colspan="3">{{ $doctor->name }} ({{ $doctor->degree }})</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('common.phone')}} </th>
                                                <td>{{ $doctor->mobile }}</td>
                                                <th>{{__('common.address')}} </th>
                                                <td>{{ $doctor->address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Consult Fee</th>
                                                <td>{{ $doctor->consult_fee }}</td>

                                            </tr>



                                        </table>
                                    </div>
                                </div>

                             
                            </div>

                            {{-- <caption>{{$comission->doctor->name}}</caption> --}}
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>

                                        <th>Group Name</th>
                                        <th>Test Title</th>
                                        <th>Comission Type</th>
                                        <th>Comission</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count =0;
                                    @endphp
                                    {{-- {{dd($comissions)}} --}}
                                    @foreach ($comissions as $k => $value)

                                    @foreach ($value->comission as $key=> $list)
                                    <tr>

                                        <td>{{ ++$count }}</td>
                                        <td>{{ $value->testGroup? $value->testGroup->title : "" }}</td>
                                        <td>{{ $list->subTestGroup? $list->subTestGroup->title : "" }}</td>
                                        <td>{{ $value->comission_type == 1? "Percentage" : "Fixed Commission" }}</td>
                                        <td>{{ $value->amount }}</td>



                                        <td>

                                            {{-- @if(\Auth::user()->can(['member.doctor_comission.edit']))
                                            <a class="btn btn-xs btn-success"
                                                href="{{ route('member.doctor_comission.edit',$list->id) }}"><i
                                                    class="fa fa-edit" title='Edit'></i>
                                                </a>
                                            @endif --}}

                                                @if(\Auth::user()->can(['member.doctor_comission.destroy']))

                                                <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.doctor_comission.destroy', $list->id) }}">
                                                    <i class="fa fa-times"></i>
                                                </a>

                                            @endif
                                        </td>

                                    </tr>
                                    @endforeach

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


    </script>
@endpush
