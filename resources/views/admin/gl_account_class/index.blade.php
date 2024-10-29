<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['admin.gl_account_class.index']) ? route('admin.gl_account_class.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'GL Account Classes',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'GL Account Classes',
    'title'=>'List Of GL Account Classes',
    'heading' =>trans('common.list_of_gl_account_classes'),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

{{--                    <div class="box-header">--}}
{{--                        <a href="{{ route('admin.gl_account_class.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add GL Account Classes </i></a>--}}
{{--                    </div>--}}
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-striped table-responsive">
                                <tr>
                                    <th>#{{__('common.serial')}}</th>
                                    <th>{{__('common.name')}} </th>
                                    <th>{{__('common.class_type')}}</th>
{{--                                    <th>Manage</th>--}}
                                </tr>
                                @foreach($gl_account_classes as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->class_type }}</td>
{{--                                    <td>--}}
{{--                                        <a href="{{ route('admin.gl_account_class.edit', $value->id) }}" class="btn btn-xs btn-success">--}}
{{--                                            <i class="fa fa-pencil"></i>--}}
{{--                                        </a>--}}

{{--                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('admin.gl_account_class.destroy', $value->id) }}">--}}
{{--                                            <i class="fa fa-times"></i>--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
                                </tr>
                                    @endforeach

                                <tr>
                                    <td class="text-right" colspan="4">
                                        {{ $gl_account_classes->links() }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
