@include('member.reports.common.head')
<style type="text/css">
    body {
        background-color: white !important;
        width: 800px;
        height: 700px;
        color: black !important;
        margin: 0 auto;

        padding: 5px;
    }

    .table_div {
        border: 1px solid;
        border-radius: 5px;
        padding: 0px;
        padding-bottom: 20px;
    }

    .textarea {
        padding: 5px;
        height: 80px;
        width: 300px;
        border: 1px solid #00000058;
        margin-top: 30px;
        background: #f0f4fd;
        border-radius: 5px;
        margin-left: 10px;
    }

    .table_div>table tr {
        border-bottom: 1px solid #00000050;
        padding: 6px;
    }

    .table_div>table,
    tbody,
    tr {
        display: block;
    }

    .td_design {
        width: 100%;
        background: #F0F4FD;
        padding: 5px 0 5px 10px;
        border-radius: 5px;
        border-bottom: 1px solid;
        height: 34.5px;
    }

    .table_div>table tr.border_none {
        border: none;
    }

    .table_div>table tr td:first-child {
        white-space: nowrap;
        padding-right: 14px;
    }

    .table_div>table tr.border_none {
        border: none;
        padding-bottom: 0;

    }

    .three_row>td {
        display: inline-block;
        max-width: 33.33%;
        width: 32.333%;
        padding: 0 !important;
        font-weight: 700;
        border: none !important;
    }

    .three_row>td>p span {
        visibility: hidden;
    }

    .three_row>td>p {
        margin: 0;
        background: #f0f4fd;
        padding: 10px 0 10px 10px;
        border-radius: 5px;
        border-bottom: 1px solid;
        /* height: 34.5px; */
    }

    span {
        font-size: 12px;
        font-weight: 400;
        padding-bottom: 5px;
        display: inline-block;
        padding-left: 2px;
    }

    .two_row>td {
        display: inline-block;
        max-width: 50%;
        width: 49%;
        padding: 0 !important;
        font-weight: 700;
        border: none !important;
    }

    .two_row>td>p span {
        visibility: hidden;
    }


    .two_row>td>p {
        margin: 0;
        background: #f0f4fd;
        padding: 10px 0 10px 10px;
        border-radius: 5px;
        border-bottom: 1px solid;
        /* height: 34.5px; */
        min-width: fit-content;
        padding-right: 10px;
        box-sizing: border-box;
        vertical-align: middle;
    }

    td.one_first {
        min-width: 160px;
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

    table tbody tr td,
    table tbody tr th,
    table thead tr th {
        border: 0.3px solid rgba(1, 1, 1, 0.32) !important;
        padding: 3px;
        font-size: 11px;
        border: none !important;
    }
</style>

<body>
    <div id="page-wrap">

        @include('member.reports.company')

        <div style="width: 100%;">

            <div class="col-md-12">



                <div class="row">
                    <div class="col-md-12 table_div">
                        <table style="color: black; font-size: 16px; ">
                            <tr class="border_none">
                                <td class="one_first" style="font-weight: bold">Patient's Name: </td>
                                <td class="td_design">{{ $patient->patient_name }}</td>
                            </tr>
                            <tr>
                                <td class="one_first" style="font-weight: bold">Patient's Phone No: </td>
                                <td class="td_design">{{ $patient->phone }}</td>
                            </tr>
                            <tr>
                                <td class="one_first" style="font-weight: bold">Email Address: </td>
                                <td class="td_design">{{ $patient->email }}</td>
                            </tr>
                            <tr class="border_none">
                                <td class="one_first" style="font-weight: bold">Patient's Address: </td>
                                <td class="td_design">{!! $patient->address !!}</td>
                            </tr>
                            <tr>
                                <td class="one_first" style="font-weight: bold"></td>
                                <td class="td_design"></td>
                            </tr>
                            <tr class="two_row">
                                <td><span>Dr. Name:</span>
                                    <p>{{ $patient->doc_name }}</p>
                                </td>
                                <td><span>Ref Dr. Name:</span>
                                    <p>{{ $patient->ref_doc_name }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="three_row">
                                <td width="33.33%"><span>Gender:</span>
                                    <p>{{ $patient->gender == 1 ? 'Male' : ($patient->gender == 1 ? 'Female' : 'Other') }}</p>
                                </td>
                                <td width="33.33%"><span>Blood Group:</span>
                                    <p>{{ $patient->blood_group }}
                                    </p>
                                </td>
                                <td width="33.33%"><span>age:</span>
                                    <p>{{ $patient->age }}</p>
                                </td>

                            </tr>
                            <tr class="two_row">
                                <td><span>Registration No:</span>
                                    <p>{{ $patient->reg_id }}</p>
                                </td>
                                <td><span>Cabin No:</span>
                                    <p>{{ $patient->cabin ? $patient->cabin->title : '' }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="three_row sm">
                                <td width="33.33%"><span>Admission Fee:</span>
                                    <p>{{ $patient->admission_fee }}</p>
                                </td>
                                <td width="33.33%"><span>Admission Fee Paid:</span>
                                    <p>{{ $patient->admission_fee_paid }}
                                    </p>
                                </td>
                                <td width="33.33%"><span>Advance Payment:</span>
                                    <p>{{ $patient->advance_payment }}</p>
                                </td>
                            </tr>
                            <tr class="two_row">
                                <td><span>Date of Admission: </span>
                                    <p>{{ date_month_year_format($patient->admit_date_time) }}</p>
                                </td>
                                <td><span>Disease Name:</span>
                                    <p>{{ $patient->disease_name }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="two_row">
                                <td><span>Guardian Name: </span>
                                    <p>{{ $patient->guardian_name }}</p>
                                </td>
                                <td><span>Blood Pressure:</span>
                                    <p>{{ $patient->blood_pressure }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="two_row">
                                <td><span> Pulse Rate: </span>
                                    <p>{{ $patient->pulse_rate }}</p>
                                </td>
                                <td><span>Admit Date:</span>
                                    <p>{{ date_month_year_format($patient->admit_date_time) }}
                                    </p>
                                </td>
                            </tr>

                        </table>

                        <table style="color: black; font-size: 16px; ">

                        </table>
                        <table>
                            <tr style="border: none">
                                <td>
                                    <table>
                                        <tr style="border: none">
                                            <td style="font-weight: bold">Description: </td>
                                            <td>
                                                <div class="textarea"
                                                    style="padding: 5px; height: 100px; width: 300px; border: 1px solid black;margin-top: 30px;">
                                                    {!! $patient->description !!}</div>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                                <td width="50%">
                                    <table width="100%">

                                        <tr
                                            style="
    border-bottom: none;
    border-top: 2px solid;
    padding-top: 0;
    width: 156px;
    margin-left: auto;
    text-align: center;
">
                                            <td style="font-weight: bold;">Authorize Signature: </td>
                                            <td>{{ $patient->operator_name }}</td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        // setTimeout(function() {
        //     window.print();
        //     self.close();
        // }, 1000);
    </script>
</body>

</html>
