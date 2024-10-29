<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['member.project.index']) ? route('member.project.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project',
        'href' => $route,
    ],
    [
        'name' => 'Projects',
    ],
];

$data['data'] = [
    'name' => 'Projects',
    'title'=>'Projects',
    'heading' => 'Projects',
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
                          <h3 class="box-title">Projects</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>#SL</th>
                                  <th>Project</th>
                                  <th>Project Category</th>
                                  <th>Client Name</th>
                                  <th>Address</th>
                                  <th>Start Date</th>
                                  <th>End Date</th>
                                  <th>Action</th>


                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $key => $task)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$task->project ? $task->project->project : ''}}</td>
                                        <td>{{$task->project ? $task->project->projectCategory->display_name :''}}</td>
                                        <td>{{$task->project? $task->project->client->name :''}}</td>
                                        <td>{{$task->project? $task->project->address :''}}</td>
                                        <td>{{$task->project? $task->project->start_date :''}}</td>
                                        <td>{{$task->project? $task->project->expire_date :''}}</td>
                                        <td>
                                            @if (!empty($task->project))
                                            <a class="btn btn-xs btn-success" href="{{url('employee/project/'.$task->project->id.'/task')}}"><i class="fa fa-plus" title='add task'></i></a>
                                            <a class="btn btn-xs btn-info" href="{{url('employee/project/'.$task->project->id.'/show')}}"><i class="fa fa-info-circle"></i></a>
                                            <a class="btn btn-xs btn-primary" href="{{route('member.users.kanban_list', $task->project->id)}}"><i class="fa fa-tasks"></i></a>

                                            {{-- <a class="btn btn-xs btn-info" href="{{url('member.project/'.$task->project->id)}}"><i class="fa fa-info-circle"></i></a> --}}
                                            @endif
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
