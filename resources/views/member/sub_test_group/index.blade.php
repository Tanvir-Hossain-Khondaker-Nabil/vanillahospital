<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.sub_test_group.index']) ? route('member.sub_test_group.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sub Test Group',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Sub Test Group',
    'title' => 'List Of Sub Test Group',
    'heading' => 'List Of Sub Test Group',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    <div class="box-header">
                        @if (\Auth::user()->can(['member.sub_test_group.create']))
                            <a href="{{ route('member.sub_test_group.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                </i> Add Sub Test Group</a>
                        @endif
                        <form class="mt-2" id="selected_id_submit" action="{{route('member.sub_test_print')}}" method="post">
                            @csrf
                            <input type="hidden" name="selected_ids" id="selected_ids">

                            <a href="#" id="printAllSelectedRecord" class="btn btn-info"> <i
                                class="fa fa-print">
                            </i> Print</a>

                        </form>



                    </div>



                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">

                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <input style="position: absolute;top: 56px;left: 30px;z-index: 10000;" type='checkbox'
                            id='select_all_ids' />
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- <div class="row">
    <div class="col-md-12">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Sub Test Group</h3>
            </div>

            <div class="box-body">
                <table id="vanilla-table1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#SL</th>
                            <th>Test Group Title</th>
                            <th>Sub Test Group Title</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Quack Ref Com</th>
                            <th>Ref Val</th>
                            <th>Room No</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sub_test_groups as $key => $list)
                            <tr>

                                <td>{{ ++$key }}</td>
                                <td>{{ $list->testGroup? $list->testGroup->title : "" }}({{$list->testGroup?$list->testGroup->specimen->specimen : ""}})</td>
                                <td>{{ $list->title }}</td>
                                <td>{{ $list->price }}</td>
                                <td>{{ $list->unit }}</td>
                                <td>{{ $list->quack_ref_com }}</td>
                                <td>{{ $list->ref_val }}</td>
                                <td>{{ $list->room_no }}</td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div> --}}
@endsection


@push('scripts')


    @push('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
        <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
        <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

        {!! $dataTable->scripts() !!}

        @include('common.label_script');
        <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
        <script>
            $(function() {

                CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align',
                    'bidi', '-',
                    'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
                ]];
                CKEDITOR.replace('lead_comment', {
                    toolbar: 'MA'
                });
            });
        </script>

        <script>
            $(function() {

                $("#select_all_ids").click(function(e) {

                    $('.checkbox_ids').prop('checked', $(this).prop('checked'));

                    var all_ids = [];

                    $('input:checkbox[name=ids]:checked').each(function() {
                        all_ids.push($(this).val());
                    });

                    if (all_ids.length > 0) {
                        $('#export-btn').prop("disabled", false);
                    } else {
                        $('#export-btn').prop("disabled", true);
                    }
                    $('#export-ids').val(all_ids);
                    // console.log('data is ss',all_ids);
                });

                $("#vanilla-table1").DataTable({

                    // "lengthMenu":[ 3,4 ],
                    "searching": true,
                    // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                });
                $("#vanilla-table2").DataTable({

                    "searching": true,
                });

            });


            $('#printAllSelectedRecord').click(function(e) {

                e.preventDefault();

                var all_ids = [];

                $('input:checkbox[name=ids]:checked').each(function() {
                    all_ids.push($(this).val());
                });

                if (all_ids.length > 0) {

                    $('#selected_ids').val(all_ids);
                    $('#selected_id_submit').submit();


                } else {

                    bootbox.alert('Please Select Data')
                }

            });
        </script>


        </script>
    @endpush
