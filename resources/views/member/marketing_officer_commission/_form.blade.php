<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

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
                    src="{{ $marketingOfficer->image == null ? asset('/public/adminLTE/dist/img/avatar5.png') : asset('/public/uploads/marketing_officer/' . $marketingOfficer->image) }}"
                    alt="User profile picture">

            </div>
            <div class="col-md-10">
                <table class="table table-responsive table-striped">

                    <tr>
                        <th>{{ __('common.name') }} </th>
                        <td colspan="3">{{ $marketingOfficer->name }} ({{ $marketingOfficer->designation }})</td>
                    </tr>
                    <tr>
                        <th>{{ __('common.phone') }} </th>
                        <td>{{ $marketingOfficer->contact_no }}</td>
                        <th>{{ __('common.address') }} </th>
                        <td>{{ $marketingOfficer->address }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $marketingOfficer->description }}</td>

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
                                    {{-- <input type="hidden" name="hospital_id" value="5">
                                    <input type="hidden" name="doc_id" value="6"> --}}

                                    <table id="group_id_table"
                                        class="table table-striped table-bordered mytable_style table-hover sell_cart">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;">S.L</th>
                                                <th style="width:34%;">Service Name</th>
                                                <th style="width:15%;">Commission Type</th>
                                                <th style="width:12%;">Amount/Per</th>
                                                <th style="width:10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="mytable_style" id="dynamic_row">

                                            <tr>
                                                <td>1</td>


                                                <td>
                                                    <input type="hidden" id="marketing_officer_id" name="marketing_officer_id" value="{{ $marketing_officer_id }}">
                                                    <select onchange="selectServices(this)" required id="service"
                                                        name="service[0][]" class=" form-control  select2"
                                                        id="" multiple="" tabindex="-1" aria-hidden="true">
                                                        <option value="all" >All</option>
                                                        @foreach ($services as $key=>$value)
                                                         <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach

                                                    </select>

                                                </td>
                                                <td>
                                                    <select required name="commission_type[]"
                                                        class="percent_select form-control custom-select select2 select2-hidden-accessible"
                                                        tabindex="-1" aria-hidden="true">

                                                        <option value="0">Fixed Commission</option>
                                                        <option value="1">Percentage</option>

                                                    </select>
                                                </td>
                                                <td>
                                                    <input required type="text" name="commission_amount[]"
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
                                    <th>Service Name</th>
                                    <th>Comission Type</th>
                                    <th>Comission Amount</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 0;
                                @endphp
                                {{-- {{dd($comissions)}} --}}
                                @foreach ($comissions as $k => $value)
                                        <tr>

                                            <td>{{ ++$count }}</td>
                                            <td>{{ $value->service?$value->service->title:'' }}</td>
                                            <td>{{ $value->commission_type == 1?'Percentage' : 'Fixed Commission' }} </td>
                                            <td>{{ $value->commission_amount }} {{  $value->commission_type == 1?'%' : 'Taka' }}
                                            </td>




                                            <td>

                                                {{-- @if (\Auth::user()->can(['member.doctor_comission.edit']))
                                    <a class="btn btn-xs btn-success"
                                        href="{{ route('member.doctor_comission.edit',$list->id) }}"><i
                                            class="fa fa-edit" title='Edit'></i>
                                        </a>
                                    @endif --}}

                                                @if (\Auth::user()->can(['member.marketing_officer_commissions.destroy']))
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-xs btn-danger delete-confirm"
                                                        data-target="{{ route('member.marketing_officer_commissions.destroy', $value->id) }}">
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


        function selectServices(e) {

            var data = $(e).find('option:selected').text();
            if (data == 'All' || e.value == 'all') {
                $(e).find('option').prop("selected", "selected");
                $(e).find("option[value='all']").prop("selected", false);
                $(e).trigger("change");

            }

        }


        let counter = 1;

        function addMore() {
            $('#dynamic_row').append(
                ` <tr>
                    <td>${++count}</td>
                    <td>
                        <select onchange="selectServices(this)" required name="service[${count-1}][]"
                            class=" form-control  select2"
                            id="district_1" multiple="" tabindex="-1" aria-hidden="true">
                                   <option value="all" >All</option>
                                    @foreach ($services as $key=>$value)
                                                         <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                        </select>

                    </td>
                    <td>
                        <select required name="commission_type[]"
                            class="percent_select form-control custom-select select2 select2-hidden-accessible"
                            tabindex="-1" aria-hidden="true">

                            <option value="0">Fixed Commission</option>
                            <option value="1">Percentage</option>

                        </select>
                    </td>
                    <td>
                        <input required type="text" name="commission_amount[]" class="form-control">
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
