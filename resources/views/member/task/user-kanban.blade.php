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
        'name' => 'Kanban',
        'href' => $route,
    ],
    [
        'name' => 'Kanban',
    ],
];

$data['data'] = [
    'name' => 'Task Kanban',
    'title' => 'Task Kanban',
    'heading' => 'Task Kanban',
];

?>

@extends('layouts.back-end.master', $data)
<style>
    .comment-box {
        border-radius: 5px;
        background-color: #eeebeb;
    }


    .ck.ck-editor__main .ck-content {
        height: 100px !important;
    }

    .cke_contents {
        height: 100px !important;
    }
</style>
@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div id="kanban">
                        <h5>Project Name: {{ $project->project }}</h5>
                        <div class="row">
                            {{-- To Do --}}
                            <div class="col-md-3 my-3">
                                <div class="kanban-col-title" style="border-bottom: 3px solid #F9A52D;"> To do <span
                                        class="float-right">{{ count($to_do) }} </span>
                                </div>
                                <div class="droppable" data-status="to_do">
                                    @foreach ($to_do as $to_do_task)
                                        <div class="mt-4 element" data-id={{ $to_do_task->task->id }}
                                            data-status={{ $to_do_task->status }}
                                            onclick="showDetailModal({{ $to_do_task->task->id }})">
                                            <div class="p-3 task-box">
                                                <div class="row">
                                                    @if (!empty($to_do_task->task->image))
                                                        <div class="col-md-2">
                                                            <img class="task-image"
                                                                 src="{{ asset('storage/app/public/task_image/' . $to_do_task->task->image) }} ">

                                                        </div>
                                                    @endif

                                                    <div class="col-md-10 px-0" style="float: none">
                                                        <span class="text-justify">{{ $to_do_task->task->title }}</span>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            {{-- In Progress --}}

                            <div class="col-md-3 my-3 ">
                                <div class="kanban-col-title" style="border-bottom: 3px solid #1672B9;"> In Progress
                                    <span class="float-right">{{ count($in_progress) }} </span>
                                </div>
                                <div class="droppable" data-status="in_progress">
                                    @foreach ($in_progress as $progress)
                                        <div class="mt-4 element" data-id={{ $progress->task->id }}
                                            data-status={{ $progress->status }}
                                            onclick="showDetailModal({{ $progress->task->id }})">
                                            <div class="p-3 task-box">
                                                <div class="row">

                                                    @if (!empty($progress->task->image))
                                                        <div class="col-md-2">
                                                            <img class="task-image"
                                                                 src="{{ asset('storage/app/public/task_image/' . $progress->task->image) }} ">

                                                        </div>
                                                    @endif

                                                    <div class="col-md-10 px-0" style="float: none">
                                                        <span class="text-justify">{{ $progress->task->title }}</span>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Review --}}

                            <div class="col-md-3 my-3 " id="elements">
                                <div class="kanban-col-title" style="border-bottom: 3px solid #ad159e;"> Review
                                    <span class="float-right">{{ count($review) }} </span>
                                </div>
                                <div class="droppable" data-status="review">
                                    @foreach ($review as $revi)
                                        <div class="mt-4 element" data-id={{ $revi->task->id }}
                                            data-status={{ $revi->status }}
                                            onclick="showDetailModal({{ $revi->task->id }})">
                                            <div class="p-3 task-box">
                                                <div class="row">
                                                    @if (!empty($revi->task->image))
                                                        <div class="col-md-2">
                                                            <img class="task-image"
                                                                 src="{{ asset('storage/app/public/task_image/' . $revi->task->image) }} ">

                                                        </div>
                                                    @endif

                                                    <div class="col-md-10 px-0" style="float: none">
                                                        <span class="text-justify">{{ $revi->task->title }}</span>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            {{-- Done --}}

                            <div class="col-md-3 my-3 " id="done-elements">
                                <div class="kanban-col-title" style="border-bottom: 3px solid #00B393;"> Done<span
                                        class="float-right">{{ count($done) }}</span>
                                </div>
                                <div class="droppable" data-status="done">
                                    @foreach ($done as $done_task)
                                        <div class="mt-4 element" data-id={{ $done_task->task->id }}
                                            data-status={{ $done_task->status }}
                                            onclick="showDetailModal({{ $done_task->task->id }})">
                                            <div class="p-3 task-box">
                                                <div class="row">
                                                    @if (!empty($done_task->task->image))
                                                        <div class="col-md-2">
                                                            <img class="task-image"
                                                                 src="{{ asset('storage/app/public/task_image/' . $done_task->task->image) }} ">

                                                        </div>
                                                    @endif

                                                    <div class="col-md-10 px-0" style="float: none">
                                                        <span class="text-justify">{{ $done_task->task->title }}</span>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div style="max-width: 1400px !important; " class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 10px;width: 740px">
                <div class="modal-header">
                    <h3 class="modal-title text-success" id="exampleModalLongTitle"></h3>

                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">


                        <div class="col-md-7">
                            <div class="mt-3">
                                <p class="" style="color: #29689e" id="employee"></p>
                                <strong class="">Status:</strong> <span class="badge" id="status"></span>
                            </div>
                            <div class="mt-3">
                                <div class="mt-3">
                                    <strong class="">Project:</strong>
                                    <span id="project-name"></span>
                                </div>
                                <div class="mt-3">
                                    <strong class="">Start Date:</strong>
                                    <span id="start-date"></span>
                                </div>

                                <div class="mt-3">
                                    <strong class="mt-3">Deadline:</strong>
                                    <span id="end-date"></span>
                                </div>
                                <div class="mt-3">
                                    <strong class="mt-3">Priority:</strong>
                                    <span id="priority"></span>
                                </div>
                                <div class="mt-3">
                                    <strong class="mt-3">Description:</strong>
                                    <span class="text-justify" id="description"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-4" id="img">

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-12">
                                <strong>Assigned To:</strong>
                                <div class="row m-2" id="assigned_to">

                                </div>
                            </div>
                            <div class="col-md-12 d-none" id="feedback">
                                <strong>Feedback:</strong>
                                <div class="row m-2">
                                    <span class="text-justify" id="admin_comment"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">

                        <div class="form-group mt-2">
                            {{-- <label for="long">Add Comment </label><br> --}}
                            <textarea class="form-control" name="comment" id='comment' cols="50"
                                      oninput="commentInput()"
                                      rows="5" placeholder="Type Comment..." style="border-radius: 5px"></textarea>
                            <input type="hidden" id="task_id" value="">
                            <input type="hidden" id="employee_info_id" value="">

                        </div>
                        <button id="add-comment" type="button" class="btn btn-success"
                                onclick="AddComment()">Send
                        </button>
                        <div id="comment-div">

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showTaskImageModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div style="max-width: 1400px !important; " class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 10px; width: 400px">
                <div class="modal-header">

                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">

                        <div id="modal-img" class="form-group mt-2">

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" type="text/javascript"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script>
        function commentInput() {
            let comments = CKEDITOR.instances.comment.getData();
            console.log('ffff', comments)
            if (comments.length > 0) {
                // console.log('len 1', comments.length)
                $('#add-comment').removeAttr('disabled')

            } else {
                $('#add-comment').attr("disabled", true)
            }
        }

        function AddComment() {


            let task_id = $('#task_id').val();
            let employee_info_id = $('#employee_info_id').val();
            let comment_div = '';
            let comments = CKEDITOR.instances.comment.getData();
            // console.log('comments, task_id', task_id, comments)
            if (comments == '') {
                bootbox.alert('Comment field is required')
            } else {
                $.ajax({
                    url: "{{ route('member.users.add_task_comment') }}",
                    method: 'POST',
                    data: {
                        'task_id': task_id,
                        'comments': comments
                    },
                    success: function (data) {
                        CKEDITOR.instances.comment.setData('');
                        // console.log('check comments data', data)
                        if (data.status == 200) {
                            $.each(data.allComment, function (i, el) {

                                comment_div += ` <div class="comment-box mt-2 p-2 ${i % 2 == 1 ? "ml-5" : ''}"> <strong>${el.employee.first_name}</strong>
                                <span class="btn float-right ${el.employee.id == employee_info_id ? '' : "d-none"}"><i onclick="DeleteComment(${el.id})"  class="flat-right text-red fa fa-times "></i></span>
                             <p class="">${el.comments}</p> </div>`

                            })
                        }
                        $('#comment').val('')
                        $('#comment-div').html(comment_div);
                    }
                })
            }


        }

        function DeleteComment(id) {
            //  alert(e)
            bootbox.confirm("Are you sure? ", function (result) {

                if (result) {
                    $.ajax({
                        url: "{{ route('member.users.task_comment_delete') }}",
                        method: 'GET',
                        data: {
                            'id': id
                        },
                        success: function (data) {
                            bootbox.alert("Delete successfully completed",
                                function () {
                                    showDetailModal($('#task_id').val())
                                });
                        },

                    });
                } else {
                    bootbox.alert("OOP! Sorry Response Denied");
                }
            });
        }

        function showDetailModal(id) {
            $('#showModal').modal('show');
            // $('#exampleModalLongTitle').text('Task Info #' + id)
            let image = '';
            let modal_image = '';
            let comment_div = '';
            let assigned_to = '';
            $('#task_id').val(id)
            //   console.log('id is',id)
            $.ajax({
                url: "{{ route('member.users.task_details') }}",
                method: 'GET',
                data: {
                    'id': id
                },
                success: function (data) {
                    if (data.status == 200) {
                        // console.log('data is ', data.employee_id)
                        // console.log('data is ', data)
                        let status_text = '';
                        if (data.data.status == 'in_progress') {
                            status_text = 'In Progress';
                            $('#status').css("background", "#1672B9");
                        } else if (data.data.status == 'to_do') {
                            status_text = 'To Do';
                            $('#status').css("background", "#F9A52D");
                        } else if (data.data.status == 'review') {
                            status_text = 'Review';
                            $('#status').css("background", "#ad159e");
                        } else if (data.data.status == 'done') {
                            status_text = 'Done';
                            $('#status').css("background", "#00B393");
                        }
                        $('#exampleModalLongTitle').text(data.data.title)
                        // $('#title').text(data.data.title)
                        $('#project-name').text(data.data.project.project)
                        $('#start-date').text(data.data.start_date)
                        $('#end-date').text(data.data.end_date)
                        $('#priority').text(data.data.priority)
                        $('#status').text(status_text)
                        $('#employee_info_id').val(data.employee_id)
                        $('#description').html(data.data.description)
                        $('#admin_comment').html(data.admin_comment?.comments)
                        // $('#employee').text(data.data.employee.first_name + ' ' + data.data.employee.last_name)
                        if (data.admin_comment?.comments) {
                            $('#feedback').removeClass('d-none');
                        } else {
                            $('#feedback').addClass('d-none');
                        }

                        $.each(data.allComment, function (i, el) {
                            console.log('ids ', i)
                            comment_div += ` <div class="comment-box mt-2 p-2 ${i % 2 == 1 ? "ml-5" : ''}"> <strong>${el.employee.first_name}</strong> <span class="btn float-right ${el.employee.id == data.employee_id ? '' : "d-none"}"><i onclick="DeleteComment(${el.id})" class="text-red fa fa-times "></i></span>
                            <p class="">${el.comments}</p> </div>`

                        })

                        $.each(data.assigned_to, function (i, el) {

                            assigned_to += ` <div class="col-md-8 mt-2">
                                <strong>${el.employee.first_name} ${el.employee.last_name}</strong>
                            </div>
                            <div class="col-md-4 mt-2">
                                <span class="badge my-1">${el.status == 'to_do' ? "To Do" : (el.status == 'in_progress' ? 'In Progress' : (el.status == 'review' ? 'Review' : (el.status == 'done' ? 'Done' : '')))}</span>
                            </div>`

                        })

                        image +=
                            `<img id="task_image" onclick="taskImage()" class="task-details-image" src="{{ asset('storage/app/public/task_image/${data.data.image}') }} ">`

                        modal_image +=
                            `<img id="task_image" onclick="taskImage()" class="" src="{{ asset('storage/app/public/task_image/${data.data.image}') }} ">`
                    }
                    $('#comment-div').html(comment_div);

                    if (data.data.image) {
                        $('#img').html(image);
                        $('#modal-img').html(modal_image);

                    } else {
                        $('#img').html('');
                        $('#modal-img').html('');
                    }
                    $('#assigned_to').html(assigned_to);
                }
            })
        }

        let status_temp = "";
        $(function () {
            $(".element").draggable({
                helper: 'clone',
                appendTo: 'body',
                cursor: "move"
            });
            $(".droppable").droppable({
                accept: ".element",
                revert: true,
                greedy: true,
                tolerance: "pointer",
                drop: async function (event, ui) {
                    // console.log(ui)
                    var id = $(ui.draggable).data('id');
                    var from_status = $(ui.draggable).attr('data-status');
                    var to_status = $(event.target).data('status');
                    var change_status = await $(ui.draggable).attr('data-status', to_status);
                    // status_temp = $(change_status).attr('data-status');

                    let current_droppable_value = Number(event.target.previousElementSibling.children[0]
                        .innerHTML);
                    let current_dragable_value = Number(ui.draggable.parent().parent().children()
                        .children().eq(0).text());

                    let dragable_value = '';
                    let droppable_value = '';
                    if (current_dragable_value > 0 && (from_status != to_status)) {
                        dragable_value = Number(current_dragable_value - 1);
                        droppable_value = Number(current_droppable_value + 1);
                        ui.draggable.parent().parent().children().children().eq(0).text(dragable_value)
                        event.target.previousElementSibling.children[0].innerHTML = droppable_value;
                        // console.log('data is',dragable_value);

                        $(event.target).append($(ui.draggable))

                        $.ajax({
                            url: "{{ route('member.users.change_task_status') }}",
                            method: 'GET',
                            data: {
                                'id': id,
                                'status': to_status,
                            },
                            success: function (data) {
                                if (data.status == 200) {

                                }
                            }
                        });
                    }

                }
            });
        });

        function taskImage() {
            $('#showTaskImageModal').modal('show');

        }

        $(function () {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align',
                'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('comment', {
                toolbar: 'MA'
            });
        });
    </script>
@endpush
