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
            font-size: x-small;
            width: 100%;
        }

        .table tr td {
            padding: 10px 0px;
            text-align: center;
        }

        .table tr th {
            padding: 10px 0px;
            width: 33%;
            border-radius: 2px
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        .logo {
            color: #878787;
            font-size: 35px;
            font-weight: 700;
        }

        body {
            font-size: x-small;
            font-weight: 400;
        }

        .signature {
            padding-top: 0px;
            border-top: 1px solid #525659af;
            width: 85%
        }

    </style>
</head>
<body>

    <table>
        <tr width="100%">
            <td valign="top" width="40%">
                <h1 class="logo">Vanilla Thunder</h1>
                <strong>Date:</strong> {{$Vehicle_details->start_date_and_time}}<br>
                <strong>Schedule:</strong> ({{$Vehicle_details->vehicleSchedule->start_time}} - {{$Vehicle_details->vehicleSchedule->end_time}})
            </td>
            <pre style="float: right">
            <strong>Ambulance Name: </strong>{{$Vehicle_details->vehicleInfo->model_no}}
            <strong>Driver Name: </strong>{{$Vehicle_details->driver->name}}
            <strong>Name: </strong>{{$Vehicle_details->patient_name}}
            <strong>Mobile Number: </strong>{{$Vehicle_details->patient_phone_one}}
            <strong>Age: </strong>{{$Vehicle_details->age}}
            <strong>Date Of Birth: </strong>{{$Vehicle_details->date_of_birth}}
            <strong>Blood Group: </strong>{{@$Vehicle_details->blood_group}}
            </pre>


        </tr>

    </table>


    {{-- <table width="100%">
        <tr>
            <td><strong>From:</strong> Linblum - Barrio teatral</td>
            <td><strong>To:</strong> Linblum - Barrio Comercial</td>
        </tr>

    </table> --}}

    <br>
    <br>
    <br>
    <br>

    <table width="100%" class="table">
        <thead style="background-color: lightgray;">
            <tr>
                <th scope="row">ID</th>
                <th>Address</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody class="margin-bottom:200px">
            <tr>
                <td>#INV{{$Vehicle_details->invoice_number}}</td>
                <td>{{$Vehicle_details->patient_address}}</td>
                <td>{{$Vehicle_details->vehicleSchedule->price}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td>Discount</td>
                <td>{{$Vehicle_details->vehicleSchedule->price - $Vehicle_details->subtotal}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Subtotal</td>
                <td>{{$Vehicle_details->subtotal}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Paid</td>
                <td>{{$Vehicle_details->paid}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Due</td>
                <td class="gray">{{$Vehicle_details->due}}</td>
            </tr>
        </tfoot>
    </table>



    <table width="100%" style="margin-top: 150px" class="table">
        <tr>
            <td>
                <div class="signature">
                    <p>Customer Signature</p>
                </div>

            </td>
            <td>
                <div class="signature">
                    <p>Driver Signature</p>
                </div>
            </td>
            <td>
                <div class="signature">
                    <p>Accounts Signature</p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
