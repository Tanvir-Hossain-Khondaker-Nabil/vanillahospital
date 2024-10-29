<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.technologists.index']) ? route('member.technologists.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Technologist ',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Technologist',
    'title' => 'List Of Technologist',
    'heading' => 'List Of Technologist',
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

                    <div class="box">
                        <div class="box-header">
                            <div class="box-header">
                                @if (\Auth::user()->can(['member.technologists.create']))
                                    {{-- <a href="{{ route('member.technologists.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Technologist</a> --}}

                                    <button onclick="addTechnologist()" class="btn btn-info"> <i class="fa fa-plus">
                                        </i> Add Technologist</button>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Technologist</th>
                                        <th>Specimen</th>
                                        <th>Group Name</th>
                                        <th>Status</th>
                                        <th>Test Name</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($technologist as $key => $list)
                                        <tr>

                                            <td>{{ ++$key }}</td>

                                            @if (@$list->technologistDoctor)
                                            <td>{{ $list->technologistDoctor->name }}</td>
                                            @else
                                            <td>{{ $list->technologistEmployee->first_name }} {{ $list->technologistEmployee->last_name }}</td>
                                            @endif
                                            <td>{{ $list->specimen->specimen }}</td>
                                            <td>{{ $list->testGroup->title }}</td>
                                            <td>
                                                @if ($list->status ==1)
                                                <span style="background-color: rgb(45, 217, 45); color: white" class="text-success badge ms-1">Active</span>
                                                @else
                                                <span style="background-color: rgb(146, 42, 42); color: white"  class="text-danger badge ms-1">Inactive</span>
                                                @endif

                                            </td>
                                            <td>

                                                @foreach ($list->assignTechnologist as $k => $item )

                                                  <span style="background-color: #00a65a; color: white" class="badge ms-1">{{$item->subTestGroup->title}}</span>
                                                @endforeach
                                            </td>

                                            <td>
                                                @if (\Auth::user()->can(['member.technologists.edit']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.technologists.edit',$list->id) }}"><i
                                                        class="fa fa-edit" title='Edit'></i>
                                                    </a>

                                                    <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.technologists.show',$list->id) }}"><i
                                                        class="fa fa-eye" title='Show'></i>
                                                    </a>

                                                    <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.technologist.active.inactive',$list->id) }}">

                                                    @if ($list->status ==1)
                                                    <i class="fa fa-check-circle-o" aria-hidden="true" title="inactive"></i>
                                                    @else
                                                    <i class="fa fa-check-circle-o" aria-hidden="true" title="Active"></i>
                                                    @endif


                                                    </a>

                                                    {{-- <a href="javascript:void(0)" class="btn btn-xs btn-success"
                                                                            onclick="editTechnologist({{ json_encode($list) }})">

                                                                            <i
                                                        class="fa fa-edit" title='Edit'></i>
                                                                        </a> --}}
                                                @endif
                                                    @if (\Auth::user()->can(['member.technologists.destroy']))

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

                <div class="modal fade mt-3" id="addTechnologistModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" data-bs-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content" style="width: 645px">

                            {!! Form::open([
                                'route' => 'member.technologists.store',
                                'method' => 'POST',
                                'files' => 'true',
                                'role' => 'form',
                            ]) !!}

                            <div class="box-body mt-4">

                                <div class="col-md-12 mt-4">


                                    <div class="form-group">
                                        <label for="name">Specimen <span class="text-red"> * </span> </label>
                                        {!! Form::select('specimen_id', $specimens, null, [
                                            'id' => 'specimen_id',
                                            'placeholder' => 'Select Specimen',
                                            'class' => 'form-control select2',
                                            'required',
                                            'onchange' => 'specimen_select(this)',
                                        ]) !!}
                                    </div>



                                    <div class="form-group">
                                        <label for="name">Test Group<span class="text-red"> * </span> </label>
                                        <select required onchange="sub_group_select(this)" id="test_group_id"
                                            name="test_group_id" class=" select2 form-control">
                                            <option selected value="">Please Select</option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Sub Test Group<span class="text-red"> * </span> </label>
                                        <select required id="sub_test_group_id" name="sub_test_group_id[]"
                                            class=" form-control  select2" multiple="" tabindex="-1" aria-hidden="true">
                                            <option disabled="disabled">Choose Test Title</option>

                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="name">Select Technologist<span class="text-red"> * </span> </label>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-check-input" type="radio" onclick="Technologist(this)"
                                                    value="1" name="technologist_status" id="flexRadioDefault2"
                                                    checked>
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Doctor
                                                </label>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-check-input" type="radio" onclick="Technologist(this)"
                                                    value="2" name="technologist_status" id="flexRadioDefault1">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Staff
                                                </label>
                                            </div>


                                        </div>
                                    </div>

                                    <div class=" d-none mt-2" id="technologist_staff_div">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="name">Staff<span class="text-red"> * </span>
                                                </label>
                                            </div>

                                            <select id="technologist_employeeinfo_id" name="technologist_employeeinfo_id"
                                                class=" select2 form-control">
                                                <option selected value="">Please Select</option>
                                                @foreach ($employees as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->first_name }}
                                                        {{ $value->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group mt-2" id="technologist_doctor_div">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="name">Doctor<span class="text-red"> * </span>
                                                </label>

                                            </div>
                                            {!! Form::select('technologist_doctor_id', $doctors, null, [
                                                'id' => 'technologist_doctor_id',
                                                'placeholder' => 'Select Doctor',
                                                'class' => 'form-control select2',
                                                'required',
                                            ]) !!}


                                        </div>
                                    </div>


                                    <div class="form-group d-none" id="technologist_degree_div">
                                        <label for="name">Technologist Degree </label>
                                        {!! Form::text('technologist_degree', null, [
                                            'id' => 'technologist_degree',
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Technologist Degree',
                                        ]) !!}

                                    </div>

                                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label>Technologist Signature <span class="text-red"> * </span></label>
                                        <div>
                                            @if (@$model)
                                                <input type="file"
                                                    data-default-file="{{ asset('/public/uploads/signature/' . $model->image) }}"
                                                    name="technologist_signature" class="dropify" data-max-file-size="1M"
                                                    data-height="100" data-allowed-file-extensions="jpg jpeg png"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            @else
                                                <input required type="file" name="technologist_signature" class="dropify"
                                                    data-max-file-size="1M" data-height="100"
                                                    data-allowed-file-extensions="jpg jpeg png"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="name">Select Prepared<span class="text-red"> * </span> </label>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-check-input" type="radio" onclick="PreparedBy(this)"
                                                    value="1" name="prepared_status" id="prepared_status1"
                                                    checked>
                                                <label class="form-check-label" for="prepared_status1">
                                                    Doctor
                                                </label>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-check-input" type="radio" onclick="PreparedBy(this)"
                                                    value="2" name="prepared_status" id="prepared_status">
                                                <label class="form-check-label" for="prepared_status">
                                                    Staff
                                                </label>
                                            </div>


                                        </div>
                                    </div>

                                    <div class=" d-none mt-2" id="prepared_staff_div">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="name">Staff<span class="text-red"> * </span>
                                                </label>
                                            </div>

                                            <select id="prepared_employeeinfo_id" name="prepared_employeeinfo_id"
                                                class=" select2 form-control">
                                                <option selected value="">Please Select</option>
                                                @foreach ($employees as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->first_name }}
                                                        {{ $value->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group mt-2" id="prepared_doctor_div">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="name">Doctor<span class="text-red"> * </span>
                                                </label>

                                            </div>
                                            {!! Form::select('prepared_doctor_id', $doctors, null, [
                                                'id' => 'prepared_doctor_id',
                                                'placeholder' => 'Select Doctor',
                                                'class' => 'form-control select2',
                                                'required',
                                            ]) !!}


                                        </div>
                                    </div>


                                    <div class="form-group d-none" id="prepared_degree_div">
                                        <label for="name">Prepared Degree <span class="text-red"> * </span></label>
                                        {!! Form::text('prepared_by_degree', null, [
                                            'id' => 'prepared_by_degree',
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Prepared By Degree',

                                        ]) !!}

                                    </div>

                                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label>Prepared Signature <span class="text-red"> * </span></label>
                                        <div>
                                            @if (@$model)
                                                <input type="file"
                                                    data-default-file="{{ asset('/public/uploads/signature/' . $model->image) }}"
                                                    name="prepared_by_signature" class="dropify" data-max-file-size="1M"
                                                    data-height="100" data-allowed-file-extensions="jpg jpeg png"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            @else
                                                <input required type="file" name="prepared_by_signature" class="dropify"
                                                    data-max-file-size="1M" data-height="100"
                                                    data-allowed-file-extensions="jpg jpeg png"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            @endif

                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="name">Select Checked By<span class="text-red"> * </span> </label>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-check-input" type="radio" onclick="CheckedBy(this)"
                                                    value="1" name="checked_by_status" id="checked_by_status1"
                                                    checked>
                                                <label class="form-check-label" for="checked_by_status1">
                                                    Doctor
                                                </label>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-check-input" type="radio" onclick="CheckedBy(this)"
                                                    value="2" name="checked_by_status" id="checked_by_status">
                                                <label class="form-check-label" for="checked_by_status">
                                                    Staff
                                                </label>
                                            </div>


                                        </div>
                                    </div>

                                    <div class=" d-none mt-2" id="checked_by_staff_div">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="name">Staff<span class="text-red"> * </span>
                                                </label>
                                            </div>

                                            <select id="checked_by_employeeinfo_id" name="checked_by_employeeinfo_id"
                                                class=" select2 form-control">
                                                <option selected value="">Please Select</option>
                                                @foreach ($employees as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->first_name }}
                                                        {{ $value->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group mt-2" id="checked_by_doctor_div">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label for="name">Doctor<span class="text-red"> * </span>
                                                </label>

                                            </div>
                                            {!! Form::select('checked_by_doctor_id', $doctors, null, [
                                                'id' => 'checked_by_doctor_id',
                                                'placeholder' => 'Select Doctor',
                                                'class' => 'form-control select2',
                                                'required',
                                            ]) !!}


                                        </div>
                                    </div>


                                    <div class="form-group d-none" id="checked_by_degree_div">
                                        <label for="name">Checked Degree <span class="text-red"> * </span></label>
                                        {!! Form::text('checked_by_degree', null, [
                                            'id' => 'checked_by_degree',
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Checked By Degree',

                                        ]) !!}

                                    </div>

                                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label>Checked By Signature <span class="text-red"> * </span></label>
                                        <div>
                                            @if (@$model)
                                                <input type="file"
                                                    data-default-file="{{ asset('/public/uploads/signature/' . $model->image) }}"
                                                    name="checked_by_signature" class="dropify" data-max-file-size="1M"
                                                    data-height="100" data-allowed-file-extensions="jpg jpeg png"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            @else
                                                <input required type="file" name="checked_by_signature" class="dropify"
                                                    data-max-file-size="1M" data-height="100"
                                                    data-allowed-file-extensions="jpg jpeg png"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            @endif

                                        </div>
                                    </div>

                                </div>
                                <div class="box-footer">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });




        function Technologist(e) {technologist

            // console.log('yesyy', e.value)
            if (e.value == 1) {
                $('#technologist_doctor_div').removeClass('d-none')
                $('#technologist_staff_div').addClass('d-none')

                $('#technologist_degree_div').addClass('d-none')
                $('#technologist_degree').prop('required',false)
                $('#technologist_doctor_id').prop('required',true)
                $('#technologist_employeeinfo_id').prop('required',false)
                $('#technologist_employeeinfo_id').val('');
                $('#technologist_employeeinfo_id').trigger('change');
            } else {
                $('#technologist_doctor_div').addClass('d-none')
                $('#technologist_staff_div').removeClass('d-none')

                $('#technologist_degree_div').removeClass('d-none')

                $('#technologist_degree').prop('required',true)

                $('#technologist_doctor_id').prop('required',false)
                $('#technologist_employeeinfo_id').prop('required',true)
                $('#technologist_doctor_id').val('');
                $('#technologist_doctor_id').trigger('change');
            }
        }

        function PreparedBy(e) {

            if (e.value == 1) {
                $('#prepared_doctor_div').removeClass('d-none')
                $('#prepared_staff_div').addClass('d-none')

                $('#prepared_degree_div').addClass('d-none')
                $('#prepared_by_degree').prop('required',false)

                $('#prepared_doctor_id').prop('required',true)
                $('#prepared_employeeinfo_id').prop('required',false)
                $('#prepared_employeeinfo_id').val('')
                $('#prepared_employeeinfo_id').trigger('change');

            } else {
                $('#prepared_doctor_div').addClass('d-none')
                $('#prepared_staff_div').removeClass('d-none')

                $('#prepared_degree_div').removeClass('d-none')

                $('#prepared_by_degree').prop('required',true)

                $('#prepared_doctor_id').prop('required',false)
                $('#prepared_employeeinfo_id').prop('required',true)
                $('#prepared_doctor_id').val('')
                $('#prepared_doctor_id').trigger('change');
            }
        }


        function CheckedBy(e) {

            if (e.value == 1) {
                $('#checked_by_doctor_div').removeClass('d-none')
                $('#checked_by_staff_div').addClass('d-none')

                $('#checked_by_degree_div').addClass('d-none')
                $('#checked_by_degree').prop('required',false)

                $('#checked_by_doctor_id').prop('required',true)
                $('#checked_by_employeeinfo_id').prop('required',false)
                $('#checked_by_employeeinfo_id').val('');
                $('#checked_by_employeeinfo_id').trigger('change');
            } else {
                $('#checked_by_doctor_div').addClass('d-none')
                $('#checked_by_staff_div').removeClass('d-none')

                $('#checked_by_degree_div').removeClass('d-none')

                $('#checked_by_degree').prop('required',true)

                $('#checked_by_doctor_id').prop('required',false)
                $('#checked_by_employeeinfo_id').prop('required',true)
                $('#checked_by_doctor_id').val('');
                $('#checked_by_doctor_id').trigger('change');
            }
        }

        function specimen_select(e) {
            let specimen_id = e.value;
            let option_is = '<option value="">Please Select</option>';
            $.ajax({
                type: "get",
                url: "{{ route('member.fetch.test_by_specimen') }}",
                data: {
                    'id': specimen_id,
                },
                success: function(response) {
                    let data_is = response.data

                    console.log('kkk',data_is)
                    if (data_is.length > 0) {
                        $.each(data_is, function(key, value) {

                            option_is +=
                                `<option value="${value.id}">${value.title}</option>`;
                        });
                        console.log('option_is has data',option_is)
                        $('#test_group_id').html(option_is);
                    }else{
                        $('#test_group_id').html(option_is);
                        $('#test_group_id').trigger('change');
                        $('#sub_test_group_id').trigger('change');
                    }

                },
                error: function(response) {

                }
            });

        }

        function sub_group_select(e) {

            let group_id = e.value;
            let option = '<option value="">Please Select</option>';
            $.ajax({
                type: "get",
                url: "{{ route('member.fetch.subtest.byId') }}",
                data: {
                    'id': group_id,
                },
                success: function(response) {

                    checkTest = response.data
                    if (checkTest.length > 0) {
                        $.each(checkTest, function(key, value) {

                            option +=
                                `<option value="${value.id}">${value.title} (${value.price})</option>`;
                        });

                        $('#sub_test_group_id').html(option);
                    }else{
                        $('#sub_test_group_id').html(option);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

        }

        function addTechnologist() {
            $('#addTechnologistModal').modal('show')
        }
    </script>

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
@endpush
