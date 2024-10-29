<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

 $route =  \Auth::user()->can(['member.sharer.index']) ? route('member.sharer.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$title = "Customer - ".$sharer->name;

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home'
    ],
    [
        'name' => $title,
        'href' => $route
    ],
    [
        'name' => 'Index'
    ],
];

$data['data'] = [
    'name' => $title,
    'title'=> $title,
    'heading' => $title,
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">
        <div class="col-md-12">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="col-md-9">
                        <table class="table table-responsive table-striped">
                            <tr>
                                <th>Name</th>
                                <td>{{ $sharer->name }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $sharer->phone }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $sharer->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Address Details</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   <table class="table table-responsive table-striped">

                       <tr>
                           <th colspan="1"> Address </th>
                           <td colspan="3"> {{  $sharer->address }} </td>
                       </tr>
                       <tr>
                           <th>State</th>
                           <td>{{ $sharer->division ? $sharer->division->display_name_bd : "" }}</td>
                           <th>City</th>
                           <td>{{ $sharer->division ? $sharer->division->display_name_bd : "" }}</td>
                       </tr>
                       {{-- <tr>
                           <th>Upazilla</th>
                           <td>{{ $sharer->upazilla ? $sharer->upazilla->display_name_bd : "" }}</td>
                           <th>Union</th>
                           <td>{{ $sharer->union ? $sharer->union->display_name_bd : ''}}</td>
                       </tr> --}}
                       <tr>
                           <th>Area</th>
                           <td>{{ $sharer->area ? $sharer->area->display_name_bd : "" }}</td>
                       </tr>
                   </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
@endsection

