<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.doctor_comission.index']) ? route('member.doctor_comission.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Doctor Comission',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Doctor Comission',
    'title'=>'Edit Doctor Comission',
    'heading' => 'Edit Doctor Comission',
];

?>



@extends('layouts.back-end.master', $data)

@section('contents')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">

        @include('common._alert')

        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Update </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::model($data, ['route' => ['member.doctor_comission.update', $data->id], 'files'=>'true', 'method' => 'put']) !!}

            <div class="box-body">

                <table id="group_id_table" class="table table-striped table-bordered mytable_style table-hover sell_cart">
                    <thead>
                        <tr>
                            <th style="width:5%;">S.L</th>
                            <th style="width:34%;">Group Name</th>
                            <th style="width:34%;">Test Name</th>
                            <th style="width:15%;">Com Type</th>
                            <th style="width:12%;">Amnt/Per</th>

                        </tr>
                    </thead>
                    <tbody class="mytable_style" id="dynamic_row">

                        <tr>
                            <td>1</td>
                            <td>
                                <input disabled type="text"  value="{{$data->doctorComission->testGroup?$data->doctorComission->testGroup->title : ""}}" class="form-control">

                            </td>

                            <td>
                                <input disabled type="text"  value="{{$data->subTestGroup?$data->subTestGroup->title : ""}}" class="form-control">


                            </td>
                            <td>
                                <select required name="comission_type" class="form-control select2" tabindex="-1" aria-hidden="true">
                                    <option {{$data->doctorComission->comission_type == 1? "selected" : "" }} value="1">Percentage</option>
                                    <option {{$data->doctorComission->comission_type == 2? "selected" : "" }} value="2">Fixed Commission</option>
                                </select>
                            </td>
                            <td>
                                <input required type="text" value="{{$data->doctorComission->amount}}" name="amount" class="form-control">
                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>


            <div class="box-footer">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection


@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

@endpush


