<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 11:40 AM
 */

$user = \Illuminate\Support\Facades\Auth::user();

?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo : asset('public/adminLTE/dist/img/user2-160x160.jpg') }}"
                    class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="username">{{ $user->full_name }}</p>
                <p>{{ $user->company_id ? $user->company->company_name : '' }}</p> <br />
                {{--                <a class="pt-5" href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a> --}}

            </div>
        </div>
        <!-- search form -->
        {{-- <form action="#" method="get" class="sidebar-form"> --}}
        {{-- <div class="input-group"> --}}
        {{-- <input type="text" name="q" class="form-control" placeholder="Search..."> --}}
        {{-- <span class="input-group-btn"> --}}
        {{-- <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> --}}
        {{-- </button> --}}
        {{-- </span> --}}
        {{-- </div> --}}
        {{-- </form> --}}
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header text-uppercase">{{ __('common.profile_setting') }}</li>
            <li>
                <a href="{{ route('change_password') }}">
                    <i class="fa fa-key"></i> <span> {{ __('common.change_password') }} </span>
                </a>
            </li>
            <li class="header">{{ __('common.main_navigation') }}</li>
            <li>
                @php
                    $route = $user->hasRole(['admin', 'super-admin', 'developer']) ? 'admin' : 'member';
                    $route .= '.dashboard';

                @endphp
                <a href="{{ route($route) }}">
                    <i class="fa fa-dashboard"></i> <span>
                        {{ __('common.dashboard') }}
                    </span>
                </a>
            </li>


            @if (config('settings.employee'))

                @if ($user->can(['member.employee.index', 'member.salary_management.index']))

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-id-badge"></i> <span>{{ __('common.employee') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">


                            @if ($user->can(['member.employee.index']))
                                <li><a href="{{ route('member.employee.index') }}"><i class="fa fa-address-book"></i>
                                        {{ __('common.list') }}</a></li>
                            @endif

                            @if ($user->can(['member.employee.create']))
                                <li><a href="{{ route('member.employee.create') }}"><i
                                            class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                            @endif

                            {{-- @if ($user->can(['member.hr.employee-visa-expires'])) --}}
                            <li><a href="{{ route('member.hr.employee-visa-expires') }}"><i
                                        class="fa fa-list"></i>{{ __('common.visa_expires') }} </a></li>
                            {{-- @endif

                            @if ($user->can(['member.hr.employee-passport-expires'])) --}}
                            <li><a href="{{ route('member.hr.employee-passport-expires') }}"><i
                                        class="fa fa-list"></i>{{ __('common.passport_expires') }} </a></li>
                            {{-- @endif --}}

                        </ul>
                    </li>
                @endif
            @endif

            {{-- @if (config('settings.hr')) --}}

            @if ($user->can(['member.department.index', 'member.department.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-briefcase"></i><span>{{ __('common.department') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.department.index']))
                            <li><a href="{{ route('member.department.index') }}"><i class="fa fa-briefcase"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.department.create']))
                            <li><a href="{{ route('member.department.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (config('settings.designation') && $user->can(['member.designation.index', 'member.designation.create']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-briefcase"></i><span>{{ __('common.designation') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.designation.index']))
                            <li><a href="{{ route('member.designation.index') }}"><i class="fa fa-briefcase"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.designation.create']))
                            <li><a href="{{ route('member.designation.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif


            @if ($user->can(['member.holiday.create', 'member.holiday.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.holiday') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.holiday.index']))
                            <li><a href="{{ route('member.holiday.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.holiday.create']))
                            <li><a href="{{ route('member.holiday.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.hospital_service.create', 'member.hospital_service.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.hospital_services') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.hospital_service.index']))
                            <li><a href="{{ route('member.hospital_service.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.hospital_service.create']))
                            <li><a href="{{ route('member.hospital_service.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif
            @if ($user->can(['member.marketing_officer.create', 'member.marketing_officer.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.marketing_officer') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.marketing_officer.index']))
                            <li><a href="{{ route('member.marketing_officer.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.marketing_officer.create']))
                            <li><a href="{{ route('member.marketing_officer.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.doctors') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.doctors.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.doctors.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif
            @if ($user->can(['member.cabin_class.create', 'member.cabin_class.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.cabin_class') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.cabin_class.index']))
                            <li><a href="{{ route('member.cabin_class.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.cabin_class.show']))
                            <li><a href="{{ route('member.cabin_class.summary') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.cabin_summary') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif


            @if ($user->can(['member.patient_registration.create', 'member.patient_registration.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.patient_registration') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.patient_registration.index']))
                            <li><a href="{{ route('member.patient_registration.index') }}"><i
                                        class="fa fa-calendar"></i>
                                    {{ __('common.patient_registration_list') }}</a></li>
                            <li><a href="{{ route('member.patient_registration.index') . '?page=due' }}"><i
                                        class="fa fa-calendar"></i>
                                    {{ __('common.due_patient_list') }}</a></li>
                            <li><a href="{{ route('member.patient_registration.index') . '?page=paid' }}"><i
                                        class="fa fa-calendar"></i>
                                    {{ __('common.paid_patient_list') }}</a></li>
                            <li><a href="{{ route('member.patient_registration.index') . '?page=discharge' }}"><i
                                        class="fa fa-calendar"></i>
                                    {{ __('common.discharge_patient_list') }}</a></li>
                            <li><a href="{{ route('member.patient_registration.index') . '?page=unreleased' }}"><i
                                        class="fa fa-calendar"></i>
                                    {{ __('common.unreleased_patient_list') }}</a></li>
                        @endif

                        @if ($user->can(['member.patient_registration.create']))
                            <li><a href="{{ route('member.patient_registration.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.patient_registration') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.specimen.create', 'member.specimen.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.specimen') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.specimen.index']))
                            <li><a href="{{ route('member.specimen.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.specimen.create']))
                            <li><a href="{{ route('member.specimen.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif
                        @if ($user->can(['member.test_group.create', 'member.test_group.index']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-calendar"></i><span>{{ __('common.test_group') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if ($user->can(['member.test_group.index']))
                                        <li><a href="{{ route('member.test_group.index') }}"><i
                                                    class="fa fa-calendar"></i>
                                                {{ __('common.list') }}</a></li>
                                    @endif

                                    @if ($user->can(['member.test_group.create']))
                                        <li><a href="{{ route('member.test_group.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif

                                </ul>
                            </li>

                        @endif

                        @if ($user->can(['member.sub_test_group.create', 'member.sub_test_group.index']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-calendar"></i><span>{{ __('common.sub_test_group') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if ($user->can(['member.sub_test_group.index']))
                                        <li><a href="{{ route('member.sub_test_group.index') }}"><i
                                                    class="fa fa-calendar"></i>
                                                {{ __('common.list') }}</a></li>
                                    @endif

                                    @if ($user->can(['member.sub_test_group.create']))
                                        <li><a href="{{ route('member.sub_test_group.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif

                                </ul>
                            </li>

                        @endif
                    </ul>

                </li>


            @endif




            @if ($user->can(['member.doctor_schedule.create', 'member.doctor_schedule.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.doctor_schedule') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        {{-- @if ($user->can(['member.doctor_schedule.index']))
                    <li><a href="{{ route('member.doctor_schedule.index') }}"><i class="fa fa-calendar"></i>
                            {{ __('common.list') }}</a></li>
                @endif --}}

                        @if ($user->can(['member.doctor_schedule.create']))
                            <li><a href="{{ route('member.doctor_schedule.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.appoinments.create', 'member.appoinments.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.appoinment') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.appoinments.index']))
                            <li><a href="{{ route('member.appoinments.index') }}"><i class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.appoinments.create']))
                            <li><a href="{{ route('member.appoinments.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.out_door_registration.create', 'member.out_door_registration.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>{{ __('common.out_door') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.out_door_registration.index']))
                            <li><a href="{{ route('member.out_door_registration.index') }}"><i
                                        class="fa fa-calendar"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.out_door_registration.create']))
                            <li><a href="{{ route('member.out_door_registration.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                        <li><a href="{{ route('member.opd.all.patient.list') }}"><i class="fa fa-plus"></i>All
                                Patient List </a></li>

                        <li><a href="{{ route('member.opd.paid.patient.list') }}"><i class="fa fa-plus"></i>Paid
                                Patient List </a></li>

                        <li><a href="{{ route('member.opd.due.patient.list') }}"><i class="fa fa-plus"></i>Due
                                Patient List </a></li>

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-calendar"></i><span>{{ __('common.specimen_wise_collection') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                {{-- @if ($user->can(['member.otd_collections.index'])) --}}
                                <li><a href="{{ route('member.specimen_wise.collection.list') }}"><i
                                            class="fa fa-calendar"></i>
                                        {{ __('common.list') }}</a></li>
                                {{-- @endif --}}



                            </ul>
                        </li>



                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-calendar"></i><span>{{ __('common.group_wise_collection') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                {{-- @if ($user->can(['member.otd_collections.index'])) --}}
                                <li><a href="{{ route('member.group_wise.collection.list') }}"><i
                                            class="fa fa-calendar"></i>
                                        {{ __('common.list') }}</a></li>
                                {{-- @endif --}}



                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#">
                                <i
                                    class="fa fa-calendar"></i><span>{{ __('common.sub_group_wise_collection') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                {{-- @if ($user->can(['member.otd_collections.index'])) --}}
                                <li><a href="{{ route('member.sub_group_wise.collection.list') }}"><i
                                            class="fa fa-calendar"></i>
                                        {{ __('common.list') }}</a></li>
                                {{-- @endif --}}



                            </ul>
                        </li>


                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-calendar"></i><span>{{ __('common.opd_discount_summary') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                {{-- @if ($user->can(['member.otd_collections.index'])) --}}
                                <li><a href="{{ route('member.opd.discount.summary.list') }}"><i
                                            class="fa fa-calendar"></i>
                                        {{ __('common.list') }}</a></li>
                                {{-- @endif --}}

                            </ul>
                        </li>


                        <li class="treeview">

                            <a href="#">
                                <i
                                    class="fa fa-calendar"></i><span>{{ __('common.opd_specimen_wise_collection') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                {{-- @if ($user->can(['member.otd_collections.index'])) --}}
                                <li><a href="{{ route('member.specimen_wise.collection.list') }}"><i
                                            class="fa fa-calendar"></i>
                                        {{ __('common.list') }}</a></li>
                                {{-- @endif --}}

                            </ul>

                        </li>

                        <li class="treeview">

                            <a href="#">
                                <i class="fa fa-calendar"></i><span>{{ __('common.opd_balance_sheet') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                {{-- @if ($user->can(['member.otd_collections.index'])) --}}
                                <li><a href="{{ route('member.opd.balance_sheet.list') }}"><i
                                            class="fa fa-calendar"></i>
                                        {{ __('common.list') }}</a></li>
                                {{-- @endif --}}

                            </ul>

                        </li>
                    </ul>
                </li>

            @endif


            @if ($user->can(['member.share_holders.create', 'member.share_holders.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-clock-o"></i><span>{{ __('common.share_holders') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.share_holders.create']))
                            <li><a href="{{ route('member.share_holders.index') }}"><i class="fa fa-clock-o"></i>
                                    {{ __('common.list') }}</a></li>
                        @endif

                        @if ($user->can(['member.share_holders.create']))
                            <li><a href="{{ route('member.share_holders.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif
                    </ul>
                </li>

            @endif

            {{-- @if ($user->can(['member.share_holders.create', 'member.share_holders.index'])) --}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-clock-o"></i><span>{{ __('common.discount_report') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">

                    <li><a href="{{ route('member.opd.share_holder_report') }}"><i class="fa fa-file"
                                aria-hidden="true"></i></i>
                            {{ __('common.share_holder_report') }}</a></li>


                    <li><a href="{{ route('member.opd.test_discount_management_report') }}"><i class="fa fa-file"
                                aria-hidden="true"></i>{{ __('common.management_report') }} </a></li>

                    <li><a href="{{ route('member.opd.test_discount_other_report') }}"><i class="fa fa-file"
                                aria-hidden="true"></i>{{ __('common.other_report') }} </a></li>
                </ul>
            </li>

            {{-- @endif --}}


            @if ($user->can(['member.technologists.create', 'member.technologists.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-clock-o"></i><span>{{ __('common.technologists') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li><a href="{{ route('member.technologists.index') }}"><i class="fa fa-file"
                                    aria-hidden="true"></i></i>
                                {{ __('common.list') }}</a></li>


                    </ul>
                </li>
            @endif

            {{-- @if ($user->can(['member.technologists.create', 'member.technologists.index'])) --}}
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-clock-o"></i><span>{{ __('common.pathology_report') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li><a href="{{ route('member.fetch.sub_tes_group') }}"><i class="fa fa-file"
                                    aria-hidden="true"></i></i>
                                {{ __('common.test_list') }}</a></li>

                        <li><a href="{{ route('member.fetch.pathology_list') }}"><i class="fa fa-file"
                                    aria-hidden="true"></i></i>
                                {{ __('common.pathology_list') }}</a></li>

                    </ul>
                </li>
            {{-- @endif --}}


            @if ($user->hasRole(['super-admin', 'admin']) && $user->can(['admin.staff_support.index']))
                <li class="treeview">
                    <a href="#">
                        {{-- <i class="fa fa-torii-gate"></i> --}}
                        <i class="fa fa-support"></i><span>{{ __('common.staff_support') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li><a href="{{ route('admin.staff_support.index') }}"><i class="fa fa-support"></i>
                                {{ __('common.list') }}</a>
                        </li>

                    </ul>
                </li>
            @elseif($user->can(['member.support.index', 'member.support.create']))
                <li class="treeview">
                    <a href="#">
                        {{-- <i class="fa fa-torii-gate"></i> --}}
                        <i class="fa fa-support"></i><span>{{ __('common.supports') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.support.index']))
                            <li><a href="{{ route('member.support.index') }}">
                                    <i class="fa fa-support"></i> {{ __('common.support_list') }}</a>
                            </li>
                        @endif

                        @if ($user->can(['member.support.create']))
                            <li><a href="{{ route('member.support.create') }}">
                                    <i class="fa fa-plus-circle"></i> {{ __('common.support_request') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif


            @if (
                $user->can([
                    'member.process-attendances',
                    'member.checkinout-attendances',
                    'member.master-attendances',
                    'member.attendances.create',
                    'member.summary-attendances',
                ]))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar-check-o"></i><span>{{ __('common.attendance') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.process-attendances']))
                            <li><a href="{{ route('member.process-attendances') }}"><i class="fa fa-spinner"></i>
                                    {{ __('common.process_attendance') }}</a></li>
                        @endif


                        @if ($user->can(['member.checkinout-attendances']))
                            <li><a href="{{ route('member.checkinout-attendances') }}"><i
                                        class="fa fa-calendar-check-o"></i>
                                    {{ __('common.see_daily_checkin_out') }}</a></li>
                        @endif


                        @if ($user->can(['member.master-attendances']))
                            <li><a href="{{ route('member.master-attendances') }}"><i
                                        class="fa fa-calendar-check-o"></i>
                                    {{ __('common.see_attendance_master') }}</a></li>
                        @endif


                        @if ($user->can(['member.summary-attendances']))
                            <li><a href="{{ route('member.summary-attendances') }}"><i
                                        class="fa fa-calendar-check-o"></i> {{ __('common.attendance_summary') }}</a>
                            </li>
                        @endif

                        @if ($user->can(['member.attendances.create']))
                            <li><a href="{{ route('member.attendances.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.set_manual_attendance') }} </a></li>
                        @endif


                    </ul>
                </li>


            @endif

            @if ($user->can(['member.leaves.create', 'member.leaves.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar-minus-o"></i><span>{{ __('common.type_of_leaves') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.leaves.index']))
                            <li><a href="{{ route('member.leaves.index') }}"><i
                                        class="fa fa-calendar-minus-o"></i>{{ __('common.leave_list') }}</a></li>
                        @endif

                        @if ($user->can(['member.leaves.create']))
                            <li><a href="{{ route('member.leaves.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif

            @if (
                $user->can([
                    'member.employee-leave.create',
                    'member.employee-leaves.index',
                    'member.hr.employee-next-attends',
                    'member.hr.employee-on-leaves',
                ]))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar-minus-o"></i><span>{{ __('common.employee_leaves') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.employee-leaves.index']))
                            <li><a href="{{ route('member.employee-leaves.index') }}"><i
                                        class="fa fa-calendar-minus-o"></i>{{ __('common.employee_leave_list') }}</a>
                            </li>
                        @endif

                        @if ($user->can(['member.employee-leaves.create']))
                            <li><a href="{{ route('member.employee-leaves.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif

                        {{-- @if ($user->can(['member.hr.employee-on-leaves'])) --}}
                        <li><a href="{{ route('member.hr.employee-on-leaves') }}"><i
                                    class="fa fa-list"></i>{{ __('common.on_leave_list') }} </a></li>
                        {{-- @endif
                        @if ($user->can(['member.hr.employee-next-attends'])) --}}
                        <li><a href="{{ route('member.hr.employee-next-attends') }}"><i
                                    class="fa fa-list"></i>{{ __('common.next_day_attend') }} </a></li>
                        {{-- @endif --}}
                    </ul>
                </li>
            @endif


            @if (config('settings.employee'))

                @if ($user->can(['member.employee.create', 'member.salary_management.salary-update']))

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-money"></i> <span>{{ __('common.salary_management') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @if ($user->can(['member.salary_management.index']))
                                <li><a href="{{ route('member.salary_management.index') }}"><i
                                            class="fa fa-plus"></i>{{ __('common.generate_salary') }} </a></li>
                            @endif

                            @if ($user->can(['member.salary_management.salary-update']))
                                <li><a href="{{ route('member.salary_management.salary-update') }}"><i
                                            class="fa fa-plus"></i>{{ __('common.update_generate_salary') }} </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
            @endif
            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>Birth Certificate</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.birth_certificate.index') }}"><i
                                        class="fa fa-calendar"></i>
                                    List</a></li>
                        @endif

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.birth_certificate.create') }}"><i class="fa fa-plus"></i>
                                    Create</a></li>
                        @endif

                    </ul>
                </li>

            @endif
            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>Death Certificate</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.death_certificate.index') }}"><i
                                        class="fa fa-calendar"></i>
                                    List</a></li>
                        @endif

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.death_certificate.create') }}"><i class="fa fa-plus"></i>
                                    Create</a></li>
                        @endif

                    </ul>
                </li>

            @endif
            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>Driver</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.driver.index') }}"><i class="fa fa-calendar"></i>
                                    List</a></li>
                        @endif

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.driver.create') }}"><i class="fa fa-plus"></i> Create</a>
                            </li>
                        @endif

                    </ul>
                </li>

            @endif
            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>Ambulance Info</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.vehicle_info.index') }}"><i class="fa fa-calendar"></i>
                                    List</a></li>
                        @endif

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.vehicle_info.create') }}"><i
                                        class="fa fa-plus"></i> Create</a></li>
                        @endif

                    </ul>
                </li>

            @endif
            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>Ambulance Schedule</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.vehicle_schedule.index') }}"><i
                                        class="fa fa-calendar"></i>
                                    List</a></li>
                        @endif

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.vehicle_schedule.create') }}"><i class="fa fa-plus"></i>
                                    Create</a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.doctors.create', 'member.doctors.index']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span>Ambulance Booking</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ url('/member/reserve_vehicle') }}"><i class="fa fa-calendar"></i>
                                All Booking</a></li>
                        @endif

                        {{-- @if ($user->can(['member.doctors.index']))
                            <li><a href="{{ route('member.vehicle_detail.index') }}"><i class="fa fa-calendar"></i>
                                    All Booking</a></li>
                        @endif --}}

                        @if ($user->can(['member.doctors.create']))
                            <li><a href="{{ route('member.vehicle_detail.create') }}"><i class="fa fa-plus"></i>
                                    Create</a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.report.employee-attendance']))

                {{-- <li class="treeview"> --}}
                {{-- <a href="javascript:void(0)"> --}}
                {{-- <i class="fa fa-bar-chart"></i> --}}
                {{-- <span>{{ __('report.title') }}</span> --}}
                {{-- <i class="fa fa-angle-left pull-right"></i> --}}
                {{-- </a> --}}
                {{-- <ul class="treeview-menu"> --}}

                @if (config('settings.requisition') && config('settings.employee'))
                    @if ($user->can(['member.report.employee-attendance']))
                        {{-- <li class="treeview"> --}}
                        {{-- <a href="#"> --}}
                        {{-- <span>{{ __('Employees Report') }}</span> --}}
                        {{-- <i class="fa fa-angle-left pull-right"></i> --}}
                        {{-- </a> --}}
                        {{-- <ul class="treeview-menu"> --}}

                        {{-- <li><a href="{{ route('member.report.employee-attendance')}}"> Attendance Report </a></li> --}}


                        {{-- </ul> --}}
                        {{-- </li> --}}
                    @endif
                @endif
                {{-- </ul> --}}
                {{-- </li> --}}
            @endif



            @if ($user->can(['super-admin', 'admin']))
                <li class="header">{{ __('common.admin_settings') }}</li>
            @endif


            @if ($user->can(['member.users.create', 'member.users.index', 'member.users.set_users_company']))
                <li class="treeview">

                    <a href="#">
                        <i class="fa fa-users"></i> <span>{{ __('common.users') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if ($user->can(['member.users.index']))
                            <li><a href="{{ route('member.users.index') }}"><i class="fa fa-user"></i>
                                    {{ __('common.users') }}</a></li>
                        @endif


                        @if ($user->can(['member.users.create']))
                            <li><a href="{{ route('member.users.create') }}"><i
                                        class="fa fa-user-plus"></i>{{ __('common.add_user') }} </a></li>
                        @endif


                        @if (
                            $user->can(['member.users.set_users_company']) &&
                                ($user->hasRole(['super-admin']) || config('settings.multi_company')))
                            <li><a href="{{ route('member.users.set_users_company') }}">
                                    <i class="fa fa-user-plus"></i>
                                    {{ __('common.assign_user_company') }}</a></li>
                        @endif
                    </ul>
                </li>

            @endif

            @if ($user->can(['member.roles.create', 'member.roles.index']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-unlock-alt"></i> <span>{{ __('common.roles') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.roles.index']))
                            <li><a href="{{ route('admin.roles.index') }}"><i class="fa fa-unlock-alt"></i>
                                    {{ __('common.roles') }}</a></li>
                        @endif

                        @if ($user->can(['member.roles.create']))
                            <li><a href="{{ route('admin.roles.create') }}"><i class="fa fa-plus"></i>
                                    {{ __('common.add_role') }}
                                </a></li>
                        @endif

                    </ul>
                </li>

            @endif



        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
