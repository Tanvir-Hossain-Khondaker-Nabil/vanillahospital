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
    </style>
@endpush


<div class="col-md-5">
    
    <div class="row mt-2">

        <div class="row col-md-6">
            <div class="col-md-12">
                <label for="name">IPD Patient </label>
            </div>

            <div class="col-md-6">
                <input class="form-check-input" type="radio" value="yes" name="ipd_patient" id="ipd_patient_yes">
                <label class="form-check-label" for="ipd_patient_yes">
                    Yes
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-check-input" type="radio" value="no" name="ipd_patient" id="ipd_patient_no"
                    checked>
                <label class="form-check-label" for="ipd_patient_no">
                    No
                </label>
            </div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-12">
                <label for="name">Member Patient </label>
            </div>

            <div class="col-md-6">
                <input class="form-check-input" type="radio" value="yes" name="member_patient"
                    id="member_patient_yes">
                <label class="form-check-label" for="member_patient_yes">
                    Yes
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-check-input" type="radio" value="no" name="member_patient"
                    id="member_patient_no">
                <label class="form-check-label" for="member_patient_no">
                    No
                </label>
            </div>
        </div>

    </div>

    <div class="form-group mt-1">
        <div class="row">

            <div class="col-md-6">
                <label for="name">Patient Name <span class="text-red"> * </span> </label>

                <input name="patient_name" id="patient-name" class="form-control">

            </div>

            <div class="col-md-6">
                <div class="form-group" style="margin-left: 10px">
                    <label for="name">Age </label>
                    <div class="row">
                        <div class="col-md-4 p-0">
                            <input class="form-control col-sm-4" tabindex="2" autocomplete="off" id="age_dt"
                                name="day" type="text" placeholder="D">
                        </div>
                        <div class="col-md-4 p-0">
                            <input class="form-control col-sm-4" tabindex="3" autocomplete="off"
                                onchange="month_to_year()" id="age_mn" name="month" type="text" placeholder="M">
                        </div>
                        <div class="col-md-4 p-0">
                            <input class="form-control col-sm-4" tabindex="4" autocomplete="off" id="age_yr"
                                name="year" type="text" placeholder="Y">
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

    <div class="form-group">
        <label for="name">Gender </label>
        <div class="row">
            <div class="col-md-2">
                <input class="form-check-input" type="radio" value="male" name="gender" id="male" checked>
                <label class="form-check-label" for="male">
                    Male
                </label>
            </div>
            <div class="col-md-3">
                <input class="form-check-input" type="radio" value="female" name="gender" id="female">
                <label class="form-check-label" for="female">
                    Female
                </label>
            </div>
            <div class="col-md-7">
                <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                    <label for="">Image </label>
                    <div>
                        <input type="file" name="patient_image" class="dropify" data-max-file-size="1M"
                            data-height="100" data-allowed-file-extensions="jpg jpeg png"
                            accept="image/png, image/jpg, image/jpeg" />

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="form-group">
        <label for="name">Phone <span class="text-red"> * </span> </label>
        {!! Form::text('phone', null, [
            'id' => 'phone',
            'class' => 'form-control',
            'autocomplete' => 'off',
            'placeholder' => 'Phone Number',
            'oninput' => 'checkNumber(this)',
            'required',
        ]) !!}

        <div class="w-50 search_bar_width" style="position:absolute; z-index:10" id="users-list">
        </div>
    </div>


    <div class="form-group">
        <label for="name">Blood Group</label>
        <select required class="form-control select2" name="blood_group" id="blood_group">
            <option value="">Please Select</option>
            @foreach (blood_group() as $key => $value)
                <option {{ @$model->blood_group == $value ? 'selected' : '' }} value="{{ $value }}">
                    {{ $value }} </option>
            @endforeach

        </select>
    </div>

    <div class="form-group">
        <label for="name">Address </label>
        <input name="address" id="address" class="form-control"></input>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label for="name">Dr. Name (<a href="{{ route('member.doctors.create') }}">Add New</a>)</label>
            <select required class="form-control select2" onchange="selectDoctor('doctor_img')" name="doctor_id"
                id="doctor_id">
                <option value="">Please Select</option>
                <option value="0">Self</option>
                @foreach ($doctors as $key => $value)
                    <option {{ @$model->doctor_id == $key ? 'selected' : '' }} value="{{ $key }}">
                        {{ $value }}</option>
                @endforeach

            </select>
        </div>

        <div class="col-md-6">
            <input type="file" id="doctor_img" name="image" class="dropify" data-max-file-size="1M"
                data-height="100" data-allowed-file-extensions="jpg jpeg png"
                accept="image/png, image/jpg, image/jpeg" />
        </div>


    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label for="name">Ref Dr. Name (<a href="{{ route('member.doctors.create') }}">Add New</a>)</label>
            <select required class="form-control select2" onchange="selectDoctor('ref_doctor_img')"
                name="ref_doctor_id" id="ref_doctor_id">
                <option value="">Please Select</option>
                <option selected value="0">Self</option>
                @foreach ($refer_doctor as $key => $value)
                    <option {{ @$model->ref_doctor_id == $key ? 'selected' : '' }} value="{{ $key }}">
                        {{ $value }}</option>
                @endforeach

            </select>
        </div>
        <div class="col-md-6">

            <input type="file" id="ref_doctor_img" name="image" class="dropify" data-max-file-size="1M"
                data-height="100" data-allowed-file-extensions="jpg jpeg png"
                accept="image/png, image/jpg, image/jpeg" />
        </div>
    </div>


    <div class="form-group">
        <label for="name">Date of Service </label>
        {!! Form::text('date_of_service', @$model->date ? month_date_year_format2($model->date) : null, [
            'id' => 'date_of_service',
            'class' => 'form-control',
            'autocomplete' => 'off',
            'required',
        ]) !!}

        </select>
    </div>







