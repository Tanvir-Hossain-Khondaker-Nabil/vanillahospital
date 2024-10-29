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
@endpush



<div class="col-md-6">


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
            <option selected value="">Please Select</option>
            {{-- @foreach ($test_group as $key => $value)
                <option value="{{ $key }}">{{ $value }}
                </option>
            @endforeach --}}
        </select>
    </div>

    <div class="form-group">
        <label for="name">Sub Test Group<span class="text-red"> * </span> </label>
        <select required id="sub_test_group_id" name="sub_test_group_id[]" class=" form-control  select2" multiple=""
            tabindex="-1" aria-hidden="true">
            <option disabled="disabled">Choose Test Title</option>

        </select>

    </div>

    <div class="form-group">
        <label for="name">Technologist Name<span class="text-red"> * </span> </label>
        {!! Form::text('technologist_name', null, [
            'id' => 'technologist_name',
            'class' => 'form-control',
            'placeholder' => 'Enter Technologist Name',
            'required',
        ]) !!}

    </div>

    <div class="form-group">
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
                <input type="file" data-default-file="{{ asset('/public/uploads/signature/' . $model->image) }}"
                    name="technologist_signature" class="dropify" data-max-file-size="1M" data-height="200"
                    data-allowed-file-extensions="jpg jpeg png" accept="image/png, image/jpg, image/jpeg" />
            @else
                <input type="file" name="technologist_signature" class="dropify" data-max-file-size="1M"
                    data-height="200" data-allowed-file-extensions="jpg jpeg png"
                    accept="image/png, image/jpg, image/jpeg" />
            @endif

        </div>
    </div>

    <div class="form-group">
        <label for="name">Prepared Name<span class="text-red"> * </span> </label>
        {!! Form::text('prepared_by_name', null, [
            'id' => 'prepared_by_name',
            'class' => 'form-control',
            'placeholder' => 'Enter Prepared Name',
            'required',
        ]) !!}

    </div>


    <div class="form-group">
        <label for="name">Prepared Degree </label>
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
                <input type="file" data-default-file="{{ asset('/public/uploads/signature/' . $model->image) }}"
                    name="prepared_by_signature" class="dropify" data-max-file-size="1M" data-height="200"
                    data-allowed-file-extensions="jpg jpeg png" accept="image/png, image/jpg, image/jpeg" />
            @else
                <input type="file" name="prepared_by_signature" class="dropify" data-max-file-size="1M"
                    data-height="200" data-allowed-file-extensions="jpg jpeg png"
                    accept="image/png, image/jpg, image/jpeg" />
            @endif

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label for="name">Checked By<span class="text-red"> * </span> </label>

        </div>

        <div class="col-md-2">
            <input class="form-check-input" type="radio" onclick="checkedBy(this)" value="1"
                name="tect_checked_status" id="flexRadioDefault2" checked>
            <label class="form-check-label" for="flexRadioDefault2">
                Doctor
            </label>
        </div>

        <div class="col-md-2">
            <input class="form-check-input" type="radio" onclick="checkedBy(this)" value="2"
                name="tect_checked_status" id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">
                Staff
            </label>
        </div>


    </div>

    <div class="form-group d-none mt-2" id="staff_div">
        <label for="name">Staff<span class="text-red"> * </span> </label>

        <select id="employeeinfo_id" name="employeeinfo_id" class=" select2 form-control">
            <option selected value="">Please Select</option>
            @foreach ($employees as $key => $value)
                <option value="{{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}
                </option>
            @endforeach
        </select>

    </div>

    <div class="form-group mt-2" id="doctor_div">
        <label for="name">Doctor<span class="text-red"> * </span> </label>
        {!! Form::select('doctor_id', $doctors, null, [
            'id' => 'doctor_id',
            'placeholder' => 'Select Doctor',
            'class' => 'form-control select2',
        ]) !!}


    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
        <label>checked By Signature <span class="text-red"> * </span></label>
        <div>
            @if (@$model)
                <input type="file" data-default-file="{{ asset('/public/uploads/signature/' . $model->image) }}"
                    name="checked_by_signature" class="dropify" data-max-file-size="1M" data-height="200"
                    data-allowed-file-extensions="jpg jpeg png" accept="image/png, image/jpg, image/jpeg" />
            @else
                <input type="file" name="checked_by_signature" class="dropify" data-max-file-size="1M"
                    data-height="200" data-allowed-file-extensions="jpg jpeg png"
                    accept="image/png, image/jpg, image/jpeg" />
            @endif

        </div>
    </div>

</div>


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



        function checkedBy(e) {

            // console.log('yesyy', e.value)
            if (e.value == 1) {
                $('#doctor_div').removeClass('d-none')
                $('#staff_div').addClass('d-none')
            } else {
                $('#doctor_div').addClass('d-none')
                $('#staff_div').removeClass('d-none')
            }
        }

        function specimen_select(e) {
            let specimen_id = e.value;
            let option = ' Please Select'
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

                            option +=
                                `<option value="${value.id}">${value.title}</option>`;
                        });

                        $('#test_group_id').html(option);
                    }else{
                        $('#test_group_id').html(option);
                    }

                },
                error: function(response) {

                }
            });

        }

        function sub_group_select(e) {

            let group_id = e.value;
            let option = ''
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
    </script>
@endpush
