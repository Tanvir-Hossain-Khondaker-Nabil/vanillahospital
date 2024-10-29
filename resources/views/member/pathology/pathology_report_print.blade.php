<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Report</title>

    <style>
        .text-center {
            text-align: center;
        }

        .my {
            margin-top: 1px;
            margin-bottom: 1px;
        }

        /* .farhana-table-3 {
            margin: 0 auto;
            width: 90%;
            margin-top: 10px;

        } */

        table {
            margin: 0 auto;
            width: 80%;
            margin-top: 10px;
        }

        .farhana-table-3 tr td {
            font-size: 12px;

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
            border-radius: 5px;
            border: 1px solid;
            padding: 5px;
            /* text-decoration: underline; */
        }

        .table-1 p {

            /* font-weight: bold; */
            text-align: center;
            font-size: 15px;
            padding: 0;
            text-decoration: underline;
        }

        .table-1 div {

            /* font-weight: bold; */
            text-align: center;
            font-size: 15px;
            /* text-decoration: underline; */
        }

        .farhana-table-3 {
            margin: 0 auto;
            width: 90%;
            /* margin-top: 10px; */

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

        @page {
                /* margin: 100px 25px; */
            }

            footer {
                position: fixed;
                bottom: -60px;
                left: 0px;
                right: 0px;
                height: 180px;

                /** Extra personal styles **/
                background-color: transparent;
                color: black;
                text-align: center;
                line-height: 35px;
            }

    </style>
</head>

<body>
    <div class="row">

        <table class="farhana-table-1">
            <tbody>
                <tr>
                    <td class="farhana-table-1-col-1">

                        @if (@$company_logo)
                            <img height="60px" width="60px" src="{{ $company_logo }}">
                        @endif
                    </td>
                    <td>
                        <h1 class=""
                            style="margin-top: 2px; font-size: 20px; text-align: center;margin-left: 5px;">
                            {{ $company_name }} </h1>

                        <p class="text-center">আপনার সু-চিকিৎসাই আমাদের ঐক্যবদ্ধ প্রচেষ্টা </p>
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

    <div style="width: 40%; margin: auto ">
    <table class="">
        <tbody>
            <tr style="text-align: center">
                <td class="" style="border-radius: 5px; border: 1px solid; padding: 5px">
                    <div style="font-weight: 700">TEST REPORT</div>
                </td>
            </tr>
        </tbody>
    </table>
    </div>

    <table class="farhana-table-3">
        <tbody>
            <tr>
                <td colspan="2"><b>Bill No: </b><label
                        id="invoice">{{ $data->outDoorRegistration->opd_id }}</label></td>
                <td><b>Date: </b><label id="date_time">4/6/2024 9:56:04 AM</label></td>
            </tr>
            <tr>

                <td><b>Sex: </b><label id="gender">{{ $data->outDoorRegistration->gender }}</label></td>
                <td class="text-center"><b>Age: </b><label id="age">{{ $data->outDoorRegistration->age }}</label>
                </td>
                <td><b>Mob: </b><label id="phone_no">{{ $data->outDoorRegistration->phone }}</label></td>
            </tr>
            <tr>

                <td style="text-align: left"><b>Patient Name: </b><label
                        id="patient_name">{{ $data->outDoorRegistration->patient_name }}</label></td>
                <td><b>Patient Address: </b><label
                        id="patient_address">{{ $data->outDoorRegistration->address }}</label></td>

            </tr>

            <tr>

                @if ($data->outDoorRegistration->doctor_id == 0)
                    <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label> Self</label></td>
                @else
                    <td colspan="3" class="doctor-name"><b>Dr. Name: </b><label
                            id="ref_by">{{ $data->outDoorRegistration->doctor ? $data->outDoorRegistration->doctor->name : '' }}
                            ({{ $data->outDoorRegistration->doctor ? $data->outDoorRegistration->doctor->degree : '' }})
                        </label></td>
                @endif

            </tr>
            <tr>
                @if ($data->outDoorRegistration->ref_doctor_id == 0)
                    <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label> Self</label></td>
                @else
                    <td colspan="3" class="doctor-name"><b>Ref Dr. Name: </b><label
                            id="quack_by">{{ $data->outDoorRegistration->refDoctor ? $data->outDoorRegistration->refDoctor->name : '' }}
                            ({{ $data->outDoorRegistration->refDoctor ? $data->outDoorRegistration->refDoctor->degree : '' }})</label>
                    </td>
                @endif


            </tr>


        </tbody>


    </table>
    <p>{!! $data->description !!}</p>


    {{-- <footer> --}}
        <div style="width: 90%; margin: auto ">
            <table style="margin-top:15px" class="farhana-table-2">
                <tbody>

                    <tr >
                        <td style="text-align: left">Checked By:</td>
                        <td style="text-align: center">Prepared By</td>
                        <td style="text-align: right">Technologist</td>
                       </tr>
                   <tr style="text-align: center">
                    <td style="text-align: left">
                        <img width="80" height="50"
                        src="{{ asset('public/uploads/signature/'.$technologist->technologist->checked_by_signature) }}">

                    </td>
                    <td style="text-align: center">
                        <img width="80" height="50"
                                    src="{{ asset('public/uploads/signature/'.$technologist->technologist->prepared_by_signature) }}">

                    </td>
                    <td style="text-align: right">
                        <img width="80" height="50"
                                    src="{{ asset('public/uploads/signature/'.$technologist->technologist->technologist_signature) }}">

                    </td>
                   </tr>

                   <tr >
                    <td style="text-align: left">
                        @if ($technologist->technologist->checkedDoctor)
                                <div>
                                    <small>{{ $technologist->technologist->checkedDoctor->name }}</small><br>
                                    <small>{{ $technologist->technologist->checkedDoctor->degree }}</small>
                                </div>
                            @else
                                <div>
                                    <small>{{ $technologist->technologist->checkedEmployee->first_name }}
                                        {{ $technologist->technologist->checkedEmployee->last_name }}</small>
                                    <br><small>{{ $technologist->checked_by_degree }}</small>
                                </div>
                            @endif
                    </td>
                    <td style="text-align: center">
                        @if ($technologist->technologist->preparedDoctor)
                        <div>
                            <small>{{ $technologist->technologist->preparedDoctor->name }}</small><br>
                            <small>{{ $technologist->technologist->preparedDoctor->degree }}</small>
                        </div>
                    @else
                        <div>
                            <small>{{ $technologist->technologist->preparedEmployee->first_name }}
                                {{ $technologist->technologist->preparedEmployee->last_name }}</small>
                            <br><small>{{ $technologist->prepared_by_degree }}</small>
                        </div>
                    @endif
                    </td>
                    <td style="text-align: right">
                        @if ($technologist->technologist->technologistDoctor)
                        <div>
                            <small>{{ $technologist->technologist->technologistDoctor->name }}</small><br>
                            <small>{{ $technologist->technologist->technologistDoctor->degree }}</small>
                        </div>
                    @else
                        <div>
                            <small>{{ $technologist->technologist->technologistEmployee->first_name }}
                                {{ $technologist->technologist->technologistEmployee->last_name }}</small>
                            <br><small>{{ $technologist->technologist_degree }}</small>
                        </div>
                    @endif
                    </td>
                   </tr>

                </tbody>

            </table>
        </div>
    {{-- </footer> --}}


</body>

</html>

<script>

    setTimeout(() => {
      window.print();
    },200 );

    window.onafterprint = back;

function back() {

    window.location.href = "{{ route('member.fetch.pathology_list')}}";

}
</script>
