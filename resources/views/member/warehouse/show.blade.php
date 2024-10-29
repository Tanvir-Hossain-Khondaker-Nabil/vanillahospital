<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */
$title = "Warehouse ";
$route =  \Auth::user()->can(['member.warehouse.index']) ? route('member.warehouse.index') : "#";
$home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => $title,
        'href' => $route,
    ],
    [
        'name' => 'Index',
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
                                <th>Title</th>
                                <td>{{ $warehouse->title }}</td>
                            </tr>
                            {{--<tr>--}}
                                {{--<th>Shortcode</th>--}}
                                {{--<td>{{ $warehouse->shortcode }}</td>--}}
                            {{--</tr>--}}
                            <tr>
                                <th>Phone</th>
                                <td>{{ $warehouse->mobile }}</td>
                            </tr>
                            <tr>
                                <th > Address </th>
                                <td > {{  $warehouse->address }} </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">Address Details</h3>--}}
                {{--</div>--}}
                <!-- /.box-header -->
                <div class="box-body">
                   {{--<table class="table table-responsive table-striped">--}}

                       {{--<tr>--}}
                           {{--<th colspan="1"> Address </th>--}}
                           {{--<td colspan="3"> {{  $warehouse->address }} </td>--}}
                       {{--</tr>--}}
                       {{--<tr>--}}
                           {{--<th>Latitude</th>--}}
                           {{--<td>{{ $warehouse->latitude }}</td>--}}
                           {{--<th>Longitude</th>--}}
                           {{--<td>{{ $warehouse->longitude }}</td>--}}
                       {{--</tr>--}}
                       {{--<tr>--}}
                           {{--<th>Division</th>--}}
                           {{--<td>{{ $warehouse->division ? $warehouse->division->display_name_bd : "" }}</td>--}}
                           {{--<th>District</th>--}}
                           {{--<td>{{ $warehouse->district ? $warehouse->district->display_name_bd : "" }}</td>--}}
                       {{--</tr>--}}
                       {{--<tr>--}}
                           {{--<th>Region</th>--}}
                           {{--<td>{{ $warehouse->region ? $warehouse->region->name : "" }}</td>--}}
                       {{--</tr>--}}
                       {{--<tr>--}}
                           {{--<th>Thana</th>--}}
                           {{--<td>{{ $warehouse->thana ? $warehouse->thana->name : "" }}</td>--}}
                           {{--<th>Area</th>--}}
                           {{--<td>{{ $warehouse->area ? $warehouse->area->display_name_bd : "" }}</td>--}}
                       {{--</tr>--}}
                   {{--</table>--}}


                    @if(isset($warehouse) && $warehouse->featured_image != '')
                        <div class="form-group">
                            <h4>Featured image</h4>
                            <img src="{{ $warehouse->featured_image_path }}" style="width: 200px !important;"/>
                        </div>
                    @endif


                    @if(isset($warehouse) && $warehouse->gallery_images != '')
                        <div class="form-group">
                            <h4>Gallery images</h4>
                            @foreach($warehouse->gallery_images_path as $value)
                                <img src="{{ $value }}" style="width: 200px !important;"/>
                            @endforeach
                        </div>
                    @endif


                </div>




            <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
@endsection

