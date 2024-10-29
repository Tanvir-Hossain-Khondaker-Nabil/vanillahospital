<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.test_group.index']) ? route('member.test_group.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Test Group',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Test Group',
    'title' => 'List Of Test Group',
    'heading' => 'List Of Test Group',
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
                                @if (\Auth::user()->can(['member.test_group.create']))
                                    <a href="{{ route('member.test_group.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Test Group</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Title</th>
                                        <th>Specimen</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($test_group as $key => $list)
                                        <tr>
                                            {{-- {{dd($list)}} --}}
                                            <td>{{ ++$key }}</td>
                                            <td>
                                                <a href="{{route('member.test_group.show',$list->id)}}">{{ $list->title }}</a>

                                            </td>
                                            <td>{{ $list->specimen->specimen }}</td>

                                            <td>
                                                @if(\Auth::user()->can(['member.test_group.edit']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.test_group.edit',$list->id) }}"><i
                                                        class="fa fa-edit" title='Edit'></i>
                                                    </a>
                                                @endif
                                                    @if(\Auth::user()->can(['member.test_group.destroy']))

                                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.test_group.destroy', $list->id) }}">
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


    </script>
@endpush
