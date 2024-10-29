<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.otd.due.list']) ? route('member.otd.due.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Lead',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OTD due',
    'title' => 'OTD due',
    'heading' => 'OTD due',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">OTD Due Details</h3>
                </div>
                <!-- /.box-header -->
                <div class="box box-primary">
                    <div class="box-body box-profile pt-4 pb-0">
                        <div class="col-md-3">
                            <img style="height: 100px" class="profile-user-img img-responsive img-circle"
                                src="{{ $outdoor_due->image == null ? asset('/public/adminLTE/dist/img/avatar5.png') : asset('/public/uploads/patient/' . $outdoor_due->image) }}"
                                alt="User profile picture">
                        </div>
                        <div class="col-md-9">
                            <table class="table table-responsive table-striped">
                                <tr>
                                    <th> OPD_ID </th>
                                    <td colspan="3">{{ $outdoor_due->opd_id }}
                                    </td>
                                </tr>
                                <tr>


                                    <th>{{ __('common.name') }} </th>
                                    <td colspan="3">{{ $outdoor_due->patient_name }}
                                    </td>

                                    <th>Gender</th>
                                    <td colspan="3">{{ $outdoor_due->gender }}

                                    </td>
                                    <th>Age </th>
                                    <td colspan="3">{{ $outdoor_due->age }} </td>

                                </tr>
                                <tr>
                                    <th>{{ __('common.phone') }} </th>
                                    <td>{{ $outdoor_due->phone }}</td>
                                    <th>{{ __('common.address') }} </th>
                                    <td>{{ $outdoor_due->address }}</td>
                                </tr>
                                <tr>
                                    <th>Due</th>
                                    <td>{{ $outdoor_due->due }}

                                    </td>
                                    <th>Date Of Service</th>
                                    <td>{{ $outdoor_due->date_of_service }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-primary">

                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">

                        <tr>
                            <th>#SL</th>
                            <th>Test Category</th>
                            <th>Test Name</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Net Amount</th>
                            {{-- <th>Action</th> --}}
                        </tr>

                        <tbody>
                            @foreach ($outdoor_due->outdoorPatientTest as $key => $list)
                                <tr>
                                    {{-- {{dd($list)}} --}}
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $list->subTestGroup ? $list->subTestGroup->title : '' }}</td>
                                    <td>{{ $list->subTestGroup->testGroup ? $list->subTestGroup->testGroup->title : '' }}
                                    </td>
                                    <td>{{ $list->price }}</td>
                                    <td>{{ $list->discount }}</td>
                                    <td>{{ $list->net_amount }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                        {!! Form::open(['route' => 'member.opd.due.store','method' => 'POST','files'=>'true','role'=>'form' ]) !!}

                        <div class="box-body">

                            <tfoot>
                                <tr>

                                    <td colspan="1" align="right"><strong> Total (৳)</strong></td>
                                    <td colspan="1"><input name="total_amount" readonly=""
                                            style="padding:2;text-align: right;" type="text" value="{{ $outdoor_due->due }}"
                                            id="total_amount" class="form-control col-md-10">
                                    </td>

                                    <td colspan="4"></td>

                                </tr>

                                <tr>
                                    <td colspan="1" align="right"><strong> Discount(%)</strong> </td>
                                    <td colspan="1"><input name="discount_percent" autocomplete="off"
                                            oninput="discountPercentAmount(this)" style="padding:2;text-align: right;"
                                            type="text" data-total="" id="discount_percent" class="form-control col-md-10">
                                    </td>

                                    <td colspan="1" autocomplete="off" align="right"><strong> Discount (৳)</strong>
                                    </td>
                                    <td colspan="1"><input name="discount" style="padding:2;text-align: right;"
                                            oninput="discountAmount()" type="text" data-total="" id="discount"
                                            class="form-control col-md-10">
                                    </td>

                                    <td colspan="1" autocomplete="off" align="right"><strong>Discount Ref</strong> </td>
                                    <td colspan="1"><input name="discount_ref" style="padding:2;text-align: right;"
                                            type="text" id="discount_ref" class="form-control col-md-12">
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="1" align="right"> <strong> Net Total (৳)</strong></td>
                                    <td colspan="1"><input name="net_total" autocomplete="off" readonly=""
                                            style="text-align: right;" type="text" id="net_total"
                                            value="{{ $outdoor_due->due }}" class="form-control col-md-10">
                                    </td>
                                    <td colspan="4"></td>

                                </tr>
                                <tr>
                                    <td colspan="1" align="right"> <strong>Total Paid (৳)</strong> </td>
                                    <td colspan="1"><input name="total_paid" required autocomplete="off"
                                            oninput="totalPaidAmount()" style="padding:2;text-align: right;" type="text"
                                            id="total_paid" class="form-control col-md-10">
                                    </td>
                                    <td colspan="4"></td>

                                </tr>
                                <tr>
                                    <td colspan="1" align="right"><strong> Due (৳)</strong></td>
                                    <td colspan="1"><input name="due" autocomplete="off" readonly=""
                                            style="padding:2;text-align: right;" type="text" value="{{ $outdoor_due->due }}"
                                            id="due" class="form-control col-md-10">
                                    </td>
                                    <td colspan="4"></td>
                                    <input type="hidden" id="totalDue" value="{{ $outdoor_due->due }}">
                                    <input type="hidden" id="totalDue" name="out_door_registration_id" value="{{ $outdoor_due->id }}">
                                </tr>
                                <tr>
                                    <td colspan="1" align="right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </td>

                                </tr>
                            </tfoot>

                        </div>

                        <!-- /.box-body -->

                        {!! Form::close() !!}

                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" type="text/javascript"></script>

    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.js') }}"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.resize.js') }}"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.pie.js') }}"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.categories.js') }}"></script>
    <script>

