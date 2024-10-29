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


    <div class="form-group {{ $errors->has('marketing_officer_id') ? 'has-error' : '' }}">
        <label for="client">Doctor Name</label>
        {!! Form::select('doctor_id', $doctor, null, [
            'id' => 'doctor_id',
            'placeholder' => 'Select Doctor',
            'class' => 'form-control select2',
            'onchange' => 'selectDoctor(this)',
            'required',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('marketing_officer_id') ? 'has-error' : '' }}">
        <label for="client">Add Day</label>

        <select required class="form-control select2" multiple name="day[]">
            @foreach (get_daysOfWeek() as $key => $val)
            {{-- {{dd($val)}} --}}
                <option {{in_array($val, $days_is->toArray()) ? 'selected' : ""}} value="{{ $val }}">{{ $val }}</option>
            @endforeach

        </select>

    </div>



    <div class="form-group">
        <label for="name">Time Per Patient</label>
        {!! Form::text('time_per_patient', null, [
            'id' => 'time_per_patient',
            'class' => 'form-control',
            'placeholder' => 'Time Per Patient',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Consult Fee </label>
        {!! Form::text('consult_fee', null, [
            'id' => 'consult_fee',
            'class' => 'form-control',
            'type' => 'number',
            'placeholder' => 'Doctor Consult Fee',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Doctor Fee (Old Patient) </label>
        {!! Form::text('fee_old_patient', null, [
            'id' => 'fee_old_patient',
            'class' => 'form-control',
            'type' => 'number',
            'placeholder' => 'Doctor Fee (Old Patient)',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Doctor Fee (Only Report) </label>
        {!! Form::text('fee_only_report', null, [
            'id' => 'fee_only_report',
            'class' => 'form-control readonly',
            'type' => 'number',
            'placeholder' => 'Doctor Fee (Only Report)',
            'readonly',
        ]) !!}
    </div>

    <div  id="add_more_time">
        <div class="row">
            <div class="form-group col-md-5">
                <label for="name">Start Time </label>
                {!! Form::text('start_time[]', null, [
                    'id' => 'address',
                    'class' => 'form-control clockpicker',
                    'type' => 'time',
                    'placeholder' => 'Start Time',
                    'required',
                ]) !!}
            </div>

            <div class="form-group col-md-5">
                <label for="name">End Time </label>
                {!! Form::text('end_time[]', null, [
                    'id' => 'address',
                    'class' => 'form-control clockpicker',
                    'type' => 'time',
                    'placeholder' => 'End Time',
                    'required',
                ]) !!}
            </div>
        </div>


    </div>

    <div class="form-group col-md-2">
        <button type="button" onclick="addMoreTime(this)" class="btn btn-success">+Add New</button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js"
        integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">

            $(function(){
                selectDoctor()
            });
        function selectDoctor() {
            // console.log('type is', type)
            //  alert(e.value);
            let state = '<option value=""> Please Select</option>';
            let id = $('#doctor_id').val();
            $.ajax({
                type: "get",
                url: "{{ route('member.doctors.fetch') }}",
                data: {
                    'id': id,
                },
                success: function(data) {
                    //  console.log('sall',data.data);
                    if (data.status == 200) {

                        $('#consult_fee').val(data.data.consult_fee);
                        $('#fee_only_report').val(data.data.fee_only_report);
                        $('#fee_old_patient').val(data.data.fee_old_patient);
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

        function addMoreTime(e) {

            $('#add_more_time').append(` <div class="row">
            <div class="form-group col-md-5">
                <label for="name">Start Time </label>
                {!! Form::text('start_time[]', null, [
                    'id' => 'address',
                    'class' => 'form-control clockpicker',
                    'type' => 'time',
                    'placeholder' => 'Start Time',
                    'required',
                ]) !!}
            </div>

            <div class="form-group col-md-5">
                <label for="name">End Time </label>
                {!! Form::text('end_time[]', null, [
                    'id' => 'address',
                    'class' => 'form-control clockpicker',
                    'type' => 'time',
                    'placeholder' => 'End Time',
                    'required',
                ]) !!}
            </div>
            <div class="col-md-2 mt-5">
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
    </script>
@endpush
