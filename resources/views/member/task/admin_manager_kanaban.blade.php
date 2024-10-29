<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.shift.index']) ? route('member.shift.index') : '#';
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
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Task Kanban',
    'title' => 'Task Kanban',
    'heading' => 'Task Kanban',
];

?>

@extends('layouts.back-end.master', $data)
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush
<style>
    .border-top {
        border-top: 1px solid black;
    }

    .comment-box {
        border-radius: 5px;
        background-color: #eeebeb;
    }

    .cke_contents {
        height: 100px !important;
    }

    .select2-selection__choice {
        color: black !important;
    }
</style>
@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    <div class="box-header">
                        @if (\Auth::user()->can(['member.task.create']))
                            <a href="{{ route('member.task.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add
                                    Task
                                </i></a>
                        @endif
                        @if (\Auth::user()->can(['member.label.create']))
                            <a href="#" onclick="manageLabel('task')" class="btn btn-success btn-sm"> <i
                                    class="fa fa-gear">
                                </i> Manage Labels</a>
                        @endif
                    </div>


                    <div class="box-body">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="row px-3">

                                {{-- To Do --}}
                                <div class="col-md-3 my-3">
                                    <div class="kanban-col-title" style="border-bottom: 3px solid #F9A52D;"> To do <span
                                            class="float-right">{{ count($to_do) }} </span>
                                    </div>
                                    <div class="droppable" data-status="to_do">
                                        @foreach ($to_do as $to_do_task)
                                            <div class="mt-4 element" data-id={{ $to_do_task->id }}
                                                data-status={{ $to_do_task->status }}
                                                onclick="showDetailModal({{ $to_do_task->id }})">
                                                <div class="p-3 task-box">
                                                    <div class="row">
                                                        @if (!empty($to_do_task->image))
                                                            <div class="col-md-2">
                                                                <img class="task-image"
                                                                     src="{{ asset('storage/app/public/task_image/' . $to_do_task->image) }} ">

                                                            </div>
                                                        @endif

                                                        <div class="col-md-10" style="float: none">
                                                            <span class="text-justify">{{ $to_do_task->title }}</span>
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
                                            <div class="mt-4 element" data-id={{ $progress->id }}
                                                data-status={{ $progress->status }}
                                                onclick="showDetailModal({{ $progress->id }})">
                                                <div class="p-3 task-box">
                                                    <div class="row">

                                                        @if (!empty($progress->image))

                                                            <div class="col-md-2"><img class="task-image"
                                                                                       src="{{ asset('storage/app/public/task_image/' . $progress->image) }} ">

                                                            </div>
                                                        @endif

                                                        <div class="col-md-10" style="float: none">
                                                            <span class="text-justify">{{ $progress->title }}</span>
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
                                            <div class="mt-4 element" data-id={{ $revi->id }}
                                                data-status={{ $revi->status }}
                                                onclick="showDetailModal({{ $revi->id }})">
                                                <div class="p-3 task-box">
                                                    <div class="row">
                                                        @if (!empty($revi->image))
                                                            <div class="col-md-2">
                                                                <img class="task-image"
                                                                     src="{{ asset('storage/app/public/task_image/' . $revi->image) }} ">

                                                            </div>
                                                        @endif

                                                        <div class="col-md-10" style="float: none">
                                                            <span class="text-justify">{{ $revi->title }}</span>
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
                                            <div class="mt-4 element" data-id={{ $done_task->id }}
                                                data-status={{ $done_task->status }}
                                                onclick="showDetailModal({{ $done_task->id }})">
                                                <div class="p-3 task-box">
                                                    <div class="row">
                                                        @if (!empty($done_task->image))
                                                            <div class="col-md-2">
                                                                <img class="task-image"
                                                                     src="{{ asset('storage/app/public/task_image/' . $done_task->image) }} ">

                                                            </div>
                                                        @endif

                                                        <div class="col-md-10" style="float: none">
                                                            <span class="text-justify">{{ $done_task->title }}</span>
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
                <!-- END SAMPLE FORM PORTLET-->

            </div>

        </div>
    </div>


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div style="max-width: 1400px !important; " class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 10px; width: 740px">
                <div class="modal-header">
                    <h4 class="modal-title text-primary" id="exampleModalLongTitle"></h4>

                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-7">

                            <div class="mt-3">
                                <div class="mt-3">
                                    <strong class="">Status:</strong>
                                    <span class="badge" id="status"></span>
                                </div>
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
                                    <p class="text-justify" id="description"></p>
                                </div>
                                <div class="mt-3" id="img">

                                </div>
                                <strong class="mt-3">Comments:</strong>
                                <div class="mt-3" id="show_comment">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">

                            <div class="row">

                                <div class="col-md-12">
                                    <strong>Assigned To:</strong>
                                    {{-- <span class="" id="assigned_to"></span> --}}
                                    <div class="row m-2" id="assigned_to">

                                    </div>


                                </div>


                            </div>
                            <div>
                                <button class="btn btn-success btn-sm" onclick="showAddEmplyForm()">+Assign
                                    Employee
                                </button>
                                <div id="hide_show_form" class="d-none">
                                    <div class="form-group">
                                        <label for="status">Assign To Employee<span class="text-red"> * </span></label>

                                        <select id="emp_id" name="emp_id[]" class="form-control" multiple="multiple">

                                        </select>
                                        <button class="btn btn-primary btn-sm mt-1" type="button"
                                                onclick="addNewEmployeeForTask()">Assign
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none" id="feedback">
                                <strong>Feedback:</strong>
                                <div class="row m-2">
                                    <span class="text-justify" id="admin_comment"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showAddCommentModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div style="max-width: 1400px !important; " class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 10px; width: 740px">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLongTitle"></h5>

                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">

                        <div class="form-group mt-2">
                            <label for="long">Add Comment </label><br>
                            <textarea class="form-control comment" name="comment" onchange="textEditor(this)"
                                      id="comment" cols="50" rows="5" placeholder="Type Comment..."
                                      style="border-radius: 5px"></textarea>
                            <input type="hidden" id="task_id" value="">
                            <input type="hidden" id="task_status" value="">

                        </div>
                        <button id="add-comment" type="button" class="btn btn-success"
                                onclick="AddComment()">Send
                        </button>
                        <button onclick="AddComment()" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    @include('common.label_script');
    <script>

        function textEditor(e) {
            console.log('text is', e.value)
        }

        function AddComment() {
            let id = $('#task_id').val()
            let to_status = $('#task_status').val()
            // let comments = $('#comment').val();
            let comments = CKEDITOR.instances.comment.getData();
            // console.log('check sss', $('textarea[name=comment]'));
            console.log('check sss cc', id, status, comments);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{ route('member.users.change_task_status') }}",
                method: 'GET',
                data: {
                    'id': id,
                    'status': to_status,
                    'comments': comments,
                },
                success: function (data) {
                    if (data.status == 200) {
                        // CKEDITOR.instances.comment.setData('');
                        // bootbox.alert('Added Successful');
                        location.reload();
                    }
                }
            });
        }

        function showDetailModal(id) {
            $('#showModal').modal('show');
            $('#task_id').val(id)
            // $('#exampleModalLongTitle').text('Task Info #' + id)
            let image = '';
            let modal_image = '';
            let comment_div = '';
            let assigned_to = '';
            let add_employee = '';

            $.ajax({
                url: "{{ route('member.show_task') }}",
                method: 'GET',
                data: {
                    'id': id
                },
                success: function (data) {
                    console.log('check data 1', data)
                    console.log('status', data.status)
                    if (data.status == 200) {
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
                        $('#description').html(data.data.description)
                        $('#admin_comment').html(data.admin_comment?.comments)
                        if (data.admin_comment?.comments) {
                            $('#feedback').removeClass('d-none');
                        } else {
                            $('#feedback').addClass('d-none');
                        }
                        // $('#employee').text(data.data.employee.first_name + ' ' + data.data.employee.last_name)

                        $.each(data.allComment, function (i, el) {

                            comment_div += `  <div class="comment-box mt-2 p-2">
                         <strong>${el.employee.uc_full_name}</strong>
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

                        $.each(data.employees, function (key, item) {
                            add_employee += `<option class='text-dark' value='${key}'>${item}</option>`
                        })


                        image +=
                            `<img id="task_image" onclick="taskImage()" class="task-details-image" src="{{ asset('storage/app/public/task_image/${data.data.image}') }} ">`

                        modal_image +=
                            `<img id="task_image" onclick="taskImage()" class="" src="{{ asset('storage/app/public/task_image/${data.data.image}') }} ">`

                    }

                    $('#show_comment').html(comment_div);
                    $('#assigned_to').html(assigned_to);
                    $('#emp_id').html(add_employee);
                    if (data?.data?.image) {
                        $('#img').html(image);
                        $('#modal-img').html(modal_image);

                    } else {
                        $('#img').html('');
                        $('#modal-img').html('');
                    }
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
                        $('#task_id').val(id)
                        $('#task_status').val(to_status)
                        $('#showAddCommentModal').modal('show');

                    }

                }
            });
        });

        function addNewEmployeeForTask() {
            let ids = $('#emp_id').val()
            let task_id = $('#task_id').val()
            console.log('length', ids.length)
            if (ids.length == 0) {
                bootbox.alert('Assign employee field is requred')
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('member.assign_employee_to_task') }}",
                    data: {
                        'employee_ids': ids,
                        'task_id': task_id,
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            showDetailModal(task_id)
                            $('#hide_show_form').addClass('d-none')
                            //  bootbox.alert('Added Employee')
                        }

                    },

                });
            }


        }

        function showAddEmplyForm() {
            $('#hide_show_form').removeClass('d-none');
        }

        $(document).ready(function () {
            $('#emp_id').select2();

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
