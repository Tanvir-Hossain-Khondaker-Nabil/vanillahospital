<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$title = ($sharer->customer_type == "both" ? "Supplier & Customer ": ucfirst($sharer->customer_type));
$route =  \Auth::user()->can(['member.sharer.index']) ? route('member.sharer.index') : "#";
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
    // 'heading' => $title,
    'heading' => trans('common.'.strtolower($title)),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">
        <div class="col-md-12">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="col-md-3">
                        <img class="profile-user-img img-responsive img-circle" src="{{ $sharer->photo == null ? asset("/public/adminLTE/dist/img/avatar5.png") : $sharer->sharer_photo }}" alt="User profile picture">

                    </div>
                    <div class="col-md-9">
                        <table class="table table-responsive table-striped">
                            <tr>
                                <th>{{__('common.name')}}</th>
                                <td>{{ $sharer->name }}</td>
                            </tr>
                            <tr>
                                <th>{{__('common.phone')}}</th>
                                <td>{{ $sharer->phone }}</td>
                            </tr>
                            <tr>
                                <th>{{__('common.email')}}</th>
                                <td>{{ $sharer->email }}</td>
                            </tr>
                            {{--<tr>--}}
                                {{--<th>Manager</th>--}}
                                {{--<td>{{ $sharer->manager ? ucfirst($sharer->manager->full_name) : ''}}</td>--}}
                            {{--</tr>--}}
                        </table>
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.address_details')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   <table class="table table-responsive table-striped">

                       <tr>
                           <th colspan="1"> {{__('common.address')}} </th>
                           <td colspan="3"> {{  $sharer->address }} </td>
                       </tr>
                       {{--<tr>--}}
                           {{--<th>State</th>--}}
                           {{--<td>{{ $sharer->division ? $sharer->division->display_name_bd : "" }}</td>--}}
                           {{--<th>City</th>--}}
                           {{--<td>{{ $sharer->division ? $sharer->division->display_name_bd : "" }}</td>--}}
                       {{--</tr>--}}
                       {{-- <tr>
                           <th>Upazilla</th>
                           <td>{{ $sharer->upazilla ? $sharer->upazilla->display_name_bd : "" }}</td>
                           <th>Union</th>
                           <td>{{ $sharer->union ? $sharer->union->display_name_bd : ''}}</td>
                       </tr> --}}
                       {{--<tr>--}}
                           {{--<th>Area</th>--}}
                           {{--<td>{{ $sharer->area ? $sharer->area->display_name_bd : "" }}</td>--}}
                       {{--</tr>--}}
                   </table>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.other_details')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   <table class="table table-responsive table-striped">

                       <tr>
                           <th colspan="1"> {{__('common.gl_account_code')}}</th>
                           <td colspan="3"> {{ $sharer->account_type->account_code }} </td>
                       </tr>
                       <tr>
                           <th colspan="1"> {{__('common.gl_account_name')}}</th>
                           <td colspan="3"> {{ $sharer->account_type->display_name }} </td>
                       </tr>
                       <tr>
                           <th colspan="1"> {{__('common.cash_and_bank_name')}}</th>
                           <td colspan="3"> {{ $sharer->cash_bank->title }} </td>
                       </tr>

                       {{--<tr>--}}
                           {{--<th>Files</th>--}}
                           {{--<td> <a target="_blank" href="{{  $sharer->file ? $sharer->sharer_file : "javascript:void(0)"  }}"> View file </a> </td>--}}
                       {{--</tr>--}}
                       {{--<tr>--}}
                           {{--<th>Included Files</th>--}}
                           {{--<td>--}}
                               {{--<ul>--}}
                               {{--@foreach($submitted_documents as $value)--}}
                                       {{--<li class=""> {{ $value->document_type->name }}</li>--}}
                               {{--@endforeach--}}
                               {{--</ul>--}}
                           {{--</td>--}}
                       {{--</tr>--}}
                   </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
@endsection