</div>

<div class="col-md-7">
    <div class="box-body">
        <table id="vanilla-table1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Test Category</th>
                    <th>Test Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($sub_test_groups as $key => $list)
                    <tr>
                        {{-- {{dd($list)}} --}}
                        <td>{{ ++$key }}</td>
                        <td>{{ $list->testGroup ? $list->testGroup->title : '' }}</td>
                        <td>{{ $list->title }}</td>
                        <td>{{ $list->price }}</td>
                        <td>

                            <button type="button" data-sub_test_id="{{ $list->id }}"
                                data-test_id="{{ $list->testGroup->id }}" data-test="{{ $list->title }}"
                                data-price="{{ $list->price }}" data-quk_ref_com="{{ $list->quack_ref_com }}"
                                onclick="selectTest(this)" data-test_group_id="{{ $list->test_group_id }}"
                                class="btn btn-xs btn-success add-test">
                                <i class="fa fa-plus"></i>
                            </button>

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
    <div>
        <div class="card no-b no_test_added">
            <div class="card-body">
                <div id="test_cart_details">
                    <div id="" class="col-md-12">
                        <div class="row">
                            <div class="alert alert-block alert-info ml-2">
                                <i class="ace-icon fa fa-info-circle bigger-120"></i>
                                &nbsp;No Test added in 'Test Order List'
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card no-b test-div d-none">
        <div class="card-body ">
            <div id="test_cart_details">
                <div id="" class="col-md-12">
                    <table class="table table-striped table-bordered table-hover test_cart test_table_report"
                        id="test_cart_table">
                        <thead>
                            <tr>
                                <th style="width:5%">SL</th>
                                <th style="width:15%"> Test</th>
                                <th style="width:15%">Price</th>
                                {{-- <th style="width:15%">Vat</th> --}}
                                <th style="width:15%">Disount</th>
                                <th style="width:15%">Net Amount</th>
                                <th style="width:15%">C/O</th>
                                <th style="width:15%">Sub. C/O </th>

                                <th style="width:5%"><i class="fa fa-trash-o" aria-hidden="true"></i></th>
                            </tr>
                        </thead>
                        <tbody class="mytable_style" id="cart_content_table">

                        </tbody>
                        <tfoot>
                            <tr>

                                <td colspan="2" align="right"><strong> Total (৳)</strong></td>
                                <td colspan="1"><input name="total_amount" readonly=""
                                        style="padding:2;text-align: right;" type="text" value=""
                                        id="total_amount" class="form-control col-md-10">
                                </td>

                                <td></td>
                                <td></td>
                                {{-- <td colspan="2">
                                    <select name="share_holder_type" class="form-control select2" onchange="selectHolder(this)">
                                        <option value="">Please Select</option>
                                        <option value="0">Share Holder</option>
                                        <option value="1">Management</option>
                                        <option value="3">Others</option>
                                    </select>
                                </td> --}}
                                <td><input name="total_doct_comission" readonly=""
                                        style="padding:2;text-align: left;" type="text" id="total_net_amount"
                                        class="form-control col-md-12"></td>
                                <td>
                                    <input name="sub_c_o" readonly="" style="padding:2;text-align: left;"
                                        type="text" id="sub_c_o" class="form-control col-md-12">

                                    <input type="hidden" id="totalAmount">
                                    <input type="hidden" id="totalDue">
                                </td>
                                <td></td>
                            </tr>
                            <tr>

                                <td colspan="2" align="right"></td>


                                <td colspan="1">
                                    <div class="form-check">
                                        <input onclick="selectHolder(this)" class="form-check-input" type="radio"
                                            value="2" name="share_holder_type" id="others" checked>
                                        <label class="form-check-label" for="others">
                                            Others
                                        </label>
                                    </div>

                                </td>
                                <td colspan="1" style="width: 18%">
                                    <div class="form-check">
                                        <input onclick="selectHolder(this)" class="form-check-input" value="1"
                                            type="radio" name="share_holder_type" id="management">
                                        <label class="form-check-label" for="management">
                                            Management
                                        </label>
                                    </div>
                                </td>
                                <td colspan="2">
                                    <div class="form-check">
                                        <input onclick="selectHolder(this)" class="form-check-input" value="0"
                                            type="radio" name="share_holder_type" id="share_holder">
                                        <label class="form-check-label" for="share_holder">
                                            Share Holder
                                        </label>
                                    </div>
                                </td>

                                {{-- <td></td> --}}
                            </tr>
                            <tr>
                                <td colspan="2" align="right"><strong> Discount(%)</strong> </td>
                                <td><input name="discount_percent" autocomplete="off"
                                        oninput="discountPercentAmount(this)" style="padding:2;text-align: right;"
                                        type="text" data-total="" id="discount_percent"
                                        class="form-control col-md-10">
                                </td>

                                <td colspan="1" autocomplete="off" align="right"><strong> Discount (৳)</strong>
                                </td>
                                <td><input name="discount" style="padding:2;text-align: right;"
                                        oninput="discountAmount()" type="text" data-total="" id="discount"
                                        class="form-control col-md-10">
                                </td>

                                <td autocomplete="off" align="right"><strong>Discount Ref</strong> </td>
                                <td colspan="2">



                                    <select name="share_holder_id" class="form-control d-none"
                                        id="discount_ref_option">

                                    </select>

                                </td>
                            </tr>


                            <input autocomplete="off" style="padding:0;text-align: right;" type="hidden"
                                data-total="" value="" id="vat_percent" class="form-control col-md-10">

                            <input autocomplete="off" style="padding:0;text-align: right;" type="hidden"
                                data-total="" id="vat" class="form-control col-md-10" value="">

                            <tr>
                                <td colspan="2" align="right"> <strong> Net Total (৳)</strong></td>
                                <td><input name="net_total" autocomplete="off" readonly=""
                                        style="text-align: right;" type="text" id="net_total" value=""
                                        class="form-control col-md-10">
                                </td>
                                <td colspan="5" rowspan="3">
                                    <div id="discount_ref_div">


                                            <textarea id="discount_ref" name="discount_ref" rows="7" cols="62"></textarea>
                                    </div>

                                </td>

                            </tr>
                            <tr>
                                <td colspan="2" align="right"> <strong>Total Paid (৳)</strong> </td>
                                <td><input name="total_paid" required autocomplete="off" oninput="totalPaidAmount()"
                                        style="padding:2;text-align: right;" type="text" id="total_paid"
                                        class="form-control col-md-10">
                                </td>
                                <td colspan="5"></td>

                            </tr>
                            <tr>
                                <td colspan="2" align="right"><strong> Due (৳)</strong></td>
                                <td><input name="due" autocomplete="off" readonly=""
                                        style="padding:2;text-align: right;" type="text" value=""
                                        id="due" class="form-control col-md-10">
                                </td>
                                <td colspan="5"></td>

                            </tr>

                        </tfoot>
                    </table>

                </div>
            </div>

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
        // let discountPercent = 0;
        // let discount_amount = 0;
        let discountPercent;
        let discount_amount;
        let count = 0;
        let comission = '';
        let total_amount = 0;
        let total_doct_comission = 0;
        let total_sub_c_o = 0;
        let test_array = [];

        // var date = new Date();
        $(function() {
            $("#vanilla-table1").DataTable({
                "lengthMenu": [5, 10],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });

        });

        // function discountCalculation() {
        //     let discount_amount = $('#discount').val();
        //     let fee_amount = $('#fee').val();
        //     // let discount_amount = $('#discount').val();
        //     $('#gross_total').val(fee_amount - discount_amount);

        // }

        let doctor_data = '';
        // var dateToday = new Date();
        $('#date_of_service').datepicker({
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

        var today = moment().format('MM\DD\YYYY');
        $('#date_of_service').datepicker('setDate', today);
        $('#date_of_service').datepicker('update');
        $('.date_of_service').datepicker('setDate', today);

        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });

        function discountCalculation() {

            let discount_percent = $('#discount_percent').val();

            let totalAmount = $('#totalAmount').val()
            if (discount_percent > 0) {
                $('#discount_percent').val((discount_percent / 100) * totalAmount);
            }
        }

        function checkNumber(e) {

            let number = e.value;

            console.log(number);
            $.ajax({
                type: "get",
                url: "{{ route('member.fetch.patient') }}",
                data: {
                    'phone': number,
                },
                success: function(response) {

                    if (number.length > 0) {
                        $('#users-list').removeClass('d-none');
                        $('#users-list').html(response);
                    } else {
                        $('#users-list').addClass('d-none');
                        $('#users-list').html('');
                    }
                }
            });
        }

        function selectPitent(e) {

            let id = $(e).attr('data-patient-id');
            let phone_is = $(e).attr('data-patient-phone');
            let gender = $(e).attr('data-patient-gender');
            let address_is = $(e).attr('data-address');

            if (gender == 'male') {
                $('#male').prop("checked", true)
            }

            if (gender == 'female') {
                $('#female').prop("checked", true)
            }

            // console.log('cccccccccc',gender,$(e).attr('data-patient-gender'))

            // $('#patient-name').val(id);
            $('#patient-name').val(id);
            $('#address').val(address_is);
            $('#phone').val(phone_is);
            $('#patient-name').trigger('change');
            $('#users-list').addClass('d-none');
        }

        function discountPercentAmount() {
            let dis_amount = 0;
            let amount;
            let discount_percent = $('#discount_percent').val();
            let totalAmount = $('#totalAmount').val()
            // $('#total_amount').val(totalAmount);
            $('#net_total').val(totalAmount);
            $('#due').val(totalAmount - $('#total_paid').val());
            $('#totalDue').val(totalAmount - $('#total_paid').val());
            $('#discount').val('');
            discount_amount = $('#discount').val();
            discountPercent = discount_percent;
            if (discount_percent > 0) {

                total_dis = parseInt((totalAmount * (discount_percent / 100)));
                amount = (totalAmount) - (total_dis);
                dis_amount = parseInt(amount);
                console.log('hisab', amount, dis_amount, totalAmount, discount_percent)
                $('#discount').val(total_dis);

                // $('#total_amount').val(dis_amount);
                $('#net_total').val(dis_amount);
                $('#due').val(dis_amount - $('#total_paid').val());
                $('#totalDue').val(dis_amount - $('#total_paid').val());

                discount_amount = total_dis;
                discountPercent = discount_percent;
            }
            // console.log('jjh',total_dis)
            //    $('#totalAmount').val(total_dis);
            tableValueCalculation();

        }

        function tableValueCalculation() {
            // alert('ok')
            // let sub_c_o = 0;
            let netAmount = 0;

            $('.td_discount').each(function(i) {
                let discount = $('#discount').val();

                let price = $(this).parent().prev().find('input').val();
                let c_o = $(this).parent().next().next().find('input').val()
                let totalAmount = $('#totalAmount').val()
                console.log('tableValueCalculation', totalAmount, price);

                // let table_dis = (discount / test_array.length).toFixed(2)
                let table_dis = ((price * discount) / totalAmount).toFixed(3)
                // console.log('sssalek',discount,table_dis)
                // console.log('hmm', table_dis, price, test_array.length)
                $(this).val(table_dis)
                netAmount = price - table_dis;
                let sub_c_o_vale = netAmount - c_o;
                $(this).parent().next().find('input').val(netAmount)
                $(this).parent().next().next().next().find('input').val(sub_c_o_vale)

                // console.log('sub_c_o',sub_c_o,sub_c_o_vale)
                // sub_c_o = parseFloat(sub_c_o) + parseFloat(sub_c_o_vale);


            });

            sumSubComissionAmount();
        }

        function sumSubComissionAmount() {
            total_sub_c_o = 0
            $('.td_subComission').each(function(i) {

                total_sub_c_o = total_sub_c_o + parseFloat($(this).val());
                console.log('chi', $(this).val(), total_sub_c_o)
                $('#sub_c_o').val(total_sub_c_o);

            });
        }

        function discountAmount() {
            let dis_amount = 0;
            let amount;
            let discount = $('#discount').val();
            let totalAmount = $('#totalAmount').val()

            $('#total_amount').val(totalAmount);
            $('#discount_percent').val('')
            $('#net_total').val(totalAmount);
            $('#due').val(totalAmount - $('#total_paid').val());
            $('#totalDue').val(totalAmount - $('#total_paid').val());
            if (discount > 0) {
                amount = (totalAmount) - (discount);
                dis_amount = parseInt(amount);
                // $('#total_amount').val(dis_amount);
                $('#net_total').val(dis_amount);
                $('#due').val(dis_amount - $('#total_paid').val());
                $('#totalDue').val(dis_amount - $('#total_paid').val());

            }

            tableValueCalculation();

        }

        function totalPaidAmount() {

            let totalPaid = $('#total_paid').val();
            let total_due = $('#totalDue').val()
            let due;
            due = parseInt(total_due - totalPaid)
            //  console.log('ojjj',totalPaid)
            $('#due').val(due);

        }

        function selectTest(e) {
            let test_id = $(e).attr('data-test_id');
            let sub_test_id = $(e).attr('data-sub_test_id');
            let test_group_id = $(e).attr('data-test_group_id');
            let test = $(e).attr('data-test');
            let price = $(e).attr('data-price');
            let quk_ref_com = $(e).attr('data-quk_ref_com');


            let ref_doctor_id = $('#ref_doctor_id').val();

            if (!ref_doctor_id) {
                bootbox.alert("Please select before Ref. Doctor");
                return
            } else {
                $.ajax({
                    type: "get",
                    url: "{{ route('member.check.doctor.comission') }}",
                    data: {
                        'doctor_id': ref_doctor_id,
                        'test_id': test_id,
                        'sub_test_id': sub_test_id,
                    },
                    success: function(response) {

                        let comission_is = response.data.data;
                        console.log('ok', comission_is);
                        if (comission_is) {
                            if (comission_is.comission_type == 1) {
                                comission = parseInt((price * (comission_is.amount / 100))) - parseInt((price *
                                    (comission_is.partiality / 100)))
                            } else {
                                comission = comission_is.amount - comission_is.partiality;
                            }

                        } else {
                            comission = 0
                        }
                        total_doct_comission = parseInt(total_doct_comission) + parseInt(comission);
                        total_sub_c_o = total_sub_c_o + (parseInt(price) - parseInt(comission));
                        $('#sub_c_o').val(total_sub_c_o);
                        $('#total_net_amount').val(total_doct_comission);
                    }
                });
            }
            // test_array.push(sub_test_id);
            $('.test-div').removeClass('d-none')
            $('.no_test_added').addClass('d-none')


            if (!test_array.includes(sub_test_id)) {
                test_array.push(sub_test_id);

                total_amount = parseInt(total_amount) + parseInt(price);

                $('#totalAmount').val(total_amount);
                $('#totalDue').val(total_amount);
                $('#total_amount').val(total_amount);
                $('#net_total').val(total_amount);
                $('#due').val(total_amount);
                // $('#discount_percent').val(discountPercent);
                // $('#discount').val(discount_amount)

                // console.log('discountPercent ccc', discountPercent,discount_amount)
                setTimeout(function() {
                    // console.log('com', comission)
                    $('#cart_content_table').append(`  <tr>
                                <td>${++count}</td>
                                <td align="center">${test}

                                 </td>

                                <td><input name="price[]" readonly="" type="text" style="text-align: right; padding:2;"
                                        class="form-control col-md-10"
                                        id="test_cart_price_447bb0824dac3ba7bc9dd8de09354fce" value="${price}">

                                        <input type="hidden"  name="sub_test_group_id[]" value="${sub_test_id}">
                                        <input type="hidden"  name="test_group_id[]" value="${test_group_id}">
                                </td>

                                <td><input name="td_discount[]" readonly="" style="padding:2" class="form-control td_discount">
                                    </td>
                                <td><input name="td_net_amount[]" value="${price}" readonly="" style="padding:2" class="form-control td_net_amount"></td>

                                <td><input class="form-control" value="${comission}" name="td_comission[]" readonly="" style="padding:2"></td>

                                <td><input class="form-control td_subComission" value="${price-comission}" name="td_sub_comission[]" readonly="" style="padding:2"></td>

                                <td onclick="deleteRecord(this)"  data-delete-subTest-id="${sub_test_id}"  data-delete-price="${price}" data-delete-doct_comission="${comission}" align="middle"><i class="fa fa-trash-o remove_test text-danger" aria-hidden="true"></i>
                                </td>

                        </tr>
                `)

                }, 1000);


                discountPercentAmount();
                tableValueCalculation();
            }

        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js"
        integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
        integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    <script type="text/javascript">
        // $(function() {
        //     selectDoctor()

        // });

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


        function selectDoctor(value) {

            let id;
            let drDestroy
            if (value == 'ref_doctor_img') {
                id = $('#ref_doctor_id').val();

                //   $("#cart_content_table").load(location.href + " #cart_content_table");
                //   $(".mytable_style").load(location.href + ".mytable_style");

            } else {
                id = $('#doctor_id').val();
            }


            $.ajax({
                type: "get",
                url: "{{ route('member.doctors.fetch') }}",
                data: {
                    'id': id,
                },
                success: function(response) {

                    doctor_data = response.data


                    if (response.data) {

                        let path = `/public/uploads/doctor/${doctor_data.image}`;
                        if (value == 'ref_doctor_img') {
                            drDestroy = $('#ref_doctor_img').dropify();
                            drDestroy = drDestroy.data('dropify')
                            if (drDestroy && drDestroy.isDropified()) {

                                drDestroy.destroy();
                            }
                            drDestroy.settings.defaultFile = path;
                            drDestroy.init();

                        } else {
                            drDestroy = $('#doctor_img').dropify();

                            drDestroy = drDestroy.data('dropify')
                            if (drDestroy && drDestroy.isDropified()) {

                                drDestroy.destroy();
                            }
                            drDestroy.settings.defaultFile = path;
                            drDestroy.init();
                        }



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



        function deleteRecord(e) {
            let discount_percent = $('#discount_percent').val();
            let discount = $('#discount').val();
            let doct_comission = $('#total_net_amount').val();

            let price = $(e).attr('data-delete-price');
            let delete_subTest_id = $(e).attr('data-delete-subTest-id');
            let delete_doct_comission = $(e).attr('data-delete-doct_comission');
            test_array = test_array.filter(item => item !== delete_subTest_id)
            // test_array.indexOf(delete_subTest_id);
            console.log('kiii', discountPercent, discount_amount)
            //  return;



            $('#totalAmount').val(total_amount - price);
            $('#total_amount').val(total_amount - price);
            total_amount = total_amount - price;
            $('#discount_percent').val(discount_percent);
            $('#discount').val(discount);
            $('#total_net_amount').val(doct_comission - delete_doct_comission);
            total_doct_comission = doct_comission - delete_doct_comission;
            if (test_array.length == 0) {
                $('#sub_c_o').val(0);
                $('#total_net_amount').val('');
                $('#discount_percent').val('');
                $('#discount').val('');
                $('#total_paid').val('');
            }
            e.parentElement.remove();

            discountPercentAmount();
            tableValueCalculation();

            discountPercent = $('#discount_percent').val();
            discount_amount = $('#discount').val()
        }

        function saveData(id) {

            $('.doctorId').val($('#doctor_id').val());
            $('.timePerPatient').val($('#time_per_patient').val());

            $(`${id}`).submit()
        }

        function selectHolder(e) {

            let option = '<option value="">Please Select</option>'
            console.log('ids is', e.value)
            let val_is = e.value;



            if (val_is != 2) {

                $.ajax({
                    type: "get",
                    url: "{{ route('member.share_holder.fetch') }}",
                    data: {
                        'val': val_is,
                    },
                    success: function(response) {


                        if (response.data.length > 0) {

                            $.each(response.data, function(key, value) {

                                option +=
                                    `<option value="${value.id}">${value.name}</option>`;

                            });

                            $('#discount_ref_option').removeClass('d-none');

                            $('#discount_ref_option').html(option);

                        }


                    },
                    error: function(response) {
                        console.log(response);
                    }
                });

            } else {

                $('#discount_ref_option').addClass('d-none');


            }

            if (val_is == 0) {

                $('#others').prop('checked', false);
                $('#management').prop('checked', false);

                $('#discount_ref_option').prop('required', true);
            }

            if (val_is == 1) {

                $('#others').prop('checked', false);
                $('#share_holder').prop('checked', false);

                $('#discount_ref_option').prop('required', true);
            }

            if (val_is == 2) {

                $('#management').prop('checked', false);
                $('#share_holder').prop('checked', false);
                $('#discount_ref_option').prop('required', false);
            }

        }

    </script>
@endpush
