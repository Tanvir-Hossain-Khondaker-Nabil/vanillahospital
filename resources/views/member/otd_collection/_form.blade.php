<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

if (isset($modal)) {
    $project_image = $modal->image != '' ? $modal->project_image_path : '';
} else {
    $project_image = '';
}
// dd($project_image);
?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
    <style>
        .box-primary {
            border-top-color: #3c8dbc !important;
        }

        .box-2 {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            /* width: 100%; */
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

<div class="col-md-12 mt-2">
    <div class="box box-primary">
        <div class="box-body box-profile pt-4 pb-0">
            <div class="col-md-2">

                <img style="height: 100px" class="profile-user-img img-responsive img-circle"
                    src="{{ $doctor->image == null ? asset('/public/adminLTE/dist/img/avatar5.png') : asset('/public/uploads/doctor/' . $doctor->image) }}"
                    alt="User profile picture">

            </div>
            <div class="col-md-10">
                <table class="table table-responsive table-striped">

                    <tr>
                        <th>{{ __('common.name') }} </th>
                        <td colspan="3">{{ $doctor->name }} ({{ $doctor->degree }})</td>
                    </tr>
                    <tr>
                        <th>{{ __('common.phone') }} </th>
                        <td>{{ $doctor->mobile }}</td>
                        <th>{{ __('common.address') }} </th>
                        <td>{{ $doctor->address }}</td>
                    </tr>
                    <tr>
                        <th>Consult Fee</th>
                        <td>{{ $doctor->consult_fee }}</td>

                    </tr>


                </table>
            </div>
        </div>


    </div>
</div>


<div class="panel panel-default form component main">
    <div class="panel-heading">
        <ul id="rowTab" class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#add_form">Add Comission</a></li>
            <li><a data-toggle="tab" href="#comission-table">Comission List</a></li>

        </ul>
        <div class="tab-content">
            <div id="add_form" class="tab-pane fade active in py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                                <form class="form-inline" action="member.doctor_comission.create" method="post">
                                    @csrf
                                    <input type="hidden" name="hospital_id" value="5">
                                    <input type="hidden" name="doc_id" value="6">

                                    <table id="group_id_table"
                                        class="table table-striped table-bordered mytable_style table-hover sell_cart">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;">S.L</th>
                                                <th style="width:34%;">Group Name</th>
                                                <th style="width:34%;">Test Name</th>
                                                <th style="width:15%;">Com Type</th>
                                                <th style="width:12%;">Amnt/Per</th>

                                                <th style="width:10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="mytable_style" id="dynamic_row">

                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <select required onchange="group_select(this)" id="group_id"
                                                        name="group_ids[]" class=" select2 form-control">
                                                        <option selected value="">Please Select</option>
                                                        @foreach ($test_group as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="hidden" id="doctor_id" name="doctor_id" value="{{ $doctor_id }}">
                                                    <select onchange="selectTest(this)" required id="test_name"
                                                        name="test_name[0][]" class=" form-control  select2"
                                                        id="" multiple="" tabindex="-1" aria-hidden="true">
                                                        <option disabled="disabled">Choose Test Title</option>

                                                    </select>

                                                </td>
                                                <td>
                                                    <select required name="com_type[]"
                                                        class="percent_select form-control custom-select select2 select2-hidden-accessible"
                                                        tabindex="-1" aria-hidden="true">

                                                        <option value="1">Percentage</option>

                                                        <option value="2">Fixed Commission</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input required type="text" name="com_amnt[]"
                                                        class="form-control">
                                                </td>
                                                <td>

                                                    <a onclick="addMore()" class="btn btn-success btn-xs">
                                                        <i class="fa fa-plus"></i>
                                                    </a>


                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="col-md-12" align="left">
                                        <button class="btn btn-white btn-primary btn-bold" type="submit">
                                            Save
                                        </button>
                                    </div>
                                </form>


                        </div>
                    </div>
                </div>
            </div>

            <div id="comission-table" class="tab-pane fade in py-5">
                <div class="row">
                    <div class="col-md-12">

                        <table id="vanilla-table1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Group Name</th>
                                    <th>Test Title</th>
                                    <th>Comission Type</th>
                                    <th>Comission</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 0;
                                @endphp
                                {{-- {{dd($comissions)}} --}}
                                @foreach ($comissions as $k => $value)
                                    @foreach ($value->comission as $key => $list)
                                        <tr>

                                            <td>{{ ++$count }}</td>
                                            <td>{{ $value->testGroup ? $value->testGroup->title : '' }}</td>
                                            <td>{{ $list->subTestGroup ? $list->subTestGroup->title : '' }} ({{ $list->subTestGroup ? $list->subTestGroup->price : '' }})</td>
                                            <td>{{ $value->comission_type == 1 ? 'Percentage' : 'Fixed Commission' }}
                                            </td>
                                            <td>{{ $value->amount }}</td>



                                            <td>

                                                {{-- @if (\Auth::user()->can(['member.doctor_comission.edit']))
                                    <a class="btn btn-xs btn-success"
                                        href="{{ route('member.doctor_comission.edit',$list->id) }}"><i
                                            class="fa fa-edit" title='Edit'></i>
                                        </a>
                                    @endif --}}

                                                @if (\Auth::user()->can(['member.doctor_comission.destroy']))
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-xs btn-danger delete-confirm"
                                                        data-target="{{ route('member.doctor_comission.destroy', $list->id) }}">
                                                        <i class="fa fa-times"></i>
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


@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>
    <!-- Date range picker -->
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
    <script type="text/javascript">
        // var date = new Date();
        let count = 1;
        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });

        $(function() {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('description', {
                toolbar: 'MA'
            });
        });


        function selectTest(e) {

            var data = $(e).find('option:selected').text();
            if (data == 'All' || e.value == 'all') {
                $(e).find('option').prop("selected", "selected");
                $(e).find("option[value='all']").prop("selected", false);
                $(e).trigger("change");

            }

        }

        function group_select(e) {

            let group_id = e.value;
            let option = '<option value="all">All</option>'

            // console.log('okk',e.value)
            //  return;
            $.ajax({
                type: "get",
                url: "{{ route('member.fetch.subtest') }}",
                data: {
                    'id': group_id,
                    'doctor_id': $('#doctor_id').val(),
                },
                success: function(response) {

                    doctor_data = response.data
                    checkTest = response.check_test

                    if (response.data.length > 0) {
                        $.each(response.data, function(key, value) {
                            if(!checkTest.includes(value.id)){

                              option += `<option value="${value.id}">${value.title} (${value.price})</option>`;

                            }

                        });

                        $(e).parent().next().find('select').html(option).trigger('change')

                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });

        }
        let counter = 1;

        function addMore() {
            $('#dynamic_row').append(
                ` <tr>
                    <td>${++count}</td>
                    <td>
                        <select required onchange="group_select(this)" name="group_ids[]"
                            class=" select2 form-control">
                            <option selected value="">Please Select</option>
                            @foreach ($test_group as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select onchange="selectTest(this)" required name="test_name[${counter++}][]"
                            class=" form-control  select2"
                            id="district_1" multiple="" tabindex="-1" aria-hidden="true">
                            <option disabled="disabled">Choose Test Title</option>

                        </select>

                    </td>
                    <td>
                        <select required name="com_type[]"
                            class="percent_select form-control custom-select select2 select2-hidden-accessible"
                            tabindex="-1" aria-hidden="true">

                            <option value="1">Percentage</option>

                            <option value="2">Fixed Commission</option>
                        </select>
                    </td>
                    <td>
                        <input required type="text" name="com_amnt[]" class="form-control">
                    </td>
                    <td>

                        <a onclick="deleteRow(this)" class="btn btn-danger btn-xs">
                            <i class="fa fa-trash"></i>
                        </a>


                    </td>
                </tr>`
            );

            $('.select2').select2();
        }

        function deleteRow(e) {
            $(e).parent().parent().remove();
        }
    </script>
@endpush
