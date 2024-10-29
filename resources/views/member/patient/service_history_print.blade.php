<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */

?>

@include('member.reports.common.head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%;">

        <div class="col-md-12">
            <table class="" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#SL</th>
                        <th>Patient ID</th>
                        <th>Order Id</th>
                        <th>Service</th>
                        <th>Marketing Officer</th>
                        <th>Doctor</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total Price</th>
                        <th>Added By</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach ($serviceHistory as $key => $list)
                        <tr>
                            {{-- {{dd($list)}} --}}
                            <td>{{ ++$key }}</td>
                            <td>{{ isset($list->patient)?$list->patient->patient_info_id:'' }}</td>
                            <td>{{ $list->order_id }}</td>
                            <td>{{ isset($list->service)?$list->service->title:''}}</td>
                            <td>{{ isset($list->marketingOfficer)?$list->marketingOfficer->name:'' }}</td>
                            <td>{{ isset($list->doctor)?$list->doctor->name:'' }}</td>

                            <td>{{ $list->price }}</td>
                            <td>{{ $list->qty }}</td>
                            <td>{{ $list->qty*$list->price }}</td>

                            <td>{{ isset($list->user)?$list->user->full_name:'' }}</td>

                           

                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
</body>
</html>



