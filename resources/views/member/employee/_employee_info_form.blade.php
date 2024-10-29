<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */
$user = \Illuminate\Support\Facades\Auth::user();
$roles = $user->hasRole('project_manager') ? 0 : 1;
?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="box box-primary">
    <div class="box-header with-border mb-3">
        <h3 class="box-title">Personal Information</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="password">Employee ID <span class="text-red"> * </span> </label>
                {!! Form::text('employeeID', null, [
                    'id' => 'employeeID',
                    'class' => 'form-control',
                    'placeholder' => 'Enter employee ID',
                    'readonly',
                    'required',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">NIF <span class="text-red"> * </span> </label>
                {!! Form::text('nid', null, [
                    'id' => 'nid',
                    'class' => 'form-control',
                    'placeholder' => 'Enter  NIF',
                    'readonly',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">First Name <span class="text-red"> * </span> </label>
                {!! Form::text('first_name', null, [
                    'id' => 'name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter First Name',
                    'required',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Last Name <span class="text-red"> * </span> </label>
                {!! Form::text('last_name', null, [
                    'id' => 'last_name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Last Name',
                    'required',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Mobile Number <span class="text-red"> * </span> </label>
                {!! Form::text('phone', isset($model) ? $model->phone2 : null, [
                    'id' => 'phone',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Mobile Number',
                    'readonly',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Email <span class="text-red"> * </span> </label>
                {!! Form::text('email', isset($model) ? $model->user->email : null, [
                    'id' => 'email',
                    'class' => 'form-control',
                    'readonly',
                    'placeholder' => 'Enter Email',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Date of Birth <span class="text-red"> * </span> </label>
                {!! Form::text('dob', isset($model) ? create_date_format($model->dob, '/') : null, [
                    'id' => 'dob',
                    'class' => 'form-control date',
                    'placeholder' => 'Enter Date of Birth',
                    'required',
                    'autocomplete' => 'off',
                ]) !!}
            </div>
        </div>

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border mb-3">
        <h3 class="box-title">Address Details</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Present Address <span class="text-red"> * </span> </label>
                {!! Form::text('present_address', isset($model) ? $model->address : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Enter present address',
                    'required',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Permanent Address <span class="text-red"> * </span> </label>
                {!! Form::text('permanent_address', isset($model) ? $model->address2 : null, [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Permanent address',
                    'required',
                ]) !!}
            </div>
        </div>
        @if (config('settings.areas'))
            <div class="col-md-6">
                <div class="form-group division area_hidden">
                    <label for="status">Nationality <span class="text-red"> * </span> </label>
                    {!! Form::select('nationality', $countries, null, [
                        'class' => 'form-control select2 select-region',
                        'placeholder' => 'Please select',
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group division area_hidden">
                    <label for="status">State <span class="text-red"> * </span> </label>
                    {!! Form::select('division_id', $divisions, null, [
                        'class' => 'form-control select2 select-region',
                        'placeholder' => 'Please select',
                        'required',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group district area_hidden">
                    <label for="status">City </label>
                    {!! Form::select('district_id', $districts, null, [
                        'id' => 'district_id',
                        'class' => 'form-control select2 select-district-thana',
                        'placeholder' => 'Please select',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group area area_hidden">
                    <label for="status">Area </label>
                    {!! Form::select('area_id', $areas, null, [
                        'id' => 'area_id',
                        'class' => 'form-control select2',
                        'placeholder' => 'Please select',
                    ]) !!}
                </div>
            </div>
        @endif

        <input type="hidden" name="designation_id" value="{{ $model->designation_id }}">


        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border mb-3">
        <h3 class="box-title"> Other Information </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Insurance Company </label>
                {!! Form::text('insurance_company', null, [
                    'id' => 'insurance_company',
                    'readonly',
                    'class' => 'form-control',
                    'placeholder' => 'Enter insurance company',
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Insurance Number </label>
                {!! Form::text('insurance_number', null, [
                    'id' => 'insurance_number',
                    'class' => 'form-control',
                    'placeholder' => 'Enter insurance number',
                    'readonly',
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="initial_balance">Join Date </label>
                {!! Form::text('join_date', isset($model) ? create_date_format($model->join_date, '/') : null, [
                    'class' => 'form-control date ',
                    'placeholder' => 'Join Date ',
                    'readonly',
                    'autocomplete' => 'off',
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="initial_balance">Visa Expire </label>
                {!! Form::text('visa_expire', isset($model) ? $model->visa_expire ? create_date_format($model->visa_expire, '/') : '' : null, [
                    'class' => 'form-control expire-date ',
                    'placeholder' => 'visa expire Date ',
                    'readonly',
                    'autocomplete' => 'off',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Passport Number </label>
                {!! Form::text('passport_number', null, [
                    'id' => 'passport_number',
                    'class' => 'form-control',
                    'placeholder' => 'Enter passport number',
                    'readonly',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="initial_balance">Passport Expire </label>
                {!! Form::text('passport_expire', isset($model) ? $model->passport_expire ? create_date_format($model->passport_expire, '/') : "" : null, [
                    'class' => 'form-control expire-date ',
                    'placeholder' => 'passport expire Date ',
                    'readonly',
                    'autocomplete' => 'off',
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Diving license </label>
                {!! Form::text('diving_license', null, [
                    'id' => 'diving_license',
                    'class' => 'form-control',
                    'readonly',
                    'placeholder' => 'Enter Diving license',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Permanent Residence (PR) Number </label>
                {!! Form::text('pr_number', null, [
                    'id' => 'pr_number',
                    'class' => 'form-control',
                    'readonly',
                    'placeholder' => 'Enter pr number',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Permanent Residence (PR) Expire</label>
                {!! Form::text('pr_expire', isset($model) ? $model->pr_expire ? create_date_format($model->pr_expire, '/') : '' : null, [
                  'class' => 'form-control',
                  'placeholder' => 'pr expire Date ',
                  'readonly',
                  'autocomplete' => 'off',
              ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border mb-3">
        <h3 class="box-title"> Given Files  </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body row">
        <div class="col-md-12 mt-2 px-0">
            <div class="form-group">
                @if (isset($model) && count($model->attached_file) > 0)
                    @foreach ($model->attached_file as $value)
                        <div class="col-md-4 mb-3">
                            <b><i>{{ $value->document_type->name }}</i></b> <br/>
                            <a target="_blank"
                               href="{{ $value->attached ? $value->attached_file : 'javascript:void(0)' }}">
                                View file </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>


</div>


{{-- <div class="col-md-12 mb-5"> --}}
{{-- <div class="form-group "> --}}
{{-- <label for="role">{{ isset($assignRole)?'Assigned ':'' }} Roles  </label><br/> --}}

{{-- @foreach ($roles as $key => $role) --}}
{{-- <div class="col-md-3"> --}}
{{-- @if (isset($assignRole)) --}}
{{-- {!! Form::checkbox('roles[]', $key, null, [ 'checked' => in_array($key, $assignRole )] ) !!} {{ $role }} --}}
{{-- @else --}}
{{-- {!! Form::checkbox('roles[]', $key, null ) !!} {{ $role }} --}}
{{-- @endif --}}
{{-- </div> --}}
{{-- @endforeach --}}
{{-- </div><br/> --}}
{{-- </div> --}}

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {

            // $('.date').datepicker({
            //     "setDate": new Date(),
            //     "format": 'mm/dd/yyyy',
            //     "endDate": "+0d",
            //     "todayHighlight": true,
            //     "autoclose": true
            // });
            //
            // $('.expire-date').datepicker({
            //     "setDate": new Date(),
            //     "format": 'mm/dd/yyyy',
            //     "todayHighlight": true,
            //     "autoclose": true
            // });

            $('#dob').datepicker({
                "changeMonth": true,
                "maxDate": '0',
                "endDate": "+0d",
                "changeYear": true,
                "autoclose": true,
                'yearRange': '1971:{{ date('Y') }}',
            });


            $('.select2').select2();

            $(".file-select").change(function () {

                var fileID = $(this).val();
                if ($(this).is(':checked')) {
                    $("#submit_file_" + fileID).removeClass("hidden");
                    $("#file_" + fileID).removeClass("hidden");
                } else {
                    $("#submit_file_" + fileID).addClass("hidden");
                    $("#file_" + fileID).addClass("hidden");
                }
            });


            $("#designation_id").change(function () {

                var salary = $("#designation_id :selected").data('salary');
                var commission = $("#designation_id :selected").data('commission');
                var commission_area = $("#designation_id :selected").data('area');

                $("#salary").val(salary);
                $("#commission").val(commission);

                $('.area_hidden').addClass('hidden');

                if (commission_area == "division") {
                    $('.division').removeClass('hidden');
                } else if (commission_area == "region") {
                    $('.division').removeClass('hidden');
                    $('.region').removeClass('hidden');
                } else if (commission_area == "area") {
                    $('.division').removeClass('hidden');
                    $('.region').removeClass('hidden');
                    $('.district').removeClass('hidden');
                    $('.thana').removeClass('hidden');
                    $('.area').removeClass('hidden');
                } else if (commission_area == "thana") {
                    $('.division').removeClass('hidden');
                    $('.region').removeClass('hidden');
                    $('.district').removeClass('hidden');
                    $('.thana').removeClass('hidden');
                } else if (commission_area == "") {
                    $('.area_hidden').removeClass('hidden');
                }

            });
        });
    </script>

    @include('common.area_script')
@endpush
