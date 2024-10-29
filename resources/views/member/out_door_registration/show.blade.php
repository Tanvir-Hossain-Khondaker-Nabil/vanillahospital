<html lang="en">

<head>
    <base href="https://hospitalerp.rcreation-bd.com/">

    <meta charset="utf-8">
    <title>
        Opd Individual Billing Details </title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="back_assets/money_receipt/css/google_api.css" rel="stylesheet">

    <style>
        body {
            height: 700px !important;
            width: 500px !important;

            /* to centre page on screen*/
            margin: 1px 0px;
            margin-left: auto;
            margin-right: auto;
            font-family: serif;
        }

        .farhana-table-1-col-1 {
            vertical-align: top;
        }

        .first-h1 {
            font-size: 18px;
            color: #111111;
            text-align: center;
            font-weight: 600;
        }

        .first-p {
            font-size: 15px;
            color: #111111;
            text-align: center;
            margin-top: -13px;

        }

        .first-p-1 {
            font-size: 15px;
            color: #111111 !important;
            text-align: center;
            margin-top: -10px;
            font-family: 'BenchNine', sans-serif;

        }

        .farhana-table-2 {
            width: 100%;
        }

        .table-1-col-1 {
            width: 33%;
            text-align: center;
        }

        .table-1-col-1 p {

            font-weight: bold;
            text-align: center;
            font-size: 16px;
            text-decoration: underline;
        }

        .farhana-table-3 {
            margin: 0 auto;
            width: 90%;
            margin-top: 10px;

        }

        .farhana-table-3 tr td {
            font-size: 12px;

        }

        .doctor-name {
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .farhana-table-4 {

            width: 90%;
            margin: 0 auto;
            margin-top: 10px;
            border-collapse: collapse;
            border: 1px solid #111111;
            font-size: 12px;
        }

        .farhana-table-4 tr th {
            border: 1px solid #111111;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
        }

        .farhana-table-4 tr th:nth-child(2) {
            text-align: left;
            width: 55%;
        }

        .farhana-table-4 tr td:nth-child(2) {
            text-align: left;
            width: 55%;
        }

        .farhana-table-4 tr td {
            border: 1px solid #111111;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
        }

        .farhana-table-5 {
            margin-top: 10px;
            width: 95%;
            margin-left: 8px;

        }

        .farhana-table-6 {
            margin-top: 40px;
            width: 95%;
            margin: 0 auto;

        }

        .farhana-table-5 tr td:nth-child(2) {
            width: 25% !important;

        }

        .farhana-table-5 tr td:last-child {
            width: 25% !important;

        }


        .farhana-table-4-col-1 {
            width: 10%;
        }

        .farhana-table-4-col-2 {
            width: 50%;
        }

        .farhana-table-4-col-3 {
            width: 22%;
        }

        .farhana-table-5 tr td {

            font-size: 12px;

        }

        .tranform-text {
            font-size: 35px !important;
            font-weight: bold;
            transform: rotate(-30deg);
            text-align: center;
            vertical-align: middle;
            width: 57%;
        }

        .unit-class {
            font-size: 12px;
            padding: 0px 0px;
        }

        .delivery {
            font-size: 10px;
        }

        .last-p {
            padding: 5px;
            font-size: 10px;
            border: 1px solid #111111;
            border-radius: 13px;
            width: 163px;
            margin: 5px 0px;
        }

        .print {
            font-size: 9px;
        }

        .authorize {
            font-size: 10px;
            text-decoration: overline;
            text-align: right;
        }

        .first-p-1 {
            font-size: 20px;
            color: #111111 !important;
            text-align: center;
            margin-top: -10px;
            font-family: 'BenchNine', sans-serif;

        }

        .first-p-11 {
            font-size: 12px;
            color: #111111 !important;
            text-align: center;
            margin-top: -10px;
            font-family: 'BenchNine', sans-serif;

        }
    </style>

</head>


<body style="color:#000; text-align: center;">

    <input type="hidden" id="hidden_patient_id" value="5331" name="hidden_patient_id">

    <input type="hidden" id="hidden_order_id" value="9383" name="hidden_order_id">
    <div style="height:940px;">
        <div style="  margin: 0 auto;" class="container">

            <div class="row">

                <table class="farhana-table-1">
                    <tbody>
                        <tr>
                            <td class="farhana-table-1-col-1">

                                @if(@$company_logo)
                                <img height="60px" width="60px" src="{{ $company_logo }}">
                                @endif
                            </td>
                            <td>
                                <h1 class=""
                                    style="margin-top: 2px; font-size: 20px; text-align: center;margin-left: 5px;">
                                    {{ $company_name }} </h1>

                                <p class="first-p-11">আপনার সু-চিকিৎসাই আমাদের ঐক্যবদ্ধ প্রচেষ্টা </p>
                                <!--    <h1 style="margin-top: 0px; font-size: 16px; text-align: center;margin-left: 5px;">
              Banskhali Maa-Shishu O General Hospital Ltd.          </h1> -->

                                <p class="first-p">{{ $company_address }}
                                </p>
                                <!--  <p class="first-p-1">Ramdas Munsir hat , P.O: Gunagari, P.S: Banskhali, Dist: Chattogram.</p> -->
                                <p class="first-p-1">Mobile Number : {{ $company_phone }}</p>
                            </td>

                        </tr>
                    </tbody>
                </table>


            </div>




            <table class="farhana-table-2">
                <tbody>
                    <tr>
                        <td class="table-1-col-1"></td>
                        <td class="table-1-col-1">
                            <p>CASH RECEIPT</p>
                        </td>
                        <td class="table-1-col-1"></td>
                    </tr>
                </tbody>
            </table>



            <table class="farhana-table-3">
                <tbody>
                    <tr>
                        <td colspan="2"><b>Bill No: </b><label id="invoice">{{$data->opd_id}}</label></td>
                        <td><b>Date: </b><label id="date_time">4/6/2024 9:56:04 AM</label></td>
                    </tr>
                    <tr>
                        <!--<td colspan="2"><b>Patient Name: </b><label id="patient_name"></td> -->
                        <td><b>Sex: </b><label id="gender">{{ $data->gender }}</label></td>
                        <td class="text-center"><b>Age: </b><label id="age">{{ $data->age }}</label></td>
                        <td><b>Mob: </b><label id="phone_no">{{ $data->phone }}</label></td>
                    </tr>
                    <tr>
                        <!--<td style="text-align: left"><b>Sex: </b><label id="gender"></label></td> -->
                        <td style="text-align: left"><b>Patient Name: </b><label
                                id="patient_name">{{ $data->patient_name }}</label></td>
                        <td><b>Patient Address: </b><label id="patient_address">{{ $data->address }}</label></td>

                    </tr>

                    <tr>

                            @if ($data->doctor_id == 0)
                            <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label> Self</label></td>
                            @else
                            <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label
                                id="ref_by">{{ $data->doctor ? $data->doctor->name : '' }}
                                ({{ $data->doctor ? $data->doctor->degree : '' }}) </label></td>
                            @endif

                    </tr>
                    <tr>
                        @if ($data->ref_doctor_id == 0)
                        <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label> Self</label></td>
                        @else
                        <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label
                            id="quack_by">{{ $data->refDoctor ? $data->refDoctor->name : '' }}
                            ({{ $data->refDoctor ? $data->refDoctor->degree : '' }})</label></td>
                        @endif


                    </tr>


                </tbody>
            </table>

            <table class="farhana-table-4">
                <tbody>
                    <tr>
                        <th class="farhana-table-4-col-1">
                            SL
                        </th>
                        <th class="farhana-table-4-col-2">
                            Name Of Investigation
                        </th>
                        <th class="farhana-table-4-col-3">
                            Amount
                        </th>
                        @if ($show_room_status == 1)
                        <th class="farhana-table-4-col-3">
                            Room No
                        </th>
                        @endif

                    </tr>

                </tbody>
                <tbody id="patient_ordered_test_table">

                    @foreach ($data->outdoorPatientTest?? [] as $key=> $list)
                    <tr>
                        <td class="farhana-table-4-col-1">{{++$key}}</td>
                        @if ($list->subTestGroup->testGroup)
                        <td class="farhana-table-4-col-2">{{$list->subTestGroup->testGroup->title}} ({{$list->subTestGroup->title}})</td>
                        @else
                        <td class="farhana-table-4-col-2"></td>
                        @endif
                        <td class="farhana-table-4-col-3">{{$list->price}}</td>
                        @if ($show_room_status == 1)
                        <td class="farhana-table-4-col-3">{{$list->subTestGroup->room_no}}</td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>


                <!--    <tr>
      <td class="farhana-table-4-col-1">
        1
      </td>
      <td class="farhana-table-4-col-2">S Creatinine</td>
      <td class="farhana-table-4-col-3">350.00</td>
    </tr> -->

            </table>


            <div class="static-data">

                <div style="padding-bottom:5px" class="row">



                    <table class="farhana-table-5">
                        <tbody>
                            <tr>
                                <td rowspan="6" class="tranform-text"><span style="opacity:0.5;"><label
                                            id="payment_status">Paid (Office Copy)</label></span></td>
                                <td><b>Total Amount </b></td>
                                <td> :<label id="total_amount">{{$data->total_amount}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Service Charge(+)</b></td>
                                <td> :<label id="vat">0.00</label></td>


                            </tr>
                            <tr>

                                <td><b>Dis(-) </b></td>
                                <td> :<label id="total_discount">{{$data->discount?? 0.00}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Net Amount </b></td>
                                <td> :<label id="net_total">{{$data->net_amount?? $data->total_amount}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Received Amount </b></td>
                                <td> :<label id="paid_amount">{{$data->total_paid}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Due Amount </b></td>
                                <td> :<label id="due_amnt">{{$data->due}}</label></td>

                            </tr>


                        </tbody>
                    </table>

                </div>

            </div>

        </div>

        <footer class="footer">

            <table class="farhana-table-6">
                <tbody>
                    <tr>
                        <td colspan="2" class="unit-class">Go to Unit:</td>

                    </tr>
                    <tr>
                        <td class="delivery">Delivery Date &amp; Time:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="last-p">Undelivered report with be held 1 month
                                <br>please colllect report on due time
                            </p>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="print">Print Date: {{date("F j, Y, g:i a")}}  <br> Developed By:
                            R-creation (01813316786)
                        </td>
                        <td class="authorize">Authorize Sig: <label id="booked_by">admin</label></td>
                    </tr>
                </tbody>
            </table>

        </footer>
    </div>
    <!-- </div> -->

    <!----Below for Print this Page---->

    <div style="width: 100%;border:dotted 1px #ccc;margin: 15px 0px 5px 0px"></div>
    <div style="height:940px;">
        <div style="margin: 0 auto;" class="container">

            <div class="row">

                <table class="farhana-table-1">
                    <tbody>
                        <tr>
                            <td class="farhana-table-1-col-1">
                                @if(@$company_logo)
                                <img height="60px" width="60px" src="{{ $company_logo }}">
                                @endif
                            </td>
                            <td>
                                <h1 class=""
                                    style="margin-top: 2px; font-size: 20px; text-align: center;margin-left: 5px;">
                                    {{ $company_name }} </h1>

                                <p class="first-p-11">আপনার সু-চিকিৎসাই আমাদের ঐক্যবদ্ধ প্রচেষ্টা </p>
                                <!--    <h1 style="margin-top: 0px; font-size: 16px; text-align: center;margin-left: 5px;">
              Banskhali Maa-Shishu O General Hospital Ltd.          </h1> -->

                                <p class="first-p">{{ $company_address }}
                                </p>
                                <!--  <p class="first-p-1">Ramdas Munsir hat , P.O: Gunagari, P.S: Banskhali, Dist: Chattogram.</p> -->
                                <p class="first-p-1">Mobile Number : {{ $company_phone }}</p>
                            </td>

                        </tr>
                    </tbody>
                </table>


            </div>




            <table class="farhana-table-2">
                <tbody>
                    <tr>
                        <td class="table-1-col-1"></td>
                        <td class="table-1-col-1">
                            <p>CASH RECEIPT</p>
                        </td>
                        <td class="table-1-col-1"></td>
                    </tr>
                </tbody>
            </table>



            <table class="farhana-table-3">
                <tbody>
                    <tr>
                        <td colspan="2"><b>Bill No: </b><label id="invoiced">{{$data->opd_id}}</label></td>
                        <td><b>Date: </b><label id="date_timed">{{date("F j, Y, g:i a")}}</label></td>
                    </tr>
                    <tr>
                        <td><b>Sex: </b><label id="gender">{{ $data->gender }}</label></td>
                        <td class="text-center"><b>Age: </b><label id="age">{{ $data->age }}</label></td>
                        <td><b>Mob: </b><label id="phone_no">{{ $data->phone }}</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>Patient Name: </b><label
                            id="patient_name">{{ $data->patient_name }}</label></td>
                         <td><b>Patient Address: </b><label id="patient_address">{{ $data->address }}</label></td>
                    </tr>
                    <tr>

                        @if ($data->doctor_id == 0)
                        <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label> Self</label></td>
                        @else
                        <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label
                            id="ref_by">{{ $data->doctor ? $data->doctor->name : '' }}
                            ({{ $data->doctor ? $data->doctor->degree : '' }}) </label></td>
                        @endif

                </tr>
                <tr>
                    @if ($data->ref_doctor_id == 0)
                    <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label> Self</label></td>
                    @else
                    <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label
                        id="quack_by">{{ $data->refDoctor ? $data->refDoctor->name : '' }}
                        ({{ $data->refDoctor ? $data->refDoctor->degree : '' }})</label></td>
                    @endif

                </tr>


                </tbody>
            </table>

            <table class="farhana-table-4">
                <tbody>
                    <tr>
                        <th class="farhana-table-4-col-1">
                            SL
                        </th>
                        <th class="farhana-table-4-col-2">
                            Name Of Investigation
                        </th>
                        <th class="farhana-table-4-col-3">
                            Amount
                        </th>
                        @if ($show_room_status == 1)
                        <th class="farhana-table-4-col-3">
                            Room No
                        </th>
                        @endif
                    </tr>

                </tbody>
                <tbody id="patient_ordered_test_table_duplicate">

                    @foreach ($data->outdoorPatientTest?? [] as $key=> $list)
                    <tr>
                        <td class="farhana-table-4-col-1">{{++$key}}</td>
                        @if ($list->subTestGroup->testGroup)
                        <td class="farhana-table-4-col-2">{{$list->subTestGroup->testGroup->title}} ({{$list->subTestGroup->title}})</td>
                        @else
                        <td class="farhana-table-4-col-2"></td>
                        @endif
                        <td class="farhana-table-4-col-3">{{$list->price}}</td>

                        @if ($show_room_status == 1)
                        <td class="farhana-table-4-col-3">{{$list->subTestGroup->room_no}}</td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>



            </table>


            <div class="static-data">

                <div style=" padding-bottom:5px" class="row">



                    <table class="farhana-table-5">
                        <tbody>
                            <tr>
                                <td rowspan="6" class="tranform-text"><span style="opacity:0.5;"><label
                                            id="payment_statusd">Paid (Patient Copy)</label></span></td>
                                <td><b>Total Amount </b></td>
                                <td> :<label id="total_amount">{{$data->total_amount}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Service Charge(+)</b></td>
                                <td> :<label id="vatd">0.00</label></td>


                            </tr>
                            <tr>

                                <td><b>Dis(-) </b></td>
                                <td> :<label id="total_discount">{{$data->discount?? 0.00}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Net Amount </b></td>
                                <td> :<label id="net_total">{{$data->net_amount?? $data->total_amount}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Received Amount </b></td>
                                <td> :<label id="paid_amount">{{$data->total_paid}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Due Amount </b></td>
                                <td> :<label id="due_amnt">{{$data->due}}</label></td>


                            </tr>


                        </tbody>
                    </table>

                </div>

            </div>

        </div>

        <footer class="footer">

            <table class="farhana-table-6">
                <tbody>
                    <tr>
                        <td colspan="2" class="unit-class">Go to Unit:</td>

                    </tr>
                    <tr>
                        <td class="delivery">Delivery Date &amp; Time:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="last-p">Undelivered report with be held 1 month
                                <br>please colllect report on due time
                            </p>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="print">Print Date: {{date("F j, Y, g:i a")}} <br> Developed By:
                            R-creation (01813316786)
                        </td>
                        <td class="authorize">Authorize Sig: <label id="booked_by"></label></td>
                    </tr>
                </tbody>
            </table>

        </footer>
    </div>

    <!---Below for Print lab Copy----->
    <div style="width: 100%;border:dotted 1px #ccc;margin: 15px 0px 5px 0px"></div>
    <div style="height:940px;">
        <div style="margin: 0 auto;" class="container">

            <div class="row">

                <table class="farhana-table-1">
                    <tbody>
                        <tr>
                            <td class="farhana-table-1-col-1">
                                @if(@$company_logo)
                                <img height="60px" width="60px" src="{{ $company_logo }}">
                                @endif
                            </td>
                            <td>
                                <h1 class=""
                                    style="margin-top: 2px; font-size: 20px; text-align: center;margin-left: 5px;">
                                    {{ $company_name }} </h1>

                                <p class="first-p-11">আপনার সু-চিকিৎসাই আমাদের ঐক্যবদ্ধ প্রচেষ্টা </p>
                                <!--    <h1 style="margin-top: 0px; font-size: 16px; text-align: center;margin-left: 5px;">
              Banskhali Maa-Shishu O General Hospital Ltd.          </h1> -->

                                <p class="first-p">{{ $company_address }}
                                </p>
                                <!--  <p class="first-p-1">Ramdas Munsir hat , P.O: Gunagari, P.S: Banskhali, Dist: Chattogram.</p> -->
                                <p class="first-p-1">Mobile Number : {{ $company_phone }}</p>
                            </td>

                        </tr>
                    </tbody>
                </table>


            </div>




            <table class="farhana-table-2">
                <tbody>
                    <tr>
                        <td class="table-1-col-1"></td>
                        <td class="table-1-col-1">
                            <p>CASH RECEIPT</p>
                        </td>
                        <td class="table-1-col-1"></td>
                    </tr>
                </tbody>
            </table>



            <table class="farhana-table-3">
                <tbody>
                    <tr>
                        <td colspan="2"><b>Bill No: </b><label id="invoiced">{{$data->opd_id}}</label></td>
                        <td><b>Date: </b><label id="date_timed">{{date("F j, Y, g:i a")}}</label></td>
                    </tr>
                    <tr>
                        <td><b>Sex: </b><label id="gender">{{ $data->gender }}</label></td>
                        <td class="text-center"><b>Age: </b><label id="age">{{ $data->age }}</label></td>
                        <td><b>Mob: </b><label id="phone_no">{{ $data->phone }}</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>Patient Name: </b><label
                            id="patient_name">{{ $data->patient_name }}</label></td>
                         <td><b>Patient Address: </b><label id="patient_address">{{ $data->address }}</label></td>
                    </tr>
                    <tr>

                        @if ($data->doctor_id == 0)
                        <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label> Self</label></td>
                        @else
                        <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label
                            id="ref_by">{{ $data->doctor ? $data->doctor->name : '' }}
                            ({{ $data->doctor ? $data->doctor->degree : '' }}) </label></td>
                        @endif

                </tr>
                <tr>
                    @if ($data->ref_doctor_id == 0)
                    <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label> Self</label></td>
                    @else
                    <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label
                        id="quack_by">{{ $data->refDoctor ? $data->refDoctor->name : '' }}
                        ({{ $data->refDoctor ? $data->refDoctor->degree : '' }})</label></td>
                    @endif

                </tr>


                </tbody>
            </table>

            <table class="farhana-table-4">
                <tbody>
                    <tr>
                        <th class="farhana-table-4-col-1">
                            SL
                        </th>
                        <th class="farhana-table-4-col-2">
                            Name Of Investigation
                        </th>
                        <th class="farhana-table-4-col-3">
                            Amount
                        </th>
                        @if ($show_room_status == 1)
                        <th class="farhana-table-4-col-3">
                            Room No
                        </th>
                        @endif
                    </tr>

                </tbody>
                <tbody id="patient_ordered_test_table_duplicate_lab">

                    @foreach ($data->outdoorPatientTest?? [] as $key=> $list)
                    <tr>
                        <td class="farhana-table-4-col-1">{{++$key}}</td>
                        @if ($list->subTestGroup->testGroup)
                        <td class="farhana-table-4-col-2">{{$list->subTestGroup->testGroup->title}} ({{$list->subTestGroup->title}})</td>
                        @else
                        <td class="farhana-table-4-col-2"></td>
                        @endif
                        <td class="farhana-table-4-col-3">{{$list->price}}</td>

                        @if ($show_room_status == 1)
                        <td class="farhana-table-4-col-3">{{$list->subTestGroup->room_no}}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>




            </table>

            <div class="static-data">

                <div style=" padding-bottom:5px" class="row">



                    <table class="farhana-table-5">
                        <tbody>
                            <tr>
                                <td rowspan="6" class="tranform-text"><span style="opacity:0.5;"><label
                                            id="payment_statuslab">Paid (Lab Copy)</label></span></td>
                                <td><b>Total Amount </b></td>
                                <td> :<label id="total_amount">{{$data->total_amount}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Service Charge(+)</b></td>
                                <td> :<label id="vatlab">0.00</label></td>


                            </tr>
                            <tr>

                                <td><b>Dis(-) </b></td>
                                <td> :<label id="total_discount">{{$data->discount?? 0.00}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Net Amount </b></td>
                                <td> :<label id="net_total">{{$data->net_amount?? $data->total_amount}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Received Amount </b></td>
                                <td> :<label id="paid_amount">{{$data->total_paid}}</label></td>


                            </tr>
                            <tr>

                                <td><b>Due Amount </b></td>
                                <td> :<label id="due_amnt">{{$data->due}}</label></td>


                            </tr>


                        </tbody>
                    </table>

                </div>

            </div>

        </div>

        <footer class="footer">

            <table class="farhana-table-6">
                <tbody>
                    <tr>
                        <td colspan="2" class="unit-class">Go to Unit:</td>

                    </tr>
                    <tr>
                        <td class="delivery">Delivery Date &amp; Time:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="last-p">Undelivered report with be held 1 month
                                <br>please colllect report on due time
                            </p>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="print">Print Date: {{date("F j, Y, g:i a")}} <br> Developed By:
                            R-creation (01813316786)
                        </td>
                        <td class="authorize">Authorize Sig: <label id="booked_by"></label></td>
                    </tr>
                </tbody>
            </table>

        </footer>
    </div>


    <!--=======  SCRIPTS =======-->
    <script src="back_assets/money_receipt/js/jquery.min.js"></script>
    <script src="back_assets/money_receipt/js/popper.min.js"></script>
    <script src="back_assets/money_receipt/js/bootstrap.min.js"></script>



    <script type="text/javascript">
        $(document).ready(function() {

            var patient_id = $('#hidden_patient_id').val();
            var order_id = $('#hidden_order_id').val();


            $.ajax({
                url: "https://hospitalerp.rcreation-bd.com/admin/get_patient_ordered_test_info",
                method: "POST",
                dataType: "json",
                data: {
                    patient_id: patient_id,
                    order_id: order_id
                },
                success: function(data) {

                    if (data['ipd_info'] != "") {
                        $('#ipd_patient_id').text(data['ipd_info'][0]['patient_info_id']);
                        $('#cabin_no').text(data['ipd_info'][0]['room_title']);
                        $('#ipd_patient_idd').text(data['ipd_info'][0]['patient_info_id']);
                        $('#cabin_nod').text(data['ipd_info'][0]['room_title']);
                        $('#ipd_patient_id_lab').text(data['ipd_info'][0]['patient_info_id']);
                        $('#cabin_no_lab').text(data['ipd_info'][0]['room_title']);

                    }


                    $.each(data['test_info'], function(key, value) {

                        $('#patient_name').text(value.patient_name);
                        $('#patient_name_lab').text(value.patient_name);

                        // 	  var add=value.address;
                        // 	  alert(add);

                        $('#patient_address').text(value.address);
                        $('#patient_address_lab').text(value.address);
                        $('#patient_address_d').text(value.address);


                        $('#patient_info_id').text(value.patient_info_id);
                        $('#phone_no').text(value.mobile_no);
                        $('#email').text(value.email);
                        $('#age').text(value.age);
                        $('#gender').text(value.gender);
                        $('#invoice').text(value.test_order_id);
                        $('#booked_by').text(value.operator_name);
                        $('#printed_by').text(value.operator_name);

                        $('#patient_named').text(value.patient_name);
                        $('#patient_info_idd').text(value.patient_info_id);
                        $('#phone_nod').text(value.mobile_no);
                        $('#phone_nolab').text(value.mobile_no);
                        $('#emaild').text(value.email);
                        $('#aged').text(value.age);
                        $('#genderd').text(value.gender);
                        $('#invoiced').text(value.test_order_id);
                        $('#invoicel').text(value.test_order_id);
                        $('#booked_byd').text(value.operator_name);
                        $('#printed_byd').text(value.operator_name);


                        $('#genderlab').text(value.gender);
                        $('#agedlab').text(value.age);





                        var status = "";

                        if (value.payment_status == "unpaid") {
                            status = "Due (Office Copy)";
                            status_n = "Due (Patient Copy)";
                            status_lab = "Due (Lab Copy)";
                        } else {
                            status = "Paid (Office Copy)";
                            status_n = "Paid (Patient Copy)";
                            status_lab = "Paid (Lab Copy)";
                        }

                        $('#payment_status').text(status);
                        $('#payment_statusd').text(status_n);
                        $('#payment_statuslab').text(status_lab);

                        var myDate = new Date(value.created_at)

                        $('#date_time').text(changeDateFormat(myDate.toLocaleString()));
                        $('#date_timed').text(changeDateFormat(myDate.toLocaleString()));
                        $('#date_timelab').text(changeDateFormat(myDate.toLocaleString()));






                        $.each(data['doctor_info_ref'], function(key1, value1) {
                            console.log(value.ref_doc_id);
                            console.log('Test');
                            console.log(value1.doctor_id);
                            if (value.ref_doc_id == value1.doctor_id) {
                                // console.log(value.ref_doc_id);
                                // console.log(value1.doctor_id);

                                $('#ref_by').text(value1.doctor_title + ' (' + value1
                                    .doctor_degree + ')');
                                $('#ref_byd').text(value1.doctor_title + ' (' + value1
                                    .doctor_degree + ')');
                                $('#ref_by_lab').text(value1.doctor_title + ' (' +
                                    value1.doctor_degree + ')');
                            } else if (value.ref_doc_id == 0) {
                                $('#ref_by').text(" Self");
                                $('#ref_byd').text(" Self");
                                $('#ref_by_lab').text(" Self");
                            }

                        })

                        $.each(data['doctor_info_quack'], function(key2, value2) {

                            if (value.quack_doc_id == value2.doctor_id) {

                                //  console.log(value.quack_doc_id);
                                // console.log(value2.doctor_id);
                                $('#quack_by').text(value2.doctor_title + ' (' + value2
                                    .doctor_degree + ')');
                                $('#quack_byd').text(value2.doctor_title + ' (' + value2
                                    .doctor_degree + ')');
                                $('#quack_by_lab').text(value2.doctor_title + ' (' +
                                    value2.doctor_degree + ')');

                            } else if (value.quack_doc_id == 0) {
                                $('#quack_by').text(" Self");
                                $('#quack_byd').text(" Self");
                                $('#quack_by_lab').text(" Self");
                            }


                        })

                    })

                    var i = 1;

                    $.ajax({
                        url: "https://hospitalerp.rcreation-bd.com/admin/get_patient_ordered_test_details_info",
                        method: "POST",
                        dataType: "json",
                        data: {
                            order_id: order_id
                        },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $("#patient_ordered_test_table").append(
                                    '<tr><td class="farhana-table-4-col-1">' +
                                    i +
                                    '</td><td class="farhana-table-4-col-2">' +
                                    value.sub_test_title + ' (' + value
                                    .room_no +
                                    ')</td><td class="farhana-table-4-col-3">' +
                                    parseFloat(value.price).toFixed(2) +
                                    '</td></tr>');
                                i++;
                            })


                            $.ajax({
                                url: "https://hospitalerp.rcreation-bd.com/admin/get_order_info",
                                method: "POST",
                                dataType: "json",
                                data: {
                                    order_id: order_id
                                },
                                success: function(data) {
                                    $.each(data, function(key, value) {

                                        var vat = parseFloat(value.vat)
                                            .toFixed(2);
                                        var net_total = (parseFloat(
                                                    value.total_amount
                                                    ) + parseFloat(value
                                                    .vat) - (value
                                                    .total_discount))
                                            .toFixed(2);
                                        var due = (parseFloat(
                                                net_total) - parseFloat(
                                                    value.paid_amount))
                                            .toFixed(2);


                                        $('#total_amount').text(
                                            parseFloat(value
                                                .total_amount)
                                            .toFixed(2));
                                        $('#total_discount').text(
                                            parseFloat(value
                                                .total_discount)
                                            .toFixed(2));
                                        $('#paid_amount').text(
                                            parseFloat(value
                                                .paid_amount)
                                            .toFixed(2));
                                        $('#vat').text(vat);
                                        $('#due_amnt').text((parseFloat(
                                                    net_total) -
                                                parseFloat(value
                                                    .paid_amount))
                                            .toFixed(2));
                                        $('#net_total').text(net_total);


                                    });
                                }
                            });
                        }

                    });

                    var i = 1;
                    $.ajax({
                        url: "https://hospitalerp.rcreation-bd.com/admin/get_patient_ordered_test_details_info_d",
                        method: "POST",
                        dataType: "json",
                        data: {
                            order_id: order_id
                        },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $("#patient_ordered_test_table_duplicate_lab")
                                    .append(
                                        '<tr><td class="farhana-table-4-col-1">' + (
                                            key + 1) +
                                        '</td><td class="farhana-table-4-col-2">' +
                                        value.sub_test_title + ' (' + value
                                        .room_no +
                                        ')</td><td class="farhana-table-4-col-3">' +
                                        parseFloat(value.price).toFixed(2) +
                                        '</td></tr>');
                                i++;
                            })


                            $.ajax({
                                url: "https://hospitalerp.rcreation-bd.com/admin/get_order_info",
                                method: "POST",
                                dataType: "json",
                                data: {
                                    order_id: order_id
                                },
                                success: function(data) {
                                    $.each(data, function(key, value) {

                                        var vat = parseFloat(value.vat)
                                            .toFixed(2);
                                        var net_total = (parseFloat(
                                                    value.total_amount
                                                    ) + parseFloat(value
                                                    .vat) - (value
                                                    .total_discount))
                                            .toFixed(2);
                                        var due = (parseFloat(
                                                net_total) - parseFloat(
                                                    value.paid_amount))
                                            .toFixed(2);


                                        $('#total_amountd').text(
                                            parseFloat(value
                                                .total_amount)
                                            .toFixed(2));
                                        $('#total_discountd').text(
                                            parseFloat(value
                                                .total_discount)
                                            .toFixed(2));
                                        $('#paid_amountd').text(
                                            parseFloat(value
                                                .paid_amount)
                                            .toFixed(2));
                                        $('#vatd').text(vat);
                                        $('#due_amntd').text((
                                                parseFloat(
                                                    net_total) -
                                                parseFloat(value
                                                    .paid_amount))
                                            .toFixed(2));
                                        $('#net_totald').text(
                                        net_total);


                                    });
                                }
                            });
                        }

                    });


                    var i = 1;
                    $.ajax({
                        url: "https://hospitalerp.rcreation-bd.com/admin/get_patient_ordered_test_details_info_d",
                        method: "POST",
                        dataType: "json",
                        data: {
                            order_id: order_id
                        },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $("#patient_ordered_test_table_duplicate").append(
                                    '<tr><td class="farhana-table-4-col-1">' + (
                                        key + 1) +
                                    '</td><td class="farhana-table-4-col-2">' +
                                    value.sub_test_title + ' (' + value
                                    .room_no +
                                    ')</td><td class="farhana-table-4-col-3">' +
                                    parseFloat(value.price).toFixed(2) +
                                    '</td></tr>');
                                i++;
                            })


                            $.ajax({
                                url: "https://hospitalerp.rcreation-bd.com/admin/get_order_info",
                                method: "POST",
                                dataType: "json",
                                data: {
                                    order_id: order_id
                                },
                                success: function(data) {
                                    $.each(data, function(key, value) {

                                        var vat = parseFloat(value.vat)
                                            .toFixed(2);
                                        var net_total = (parseFloat(
                                                    value.total_amount
                                                    ) + parseFloat(value
                                                    .vat) - (value
                                                    .total_discount))
                                            .toFixed(2);
                                        var due = (parseFloat(
                                                net_total) - parseFloat(
                                                    value.paid_amount))
                                            .toFixed(2);


                                        $('#total_amountlab').text(
                                            parseFloat(value
                                                .total_amount)
                                            .toFixed(2));
                                        $('#total_discount_lab').text(
                                            parseFloat(value
                                                .total_discount)
                                            .toFixed(2));
                                        $('#paid_amount_lab').text(
                                            parseFloat(value
                                                .paid_amount)
                                            .toFixed(2));
                                        $('#vatlab').text(vat);
                                        $('#due_amnt_lab').text((
                                                parseFloat(
                                                    net_total) -
                                                parseFloat(value
                                                    .paid_amount))
                                            .toFixed(2));
                                        $('#net_total_lab').text(
                                            net_total);


                                    });
                                }
                            });
                        }

                    });



                }

            });

        });


        function changeDateFormat(inputDate) { // expects Y-m-d
            var splitDate = inputDate.split(',');

            var splitDate1 = splitDate[0].split('/');

            var year = splitDate1[2];
            var month = splitDate1[0];
            var day = splitDate1[1];

            return day + '/' + month + '/' + year + splitDate[1];

        }
    </script>

    //
    <script type="text/javascript">
     window.print();

        //   setTimeout(function() {
        //     window.print();

        //   }, 1000);

    </script>






</body>

</html>
