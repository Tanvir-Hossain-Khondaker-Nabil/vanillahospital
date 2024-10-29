<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.fetch.pathology_list']) ? route('member.fetch.pathology_list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Test List',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Pathology List ',
    'title' => 'List of Pathology',
    'heading' => 'List of Pathology',
];

?>

@extends('layouts.back-end.master', $data)
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush
@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="box-header">
                        <div class="box-header">
                            <form id="multiple_test_print" method="post" action="{{ route('member.multiple_test_print') }}">
                                @csrf
                                <input type="hidden" name="ids[]" value="" id="print_ids">
                                <button onclick="multipleTestPrint()" type="button" class="btn btn-info"> <i
                                        class="fa fa-print" title='Report Print'></i> Print</button>
                            </form>

                        </div>
                    </div>
                    <div class="box">

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>All <input type="checkbox" id="select_all_ids"></th>
                                        <th>Patient Name</th>
                                        <th>Order No</th>
                                        <th>Test Title</th>
                                        <th>Test Group</th>
                                        <th>Specimen</th>
                                        <th>Order Date</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sl = 0;

                                    @endphp
                                    @foreach ($pathology_list as $key => $item)
                                        @foreach ($item->outdoorPatientTest as $k => $list)
                                            <tr>

                                                <td>{{ ++$sl }}</td>
                                                <td>
                                                    @if ($list->report_making_status == 1)
                                                        <input onclick="signleSelect(this)" type="checkbox"
                                                            value="{{ $list->id }}" data-opd-id={{ $item->opd_id }}
                                                            name="ids" class="checkbox_ids">
                                                    @endif

                                                </td>
                                                <td>{{ $item->patient_name }}</td>
                                                <td>{{ $item->opd_id }}</td>
                                                <td>{{ $list->subTestGroup->title }}</td>
                                                <td>{{ $list->subTestGroup->testGroup->title }}</td>
                                                <td>{{ $list->subTestGroup->testGroup->specimen->specimen }}</td>
                                                <td>{{ $item->date_of_service }}</td>
                                                <td>
                                                    @if ($list->report_making_status == 0)
                                                        <strong class='text-success'>Receive</strong>
                                                    @else
                                                        <strong class='text-danger'>On Reception</strong>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($list->report_making_status == 0)
                                                        <a class="btn btn-xs btn-success"
                                                            href="{{ route('member.pathology_reports.edit', $list->id) }}"><i
                                                                class="fa fa-plus" title='Report Provide'></i>
                                                        </a>
                                                    @else
                                                        <a class="btn btn-xs btn-info"
                                                            href="{{ route('member.pathology_reports.show', $list->id) }}"><i
                                                                class="fa fa-print" title='Print Report'></i>
                                                        </a>
                                                    @endif



                                                </td>

                                            </tr>
                                        @endforeach
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
        let all_ids = [];
        let opd_ids = [];
        let opd = '';

        function signleSelect(e) {
            opd = $(e).attr('data-opd-id');


            if ($(e).is(':checked')) {
                opd_ids.push(opd);
                all_ids.push(e.value);
                console.log('jk', all_ids,opd_ids);
            } else {
                const index = all_ids.indexOf(e.value);
                const index_odp = opd_ids.indexOf(opd);
                if (index > -1) { // only splice array when item is found
                    all_ids.splice(index, 1); // 2nd parameter means remove one item only

                } if (index_odp > -1) { // only splice array when item is found
                    opd_ids.splice(index_odp, 1); // 2nd parameter means remove one item only
                }

                // array = [2, 9]
                console.log(all_ids);
                // console.log('no')
            }


        }

        function multipleTestPrint() {

            let array_first_val = [];
            array_first_val.push(opd_ids[0]);
            let check_val = true;

            for (var i = 0; i < array_first_val.length; i++) {
                for (var e = 0; e < opd_ids.length; e++) {
                    if (array_first_val[i] != opd_ids[e]){
                        check_val = false;
                    }
                }
            }

            console.log('check val',array_first_val, opd_ids,check_val);
            // return;
            if(check_val){
                if (all_ids.length > 0) {
                $('#print_ids').val(all_ids);
                $('#multiple_test_print').submit();

            } else {
                bootbox.alert("Please select at least one record!");
                return;
            }
            }else{
                bootbox.alert("Please select same patient test!");
                return;
            }


        }
        $("#select_all_ids").click(function(e) {
            all_ids = [];
            $('.checkbox_ids').prop('checked', $(this).prop('checked'));

            $('input:checkbox[name=ids]:checked').each(function() {
                all_ids.push($(this).val());
            });

            // console.log(all_ids);
        });
        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
                "ordering": false,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
                "ordering": false,
            });

        });
    </script>
@endpush
