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
        .radioBtn {
            position: absolute;
            top: 0%;
            left: 0%;
            display: block;
            width: 100%;
            height: 100%;
            margin: 0px;
            padding: 0px;
            background: rgb(255, 255, 255);
            border: 0px;
            opacity: 0;
        }
    </style>
@endpush


<div class="col-md-6">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Patient Personal Information</h3>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label for="patient_name">Patient Name <span class="text-red"> * </span> </label>
                {!! Form::text('patient_name', null, [
                    'id' => 'patient_name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter patient name',
                    'required',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="father_name">Father Name <span class="text-red"> * </span> </label>
                {!! Form::text('father_name', null, [
                    'id' => 'father_name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Father name',
                    'required',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="mother_name">Mother Name <span class="text-red"> * </span> </label>
                {!! Form::text('mother_name', null, [
                    'id' => 'mother_name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter mother name',
                    'required',
                ]) !!}
            </div>

            <div class="form-group">
                <label for="reg_no">Registration No <span class="text-red"> * </span> </label>
                {!! Form::text('reg_no', null, [
                    'id' => 'reg_no',
                    'class' => 'form-control',
                    'placeholder' => 'Registration No',
                    'required',
                ]) !!}
            </div>

            <div class="form-group">
                <label for="patient_phone">Phone No </label>
                {!! Form::text('patient_phone', null, [
                    'id' => 'patient_phone',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Phone No',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="birth_place">Place of Birth</label>
                {!! Form::text('birth_place', null, [
                    'id' => 'birth_place',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Birth Place',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                {!! Form::text('date_of_birth', null, [
                    'id' => 'date_of_birth',
                    'class' => 'form-control',
                    'type' => 'number',
                    'placeholder' => 'Select Date of Birth',
                ]) !!}
            </div>



            <div class="form-group">
                <label for="lead_category_id">Blood Group<span class="text-red"> * </span></label>
                <select class="form-control select2" name="blood_group">
                    <option value="">Select Blood Group</option>
                    @foreach ($bloodGroups as $item)
                <option value="{{ $item->name }}" >{{ $item->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nid">NID Number</label>
                {!! Form::number('nid', null, [
                    'id' => 'nid',
                    'class' => 'form-control',
                    'placeholder' => 'Enter NID Number',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="passport">Passport No</label>
                {!! Form::number('passport', null, [
                    'id' => 'passport',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Passport No',
                ]) !!}
            </div>

            <div class="form-group">
                <label for="nationality">Nationality<span class="text-red"> * </span></label>
                <select class="form-control select2" name="nationality">
                    <option value="">Select Nationality</option>
                    @foreach ($country as $item)
                <option value="{{ $item->countryName }}" >{{ $item->countryName }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="occupaton">Occupation</label>
                {!! Form::text('occupaton', null, [
                    'id' => 'occupaton',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Occupation',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                {!! Form::email('email', null, [
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Email',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="spouse_name">Spouse Name</label>
                {!! Form::text('spouse_name', null, [
                    'id' => 'spouse_name',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Spouse Name',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="spouse_phone">Spouse Mobile Number</label>
                {!! Form::text('spouse_phone', null, [
                    'id' => 'spouse_phone',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Spouse Mobile Number',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="spouse_name">Guardian Name</label>
                {!! Form::text('guardian_name', null, [
                    'id' => 'guardian_name',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Guardian Name',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="guardian_phone">Guardian Mobile Number</label>
                {!! Form::text('guardian_phone', null, [
                    'id' => 'guardian_phone',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Guardian Mobile Number',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="guardian_relation">Guardian Relationship</label>
                {!! Form::text('guardian_relation', null, [
                    'id' => 'guardian_relation',
                    'class' => 'form-control',
                    'placeholder' => 'Guardian Relationship',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="gender">Gender<span class="text-red"> * </span></label>
                <select class="form-control select2" name="gender">
                    <option value="">Select Gender</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                    <option value="3">Others</option>

                </select>
            </div>
            <div class="form-group">
                <label for="married_status">Married Status<span class="text-red"> * </span></label>
                <select class="form-control select2" name="married_status">
                    <option value="">Select Married Status</option>
                    <option value="1">Married</option>
                    <option value="2">Unmarried</option>
                    <option value="3">Others</option>

                </select>
            </div>
            <div class="form-group">
                <label for="religion">Religion<span class="text-red"> * </span></label>
                <select class="form-control select2" name="religion">
                    <option value="">Select Religion</option>
                    <option value="1">Muslim</option>
                    <option value="2">Hindu</option>
                    <option value="3">Buddhist</option>
                    <option value="4">Christian</option>
                    <option value="5">Others</option>

                </select>
            </div>

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

        $(function() {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('description', {
                toolbar: 'MA'
            });
        });
    </script>
@endpush
