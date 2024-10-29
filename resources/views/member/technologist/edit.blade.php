<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
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
        'name' => 'Technologist',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Technologist',
    'title' => 'Edit Technologist',
    'heading' => 'Edit Technologist',
];

?>



@extends('layouts.back-end.master', $data)
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush
@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">

            @include('common._alert')

            <!-- general form elements -->
            <div class="box box-primary">

                <input type="hidden" value="{{ $model->test_group_id }}" id="edit_test_group_id">
                <!-- /.box-header -->
                <!-- form start -->


                {!! Form::model($model, [
                    'route' => ['member.technologists.update', $model],
                    'files' => 'true',
                    'method' => 'put',
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
                            <select required onchange="sub_group_select(this)" id="test_group_id" name="test_group_id"
                                class=" select2 form-control">


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
                                    <label for="name">Select Technologist<span class="text-red"> * </span>
                                    </label>
                                </div>

                                <div class="col-md-2">
                                    <input class="form-check-input" {{$model->technologist_status ==1? "checked" : ""}} type="radio" onclick="Technologist(this)"
                                        value="1" name="technologist_status" id="flexRadioDefault2" >
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Doctor
                                    </label>
                                </div>

                                <div class="col-md-2">
                                    <input class="form-check-input" {{$model->technologist_status ==2? "checked" : ""}} type="radio" onclick="Technologist(this)"
                                        value="2" name="technologist_status" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Staff
                                    </label>
                                </div>


                            </div>
                        </div>

                        <div class=" {{$model->technologist_status ==2? "" : 'd-none'}} mt-2" id="technologist_staff_div">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="name">Staff<span class="text-red"> * </span>
                                    </label>
                                </div>

                                <select id="technologist_employeeinfo_id" name="technologist_employeeinfo_id"
                                    class=" select2 form-control">
                                    <option selected value="">Please Select</option>
                                    @foreach ($employees as $key => $value)
                                        <option {{$model->technologist_employeeinfo_id == $value->id? 'selected' : ""}} value="{{ $value->id }}">{{ $value->first_name }}
                                            {{ $value->last_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group mt-2 {{$model->technologist_status ==2? 'd-none' : ''}}" id="technologist_doctor_div">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="name">Doctor<span class="text-red"> * </span>
                                    </label>

                                </div>
                                {!! Form::select('technologist_doctor_id', $doctors, null, [
                                    'id' => 'technologist_doctor_id',
                                    'placeholder' => 'Select Doctor',
                                    'class' => 'form-control select2',

                                ]) !!}


                            </div>
                        </div>


                        <div class="form-group {{$model->technologist_status ==2? "" : 'd-none'}}" id="technologist_degree_div">
                            <label for="name">Technologist Degree <span class="text-red"> * </span></label>
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
                                        data-default-file="{{ asset('/public/uploads/signature/' . $model->technologist_signature) }}"
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
                                    <label for="name">Select Prepared<span class="text-red"> * </span>
                                    </label>
                                </div>

                                <div class="col-md-2">
                                    <input class="form-check-input" {{$model->prepared_status ==1? "checked" : ""}} type="radio" onclick="PreparedBy(this)"
                                        value="1" name="prepared_status" id="prepared_status1">
                                    <label class="form-check-label" for="prepared_status1">
                                        Doctor
                                    </label>
                                </div>

                                <div class="col-md-2">
                                    <input class="form-check-input" {{$model->prepared_status ==2? "checked" : ""}} type="radio" onclick="PreparedBy(this)"
                                        value="2" name="prepared_status" id="prepared_status">
                                    <label class="form-check-label" for="prepared_status">
                                        Staff
                                    </label>
                                </div>


                            </div>
                        </div>

                        <div class="{{$model->prepared_status ==2? "" : 'd-none'}}  mt-2" id="prepared_staff_div">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="name">Staff<span class="text-red"> * </span>
                                    </label>
                                </div>

                                <select id="prepared_employeeinfo_id" name="prepared_employeeinfo_id"
                                    class=" select2 form-control">
                                    <option selected value="">Please Select</option>
                                    @foreach ($employees as $key => $value)
                                        <option {{$model->prepared_employeeinfo_id == $value->id? 'selected' : ""}} value="{{ $value->id }}">{{ $value->first_name }}
                                            {{ $value->last_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group mt-2 {{$model->prepared_status ==2? 'd-none' : ''}}" id="prepared_doctor_div">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="name">Doctor<span class="text-red"> * </span>
                                    </label>

                                </div>
                                {!! Form::select('prepared_doctor_id', $doctors, null, [
                                    'id' => 'prepared_doctor_id',
                                    'placeholder' => 'Select Doctor',
                                    'class' => 'form-control select2',

                                ]) !!}


                            </div>
                        </div>


                        <div class="form-group {{$model->prepared_status ==2? "" : 'd-none'}}" id="prepared_degree_div">
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
                                        data-default-file="{{ asset('/public/uploads/signature/' . $model->prepared_by_signature) }}"
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
                                    <label for="name">Select Checked By<span class="text-red"> * </span>
                                    </label>
                                </div>

                                <div class="col-md-2">
                                    <input class="form-check-input" {{$model->checked_by_status ==1? "checked" : ""}} type="radio" onclick="CheckedBy(this)"
                                        value="1" name="checked_by_status" id="checked_by_status1">
                                    <label class="form-check-label" for="checked_by_status1">
                                        Doctor
                                    </label>
                                </div>

                                <div class="col-md-2">
                                    <input class="form-check-input" {{$model->checked_by_status ==2? "checked" : ""}} type="radio" onclick="CheckedBy(this)"
                                        value="2" name="checked_by_status" id="checked_by_status">
                                    <label class="form-check-label" for="checked_by_status">
                                        Staff
                                    </label>
                                </div>


                            </div>
                        </div>

                        <div class=" {{$model->checked_by_status ==2? '' : 'd-none'}} mt-2" id="checked_by_staff_div">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="name">Staff<span class="text-red"> * </span>
                                    </label>
                                </div>

                                <select id="checked_by_employeeinfo_id" name="checked_by_employeeinfo_id"
                                    class=" select2 form-control">
                                    <option selected value="">Please Select</option>
                                    @foreach ($employees as $key => $value)
                                        <option {{$model->checked_by_employeeinfo_id == $value->id? 'selected' : ""}} value="{{ $value->id }}">{{ $value->first_name }}
                                            {{ $value->last_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group mt-2 {{$model->checked_by_status ==2? 'd-none' : ''}}" id="checked_by_doctor_div">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="name">Doctor<span class="text-red"> * </span>
                                    </label>

                                </div>
                                {!! Form::select('checked_by_doctor_id', $doctors, null, [
                                    'id' => 'checked_by_doctor_id',
                                    'placeholder' => 'Select Doctor',
                                    'class' => 'form-control select2',

                                ]) !!}


                            </div>
                        </div>


                        <div class="form-group {{$model->checked_by_status ==2? "" : 'd-none'}}" id="checked_by_degree_div">
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
                                        data-default-file="{{ asset('/public/uploads/signature/' . $model->checked_by_signature) }}"
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
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}


            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>
    <script>
        $(function() {

            editTechnologist();
            $('.select2').select2();
            $('.dropify').dropify();
            $('#specimen_id').trigger('change');

            setTimeout(function() {
                $('#test_group_id').trigger('change');

            }, 1000);



        });


        function editTechnologist() {
            // alert('yes')
            setTimeout(function() {
                $("#EditTechnologistModal").modal('show');

            }, 1000);

        }


        function Technologist(e) {

            // console.log('yesyy', e.value)
            if (e.value == 1) {
                $('#technologist_doctor_div').removeClass('d-none')
                $('#technologist_staff_div').addClass('d-none')

                $('#technologist_degree_div').addClass('d-none')
                $('#technologist_degree').prop('required', false)
                $('#technologist_doctor_id').prop('required', true)
                $('#technologist_employeeinfo_id').prop('required', false)
                $('#technologist_employeeinfo_id').val('');
                $('#technologist_employeeinfo_id').trigger('change');
            } else {
                $('#technologist_doctor_div').addClass('d-none')
                $('#technologist_staff_div').removeClass('d-none')

                $('#technologist_degree_div').removeClass('d-none')

                $('#technologist_degree').prop('required', true)

                $('#technologist_doctor_id').prop('required', false)
                $('#technologist_employeeinfo_id').prop('required', true)
                $('#technologist_doctor_id').val('');
                $('#technologist_doctor_id').trigger('change');
            }
        }

        function PreparedBy(e) {

            if (e.value == 1) {
                $('#prepared_doctor_div').removeClass('d-none')
                $('#prepared_staff_div').addClass('d-none')

                $('#prepared_degree_div').addClass('d-none')
                $('#prepared_by_degree').prop('required', false)

                $('#prepared_doctor_id').prop('required', true)
                $('#prepared_employeeinfo_id').prop('required', false)
                $('#prepared_employeeinfo_id').val('')
                $('#prepared_employeeinfo_id').trigger('change');

            } else {
                $('#prepared_doctor_div').addClass('d-none')
                $('#prepared_staff_div').removeClass('d-none')

                $('#prepared_degree_div').removeClass('d-none')

                $('#prepared_by_degree').prop('required', true)

                $('#prepared_doctor_id').prop('required', false)
                $('#prepared_employeeinfo_id').prop('required', true)
                $('#prepared_doctor_id').val('')
                $('#prepared_doctor_id').trigger('change');
            }
        }


        function CheckedBy(e) {

            if (e.value == 1) {
                $('#checked_by_doctor_div').removeClass('d-none')
                $('#checked_by_staff_div').addClass('d-none')

                $('#checked_by_degree_div').addClass('d-none')
                $('#checked_by_degree').prop('required', false)

                $('#checked_by_doctor_id').prop('required', true)
                $('#checked_by_employeeinfo_id').prop('required', false)
                $('#checked_by_employeeinfo_id').val('');
                $('#checked_by_employeeinfo_id').trigger('change');
            } else {
                $('#checked_by_doctor_div').addClass('d-none')
                $('#checked_by_staff_div').removeClass('d-none')

                $('#checked_by_degree_div').removeClass('d-none')

                $('#checked_by_degree').prop('required', true)

                $('#checked_by_doctor_id').prop('required', false)
                $('#checked_by_employeeinfo_id').prop('required', true)
                $('#checked_by_doctor_id').val('');
                $('#checked_by_doctor_id').trigger('change');
            }
        }

        function specimen_select(e) {

            let specimen_id = e.value;
            let edit_test_group_id = $('#edit_test_group_id').val();

            console.log('edit_test_group_id',edit_test_group_id)
            let option_is = 'Please Select';
            $.ajax({
                type: "get",
                url: "{{ route('member.fetch.test_by_specimen') }}",
                data: {
                    'id': specimen_id,
                },
                success: function(response) {
                    let data_is = response.data

                    if (data_is.length > 0) {
                        $.each(data_is, function(key, value) {

                            if (edit_test_group_id == value.id) {
                                option_is +=
                                    `<option selected value="${value.id}">${value.title}</option>`;
                            } else {
                                option_is +=
                                    `<option value="${value.id}">${value.title}</option>`;
                            }
                        });


                        $('#test_group_id').html(option_is);
                    }

                },
                error: function(response) {

                }
            });

        }

        function sub_group_select(e) {

            // console.log('salccccccc', @json($assignTechnologist_ids));
            let test_ids = @json($assignTechnologist_ids);
            let group_id = e.value;
            let option = 'Please Select';
            console.log('kkk',test_ids)
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

                            if(@json($assignTechnologist_ids).includes(value.id)){
                                option +=
                                `<option selected value="${value.id}">${value.title} (${value.price})</option>`;
                            }else{
                                option +=
                                `<option value="${value.id}">${value.title} (${value.price})</option>`;
                            }

                        });

                        $('#sub_test_group_id').html(option);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

        }
    </script>
@endpush
