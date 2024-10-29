<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

 $route =  \Auth::user()->can(['member.employee.index']) ? route('member.employee.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Employee',
        'href' =>  $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Employee Details',
    'title'=>' Employee Details ',
    'heading' =>trans('common.employee_details').' : '.$employee->first_name." ".$employee->last_name."(".$employee->employeeID.")",
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">
        <div class="col-md-12">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile pt-4 pb-0">
                    <div class="col-md-3">
                        <img class="profile-user-img img-responsive img-circle" src="{{ $employee->photo == null ? asset("/public/adminLTE/dist/img/avatar5.png") : $employee->photo }}" alt="User profile picture">

                    </div>
                    <div class="col-md-9">
                        <table class="table table-responsive table-striped">
                            <tr>
                                <th>{{__('common.employee_id')}}</th>
                                <td colspan="3">{{ $employee->employeeID }} </td>
                            </tr>
                            <tr>
                                <th>{{__('common.name')}} </th>
                                <td colspan="3">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            </tr>
                            <tr>
                                <th>{{__('common.phone')}} </th>
                                <td>{{ $employee->phone2 }}</td>
                                <th>{{__('common.email')}} </th>
                                <td>{{ $employee->user->email }}</td>
                            </tr>

                            <tr>
                                <th>NIF</th>
                                <td>{{ $employee->nid }}</td>
                                <th>{{__('common.date_of_birth')}} </th>
                                <td>{{ create_date_format($employee->dob, "/") }}</td>
                            </tr>



                        </table>
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.address_details')}}  </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">

                        <tr>
                            <th colspan="1" width="250px">{{__('common.present_address')}}  </th>
                            <td colspan="3"> {{  $employee->address }} </td>
                        </tr>
                        <tr>
                            <th colspan="1" width="250px">{{__('common.permanent_address')}}   </th>
                            <td colspan="3"> {{  $employee->address2 }} </td>
                        </tr>
                        <tr>
                            <th>{{__('common.country')}} </th>
                            <td>{{ $employee->nationality ? $employee->country->countryName : "" }}</td>
                            <th>{{__('common.state')}} </th>
                            <td>{{ $employee->division ? $employee->division->name : "" }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.city')}} </th>
                            <td>{{ $employee->district ? $employee->district->name : "" }}</td>
                            <th>{{__('common.area')}} </th>
                            <td>{{ $employee->area ? $employee->area->name : "" }}</td>
                        </tr>
                        {{--<tr>--}}
                            {{--<th>Thana</th>--}}
                            {{--<td>{{ $employee->thana ? $employee->thana->name : "" }}</td>--}}
                            {{--<th>Area</th>--}}
                            {{--<td>{{ $employee->area ? $employee->area->name : "" }}</td>--}}
                        {{--</tr>--}}
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.personal_information')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped text-capitalize">
                        <tr>
                            <th>{{__('common.passport_number')}}  </th>
                            <td>{{ $employee->passport_number }}</td>
                            <th>{{__('common.passport_expired')}} </th>
                            <td>{{ create_date_format($employee->passport_expire, "/")  }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.driving_license')}}  </th>
                            <td>{{ $employee->diving_license }}</td>
                            <th>{{__('common.driving_license_expiry_date')}} </th>
                            <td>{{ create_date_format($employee->driving_expire, "/")  }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.visa_expired')}} </th>
                            <td>{{ create_date_format($employee->visa_expire, "/") }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.permanent_residence_pr_number')}} </th>
                            <td>{{ $employee->pr_number }}</td>
                            <th>{{__('common.permanent_residence_pr_expiry_date')}} </th>
                            <td>{{ create_date_format($employee->pr_expire, "/")  }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.insurance_company')}} </th>
                            <td>{{ $employee->insurance_company }}</td>
                            <th>{{__('common.insurance_number')}}   </th>
                            <td>{{ $employee->insurance_number }}</td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.employee_job_information')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">
                        <tr>
                            <th>{{__('common.join_date')}} </th>
                            <td>{{ create_date_format($employee->join_date, "/") }}</td>
                            <th>{{__('common.department')}}  </th>
                            <td>{{ $employee->department->name }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.designation')}}  </th>
                            <td>{{ $employee->designation->name }}</td>
                            <th>{{__('common.shift')}}  </th>
                            <td>{{ $employee->shift->shift_type_name." (".$employee->shift->time_in_out.")" }}</td>
                        </tr>
                        <tr>
                            <th>{{__('common.salary_system')}}  </th>
                            <td>{{ $employee->salary_system }}</td>
                            <th>{{__('common.weekend_accept')}}  </th>
                            <td>{{ $employee->weekend_accept ? "Yes" : "No" }}</td>
                        </tr>
                        <tr>
                            <th> {{__('common.salary')}} </th>
                            <td>{{ create_money_format($employee->salary) }}</td>
                            <th>{{__('common.commission')}}  </th>
                            <td>{{ create_money_format($employee->commission) }}</td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.other_details')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">

                        {{--<tr>--}}
                            {{--<th colspan="1"> GL Account Code</th>--}}
                            {{--<td colspan="3"> {{ $employee->account_type->account_code }} </td>--}}
                        {{--</tr>--}}

                        @foreach($employee->attached_file as $value)
                        <tr>
                            <th width="300px"> {{ $value->document_type->name }}</th>
                            <td> <a target="_blank" href="{{  $value->attached ? $value->attached_file : "javascript:void(0)"  }}"> View file </a> </td>
                        </tr>
                        @endforeach

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
@endsection

