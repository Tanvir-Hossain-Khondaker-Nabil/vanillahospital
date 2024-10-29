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
@endpush


<div class="col-md-7">


    <div class="form-group {{ $errors->has('marketing_officer_id') ? 'has-error' : '' }}">
        <label for="client">Doctor Name</label>

        <select onchange="selectDoctor(this)" required class="form-control select2" id="doctor_id" name="doctor_id">
            <option value="">Select Doctor</option>
            @foreach ($doctor as $key => $val)
                <option value="{{ $key }}">{{ $val }}</option>
            @endforeach

        </select>

    </div>



    <div class="form-group">
        <label for="name">Time Per Patient</label>

        <input required class="form-control" name="time_per_patient" id="time_per_patient">


    </div>

    <div class="form-group">
        <label for="name">Consult Fee </label>
        <input readonly class="form-control" name="consult_fee" id="consult_fee">


    </div>

    <div class="form-group">
        <label for="name">Doctor Fee (Old Patient) </label>
        <input readonly class="form-control" name="fee_old_patient" id="fee_old_patient">


    </div>

    <div class="form-group">
        <label for="name">Doctor Fee (Only Report) </label>

        <input readonly class="form-control" name="fee_only_report" id="fee_only_report">


    </div>
    <div class="form-group">
        <div class="form-component-container">
            <div class="panel panel-default form component main">
                <div class="panel-heading">
                    <ul id="rowTab" class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#saturday">Saturday</a></li>
                        <li><a data-toggle="tab" href="#sunday">Sunday</a></li>
                        <li><a data-toggle="tab" href="#monday">Monday</a></li>
                        <li><a data-toggle="tab" href="#tuesday">Tuesday</a></li>
                        <li><a data-toggle="tab" href="#wednesday">Wednesday</a></li>
                        <li><a data-toggle="tab" href="#thursday">Thursday</a></li>
                        <li><a data-toggle="tab" href="#friday">Friday</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="saturday" class="tab-pane fade active in py-5">
                            <form id="saturday_form" action="{{ route('member.doctor_schedule.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="saturday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_saturday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2">

                                            <button type="button" onclick="addMoreTime('#add_more_saturday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#saturday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>


                        </div>
                        <div id="sunday" class="tab-pane fade py-5">
                            <form id="sunday_form" action="{{ route('member.doctor_schedule.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="sunday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_sunday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2">

                                            <button type="button" onclick="addMoreTime('#add_more_sunday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#sunday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>
                        </div>
                        <div id="monday" class="tab-pane fade  py-5">
                            <form id="monday_form" action="{{ route('member.doctor_schedule.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="monday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_monday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2">

                                            <button type="button" onclick="addMoreTime('#add_more_monday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#monday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>
                        </div>
                        <div id="tuesday" class="tab-pane fade py-5">
                            <form id="tuesday_form" action="{{ route('member.doctor_schedule.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="tuesday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_tuesday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2">

                                            <button type="button" onclick="addMoreTime('#add_more_tuesday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#tuesday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>
                        </div>
                        <div id="wednesday" class="tab-pane fade py-5">
                            <form id="wednesday_form" action="{{ route('member.doctor_schedule.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="wednesday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_wednesday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2">

                                            <button type="button" onclick="addMoreTime('#add_more_wednesday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#wednesday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>
                        </div>
                        <div id="thursday" class="tab-pane fade py-5">
                            <form id="thursday_form" action="{{ route('member.doctor_schedule.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="thursday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_thursday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2">

                                            <button type="button" onclick="addMoreTime('#add_more_thursday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#thursday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>
                        </div>
                        <div id="friday" class="tab-pane fade py-5">
                            <form id="friday_form" action="{{ route('member.doctor_schedule.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="day_name" value="friday">
                                <input type="hidden" class="doctorId" name="doctor_id" value="">
                                <input type="hidden" class="timePerPatient" name="time_per_patient" value="">
                                <div id="add_more_friday">
                                    <div class="row">
                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="start_time[]" placeholder="Start Time">


                                        </div>

                                        <div class="form-group col-md-5">

                                            <input required autocomplete="off" class="form-control clockpicker"
                                                name="end_time[]" placeholder="End Time">

                                        </div>
                                        <div class="form-group col-md-2 ">

                                            <button type="button" onclick="addMoreTime('#add_more_friday')"
                                                class="btn btn-success">+Add</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-left">
                                    <button type="button" onclick="saveData('#friday_form')"
                                        class="btn btn-success ">Save</button>

                                </div>
                            </form>


                        </div>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    <!-- Date range picker -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js"
    integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer">

</script>

    <script type="text/javascript">
        // var date = new Date();
        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });


    </script>



    <script type="text/javascript">
        $(function() {
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
                success: function(response) {

                    console.log('sall', response.schedule);
                    if (response.schedule.schedule_day.length > 0) {
                        $.each(response.schedule.schedule_day ?? [], function(key, value) {

                            // if(value && key==0){
                            //     $(`#add_more_${value.day_name}`).append(`
                            //             <div class="row">
                            //             <div class="form-group col-md-5">

                            //             <input autocomplete="off" value=${value.start_time } required placeholder="Start Time" class="form-control clockpicker" name="start_time[]">


                            //             </div>

                            //             <div class="form-group col-md-5">

                            //             <input autocomplete="off" value=${value.end_time } required placeholder="End Time" class="form-control clockpicker" name="end_time[]">

                            //             </div>

                            //             <div class="form-group col-md-2 ">

                            //             <button type="button" onclick="addMoreTime('#add_more_${value.day_name}')"
                            //                 class="btn btn-success">+Add</button>
                            //             </div>
                            //             </div>

                            //             `)
                            // }else{
                                $(`#add_more_${value.day_name}`).append(`
                                        <div class="row">
                                        <div class="form-group col-md-5">

                                        <input autocomplete="off" value=${value.start_time } required placeholder="Start Time" class="form-control clockpicker" name="start_time[]">


                                        </div>

                                        <div class="form-group col-md-5">

                                        <input autocomplete="off" value=${value.end_time } required placeholder="End Time" class="form-control clockpicker" name="end_time[]">

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
                            // }

                            $('.clockpicker').clockpicker({
                                twelvehour: true,
                                donetext: "Done",
                                autoclose: false,
                            })

                        });
                    } else {
                        $(".form-component-container").load(location.href + " .form-component-container");
                    }

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