function discountPercentAmount() {
            let dis_amount = 0;
            let amount;
            let discount_percent = $('#discount_percent').val();
            let totalAmount = $('#total_amount').val()
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
            // tableValueCalculation();

        }

        function discountAmount() {
            let dis_amount = 0;
            let amount;
            let discount = $('#discount').val();
            let totalAmount = $('#total_amount').val()

            // console.log('ok',discount,totalAmount);
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

            // tableValueCalculation();

        }

        function totalPaidAmount() {

            let totalPaid = $('#total_paid').val();
            let total_due = $('#totalDue').val()
            console.log('ojjj', total_due, totalPaid)
            let due;
            due = parseInt(total_due - totalPaid)
            $('#due').val(due);

        }

        $(function() {
            singleProjectData();
            /*
             * DONUT CHART
             * -----------
             */
            // let aa= 40;



            function singleProjectData() {
                let url = "{{ route('member.singleProject') }}";
                let auth = {!! \Auth::user()->hasRole(['project_manager']) ? 1 : 0 !!}
                if (auth) {
                    url = "{{ route('member.employee.singleProject') }}";
                }

                let id = $('#project_id').val();
                $.ajax({
                    url: url,
                    method: 'get',
                    data: {
                        'id': id,
                    },
                    success: function(data) {

                        var donutData = [{
                                label: 'To Do',
                                data: data.data.total_to_do,
                                color: '#3c8dbc'
                            },
                            {
                                label: 'In Progress',
                                data: data.data.total_in_progress,
                                color: '#0073b7'
                            },
                            {
                                label: 'Review',
                                data: data.data.total_review,
                                color: '#00c0ef'
                            },
                            {
                                label: 'Done',
                                data: data.data.total_done,
                                color: '#25ab17'
                            }
                        ]

                        $.plot('#donut-chart', donutData, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    innerRadius: 0.5,
                                    label: {
                                        show: true,
                                        radius: 2 / 3,
                                        formatter: labelFormatter,
                                        threshold: 0.1
                                    }

                                }
                            },
                            legend: {
                                show: false
                            }
                        })
                    }
                });
            }


            function labelFormatter(label, series) {
                return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' +
                    label +
                    '<br>' +
                    Math.round(series.percent) + '%</div>'
            }

            /*
             * END DONUT CHART
             */
        })
    </script>
@endpush
