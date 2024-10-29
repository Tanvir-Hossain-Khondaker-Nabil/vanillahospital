<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.doctors.index']) ? route('member.doctors.index') : '#';
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
    'name' => 'Doctor',
    'title' => 'List Of Doctor',
    'heading' => 'List Of Doctor',
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
                            @if (\Auth::user()->can(['member.doctors.create']))
                            <a href="{{ route('member.doctors.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                </i> Add Doctor</a>
                            @endif

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="vanilla-table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Image</th>

                                    <th>Action</th>


                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($doctors as $key => $list)
                                <tr>
                                    {{-- {{dd($list)}} --}}
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>{{ $list->degree }}</td>
                                    <td>{{ $list->mobile }}</td>
                                    <td>{{ $list->address }}</td>
                                    <td>{{ $list->type }}</td>

                                    <td>
                                        <img class="mt-2" src="{{asset('/public/uploads/doctor/'.$list->image)}}" style="max-height: 100px; width: 100px !important;" alt=""> <br />
                                    </td>



                                    <td>
                                        @if(\Auth::user()->can(['member.doctors.edit']))
                                        <a class="btn btn-xs btn-success" href="{{ route('member.doctors.edit',$list->id) }}"><i class="fa fa-edit" title='Edit'></i>
                                        </a>
                                        @endif

                                        @if(\Auth::user()->can(['member.doctor_comission.create']))
                                        <a class="btn btn-xs btn-success" href="{{ route('member.doctor_comission.create') }}?doct_id={{$list->id}}"><i class="fa fa-plus-circle" title='Add comission'></i>
                                        </a>
                                        @endif

                                        <button type="button" class="btn btn-xs btn-success"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="fa fa-plus-circle" title='Add comission'></i>
                                        </button>


                                        @if(\Auth::user()->can(['member.doctor_comission.show']))
                                        <a class="btn btn-xs btn-success" href="{{ route('member.doctor_comission.show',$list->id) }}"><i class="fa fa-eye" title='Comission Show'></i>
                                        </a>
                                        @endif

                                        @if(\Auth::user()->can(['member.doctors.destroy']))
                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.doctors.destroy', $list->id) }}">
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


<!-- Modal -->
<div class="modal fade" id="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ending Reserve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('/member/booking_vehicle')}}">
                @csrf
                <div class="modal-body">

                    @method("PUT")
                    <input type="hidden" id="vehicle_detail_id" name="vehicle_detail_id">
                    <div class="form-group">
                        <label for="name">End Date And Time <span class="text-red"> * </span> </label>
                        <input id="datePicker" class="form-control" placeholder="Enter End Date And Time" required="" name="end_date_and_time" type="text">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(function() {
        $("#vanilla-table1").DataTable({
            // "lengthMenu":[ 3,4 ],
            "searching": true
        , });
        $("#vanilla-table2").DataTable({

            "searching": true
        , });

    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</script>
@endpush
