<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/8/2019
 * Time: 2:27 PM
 */
// $company_logo = asset($model->institute->logo);
?>


<!DOCTYPE html>
<html>

<head>
    {{-- <title>Pos Invoice</title> --}}
    <style type="text/css">
        body {
            margin: 0 auto;
            padding: 0;
            font-family: Verdana;
        }


        /* .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        } */

        .border-bottom {
            border-bottom: 2px solid;
        }

        .fw-500 {
            font-weight: 500;
        }

        .table-academic {
            width: 100%;
            margin-bottom: 2px solid !important;
            background-color: transparent;
        }

        .table-1-col-1 {
            width: 33%;
            text-align: center;
        }

        .sub_test_table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .appointment_table {
            width: 100%;
            margin: 0 auto;
            margin-top: 10px;
            border-collapse: collapse;
            border: 1px dotted #111111;
            font-size: 12px;
        }
        
        tr{
            line-height: 25px;
        }
    </style>


</head>

<body>
    <div class="container">
        <center id="top">

            <h2 style=" font-size: 20px; margin: 10px auto!important">{{ $company_name }}</h2>
            <div class="info">
                <table class="table_info">
                    @if (isset($report_head_sub_text))
                        <span> {!! $report_head_sub_text !!}</span> <br>
                    @else
                        <span> {{ $company_address }}</span> <br>
                        <span> {{ $company_city . ($company_country ? ', ' . $company_country : '') }}</span> <br>
                    @endif
                    <span>Phone : {{ $company_phone }}</span>

                    <h3 style="text-decoration: underline;">Appointment Slip</h3>

                </table>
            </div>
            <!--End Info-->
        </center>

        <div style="padding-bottom: 1rem;">

            <div class="box-body">
                <center>
                    <table id="" style="margin-top: 5px" class="sub_test_table table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="2">Appoinment ID: {{ $appoinment->appointment_id }} </th>

                            </tr>
                            <tr>
                                <th colspan="2">Serial No: {{ $appoinment->serial_no }} </th>
                            </tr>



                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td class="table-1-col-1">Patient Name </td>
                                <td>{{ $appoinment->patient_name }}</td>
                            </tr>
                            <tr>
                                <td class="table-1-col-1">Phone </td>
                                <td>{{ $appoinment->phone }}</td>
                            </tr>
                            <tr>
                                <td class="table-1-col-1">Doctor Info </td>
                                <td>{{ $appoinment->doctor ? $appoinment->doctor->name : '' }}
                                    ({{ $appoinment->doctor ? $appoinment->doctor->degree : '' }})</td>
                            </tr>
                            <tr>
                                <td class="table-1-col-1">Appointment Date </td>
                                <td>{{ $appoinment->date }}</td>
                            </tr>
                            <tr>
                                <td class="table-1-col-1">Schedule </td>
                                @if ($appoinment->doctorScheduleDay)
                                    <td>{{ $appoinment->doctorScheduleDay->start_time }} -
                                        {{ $appoinment->doctorScheduleDay->end_time }}</td>
                                @endif

                            </tr>
                            <tr>
                                <td class="table-1-col-1">Visiting Fee </td>
                                <td>{{ $appoinment->fee }}</td>
                            </tr>
                            <tr>
                                <td class="table-1-col-1">Discount </td>
                                <td>{{ $appoinment->discount }}</td>
                            </tr>
                            <tr>
                                <td class="table-1-col-1">Gross Total </td>
                                <td>{{ $appoinment->gross_total }}</td>
                            </tr>


                        </tbody>

                    </table>

                </center>
                <div style="display: flex; justify-content: center; margin-top: 8px">
                    <div style="width: 50%">
                        <small style="">Print Date: {{date('d-m-Y H:i:s')}}</small><br>
                        <small style="">Developed By: R-Creation</small>
                    </div>
                    <div style="margin-top: 10px">
                        <p style="border-top: 1px solid;">Authorize Sig:</p>

                    </div>

                </div>

            </div>


        </div>
        {{-- <div id="footer" style="text-align: left; border-top: 1px solid black; margin-top: 2px;">
            <span style="font-size:7px;">Generated by {{ env('APP_NAME') }} </span><span
                style="font-size:7px;color:blue"> {{ url("") }}</span>

        </div> --}}
    </div>

    <!--End Invoice-->
</body>
<script type="text/javascript">
    window.print();
    window.onafterprint = back;

    // function back() {
    //     window.location.replace(pos_url);
    // }
</script>

</html>
