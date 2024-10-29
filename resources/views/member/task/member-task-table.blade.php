<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */
$route = \Auth::user()->can(['member.users.user_task']) ? route('member.users.user_task') : '#';
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Task',
        'href' => $route,
    ],
    [
        'name' => 'Task',
    ],
];

$data['data'] = [
    'name' => 'Task',
    'title' => 'Task',
    'heading' => 'Task',
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
                            <h3 class="box-title">Task</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Project</th>
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>

                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach ($tasks as $key => $task)
                                         {{-- {{dd($task)}} --}}
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ $task->task->project? $task->task->project->project : '' }}</td>
                                            <td>{{ $task->task->title }}</td>
                                            <td>{{ $task->task->start_date }}</td>
                                            <td>{{ $task->task->end_date }}</td>
                                            <td>{{ ucfirst($task->task->priority) }}</td>
                                            <td>
                                                @if ($task->status == 'done')
                                                    <span style="background-color:#0da540" class="badge">Done</span>
                                                @elseif($task->status == 'in_progress')
                                                    <span style="background-color:#93d517" class="badge">In Progress</span>
                                                @elseif($task->status == 'review')
                                                    <span style="background-color:#73075f" class="badge">Review</span>
                                                @elseif($task->status == 'to_do')
                                                    <span style="background-color:#10a99e" class="badge">To Do</span>
                                                @endif


                                            </td>

                                            <td>
                                                <select class="form-control w-100" data-id="{{ $task->task_id }}"
                                                    name="status" onchange="changeStatus(this,'task')">
                                                    <option value=""> Select Status </option>
                                                    <option value="to_do"> To Do </option>
                                                    <option value="in_progress"> In Progress </option>
                                                    <option value="review"> Review </option>
                                                    <option value="done"> Done </option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="float-right">
                                {{-- {{$tasks->links()}} --}}

                            </div>
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

        // function changeStatus(e,id){
        //     // alert(e.value);
        //     // alert($(this).attr("data-id"));
        //     console.log('kk',e.value,id)

        //     // let id =
        // }

        // $('#change-status').on('change',function(){
        //     console.log('kk')
        //     alert($(this).attr("data-id"));
        // })
    </script>
@endpush
