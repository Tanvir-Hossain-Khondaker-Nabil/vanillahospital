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
        .form-group.ml-3.d-flex {
            display: flex;
            margin: 0 !important;
            gap: 5px;
        }

        .radioArea .yes,
        .radioArea .no {}

        .radioArea .yes span,
        .radioArea .no span {}

        .radioArea input {
            margin: 0;
        }

        .radioArea {
            display: flex;
            align-items: center;
            gap: 20px;
        }

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
            <h3 class="card-title">Basic Info</h3>
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
                <label for="reg_no">Registration No <span class="text-red"> * </span> </label>
                {!! Form::text('reg_no', $reg_id, [
                    'id' => 'reg_no',
                    'class' => 'form-control',
                    'placeholder' => 'Registration No',
                    'required',
                    'readonly',
                ]) !!}
            </div>

            <div class="form-group">
                <label for="phone_no">Phone No </label>
                {!! Form::text('phone_no', null, [
                    'id' => 'phone_no',
                    'class' => 'form-control',
                    'placeholder' => 'Patient Phone No',
                ]) !!}
            </div>

            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                {!! Form::text('date_of_birth', null, [
                    'id' => 'date_of_birth',
                    'class' => 'form-control date',
                    'onchange' => 'SetDMY(this)',
                    'placeholder' => 'Select Date of Birth',
                ]) !!}
            </div>
            <div class="form-group">

                <label for="date_of_birth">Age</label>
                <div class="form-group ml-3 d-flex ">
                    <input class="form-control col-sm-4" onchange="dateOfBirth()" id="Day" name="Day"
                        type="number" placeholder="D">
                    <input class="form-control col-sm-4" onchange="dateOfBirth()" id="Month" name="Month"
                        type="number" placeholder="M">
                    <input class="form-control col-sm-4" onchange="dateOfBirth()" id="Year" name="Year"
                        type="number" placeholder="Y">
                </div>
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
                <label for="cabin_no">Cabin No<span class="text-red"> * </span></label>
                <select required class="form-control select2" name="cabin_no">
                    <option value="">Select Cabin No</option>
                    @foreach ($rooms as $item)
                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group<span class="text-red"> * </span></label>
                <select required class="form-control select2" name="blood_group">
                    <option value="">Select Blood Group</option>
                    @foreach ($bloodGroups as $item)
                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="advance_payment">Advance Payment</label>
                {!! Form::number('advance_payment', 0, [
                    'id' => 'advance_payment',
                    'class' => 'form-control',
                    'type' => 'number',
                    'placeholder' => 'Enter Advance Payment',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="admission_fee">Admission Fee</label>
                {!! Form::number('admission_fee', 0, [
                    'id' => 'admission_fee',
                    'class' => 'form-control',
                    'type' => 'number',
                    'placeholder' => 'Enter Admission Fee',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="admission_fee_paid">Admission Fee Paid</label>
                {!! Form::number('admission_fee_paid', 0, [
                    'id' => 'admission_fee_paid',
                    'class' => 'form-control',
                    'type' => 'number',
                    'placeholder' => 'Enter Admission Fee Paid',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="">
                    Do the references made any marketing officer?
                </label>
                <div class="radioArea">
                    <div class="yes"> <input type="radio"  value="1" name="marketingOffice" id="">
                        <span>Yes</span> </div>
                    <div class="no"> <input type="radio" checked value="0" name="marketingOffice" id="">
                        <span>No</span> </div>
                </div>
            </div>
            <div class="form-group" id="marketing">

            </div>
        </div>
    </div>

</div>
<div class="col-md-6">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Other Info</h3>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label for="doc_name">Doctor Name<span class="text-red"> * </span></label>
                <select class="form-control select2" name="doc_name">
                    <option value="">Select Doctor Name</option>
                    @foreach ($doctors as $item)
                        <option value="{{ $item->name . '#' . $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="ref_doc_name">Reference Doctor Name<span class="text-red"> * </span></label>
                <select class="form-control select2" name="ref_doc_name">
                    <option value="">Select Reference Doctor Name</option>
                    @foreach ($doctors as $item)
                        <option value="{{ $item->name . '#' . $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="admit_date">Date Of Admission</label>
                {!! Form::text('admit_date', date('Y-m-d'), [
                    'id' => 'admit_date',
                    'class' => 'form-control',
                    'placeholder' => 'Date Of Admission',
                    'readonly',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                {!! Form::email('email', null, [
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="disease_name">Disease Name</label>
                {!! Form::text('disease_name', null, [
                    'id' => 'disease_name',
                    'class' => 'form-control',
                    'placeholder' => 'Disease Name',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="guardian_name">Guardian Name</label>
                {!! Form::text('guardian_name', null, [
                    'id' => 'guardian_name',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Guardian Name',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                {!! Form::textarea('address', null, [
                    'id' => 'address',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Address',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="blood_pressure">Blood Pressure</label>
                {!! Form::text('blood_pressure', null, [
                    'id' => 'blood_pressure',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Blood Pressure',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="pulse_rate">Pulse Rate</label>
                {!! Form::text('pulse_rate', null, [
                    'id' => 'pulse_rate',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Pulse Rate',
                ]) !!}
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::textarea('description', null, [
                    'id' => 'description',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Description',
                ]) !!}
            </div>

            <div class="form-group">
                <label for="operator_id">Operator Name</label>
                {!! Form::text('operator_id', auth()->user()->full_name, [
                    'id' => 'operator_id',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Pulse Rate',
                    'readonly',
                ]) !!}
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
    $(document).ready(function(){
        $('input[name=marketingOffice]').click(function(){
            let v = $('input[name=marketingOffice]:checked').val();
         if(v==1){
            $("#marketing").html(`<label for="marketing_officer_id">Marketing Officer<span class="text-red"> * </span></label>
                <select required class="form-control select2" name="marketing_officer_id">
                    <option value="">Select Marketing Officer</option>
                    @foreach ($marketingOfficers as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>`)
                $(".select2").select2();
         }else{
            $("#marketing").html('')
         }
        })
    })
        function SetDMY(e) {
            // var age = calculateAge($(e).val());
            let date = $(e).val();
            $.ajax({
                type: "POST",
                url: "{{ route('member.calculateAge') }}",
                data: {
                    data: date,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    // res = res.date;
                    // console.log(res.data[0]);
                    $("#Year").val(res.data[0]);
                    $("#Month").val(res.data[1]);
                    $("#Day").val(res.data[2]);
                }
            })
        }

        function dateOfBirth() {
            let day = Number($("#Day").val());
            let month = Number($("#Month").val());
            let year = Number($("#Year").val());
            $.ajax({
                type: "POST",
                url: "{{ route('member.calculateDate') }}",
                data: {
                    day: day,
                    month: month,
                    year: year,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    // res = res.date;
                    console.log(res);
                    $("#date_of_birth").val(res.data);

                }
            })
        }


        // console.log("Age: " + age.years + " years, " + age.months + " months, and " + age.days + " days.");

        $(".date").datepicker({
            autoclose: true,
            // dateFormat: 'yy-mm-dd',
            maxDate: '0',
        });
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
            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('address', {
                toolbar: 'MA'
            });
        });
    </script>
@endpush
