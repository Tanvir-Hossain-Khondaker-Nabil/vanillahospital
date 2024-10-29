<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: 15px;
            width: 100%;
        }

        .table tr td {
            padding: 15px 0px;
            text-align: center;
        }

        .table tr th {
            padding: 15px 0px;
            width: 50%;
            border-radius: 2px
        }

        .gray {
            background-color: lightgray
        }

        .logo {
            color: #878787;
            font-size: 55px;
            font-weight: 700;
        }

        body {
            font-size: 15px;
            font-weight: 400;
        }

    </style>
</head>
<body>

    <table>
        <tr width="100%">
            <td valign="top" width="40%">
                <h1 class="logo">Vanilla Thunder</h1>
            </td>


        </tr>

    </table>


    <table width="100%">
        <tr>
            <td><strong>From:</strong> {{ $start_date }}</td>
            <td><strong>To:</strong> {{ $end_date }}</td>
        </tr>

    </table>

    <br>
    <br>
    <br>
    <br>

    <table width="100%" class="table">
        <thead style="background-color: lightgray;">
            <tr>
                <th>Type Of Patient</th>
                <th>Booking No</th>
                <th>Amdulance Name</th>
                <th>Driver Name</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Schedule</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Subtotal</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Date And Time</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehicle_details as $key => $list)
            <tr>
                <td>
                    @if (@$list->ipd_patient_info_registration_id)
                    IPD Patient
                    @elseif (@$list->outdoor_registration_id)
                    Outdoor Patient
                    @else
                    Normal Patient
                    @endif
                </td>
                <td>{{ $list->invoice_number }}</td>
                <td>{{$list->vehicleInfo->model_no}}</td>
                <td>{{$list->driver->name}}</td>
                <td>{{$list->patient_name}}</td>
                <td>{{$list->patient_phone_one}}</td>
                <td>({{$list->vehicleSchedule->start_time}} - {{$list->vehicleSchedule->end_time}})</td>
                <td>{{ @$list->vehicleSchedule->price }} tk</td>
                <td>{{ @$list->vehicleSchedule->price - $list->subtotal }} tk</td>
                <td>{{ $list->subtotal }} tk</td>
                <td>{{ $list->paid }} tk</td>
                <td>{{ $list->due }} tk</td>
                <td>{{ $list->start_date_and_time }}</td>
                <td>{{ $list->patient_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



</body>
</html>
