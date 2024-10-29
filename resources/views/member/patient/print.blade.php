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
                        <th>Reg No</th>
                        <th>Patient Name</th>
                        <th>Dr.</th>
                        <th>Ref Dr.</th>
                        <th>Disease Name</th>
                        <th>Cabin No</th>
                        <th>Admit Date</th>
                        <th>Reg Form</th>
                        <th>Release/UnRelease</th>


                    </tr>
                </thead>
                <tbody>

                    @foreach ($patinets as $key => $list)
                        <tr>
                            {{-- {{dd($list)}} --}}
                            <td>{{ ++$key }}</td>
                            <td>{{ $list->patient_info_id }}</td>
                            <td>{{ $list->reg_id }}</td>
                            <td>{{ $list->patient_name }}</td>
                            <td>{{ $list->doc_name }}</td>
                            <td>{{ $list->ref_doc_name }}</td>
                            <td>{{ $list->disease_name }}</td>
                            <td>{{ isset($list->cabin) ? $list->cabin->title : '' }}</td>
                            <td>{{ date_month_year_format($list->admit_date_time) }}</td>

                            <td> <a href="{{ route('member.patient_form_print', $list->id) }}"
                                    target="_blank" class="btn btn-info btn-sm">Reg Form </a> </td>
                            <td> @php
                                $status = $list->released_date == null;
                            @endphp <button
                                    class="btn btn-{{ $status ? 'danger' : 'success' }} btn-sm">{{ $status ? 'Unreleased' : 'Released' }}
                                </button> </td>


                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
</body>
</html>



