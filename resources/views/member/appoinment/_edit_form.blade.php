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
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

    <style>
        #results img {

            width: 66% !important;
        }

        /* #my_camera{
                width: 250;
                height: 250;
                border: 1px solid;
            } */
    </style>
@endpush


<div class="col-md-6">

    <div class="form-group {{ $errors->has('marketing_officer_id') ? 'has-error' : '' }}">
        <label for="client">Appoinment Id</label>
        {!! Form::text('appointment_id', $appiontment_id ?? $model->appiontment_id, [
            'id' => 'marketing_officer_id',
            'placeholder' => 'Appoinment Id',
            'class' => 'form-control',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Patient Name <span class="text-red"> * </span> </label>
        {!! Form::text('patient_name', null, [
            'id' => 'patient_name',
            'class' => 'form-control',
            'placeholder' => 'Enter Patient Name',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Phone <span class="text-red"> * </span> </label>
        {!! Form::text('phone', null, [
            'id' => 'phone',
            'class' => 'form-control',
            'placeholder' => 'Phone Number',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Account Name <span class="text-red"> * </span> </label>
        {!! Form::select('cash_or_bank_id', $banks, config('settings.cash_bank_id'), [
            'class' => 'form-control select2 ',
            'required',
        ]) !!}

    </div>

    <div class="form-group">
        <label for="name">Visit Time </label>
        <select class="form-control select2" onchange="VisitingTime(this)" name="visit_time" id="visit_time">
            <option {{ @$model->visit_time == 'consult_fee' ? 'selected' : '' }} value="consult_fee">First Time</option>
            <option {{ @$model->visit_time == 'fee_old_patient' ? 'selected' : '' }} value="fee_old_patient">Second Time
            </option>
            <option {{ @$model->visit_time == 'fee_only_report' ? 'selected' : '' }} value="fee_only_report">Only Report
            </option>
        </select>

    </div>

    <div class="form-group">
        <label for="name">Address </label>
        <textarea name="address" class="form-control" rows="5" cols="5"></textarea>
    </div>

    <div class="form-group">
        <label for="name">Doctor <span class="text-red"> * </span></label>
        <select required class="form-control select2" onchange="selectDoctor()" name="doctor_id" id="doctor_id">
            <option value="">Please Select</option>
            @foreach ($doctors as $key => $value)
                <option {{ @$model->doctor_id == $value->id ? 'selected' : '' }} value="{{ $value->id }}">
                    {{ $value->name }}({{ $value->degree }})</option>
            @endforeach

        </select>
    </div>

    <div class="form-group">
        <label for="name">Schedule </label>
        {!! Form::text('schedule', null, [
            'id' => 'schedule',
            'class' => 'form-control',
            'placeholder' => 'schedule',
            'required',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Fee </label>
        {!! Form::number('fee', null, [
            'id' => 'fee',
            'class' => 'form-control',
            'placeholder' => 'Fee',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Discount </label>
        {!! Form::number('discount',   0, [
            'id' => 'discount',
            'class' => 'form-control',
            'placeholder' => 'Discount',
            'oninput' => 'discountCalculation()',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Net Amount </label>
        {!! Form::number('net_amount', null, [
            'id' => 'net_amount',
            'class' => 'form-control',
            'placeholder' => 'Net Amount',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Due </label>
        {!! Form::number('due', null, [
            'id' => 'due',
            'class' => 'form-control',
            'placeholder' => 'Due',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Paid Amount </label>
        <input class="form-control" name="paid_amount" id="paid_amount" placeholder="Paid Amount" oninput="paidCalculation()">
        {{-- {!! Form::number('paid_amount', null, [
            'id' => 'paid_amount',
            'class' => 'form-control',
            'placeholder' => 'Paid Amount',
            'oninput' => 'paidCalculation()',
        ]) !!} --}}
    </div>


    <div class="form-group">
        <label for="name">Serial No </label>
        {!! Form::number('serial_no', null, [
            'id' => 'serial_no',
            'class' => 'form-control',
            'placeholder' => 'Serial No',
            'readonly',
        ]) !!}
    </div>



    <div class="form-group">
        <label for="name">Doctor Schedule <span class="text-red"> * </span></label>

        <select required value="{{ @$model->doctor_schedule_day_id }}" class="form-control select2"
            onchange="selectSchedule(this)" name="doctor_schedule_day_id" id="doctor_schedule">

        </select>
    </div>

    <div class="form-group">
        <label for="name">Date </label>
        {!! Form::text('date', @$model->date ? month_date_year_format2($model->date) : null, [
            'id' => 'date',
            'class' => 'form-control',
            'autocomplete' => 'off',
            'onchange' => 'selectDate()',
            'required',
        ]) !!}

        </select>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <div id="my_camera"></div>
                <br />
                <input type=button class="btn btn-primary" value="Take Snapshot" onClick="take_snapshot()">
                <input type="hidden" name="image" class="image-tag">

            </div>

            <div class="col-md-6">
                @if (@$model->image)
                    <div style="margin-top: 32px" id="results">

                        <img width="" src="{{ asset('/public/uploads/appointment/' . $model->image) }}">
                    </div>
                @else
                    <div style="margin-top: 32px" id="results">Your captured image will appear here...</div>
            </div>
            @endif


        </div>



    </div>
</div>



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        let paid_amount_is
        let due_is
        let discount_is
        if(@json($model->due)){

            due_is = @json($model->due);
             paid_amount_is = @json($model->paid_amount);
             discount_is = @json($model->discount);
             console.log('yesss sa',due_is,paid_amount_is,discount_is)
             $('#due').val(due_is);
         }

        function discountCalculation() {
            console.log('aa yesss sa',due_is,paid_amount_is,discount_is)

            let discount_amount = parseInt($('#discount').val());
            let fee_amount = $('#fee').val();
            // let discount_amount = $('#discount').val();
                $('#net_amount').val(fee_amount - (discount_amount + discount_is));

                // if(due_is > 0){
                //     console.log('yesss sa',due_is);
                //     $('#due').val(due_is);
                // }else{
                // }

                $('#due').val(fee_amount - parseInt(discount_amount+discount_is+paid_amount_is));

            paidCalculation();
        }

        function paidCalculation() {

            let paid_is =0;
            let paid_amount =$('#paid_amount').val();

            let discount_amount = $('#discount').val();
            let fee_amount = $('#fee').val();

            if(!discount_amount){

                discount_amount =0;
            }
            paid_is = parseInt(paid_amount)+parseInt(discount_amount)+ parseInt(paid_amount_is) + discount_is ;
            if(isNaN(paid_is)){
                paid_is= parseInt(discount_amount)+ parseInt(paid_amount_is) + discount_is;
            }
            console.log('fee_amount,discount_amount,paid_amount,paid_is',fee_amount,discount_amount,paid_amount,paid_is)

            $('#due').val(fee_amount - (paid_is));

        }

        let doctor_data = '';
        // var dateToday = new Date();
        $('#date').datepicker({
            //   format: 'L',
            //    minDate: new Date(),
            //  "minDate": new Date(),
            // "setDate": new Date(),
            // "format": 'mm/dd/yyyy',
            // "endDate": "+0d",
            // "todayHighlight": true,
            // "autoclose": true
            "showTime": true,
            "startDate": "+0d",
        });

        // var today = moment().format('MM\DD\YYYY');
        // $('#date').datepicker('setDate', today);
        // $('#date').datepicker('update');
        // $('.date').datepicker('setDate', today);

        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });

        function VisitingTime(e) {

            let visitTime = $(e).val();
            if (visitTime == 'consult_fee') {
                $('#fee').val(doctor_data.consult_fee);

            } else if (visitTime == 'fee_old_patient') {
                $('#fee').val(doctor_data.fee_old_patient);
            } else if (visitTime == 'fee_only_report') {
                $('#fee').val(doctor_data.fee_only_report);
            }

            discountCalculation();
        }

        $(function() {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('description', {
                toolbar: 'MA'
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js"
        integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
        integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        Webcam.set({
            width: 250,
            height: 250,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#my_camera');

        function take_snapshot() {

            Webcam.snap(function(data_uri) {
                url_is = data_uri;
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img  width: 250, height: 250,  src="' + data_uri +
                    '"/>';
            });


        }
    </script>

    <script type="text/javascript">
        $(function() {
            selectDoctor()

        });

        function fetchSchedule() {
            $.ajax({
                type: "get",
                url: "{{ route('member.schedule.fetch') }}",
                data: {
                    'doctor_id': $('#doctor_id').val(),
                    'doctor_schedule_day_id': $('#doctor_schedule').val(),
                    'date': $('#date').val(),
                },
                success: function(response) {

                    doctor_data = response.data
                    if (response.status == 200) {
                        $('#serial_no').val(response.data + 1);
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function selectDate() {

            fetchSchedule()
        }

        function selectSchedule(e) {


            let val = $(e).find('option:selected').attr('data-schedule');
            $('#schedule').val(val);
            fetchSchedule()

        }

        function selectDoctor() {

            // console.log('type is', type)
            //  alert(e.value);
            let state = '<option value=""> Please Select</option>';
            let id = $('#doctor_id').val();
            let scheduleId = $('#scheduleId').val();
            let scheduleOption = '<option value ="">Please Select</option>';

            $.ajax({
                type: "get",
                url: "{{ route('member.doctors.fetch') }}",
                data: {
                    'id': id,
                },
                success: function(response) {

                    doctor_data = response.data


                    if (response.data) {
                        let visitTime = $('#visit_time').val();
                        if (visitTime == 'consult_fee') {
                            $('#fee').val(doctor_data.consult_fee);

                        } else if (visitTime == 'fee_old_patient') {
                            $('#fee').val(doctor_data.fee_old_patient);
                        } else if (visitTime == 'fee_only_report') {
                            $('#fee').val(doctor_data.fee_only_report);
                        }


                        let fee_amount = $('#fee').val();
                        let discount_amount = $('#discount').val();
                        $('#net_amount').val(fee_amount - discount_amount);
                        $('#due').val(fee_amount - discount_amount);
                        discountCalculation();
                        //    console.log('sall', visitTime);
                    }
                    if (response.schedule.schedule_day.length > 0) {
                        $.each(response.schedule.schedule_day ?? [], function(key, el) {
                            if (scheduleId && el.id == scheduleId) {
                                scheduleOption +=
                                    `<option selected data-schedule="${el.day_name} (${el.start_time}-${el.end_time})" value="${el.id}">${el.day_name} (${el.start_time}-${el.end_time})</option>`

                            } else {

                                scheduleOption +=
                                    `<option data-schedule="${el.day_name} (${el.start_time}-${el.end_time})" value="${el.id}">${el.day_name} (${el.start_time}-${el.end_time})</option>`
                            }


                        });
                    }


                    $('#doctor_schedule').html(scheduleOption)
                    if (response.status == 200) {

                        $('#consult_fee').val(response.data.consult_fee);
                        $('#fee_only_report').val(response.data.fee_only_report);
                        $('#fee_old_patient').val(response.data.fee_old_patient);
                    }


                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $('.clockpicker').clockpicker({
                twelvehour: true,
                donetext: "Done",
                autoclose: false,
            })
            .find('input').change(function() {

            });

        function addMoreTime(id) {

            $(`${id}`).append(` <div class="row">
                <div class="form-group col-md-5">

                    <input required autocomplete="off" class="form-control clockpicker"
                        name="start_time[]" placeholder="Start Time">


                    </div>

                    <div class="form-group col-md-5">

                    <input required autocomplete="off" class="form-control clockpicker"
                        name="end_time[]" placeholder="End Time">

                    </div>

            <div class="col-md-2">
                <a href="javascript:void(0)"
                  class="btn btn-icon btn-pure btn-danger btn-delete"
                  data-toggle="tooltip" data-original-title="Delete"
                  onclick="confirmDeletion(this)">
                  <i class="fa fa-times"></i>
              </a>
          </div>
        </div>
                `)

            $('.clockpicker').clockpicker({
                twelvehour: true,
                donetext: "Done",
                autoclose: false,
            })
        }

        function confirmDeletion(e) {
            e.parentElement.parentElement.remove();
        }

        function saveData(id) {

            $('.doctorId').val($('#doctor_id').val());
            $('.timePerPatient').val($('#time_per_patient').val());

            $(`${id}`).submit()
        }
    </script>
@endpush
