<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

$route = \Auth::user()->can(['member.doctors.index']) ? route('member.doctors.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Patient Details',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Patient Details',
    'title' => 'Create Hospital Service',
    'heading' => 'Patient Details',
];

?>



@extends('layouts.back-end.master', $data)
@push('style')
    <style>
        /* .scrtabs-tab-container {
            display: flex;
            width: 100%;
            max-width: 100%;
        }
        .scrtabs-tab-scroll-arrow {
            border: 1px solid #dddddd;
            border-top: none;
            color: #428bca;
            display: none;
            float: left;
            font-size: 12px;
            height: 42px;
            margin-bottom: -1px;

            padding-top: 13px;
            text-align: center;
            width: 20px;
            flex: 1 0 20px;
        }
        .scrtabs-tabs-fixed-container {
            flex: 1 0 calc(100% - 40px) !important;
            max-width: calc(100% - 40px) !important;
            width: calc(100% - 40px) !important;
        }
        ul.nav.nav-tabs.navlistscroll {
            display: flex;
            overflow-x: auto;
            flex-wrap: wrap;
            width: 100%;
        } */
        .navlistscroll {
            overflow-x: auto;
            overflow-y: hidden;
            display: -webkit-box;
            display: -moz-box;
        }

        .scrtabs-tabs-movable-container {
            position: relative;
        }

        .scrtabs-tab-container {
            height: 42px;
            position: relative;
        }

        .scrtabs-tab-container .tab-content {
            clear: left;
        }

        .scrtabs-tab-container.scrtabs-bootstrap4 .scrtabs-tabs-movable-container>.navbar-nav {
            -ms-flex-direction: row;
            flex-direction: row;
        }

        .scrtabs-tabs-fixed-container {
            float: left;
            height: 42px;
            overflow: hidden;
            position: absolute;
            left: 20px;
            border-bottom: 1px solid #ddd;
            width: 100%;
        }

        .scrtabs-tab-container {
            overflow: hidden;
        }

        .scrtabs-tabs-movable-container {
            position: relative;
        }

        .scrtabs-tabs-movable-container .tab-content {
            display: none;
        }

        .scrtabs-tab-container.scrtabs-rtl .scrtabs-tabs-movable-container>ul.nav-tabs {
            padding-right: 0;
        }

        .scrtabs-tab-scroll-arrow {
            border: 1px solid #dddddd;
            border-top: none;
            color: #428bca;
            display: none;
            float: left;
            font-size: 12px;
            height: 42px;
            margin-bottom: -1px;
            /*padding-left: 2px;*/
            padding-top: 13px;
            text-align: center;
            width: 20px;
        }

        .scrtabs-tab-scroll-arrow:hover {
            background-color: #eeeeee;
        }

        .scrtabs-js-tab-scroll-arrow-left {
            border-left: 0
        }

        .scrtabs-js-tab-scroll-arrow-right {
            border-right: 0
        }

        .scrtabs-tab-scroll-arrow,
        .scrtabs-tab-scroll-arrow .scrtabs-click-target {
            cursor: pointer;
        }

        .scrtabs-tab-scroll-arrow.scrtabs-with-click-target {
            cursor: default;
        }

        .scrtabs-tab-scroll-arrow.scrtabs-disable,
        .scrtabs-tab-scroll-arrow.scrtabs-disable .scrtabs-click-target {
            color: #ddd;
            cursor: default;
        }

        .scrtabs-tab-scroll-arrow.scrtabs-disable:hover {
            background-color: initial;
        }

        .scrtabs-tabs-fixed-container ul.nav-tabs>li {
            white-space: nowrap;
        }

        .scrtabs-tabs-fixed-container ul.nav-tabs>li a {
            color: #444;
            border: 0;
        }

        /*.scrtabs-tabs-fixed-container ul.nav-tabs > li a:hover{color: #999;border: 0;
            border-bottom:3px solid transparent;}*/

        .scrtabs-tabs-fixed-container ul.nav-tabs>li.active>a,
        .scrtabs-tabs-fixed-container ul.nav-tabs>li.active:hover>a {
            background-color: #fff;
            color: #0183cc;
            /*padding-left: 30px;*/
            border: 0;
            border-bottom: 3px solid #0283cc;
        }

        .scrtabs-js-tab-scroll-arrow-right {
            display: block;
            float: right;
            margin-top: 0;
            background: #fff;
            position: absolute;
            top: 0;
            right: 0;
        }

        /*@media (max-width: 768px) {
         .nav-tabs>li {
            width: 100%;
        }
        }
        */

        /*data table fixed*/
        .dataTables_scrollBody {
            overflow: visible !important;
        }

        /*data table*/
        /*data table header fixed*/
        .dataTables_scrollHeadInner,
        .table {
            width: 100% !important;
        }
    </style>
@endpush
@section('contents')
    <div class="row">

        <div class="col-md-12">

            @include('common._alert')

            @include('common._error')

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Patient</h3>
                </div>
                <div class="box box-primary">
                    <div class="box border0 mb0">
                        <div class="nav-tabs-custom border0 mb0" id="tabs">
                            <div class="scrtabs-tab-container" style="visibility: visible;">
                                <div class="scrtabs-tab-scroll-arrow scrtabs-js-tab-scroll-arrow-left"
                                    style="display: block;"><span class="fa fa-chevron-left"></span></div>
                                <div class="scrtabs-tabs-fixed-container" style="width: 1076px;">
                                    <div class="scrtabs-tabs-movable-container" style="width: 1626px; left: 0px;">
                                        <ul class="nav nav-tabs navlistscroll">
                                            <li class="active"><a href="#overview" data-toggle="tab" aria-expanded="true"><i
                                                        class="fa fa-th"></i> Overview</a></li>
                                            <li class="">
                                                <a href="#nurse_note" data-toggle="tab" aria-expanded="false"><i
                                                        class="fas fa-sticky-note"></i> Nurse Notes</a>
                                            </li>
                                            <li class="">
                                                <a href="#medication" class="medication" data-toggle="tab"
                                                    aria-expanded="false"><i class="fa fa-medkit" aria-hidden="true"></i>
                                                    Medication</a>
                                            </li>

                                            <li class="">
                                                <a href="#prescription" data-toggle="tab" aria-expanded="false"><i
                                                        class="far fa-calendar-check"></i> Prescription</a>
                                            </li>

                                            <li class="">
                                                <a href="#consultant_register" data-toggle="tab" aria-expanded="false"><i
                                                        class="fas fa-file-prescription"></i> Consultant Register</a>
                                            </li>

                                            <li class="">
                                                <a href="#labinvestigation" data-toggle="tab" aria-expanded="false"><i
                                                        class="fas fa-diagnoses"></i> Lab Investigation</a>
                                            </li>
                                            <li class="">
                                                <a href="#operationtheatre" class="operationtheatre" data-toggle="tab"
                                                    aria-expanded="false"><i class="fas fa-cut" aria-hidden="true"></i>
                                                    Operations</a>

                                            </li>


                                            <li class="">
                                                <a href="#charges" data-toggle="tab" aria-expanded="false"><i
                                                        class="fas fa-donate"></i> Charges</a>
                                            </li>

                                            <li class="">
                                                <a href="#payment" data-toggle="tab" aria-expanded="false"><i
                                                        class="fas fa-hand-holding-usd"></i> Payments</a>
                                            </li>
                                            <li>
                                                <a href="#live_consult" class="live_consult" data-toggle="tab"
                                                    aria-expanded="true"><i class="fa fa-video-camera ftlayer"></i> Live
                                                    Consultation</a>
                                            </li>
                                            <li>
                                                <a href="#bed_history" class="bed_history" data-toggle="tab"
                                                    aria-expanded="true"><i class="fas fa-procedures"></i> Bed History</a>
                                            </li>


                                            <li>
                                                <a href="#timeline" data-toggle="tab" aria-expanded="true"><i
                                                        class="far fa-calendar-check"></i> Timeline</a>
                                            </li>




                                            <li>
                                                <a href="#treatment_history" data-toggle="tab" aria-expanded="true"><i
                                                        class="fas fa-hourglass-half"></i> Treatment History</a>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                                <div class="scrtabs-tab-scroll-arrow scrtabs-js-tab-scroll-arrow-right"
                                    style="display: block;"><span class="fa fa-chevron-right"></span></div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane tab-content-height active" id="overview">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 border-r">
                                            <div class="box-header border-b mb10 pl-0 pt0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">Nishant Kadakia
                                                    (980)</h3>
                                                <div class="pull-right">
                                                    <div class="editviewdelete-icon pt8">
                                                        <a class="" href="#" onclick="getRecord('97')"
                                                            data-toggle="tooltip" title="Profile"><i
                                                                class="fa fa-reorder"></i>
                                                        </a>
                                                        <a class="" href="#" onclick="getEditRecord('97')"
                                                            data-toggle="tooltip" title="Edit Profile"><i
                                                                class="fa fa-pencil"></i>
                                                        </a>

                                                        <a class="patient_discharge" href="#" data-toggle="tooltip"
                                                            title="Patient Discharge"><i class="fa fa-hospital-o"></i>
                                                        </a>


                                                        <a class="" href="#" onclick="deleteIpdPatient('97')"
                                                            data-toggle="tooltip" title="Delete Patient"><i
                                                                class="fa fa-trash"></i>
                                                        </a>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-sm-12 ptt10">

                                                    <img width="115" height="115"
                                                        class="profile-user-img img-responsive img-rounded"
                                                        src="https://demo.smart-hospital.in/uploads/patient_images/xuzOY_980.jpg?1718257701">

                                                </div>
                                                <div class="col-lg-9 col-md-8 col-sm-12">
                                                    <table class="table table-bordered mb0">
                                                        <tbody>
                                                            <tr>
                                                                <td class="bolds">Gender</td>
                                                                <td>Male</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="bolds">Age</td>
                                                                <td>25 Year 20 Days</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="bolds">Guardian Name</td>
                                                                <td>Dinesh Kadakia</td>
                                                            </tr>

                                                            <tr>
                                                                <td class="bolds">Phone</td>
                                                                <td>94564651414</td>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr class="hr-panel-heading hr-10">
                                            <div class="row">
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="align-content-center pt25">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="bolds">Case ID</td>
                                                                    <td>6290</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="bolds">IPD No</td>
                                                                    <td>IPDN97</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="white-space-nowrap bolds" width="40%">
                                                                        Admission Date</td>
                                                                    <td>06/15/2024 02:51 PM</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="bolds">Bed</td>
                                                                    <td>FF - 117 - AC (Normal) - 1st Floor</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <div class="chart-responsive text-center">
                                                        <div class="chart">
                                                            <canvas id="pieChart" style="height: 150px; width: 166px;"
                                                                width="166" height="150"><span></span></canvas>
                                                        </div>

                                                        <p class="font12 mb0 font-medium">Credit Limit: $20000</p>
                                                        <p class="font12 mb0 font-medium text-danger">Used Credit Limit:
                                                            $-1553</p>
                                                        <p class="font12 mb0 font-medium text-success-xl">Balance Credit
                                                            Limit: $21553</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="hr-panel-heading hr-10">
                                            <p><b><i class="fa fa-tag"></i> Known Allergies</b></p>

                                            <ul class="list-pl-3">
                                                <li>
                                                    <div>Sea Food</div>
                                                </li>
                                            </ul>

                                            <hr class="hr-panel-heading hr-10">
                                            <p><b><i class="fa fa-tag"></i> Finding</b></p>
                                            <ul class="list-pl-3">

                                                <li>
                                                    Elevated temperature (above 100.4°)
                                                    The medical community generally defines a fever as a body temperature
                                                    above 100.4 degrees Fahrenheit. A body temp between 100.4 and 102.2
                                                    degree is usually considered a low-grade fever. </li>

                                            </ul>

                                            <hr class="hr-panel-heading hr-10">
                                            <p><b><i class="fa fa-tag"></i> Symptoms</b></p>
                                            <hr class="hr-panel-heading hr-10">
                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">Consultant
                                                    Doctor</h3>
                                                <div class="pull-right">
                                                    <div class="editviewdelete-icon pt8">
                                                        <a href="#" class=" dropdown-toggle adddoctor"
                                                            onclick="get_doctoripd('97')" title="Add Doctor"
                                                            data-toggle="tooltip"><i class="fa fa-plus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="staff-members">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <a href="https://demo.smart-hospital.in/admin/staff/profile/11">
                                                            <img src="https://demo.smart-hospital.in/uploads/staff_images/11.jpg?1718257701"
                                                                class="member-profile-small media-object"></a>
                                                    </div>
                                                    <div class="media-body">

                                                        <h5 class="media-heading"><a
                                                                href="https://demo.smart-hospital.in/admin/staff/profile/11">Amit
                                                                Singh (9009)</a>


                                                        </h5>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">Nurse Notes
                                                </h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="timeline-header no-border pb1">
                                                <div id="timeline_list">
                                                    <ul class="timeline timeline-inverse">

                                                        <li class="time-label">
                                                            <span class="bg-blue">
                                                                06/22/2024 05:39 PM</span>
                                                        </li>
                                                        <li>
                                                            <i class="fa fa-list-alt bg-blue"></i>
                                                            <div class="timeline-item">



                                                                <h3 class="timeline-header text-aqua"> April Clinton ( 9020
                                                                    ) </h3>

                                                                <div class="timeline-body">
                                                                    Note<br>Take medicine after meal everyday .
                                                                </div>

                                                                <div class="timeline-body">
                                                                    Comment<br> Take medicine after meal everyday .
                                                                </div>



                                                            </div>
                                                        </li>

                                                        <li><i class="fa fa-clock-o bg-gray"></i></li>

                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">Timeline</h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="timeline-header no-border">
                                                <div id="timeline_list">
                                                    <br>



                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6 project-progress-bars">
                                                    <div class="row">
                                                        <div class="col-md-12 mtop5">
                                                            <div class="topprograssstart">
                                                                <h5 class="text-uppercase mt5 bolds">IPD
                                                                    Payment/Billing<span
                                                                        class="pull-right text-gray-light"><i
                                                                            class="fas fa-procedures"></i></span>
                                                                </h5>
                                                                <p class="text-muted bolds mb4">0%<span
                                                                        class="pull-right"> $1800.00/$0</span></p>
                                                                <div class="progress-group">
                                                                    <div class="progress progress-minibar">
                                                                        <div class="progress-bar progress-bar-aqua"
                                                                            style="width: 0%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 project-progress-bars">
                                                    <div class="row">
                                                        <div class="col-md-12 mtop5">
                                                            <div class="topprograssstart">
                                                                <h5 class="text-uppercase mt5 bolds">Pharmacy
                                                                    Payment/Billing<span
                                                                        class="pull-right text-gray-light"><i
                                                                            class="fas fa-mortar-pestle"></i></span>
                                                                </h5>
                                                                <p class="text-muted bolds mb4">65.79%<span
                                                                        class="pull-right"> $250/$380.00</span></p>
                                                                <div class="progress-group">
                                                                    <div class="progress progress-minibar">
                                                                        <div class="progress-bar progress-bar-aqua"
                                                                            style="width: 65.79%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-md-6 project-progress-bars">
                                                    <div class="row">
                                                        <div class="col-md-12 mtop5">
                                                            <div class="topprograssstart">
                                                                <h5 class="text-uppercase mt5 bolds">Pathology
                                                                    Payment/Billing<span
                                                                        class="pull-right text-gray-light"><i
                                                                            class="fas fa-flask"></i></span>
                                                                </h5>
                                                                <p class="text-muted bolds mb4">76.92%<span
                                                                        class="pull-right"> $150.00/$195.00</span></p>
                                                                <div class="progress-group">
                                                                    <div class="progress progress-minibar">
                                                                        <div class="progress-bar progress-bar-aqua"
                                                                            style="width: 76.92%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 project-progress-bars">
                                                    <div class="row">
                                                        <div class="col-md-12 mtop5">
                                                            <div class="topprograssstart">
                                                                <h5 class="text-uppercase mt5 bolds">Radiology
                                                                    Payment/Billing<span
                                                                        class="pull-right text-gray-light"><i
                                                                            class="fas fa-microscope"></i></span>
                                                                </h5>
                                                                <p class="text-muted bolds mb4">62.50%<span
                                                                        class="pull-right"> $120.00/$192.00</span></p>
                                                                <div class="progress-group">
                                                                    <div class="progress progress-minibar">
                                                                        <div class="progress-bar progress-bar-aqua"
                                                                            style="width: 62.50%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-6 project-progress-bars">
                                                    <div class="row">
                                                        <div class="col-md-12 mtop5">
                                                            <div class="topprograssstart">
                                                                <h5 class="text-uppercase mt5 bolds">Blood Bank
                                                                    Payment/Billing<span
                                                                        class="pull-right text-gray-light"><i
                                                                            class="fas fa-tint"></i></span>
                                                                </h5>
                                                                <p class="text-muted bolds mb4">100.00%<span
                                                                        class="pull-right"> $121.00/$121.00</span></p>
                                                                <div class="progress-group">
                                                                    <div class="progress progress-minibar">
                                                                        <div class="progress-bar progress-bar-aqua"
                                                                            style="width: 100.00%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 project-progress-bars">
                                                    <div class="row">
                                                        <div class="col-md-12 mtop5">
                                                            <div class="topprograssstart">
                                                                <h5 class="text-uppercase mt5 bolds">Ambulance
                                                                    Payment/Billing<span
                                                                        class="pull-right text-gray-light"><i
                                                                            class="fas fa-ambulance"></i></span>
                                                                </h5>
                                                                <p class="text-muted bolds mb4">100.00%<span
                                                                        class="pull-right"> $195.00/$195.00</span></p>
                                                                <div class="progress-group">
                                                                    <div class="progress progress-minibar">
                                                                        <div class="progress-bar progress-bar-aqua"
                                                                            style="width: 100.00%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-header pl-0">
                                                <h3 class="text-uppercase bolds mt0 mb0 ptt10 pull-left font14">Medication
                                                </h3>
                                                <div class="pull-right">
                                                </div>
                                            </div>

                                            <div class="box-header pl-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered mb0">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Medicine Name</th>
                                                                <th>Dose</th>
                                                                <th>Time</th>
                                                                <th>Remark</th>
                                                            </tr>
                                                            <tr>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>06/22/2024</td>
                                                                <td>WORMSTOP</td>
                                                                <td>1 (MG)</td>
                                                                <td> 01:00 PM</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>06/22/2024</td>
                                                                <td>WORMSTOP</td>
                                                                <td>1 (MG)</td>
                                                                <td> 09:00 PM</td>
                                                                <td></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <hr class="hr-panel-heading hr-10">
                                            <div class="box-header pl-0">
                                                <h3 class="text-uppercase bolds mt0 mb0 ptt10 pull-left font14">
                                                    Prescription</h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="box-header pl-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered   mb0">
                                                        <thead>
                                                            <tr>
                                                                <th>Prescription No</th>
                                                                <th>Date</th>
                                                                <th>Finding</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td>IPDP294</td>
                                                                <td>06/01/2024</td>
                                                                <td>Elevated temperature (above 100.4°)
                                                                    The medical community generally defines a fever as a
                                                                    body temperature above 100.4 degrees Fahrenheit. A body
                                                                    temp between 100.4 and 102.2 degree is usually
                                                                    considered a low-grade fever. </td>

                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr class="hr-panel-heading hr-10">
                                            <div class="box-header pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 mb0 pull-left font14">Consultant
                                                    Register</h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="box-header pl-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered  mb0">
                                                        <thead>
                                                            <tr>
                                                                <th>Applied Date</th>
                                                                <th>Consultant Doctor</th>
                                                                <th>Instruction</th>
                                                                <th>Instruction Date</th>


                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td>06/22/2024 05:40 PM</td>
                                                                <td>Reyan Jain (9011)</td>
                                                                <td>Take medicine after meal everyday .</td>
                                                                <td>06/22/2024</td>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="box-header pl-0">
                                                <h3 class="text-uppercase bolds mt0 mb0 ptt10 pull-left font14">Lab
                                                    Investigation</h3>
                                                <div class="pull-right">
                                                </div>
                                            </div>
                                            <div class="box-header pl-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered  mb0"
                                                        data-export-title="Lab Investigation">
                                                        <thead>
                                                            <tr>
                                                                <th>Test Name</th>
                                                                <th>Lab</th>
                                                                <th>Sample Collected</th>
                                                                <td><strong>Expected Date</strong></td>
                                                                <th>Approved By</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="">
                                                            <tr>
                                                                <td>Abdomen X-rays<br>
                                                                    (AX)</td>
                                                                <td>Pathology</td>
                                                                <td><label>
                                                                        Belina Turner (9005) </label>

                                                                    <br>
                                                                    <label for="">Pathology : </label>
                                                                    In-House Pathology Lab <br>
                                                                    06/22/2024
                                                                </td>

                                                                <td>
                                                                    06/23/2024
                                                                </td>
                                                                <td class="text-left">
                                                                    <label for="">Approved By : </label>
                                                                    Reyan Jain (9011) <br>
                                                                    06/24/2024
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>Functional MRI (RI)<br>
                                                                    (FMRI)</td>
                                                                <td>Radiology</td>
                                                                <td><label>
                                                                        John Hook (9006) </label>

                                                                    <br>
                                                                    <label for="">Radiology : </label>
                                                                    In-House Radiology Lab <br>
                                                                    06/21/2024
                                                                </td>

                                                                <td>
                                                                    06/22/2024
                                                                </td>
                                                                <td class="text-left">
                                                                    <label for="">Approved By : </label>
                                                                    Reyan Jain (9011) <br>
                                                                    06/23/2024
                                                                </td>

                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>

                                            <hr class="hr-panel-heading hr-10">
                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 mb0 ptt10 pull-left font14">Operation
                                                </h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered  mb0">
                                                        <thead>
                                                            <tr>
                                                                <th>Reference No</th>
                                                                <th>Operation Date</th>
                                                                <th>Operation Name</th>
                                                                <th>Operation Category</th>
                                                                <th>OT Technician</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td>OTREF215</td>
                                                                <td>06/22/2024 09:30 PM</td>
                                                                <td> Facelift Surgery</td>
                                                                <td>Plastic Surgery</td>
                                                                <td>Vinesh</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                            <hr class="hr-panel-heading hr-10">


                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 mb0 ptt10 pull-left font14">Charges
                                                </h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <div class="table-responsive">
                                                    <div class="box-header mb10 pl-0">
                                                        <h3
                                                            class="text-uppercase bolds mt0 mb0 ptt10 mb0 pull-left font14">
                                                            Payment</h3>
                                                        <div class="pull-right">

                                                        </div>
                                                    </div>
                                                    <div class="box-header mb10 pl-0">
                                                        <div class="table-responsive">
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped table-bordered  mb0">



                                                    </table>
                                                    <table class="table table-striped table-bordered  mb0">
                                                        <thead>
                                                            <tr>
                                                                <th>Transaction ID</th>
                                                                <th>Date</th>
                                                                <th>Note</th>
                                                                <th>Payment Mode</th>
                                                                <th class="text-right">Paid Amount ($)</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td>TRANID9156</td>
                                                                <td>06/26/2024 05:42 PM</td>
                                                                <td></td>
                                                                <td style="text-transform: capitalize;">Cash<br> </td>
                                                                <td class="text-right">1800.00</td>


                                                            </tr>


                                                        </tbody>













                                                    </table>
                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 mb0 ptt10 pull-left font14">Live
                                                    Consultation</h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <div class="table-responsive">
                                                </div>
                                            </div>


                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">Treatment
                                                    History</h3>
                                                <div class="pull-right">
                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <div class="table-responsive">
                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">Bed History
                                                </h3>
                                                <div class="pull-right">

                                                </div>
                                            </div>
                                            <div class="box-header mb10 pl-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered ">
                                                        <thead>
                                                            <tr>
                                                                <th>Bed Group</th>
                                                                <th>Bed </th>
                                                                <th>From Date</th>
                                                                <th>To Date</th>
                                                                <th>Active Bed</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="mailbox-name">AC (Normal)</td>
                                                                <td class="mailbox-name">FF - 117</td>
                                                                <td class="mailbox-name">06/15/2024 02:51 PM</td>
                                                                <td class="mailbox-name"></td>
                                                                <td class="mailbox-name">Yes</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane tab-content-height" id="nurse_note">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Nurse Notes</h3>
                                        <div class="box-tab-tools">

                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addnursenote"
                                                onclick="holdModal('add_nurse_note')" data-toggle="modal"><i
                                                    class="fas fa-plus"></i> Add Nurse Note</a>


                                        </div>
                                    </div>

                                    <div class="download_label">Nishant Kadakia (980) IPD Details</div>

                                    <div id="">
                                        <ul class="timeline timeline-inverse">

                                            <li class="time-label">
                                                <span class="bg-blue">
                                                    06/22/2024 05:39 PM</span>
                                            </li>
                                            <li>
                                                <i class="fa fa-list-alt bg-blue"></i>
                                                <div class="timeline-item">
                                                    <span class="time">

                                                        <a class="btn btn-default btn-xs" data-toggle="tooltip"
                                                            title=""
                                                            onclick="delete_record('https://demo.smart-hospital.in/admin/patient/deleteIpdnursenote/131/97', 'Record Deleted Successfully')"
                                                            data-original-title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </span>


                                                    <span class="time">
                                                        <a onclick="addcommentNursenote('131',97)"
                                                            class="defaults-c text-right" data-toggle="tooltip"
                                                            title="" data-original-title="Comment">
                                                            <i class="fa fa-comment"></i>
                                                        </a>
                                                    </span>
                                                    <span class="time">
                                                        <a onclick="editNursenote('131')" class="defaults-c text-right"
                                                            data-toggle="tooltip" title=""
                                                            data-original-title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    </span>



                                                    <h3 class="timeline-header text-aqua"> April Clinton ( 9020 ) </h3>

                                                    <div class="timeline-body">
                                                        <b>Note</b><br>Take medicine after meal everyday .
                                                    </div>
                                                    <div class="timeline-body">
                                                        <b>Comment</b><br> Take medicine after meal everyday .
                                                    </div>



                                                </div>
                                            </li>

                                            <li><i class="fa fa-clock-o bg-gray"></i></li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-pane tab-content-height" id="consultant_register">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Consultant Register</h3>
                                        <div class="box-tab-tools">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addconsultant"
                                                onclick="holdModal('add_instruction')" data-toggle="modal"><i
                                                    class="fas fa-plus"></i> Consultant Register</a>

                                        </div>
                                    </div>


                                    <div class="download_label">Nishant Kadakia Consultant Register</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_1" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_1" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_1" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_1" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_1" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a> </div>
                                            <div id="DataTables_Table_1_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_1"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_1" role="grid"
                                                aria-describedby="DataTables_Table_1_info" style="width: 1113px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_1" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Applied Date: activate to sort column ascending"
                                                            style="width: 218px;">Applied Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_1" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Consultant Doctor: activate to sort column ascending"
                                                            style="width: 209px;">Consultant Doctor</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_1" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Instruction: activate to sort column ascending"
                                                            style="width: 355px;">Instruction</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_1" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Instruction Date: activate to sort column ascending"
                                                            style="width: 188px;">Instruction Date</th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_1" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Action: activate to sort column ascending"
                                                            style="width: 93px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>



                                                    <tr role="row" class="odd">
                                                        <td>06/22/2024 05:40 PM</td>
                                                        <td>Reyan Jain (9011)</td>
                                                        <td>Take medicine after meal everyday .</td>
                                                        <td>06/22/2024</td>

                                                        <td class="text-right">

                                                            <a onclick="editConsultantRegister('137')"
                                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                                title="" data-original-title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a class="btn btn-default btn-xs" data-toggle="tooltip"
                                                                title=""
                                                                onclick="delete_record('https://demo.smart-hospital.in/admin/patient/deleteIpdPatientConsultant/137', 'Record Deleted Successfully')"
                                                                data-original-title="Delete">
                                                                <i class="fa fa-trash"></i>

                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_1_info" role="status"
                                                aria-live="polite">Records: 1 to 1 of 1</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_1_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_1" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_1_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current" aria-controls="DataTables_Table_1"
                                                        data-dt-idx="1" tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_1" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_1_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tab-content-height" id="prescription">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Prescription</h3>
                                        <div class="box-tab-tools">
                                            <a href="#"
                                                class="btn btn-sm btn-primary dropdown-toggle addprescription"
                                                data-toggle="modal"><i class="fas fa-plus"></i> Add Prescription</a>
                                        </div>
                                    </div>

                                    <div class="download_label">Nishant Kadakia IPD Details</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_2_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_2" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_2" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_2" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_2" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_2" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a> </div>
                                            <div id="DataTables_Table_2_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_2"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_2" role="grid"
                                                aria-describedby="DataTables_Table_2_info" style="width: 1113px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_2" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Prescription No: activate to sort column ascending"
                                                            style="width: 94px;">Prescription No</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_2" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Date: activate to sort column ascending"
                                                            style="width: 67px;">Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_2" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Finding: activate to sort column ascending"
                                                            style="width: 865px;">Finding</th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_2" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Action: activate to sort column ascending"
                                                            style="width: 47px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>



                                                    <tr role="row" class="odd">
                                                        <td>IPDP294</td>
                                                        <td>06/01/2024</td>
                                                        <td>Elevated temperature (above 100.4°)
                                                            The medical community generally defines a fever as a body
                                                            temperature above 100.4 degrees Fahrenheit. A body temp between
                                                            100.4 and 102.2 degree is usually considered a low-grade fever.
                                                        </td>
                                                        <td class="text-right">
                                                            <a href="#prescription" class="btn btn-default btn-xs"
                                                                onclick="view_prescription('294', '97','no')"
                                                                data-toggle="tooltip" title="View Prescription">
                                                                <i class="fas fa-file-prescription"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_2_info" role="status"
                                                aria-live="polite">Records: 1 to 1 of 1</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_2_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_2" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_2_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current" aria-controls="DataTables_Table_2"
                                                        data-dt-idx="1" tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_2" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_2_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="tab-pane tab-content-height" id="labinvestigation">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Lab Investigation</h3>
                                        <div class="box-tab-tools">

                                        </div>
                                    </div>

                                    <div class="download_label">Nishant Kadakia Lab Investigation</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_3_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_3" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_3" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_3" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_3" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_3" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a> </div>
                                            <div id="DataTables_Table_3_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_3"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                data-export-title="Lab Investigation" id="DataTables_Table_3"
                                                role="grid" aria-describedby="DataTables_Table_3_info"
                                                style="width: 1115px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_3" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Test Name: activate to sort column ascending"
                                                            style="width: 165px;">Test Name</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_3" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Lab: activate to sort column ascending"
                                                            style="width: 85px;">Lab</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_3" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Sample Collected: activate to sort column ascending"
                                                            style="width: 304px;">Sample Collected</th>
                                                        <td class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_3" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Expected Date: activate to sort column ascending"
                                                            style="width: 146px;"><strong>Expected Date</strong></td>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_3" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Approved By: activate to sort column ascending"
                                                            style="width: 276px;">Approved By</th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_3" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Action: activate to sort column ascending"
                                                            style="width: 79px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="">


                                                    <tr role="row" class="odd">
                                                        <td>Abdomen X-rays<br>
                                                            (AX)</td>
                                                        <td>Pathology</td>
                                                        <td><label>
                                                                Belina Turner (9005) </label>

                                                            <br>
                                                            <label for="">Pathology : </label>

                                                            In-House Pathology Lab <br>
                                                            06/22/2024
                                                        </td>

                                                        <td>
                                                            06/23/2024
                                                        </td>
                                                        <td class="text-left">
                                                            <label for="">Approved By : </label>
                                                            Reyan Jain (9011) <br>
                                                            06/24/2024
                                                        </td>
                                                        <td class="text-right"><a href="javascript:void(0)"
                                                                data-loading-text="<i class=&quot;fa fa-reorder&quot;></i>"
                                                                data-record-id="719" data-type-id="pathology"
                                                                class="btn btn-default btn-xs view_report"
                                                                data-toggle="tooltip" title="Show"><i
                                                                    class="fa fa-reorder"></i></a></td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td>Functional MRI (RI)<br>
                                                            (FMRI)</td>
                                                        <td>Radiology</td>
                                                        <td><label>
                                                                John Hook (9006) </label>

                                                            <br>
                                                            <label for="">Radiology : </label>

                                                            In-House Radiology Lab <br>
                                                            06/21/2024
                                                        </td>

                                                        <td>
                                                            06/22/2024
                                                        </td>
                                                        <td class="text-left">
                                                            <label for="">Approved By : </label>
                                                            Reyan Jain (9011) <br>
                                                            06/23/2024
                                                        </td>
                                                        <td class="text-right"><a href="javascript:void(0)"
                                                                data-loading-text="<i class=&quot;fa fa-reorder&quot;></i>"
                                                                data-record-id="555" data-type-id="radiology"
                                                                class="btn btn-default btn-xs view_report"
                                                                data-toggle="tooltip" title="Show"><i
                                                                    class="fa fa-reorder"></i></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_3_info" role="status"
                                                aria-live="polite">Records: 1 to 2 of 2</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_3_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_3" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_3_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current" aria-controls="DataTables_Table_3"
                                                        data-dt-idx="1" tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_3" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_3_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane tab-content-height" id="timeline">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Timeline</h3>
                                        <div class="box-tab-tools">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addtimeline"
                                                onclick="holdModal('myTimelineModal')" data-toggle="modal"><i
                                                    class="fa fa-plus"></i> Add Timeline</a>
                                        </div>
                                    </div>

                                    <div class="download_label">Nishant Kadakia IPD Details</div>
                                    <div class="timeline-header no-border">
                                        <div id="timeline_list">
                                            <br>
                                            <div class="alert alert-info">No Record Found</div>


                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane tab-content-height" id="live_consult">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Live Consultation</h3>
                                        <div class="box-tab-tools">

                                        </div>
                                    </div>
                                    <div class="download_label">Nishant Kadakia IPD Details</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_4_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_4" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_4" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_4" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_4" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_4" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a> </div>
                                            <div id="DataTables_Table_4_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_4"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_4" role="grid"
                                                aria-describedby="DataTables_Table_4_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Consultation Title: activate to sort column ascending"
                                                            style="width: 0px;">Consultation Title</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1" style="width: 0px;"
                                                            aria-label="Date: activate to sort column ascending">Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1" style="width: 0px;"
                                                            aria-label="Created By : activate to sort column ascending">
                                                            Created By </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1" style="width: 0px;"
                                                            aria-label="Created For: activate to sort column ascending">
                                                            Created For</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1" style="width: 0px;"
                                                            aria-label="Patient: activate to sort column ascending">Patient
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1" style="width: 0px;"
                                                            aria-label="Status: activate to sort column ascending">Status
                                                        </th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_4" rowspan="1"
                                                            colspan="1" style="width: 0px;"
                                                            aria-label="Action: activate to sort column ascending">Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>


                                                    <tr class="odd">
                                                        <td valign="top" colspan="7" class="dataTables_empty">
                                                            <div align="center">No data available in table <br> <br><img
                                                                    src="https://smart-hospital.in/shappresource/images/addnewitem.svg"
                                                                    width="150"><br><br> <span
                                                                    class="text-success bolds"><i
                                                                        class="fa fa-arrow-left"></i> Add new record or
                                                                    search with different criteria.</span>
                                                                <div></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_4_info" role="status"
                                                aria-live="polite">Records: 0 to 0 of 0</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_4_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_4" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_4_previous"><i
                                                        class="fa fa-angle-left"></i></a><span></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_4" data-dt-idx="1" tabindex="0"
                                                    id="DataTables_Table_4_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tab-content-height" id="bed_history">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Bed History</h3>
                                        <div class="box-tab-tools">
                                        </div>
                                    </div>
                                    <div class="download_label"></div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_5_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_5" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_5" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_5" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_5" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_5" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a> </div>
                                            <div id="DataTables_Table_5_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_5"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_5" role="grid"
                                                aria-describedby="DataTables_Table_5_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_5" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Bed Group: activate to sort column ascending"
                                                            style="width: 0px;">Bed Group</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_5" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Bed : activate to sort column ascending"
                                                            style="width: 0px;">Bed </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_5" rowspan="1"
                                                            colspan="1"
                                                            aria-label="From Date: activate to sort column ascending"
                                                            style="width: 0px;">From Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_5" rowspan="1"
                                                            colspan="1"
                                                            aria-label="To Date: activate to sort column ascending"
                                                            style="width: 0px;">To Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_5" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Active Bed: activate to sort column ascending"
                                                            style="width: 0px;">Active Bed</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr role="row" class="odd">
                                                        <td class="mailbox-name">AC (Normal)</td>
                                                        <td class="mailbox-name">FF - 117</td>
                                                        <td class="mailbox-name">06/15/2024 02:51 PM</td>
                                                        <td class="mailbox-name"></td>
                                                        <td class="mailbox-name">Yes</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_5_info" role="status"
                                                aria-live="polite">Records: 1 to 1 of 1</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_5_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_5" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_5_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current"
                                                        aria-controls="DataTables_Table_5" data-dt-idx="1"
                                                        tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_5" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_5_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tab-content-height" id="medication">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Medication</h3>
                                        <div class="box-tab-tools">
                                            <a href="#"
                                                class="btn btn-sm btn-primary dropdown-toggle addmedication"
                                                onclick="addmedicationModal()" data-toggle="modal"><i
                                                    class="fa fa-plus"></i> Add Medication Dose</a>
                                        </div>
                                    </div>

                                    <div class="download_label">Nishant Kadakia IPD Details</div>
                                    <div class="table_inner">
                                        <table class="table table-striped table-bordered  mb0">
                                            <thead>
                                                <tr>
                                                    <th class="hard_left">Date </th>
                                                    <th class="next_left table_inner_tdwidth">Medicine Name</th>

                                                    <th class="table_inner_tdwidth">Dose1</th>

                                                    <th class="table_inner_tdwidth">Dose2</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="hard_left">06/22/2024<br>(Saturday)</td>
                                                    <td class="next_left">WORMSTOP</td>
                                                    <td class="dosehover">Time: 01:00 PM<span><a href="#"
                                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                                data-original-title="Edit"
                                                                onclick="medicationDoseModal(655)"><i
                                                                    class="fa fa-pencil"></i></a></span><span><a
                                                                class="btn btn-default btn-xs delete_record_dosage"
                                                                data-toggle="tooltip" data-original-title="Delete"
                                                                data-record-id="655"><i
                                                                    class="fa fa-trash"></i></a></span><br>1 MG</td>
                                                    <td class="dosehover">Time: 09:00 PM<span><a href="#"
                                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                                data-original-title="Edit"
                                                                onclick="medicationDoseModal(656)"><i
                                                                    class="fa fa-pencil"></i></a></span><span><a
                                                                class="btn btn-default btn-xs delete_record_dosage"
                                                                data-toggle="tooltip" data-original-title="Delete"
                                                                data-record-id="656"><i
                                                                    class="fa fa-trash"></i></a></span><br>1 MG</td>
                                                    <td class="dosehover"> <a href="#"
                                                            class="btn btn-sm btn-primary dropdown-toggle addmedication"
                                                            onclick="medicationModal('2','84','06/22/2024')"
                                                            data-toggle="modal"><i class="fa fa-plus"></i>

                                                        </a>
                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>


                                    </div>

                                </div>
                                <div class="tab-pane tab-content-height" id="operationtheatre">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Operations</h3>
                                        <div class="box-tab-tools">
                                            <a data-toggle="modal" onclick="holdModal('add_operationtheatre')"
                                                class="btn btn-primary btn-sm addoperationtheatre"><i
                                                    class="fa fa-plus"></i> Add Operation</a>
                                        </div>
                                    </div>
                                    <div class="download_label">Nishant Kadakia IPD Details</div>
                                    <div class="table_inner">
                                        <div id="DataTables_Table_6_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_6" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_6" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a>
                                                <a class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_6" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_6" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_6" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a>
                                            </div>
                                            <div id="DataTables_Table_6_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_6"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_6" role="grid"
                                                aria-describedby="DataTables_Table_6_info" style="width: 1115px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_6" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Reference No: activate to sort column ascending"
                                                            style="width: 161px;">Reference No</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_6" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Operation Date: activate to sort column ascending"
                                                            style="width: 214px;">Operation Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_6" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Operation Name: activate to sort column ascending"
                                                            style="width: 187px;">Operation Name</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_6" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Operation Category: activate to sort column ascending"
                                                            style="width: 218px;">Operation Category</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_6" rowspan="1"
                                                            colspan="1"
                                                            aria-label="OT Technician: activate to sort column ascending"
                                                            style="width: 166px;">OT Technician</th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_6" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Action: activate to sort column ascending"
                                                            style="width: 109px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>








                                                    <tr role="row" class="odd">
                                                        <td>OTREF215</td>
                                                        <td>06/22/2024 09:30 PM</td>
                                                        <td> Facelift Surgery</td>
                                                        <td>Plastic Surgery</td>
                                                        <td>Vinesh</td>
                                                        <td class="text-right">
                                                            <a href="#" data-toggle="tooltip" title="Show"
                                                                class="btn btn-default btn-xs"
                                                                data-target="#view_ot_modal"
                                                                onclick="viewdetail(&quot;215&quot;)"> <i
                                                                    class="fa fa-reorder"></i> </a>
                                                            <a onclick="editot('215')" class="btn btn-default btn-xs"
                                                                data-toggle="tooltip" title=""
                                                                data-original-title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a onclick="deleteot('215')" class="btn btn-default btn-xs"
                                                                data-toggle="tooltip" title=""
                                                                data-original-title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>

                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_6_info" role="status"
                                                aria-live="polite">Records: 1 to 1 of 1</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_6_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_6" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_6_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current"
                                                        aria-controls="DataTables_Table_6" data-dt-idx="1"
                                                        tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_6" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_6_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="tab-pane tab-content-height" id="charges">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Charges</h3>
                                        <div class="box-tab-tools">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addcharges"
                                                onclick="holdModal('myChargesModal')" data-toggle="modal"><i
                                                    class="fa fa-plus"></i> Add Charges</a>

                                        </div>
                                    </div>

                                    <div class="download_label">Charges</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_7_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_7" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_7" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a>
                                                <a class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_7" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_7" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_7" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a>
                                            </div>
                                            <div id="DataTables_Table_7_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_7"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_7" role="grid"
                                                aria-describedby="DataTables_Table_7_info" style="width: 1117px;">
                                                <thead class="white-space-nowrap">
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Date: activate to sort column ascending"
                                                            style="width: 49px;">Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Name: activate to sort column ascending"
                                                            style="width: 57px;">Name</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Charge Type: activate to sort column ascending"
                                                            style="width: 103px;">Charge Type</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Charge Category: activate to sort column ascending"
                                                            style="width: 132px;">Charge Category</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Qty: activate to sort column ascending"
                                                            style="width: 41px;">Qty</th>
                                                        <th class="text-right sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Standard Charge ($) : activate to sort column ascending"
                                                            style="width: 160px;">Standard Charge ($) </th>
                                                        <th class="text-right sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="TPA Charge ($): activate to sort column ascending"
                                                            style="width: 121px;">TPA Charge ($)</th>
                                                        <th class="text-right sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Tax: activate to sort column ascending"
                                                            style="width: 41px;">Tax</th>
                                                        <th class="text-right sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Applied Charge ($): activate to sort column ascending"
                                                            style="width: 146px;">Applied Charge ($)</th>
                                                        <th class="text-right sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Amount ($): activate to sort column ascending"
                                                            style="width: 95px;">Amount ($)</th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_7" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Action: activate to sort column ascending"
                                                            style="width: 62px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr class="box box-solid total-bg odd" role="row">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>Total : </td>
                                                        <td class="text-right">$0.00 <input type="hidden"
                                                                id="charge_total" name="charge_total" value="0">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>



                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_7_info" role="status"
                                                aria-live="polite">Records: 1 to 1 of 1</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_7_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_7" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_7_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current"
                                                        aria-controls="DataTables_Table_7" data-dt-idx="1"
                                                        tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_7" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_7_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tab-content-height" id="payment">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Payment</h3>
                                        <div class="box-tab-tools">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle addpayment"
                                                onclick="addpaymentModal()" data-toggle="modal"><i
                                                    class="fa fa-plus"></i> Add Payment</a>

                                        </div>
                                    </div>

                                    <div class="download_label">Payment</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_8_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="dt-buttons btn-group btn-group2"> <a
                                                    class="btn btn-default dt-button buttons-copy buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_8" href="#"
                                                    title="Copy"><span><i class="fa fa-files-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-excel buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_8" href="#"
                                                    title="Excel"><span><i class="fa fa-file-excel-o"></i></span></a>
                                                <a class="btn btn-default dt-button buttons-csv buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_8" href="#"
                                                    title="CSV"><span><i class="fa fa-file-text-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-pdf buttons-html5"
                                                    tabindex="0" aria-controls="DataTables_Table_8" href="#"
                                                    title="PDF"><span><i class="fa fa-file-pdf-o"></i></span></a> <a
                                                    class="btn btn-default dt-button buttons-print" tabindex="0"
                                                    aria-controls="DataTables_Table_8" href="#"
                                                    title="Print"><span><i class="fa fa-print"></i></span></a>
                                            </div>
                                            <div id="DataTables_Table_8_filter" class="dataTables_filter"><label><input
                                                        type="search" class="" placeholder="Search..."
                                                        aria-controls="DataTables_Table_8"></label></div>
                                            <table class="table table-striped table-bordered  example dataTable no-footer"
                                                id="DataTables_Table_8" role="grid"
                                                aria-describedby="DataTables_Table_8_info" style="width: 1115px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_8" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Transaction ID: activate to sort column ascending"
                                                            style="width: 193px;">Transaction ID</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_8" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Date: activate to sort column ascending"
                                                            style="width: 242px;">Date</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_8" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Note: activate to sort column ascending"
                                                            style="width: 85px;">Note</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_8" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Payment Mode: activate to sort column ascending"
                                                            style="width: 198px;">Payment Mode</th>
                                                        <th class="text-right sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_8" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Paid Amount ($): activate to sort column ascending"
                                                            style="width: 212px;">Paid Amount ($)</th>
                                                        <th class="text-right noExport sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_8" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Action: activate to sort column ascending"
                                                            style="width: 125px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>




                                                    <tr role="row" class="odd">
                                                        <td>TRANID9156</td>
                                                        <td>06/26/2024 05:42 PM</td>
                                                        <td></td>
                                                        <td style="text-transform: capitalize;">Cash<br> </td>
                                                        <td class="text-right">1800.00</td>

                                                        <td class="text-right">



                                                            <a href="javascript:void(0)"
                                                                class="btn btn-default btn-xs print_trans"
                                                                data-record-id="9156" data-loading-text="Please Wait.."
                                                                data-toggle="tooltip" data-original-title="Print"><i
                                                                    class="fa fa-print"></i></a>
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-default btn-xs editpayment"
                                                                data-toggle="tooltip" title=""
                                                                data-payment-amount="1800.00" data-record-id="9156"
                                                                data-original-title="Edit"><i
                                                                    class="fa fa-pencil"></i></a>
                                                            <a href="javascript:void(0);"
                                                                onclick="deletePayment('9156')"
                                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                                title="" data-original-title="Delete"><i
                                                                    class="fa fa-trash"></i></a>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody>
                                                    <tr class="box box-solid total-bg">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td colspan="" class="text-right">Total : $1,800.00 </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="DataTables_Table_8_info" role="status"
                                                aria-live="polite">Records: 1 to 1 of 1</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_8_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_8" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_8_previous"><i
                                                        class="fa fa-angle-left"></i></a><span><a
                                                        class="paginate_button current"
                                                        aria-controls="DataTables_Table_8" data-dt-idx="1"
                                                        tabindex="0">1</a></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_8" data-dt-idx="2" tabindex="0"
                                                    id="DataTables_Table_8_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tab-content-height" id="treatment_history">
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title">Treatment History</h3>
                                        <div class="box-tab-tools">

                                        </div>
                                    </div>

                                    <div class="download_label">Treatment History</div>
                                    <div class="table-responsive">
                                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="top">
                                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                                    <label><input type="search" class=""
                                                            placeholder="Search..."
                                                            aria-controls="DataTables_Table_0"></label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="dt-buttons btn-group btn-group2"> <a
                                                        class="btn btn-default dt-button buttons-copy buttons-html5 btn-copy"
                                                        tabindex="0" aria-controls="DataTables_Table_0"
                                                        href="#" title="Copy"><span><i
                                                                class="fa fa-files-o"></i></span></a> <a
                                                        class="btn btn-default dt-button buttons-excel buttons-html5 btn-excel"
                                                        tabindex="0" aria-controls="DataTables_Table_0"
                                                        href="#" title="Excel"><span><i
                                                                class="fa fa-file-excel-o"></i></span></a> <a
                                                        class="btn btn-default dt-button buttons-csv buttons-html5 btn-csv"
                                                        tabindex="0" aria-controls="DataTables_Table_0"
                                                        href="#" title="CSV"><span><i
                                                                class="fa fa-file-text-o"></i></span></a> <a
                                                        class="btn btn-default dt-button buttons-pdf buttons-html5 btn-pdf"
                                                        tabindex="0" aria-controls="DataTables_Table_0"
                                                        href="#" title="PDF"><span><i
                                                                class="fa fa-file-pdf-o"></i></span></a> <a
                                                        class="btn btn-default dt-button buttons-print btn-print"
                                                        tabindex="0" aria-controls="DataTables_Table_0"
                                                        href="#" title="Print"><span><i
                                                                class="fa fa-print"></i></span></a> </div>
                                                <div class="dataTables_length" id="DataTables_Table_0_length">
                                                    <label><select name="DataTables_Table_0_length"
                                                            aria-controls="DataTables_Table_0" class="">
                                                            <option value="100">100</option>
                                                            <option value="-1">All</option>
                                                        </select></label>
                                                </div>
                                            </div>
                                            <div id="DataTables_Table_0_processing" class="dataTables_processing"
                                                style="display: none;"><i
                                                    class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span
                                                    class="sr-only">Loading...</span> </div>
                                            <div>
                                                <table
                                                    class="table table-striped table-bordered  treatmentlist dataTable no-footer"
                                                    data-export-title="Treatment History" id="DataTables_Table_0"
                                                    role="grid" aria-describedby="DataTables_Table_0_info"
                                                    style="width: 0px;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="DataTables_Table_0" rowspan="1"
                                                                colspan="1" style="width: 0px;"
                                                                aria-label="IPD No: activate to sort column ascending">IPD
                                                                No</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="DataTables_Table_0" rowspan="1"
                                                                colspan="1" style="width: 0px;"
                                                                aria-label="Symptoms: activate to sort column ascending">
                                                                Symptoms</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="DataTables_Table_0" rowspan="1"
                                                                colspan="1" style="width: 0px;"
                                                                aria-label="Consultant: activate to sort column ascending">
                                                                Consultant</th>
                                                            <th class="text-right dt-body-right sorting_disabled"
                                                                rowspan="1" colspan="1" style="width: 0px;"
                                                                aria-label="Bed">Bed</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="odd">
                                                            <td valign="top" colspan="4"
                                                                class="dataTables_empty">
                                                                <div align="center">No data available in table <br>
                                                                    <br><img
                                                                        src="https://smart-hospital.in/shappresource/images/addnewitem.svg"
                                                                        width="150"><br><br> <span
                                                                        class="text-success bolds"><i
                                                                            class="fa fa-arrow-left"></i> Add new record
                                                                        or search with different criteria.</span>
                                                                    <div></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                                aria-live="polite">Records: 0 to 0 of 0</div>
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_0_paginate"><a
                                                    class="paginate_button previous disabled"
                                                    aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0"
                                                    id="DataTables_Table_0_previous"><i
                                                        class="fa fa-angle-left"></i></a><span></span><a
                                                    class="paginate_button next disabled"
                                                    aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0"
                                                    id="DataTables_Table_0_next"><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('member.patient.all_model')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            let totalWidth = 0;
            $(".scrtabs-tabs-fixed-container ul.nav-tabs > li").each(function() {
                totalWidth += $(this).outerWidth();
            })
            let wraper = $('.scrtabs-tab-container').outerWidth();
            let top = $('.scrtabs-tabs-fixed-container')
            let content = $('.scrtabs-tabs-movable-container')
            content.css('width', totalWidth + 'px');
            top.css('width', wraper - 40 + 'px');
            let slice = Math.ceil(content.outerWidth() / top.outerWidth()) - 1;
            let leftValueCount = 0;
            let value = top.outerWidth();

            // console.log(slice);
            // console.log(leftValueCount);
            $(".scrtabs-js-tab-scroll-arrow-left").on('click', function() {

                if (slice == 1) {
                    content.animate({
                        left: 0 + "px"
                    })
                } else {
                    leftValueCount += value;
                    if (leftValueCount > 0) {
                        leftValueCount = 0;
                        content.animate({
                            left: 0 + "px"
                        })

                    } else {

                        content.animate({
                            left: leftValueCount + "px"
                        })
                    }
                    console.log(leftValueCount);
                }
            })
            $(".scrtabs-js-tab-scroll-arrow-right").on('click', function() {
                // console.log(content);
                if (slice == 1) {
                    content.animate({
                        left: (top.outerWidth() - content.outerWidth()) + "px"
                    })
                } else {
                    // console.log(content.outerWidth());
                    leftValueCount -= value;
                    if ((-leftValueCount) > (content.outerWidth() - value)) {
                        leftValueCount = top.outerWidth() - content.outerWidth();
                        content.animate({
                            left: leftValueCount + "px"
                        })

                    } else {
                        content.animate({
                            left: leftValueCount + "px"
                        })

                    }
                    console.log(leftValueCount);

                }
            })
        })
    </script>
    <script>
        function addpaymentModal() {
            var total = $("#charge_total").val();
            var patient_id = '980';
            $("#total").val(total);
            $("#payment_file").dropify();
            $("#payment_patient_id").val(patient_id);
            holdModal('myPaymentModal');
        }

        function addmedicationModal() {
            holdModal('myaddMedicationModal');
        }

        function medicationModal(medicine_category_id, pharmacy_id, date) {
            var div_data = "<option value=''>Select</option>";
            if (medicine_category_id != "") {
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/getMedicineDoseDetails',
                    type: "POST",
                    data: {
                        medicine_category_id: medicine_category_id
                    },
                    dataType: 'json',
                    success: function(res) {
                        $.each(res, function(i, obj) {
                            var sel = "";
                            div_data += "<option value='" + obj.id + "'>" + obj.dosage + " " + obj
                                .unit + "</option>";

                        });

                        $("#mdosage").html(div_data);
                        $("#mdosage").select2("val", '');
                        $("#add_dose_medicine_category").select2("val", medicine_category_id);
                        getMedicineForMedication(medicine_category_id, pharmacy_id);
                        $("#add_dose_date").val(date);
                        $("#mpharmacy_id").val(pharmacy_id);
                        $("#mdate").val(date);

                        holdModal('myMedicationModal');
                    },
                });
            }
        }

        function medicationDoseModal(medication_id) {
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/getMedicationDoseDetails',
                type: "POST",
                data: {
                    medication_id: medication_id
                },
                dataType: 'json',
                success: function(data) {
                    $("#date_edit_medication").val(data.date);
                    $("#dosagetime").val(data.dosagetime);
                    $('select[id="medicine_dose_id"] option[value="' + data.medicine_dosage_id + '"]').attr(
                        "selected", "selected");
                    $("#medicine_dose_edit_id").select2().select2('val', data.medicine_dosage_id);
                    $("#mmedicine_category_edit_id ").val(data.medicine_category_id).trigger('change');
                    getMedicineForMedication(data.medicine_category_id, data.pharmacy_id);
                    $("#medicine_dosage_remark").val(data.remark);
                    $("#medication_id").val(data.id);
                    $('#edit_delete').html("<a href='#' class='delete_record_dosage' data-record-id='" +
                        medication_id +
                        "' data-toggle='tooltip' title='Delete' data-target='' data-toggle='modal'  data-original-title='Delete'><i class='fa fa-trash'></i></a>"
                        );
                    holdModal('myMedicationDoseModal');
                },
            });
        }


        $('#myChargesModal').on('hidden.bs.modal', function(e) {
            $('.charge_type ', $(this)).select2('val', '');
            $('.charge_category', $(this)).html('').select2();
            $('.charge', $(this)).html('').select2();
        });


        $("#add_instruction").on('hidden.bs.modal', function(e) {
            $(".filestyle").next(".dropify-clear").trigger("click");
            $('#consultant_register_form #doctor_field').select2("val", "");
            $('form#consultant_register_form').find('input:text, input:password, input:file, textarea').val('');
            $('form#consultant_register_form').find('select option:selected').removeAttr('selected');
            $('form#consultant_register_form').find('input:checkbox, input:radio').removeAttr('checked');
        });



        $("#add_operationtheatre").on('hidden.bs.modal', function(e) {
            $(".filestyle").next(".dropify-clear").trigger("click");
            $('#form_operationtheatre #operation_category').select2("val", "");
            $('#form_operationtheatre #operation_name').select2("val", "");
            $('#form_operationtheatre #consultant_doctorid').select2("val", "");
            $('form#form_operationtheatre').find('input:text, input:password, input:file, textarea').val('');
            $('form#form_operationtheatre').find('select option:selected').removeAttr('selected');
            $('form#form_operationtheatre').find('input:checkbox, input:radio').removeAttr('checked');
        });

        $('#modal-chkstatus').on('shown.bs.modal', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = $(e.relatedTarget).data('id');

            $.ajax({
                type: "POST",
                url: base_url + 'admin/zoom_conference/getlivestatus',
                data: {
                    'id': id
                },
                dataType: "JSON",
                beforeSend: function() {
                    $('#zoom_details').html("");
                    $modalDiv.addClass('modal_loading');
                },
                success: function(data) {
                    $('#zoom_details').html(data.page);
                    $modalDiv.removeClass('modal_loading');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function(data) {
                    $modalDiv.removeClass('modal_loading');
                }
            });
        })


        function getRecord(ipdid) {

            // $.ajax({
            //     url: 'https://demo.smart-hospital.in/admin/patient/getIpdDetails',
            //     type: "POST",
            //     data: {ipdid: ipdid},
            //     dataType: 'json',
            //     success: function (data) {
            //          var table_html = '';
            //               $.each(data.field_data, function (i, obj)
            //               {
            //               if (obj.field_value == null) {
            //               var field_value = "";
            //               } else {
            //               var field_value = obj.field_value;
            //               }
            //               var name = obj.name ;
            //               table_html += "<div class='row'><div class='col-lg-2 col-md-2 col-sm-2'><span ><b>" + capitalizeFirstLetter(name) + "</b></span></div></div> <div class='row'><div class='col-lg-10 col-md-10 col-sm-10'><span >" + field_value + "</span></div></div><br>";
            //           });
            //         $("#field_data").html(table_html);
            //         $("#patients_id").html(data.patient_id);
            //         $("#patient_name").html(data.patient_name);
            //         $("#contact").html(data.mobileno);
            //         $("#email").html(data.email);
            //         $("#age").html(data.age);
            //         $("#gen").html(data.gender);
            //         $("#guardian_name").html(data.guardian_name);
            //         $("#admission_date").html(data.date);
            //         $("#case").html(data.case_type);
            //         $("#casualty").html(data.casualty);
            //         $("#symptoms").html(data.symptoms);
            //         $("#known_allergies").html(data.known_allergies);
            //         $("#refference").html(data.refference);
            //         $("#doc").html(data.name + ' ' + data.surname + ' (' + data.employee_id + ')');
            //         $("#amount").html(data.amount);
            //         $("#tax").html(data.tax);
            //         $("#height").html(data.height);
            //         $("#weight").html(data.weight);
            //         $("#temperature").html(data.temperature);
            //         $("#respiration").html(data.respiration);
            //         $("#pulse").html(data.pulse);
            //         $("#patient_bp").html(data.bp);
            //         $("#blood_group").html(data.blood_group_name);
            //         $("#old_patient").html(data.patient_old);
            //         $("#payment_mode").html(data.payment_mode);
            //         $("#organisation").html(data.organisation_name);
            //         $("#opdid").val(data.opdid);
            //         $("#patient_address").html(data.address);
            //         $("#marital_status").html(data.marital_status);
            //         $("#note").val(data.note);
            //         $("#bed_group").html(data.bedgroup_name + '-' + data.floor_name);
            //         $("#bed_name").html(data.bed_name);
            //         $("#etpa_id").html(data.insurance_id);
            //         $("#etpa_validity").html(data.insurance_validity);
            //         $("#eidentification_number").html(data.identification_number);
            //         $("#evblood_group").html(data.blood_group_name);
            //         holdModal('viewModal');
            //     },
            // });
            holdModal('viewModal');
        }

        function getEditRecord(ipdid) {

            // $.ajax({
            //     url: 'https://demo.smart-hospital.in/admin/patient/getIpdDetails',
            //     type: "POST",
            //     data: {ipdid: ipdid},
            //     dataType: 'json',
            //     success: function (data) {
            //         $('#customfield').html(data.custom_fields_value);
            //         $('#evlistname').html(data.patient_name+" ("+data.patient_id+")");
            //         $('#evguardian').html(data.guardian_name);
            //         $('#evlistnumber').html(data.mobileno);
            //         $('#evemail').html(data.email);
            //         $("#etpa_id").html(data.insurance_id);
            //         $("#etpa_validity").html(data.einsurance_validity);
            //         $("#eidentification_number").html(data.identification_number);
            //         $("#evaddress").html(data.address);
            //         $("#enote").html(data.note);
            //         $("#evgenders").html(data.gender);
            //         $("#evmarital_status").html(data.marital_status);
            //         $("#evblood_group").html(data.blood_group_name);
            //         $("#evallergies").html(data.known_allergies);
            //         $("#patients_ids").val(data.patient_unique_id);
            //         $("#patient_names").val(data.patient_name);
            //         $("#edit_admission_date").val(data.date);
            //         $("#contacts").val(data.mobileno);
            //         $("#patient_image").val(data.image);
            //         $("#emails").val(data.email);
            //         $("#ages").val(data.age);
            //         $("#months").val(data.month);
            //         $("#evheight").val(data.height);
            //         $("#evweight").val(data.weight);
            //         $("#evbp").val(data.bp);
            //         $("#evpulse").val(data.pulse);
            //         $("#evtemperature").val(data.temperature);
            //         $("#evrespiration").val(data.respiration);
            //         $("#edit_patient_address").val(data.address);
            //         $("#patient_case").val(data.case_type);
            //         $("#symptoms_description").val(data.symptoms);
            //         $("#patient_allergies").val(data.known_allergies);
            //         $("#evnoteipd").val(data.ipdnote);
            //         $("#patient_refference").val(data.refference);
            //         $("#guardian_names").val(data.guardian_name);
            //         $("#credits_limits").val(data.ipdcredit_limit);
            //         $("#ipdid_edit").val(data.ipdid);
            //         $("#ipdid").val(data.ipdid);
            //         $("#previous_bed_id").val(data.bed);
            //         $("#ebed_group_id").val(data.bed_group_id).attr('selected', true);

            //         getBed(data.bed_group_id, data.bed, 'yes','ebed_nos');
            //         $('select[id="patient_consultant"] option[value="' + data.cons_doctor + '"]').attr("selected", "selected");
            //         $('select[id="patient_casualty"] option[value="' + data.casualty + '"]').attr("selected", "selected");
            //         $('select[id="old"] option[value="' + data.patient_old + '"]').attr("selected", "selected");
            //         $('select[id="genders"] option[value="' + data.gender + '"]').attr("selected", "selected");
            //         $('select[id="marital_statuss"] option[value="' + data.marital_status + '"]').attr("selected", "selected");
            //         $('select[id="edit_organisations"] option[value="' + data.organisation_id + '"]').attr("selected", "selected");
            //         $("#patient_consultant").select2().select2('val', data.cons_doctor);
            //         $("#evaddpatient_id").select2().select2('val', data.patient_id);
            //         $('.select2').select2();
            //         holdModal('myModaledit');
            //     },
            // });
            holdModal('myModaledit');
        }

        function get_doctoripd(ipdid) {
            holdModal('add_doctor');
        }

        function getEditRecordDischarged(id, ipdid) {
            var active = 'yes';
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/getIpdDetails',
                type: "POST",
                data: {
                    recordid: id,
                    ipdid: ipdid,
                    active: active
                },
                dataType: 'json',
                success: function(data) {
                    $('#disevlistname').html(data.patient_name);
                    $('#disevguardian').html(data.guardian_name);
                    $('#disevlistnumber').html(data.mobileno);
                    $('#disevemail').html(data.email);
                    if (data.age == "") {
                        $("#disevage").html("");
                    } else {
                        if (data.age) {
                            var age = data.age + " " + "Years";
                        } else {
                            var age = '';
                        }
                        if (data.month) {
                            var month = data.month + " " + "Month";
                        } else {
                            var month = '';
                        }
                        if (data.dob) {
                            var dob = "(" + data.dob + ")";
                        } else {
                            var dob = '';
                        }

                        $("#disevage").html(age + "," + month + " " + dob);
                    }
                    $("#disevaddress").html(data.address);
                    $("#disenote").html(data.note);
                    $("#disevgenders").html(data.gender);
                    $("#disevmarital_status").html(data.marital_status);
                    $("#disedit_admission_date").html(data.date);
                    $("#disedit_discharge_date").html(data.discharge_date);
                    $("#disipdid").val(data.ipdid);
                    $("#disupdateid").val(data.summary_id);
                    $("#disevpatients_id").val(data.patient_id);
                    $("#disinvestigations").val(data.summary_investigations);
                    $("#disevnoteipd").val(data.summary_note);
                    $("#disdiagnosis").val(data.disdiagnosis);
                    $("#disoperation").val(data.disoperation);
                    $("#distreatment_at_home").val(data.summary_treatment_home);
                    $('#summary_print').html("<a href='#' data-toggle='tooltip' onclick='printData(" + data
                        .summary_id + ")'   data-original-title='Print'><i class='fa fa-print'></i></a> ");
                    holdModal('myModaldischarged');
                },
            });
        }

        function printData(insert_id) {
            var base_url = 'https://demo.smart-hospital.in/';
            $.ajax({
                url: base_url + 'admin/patient/getsummaryDetails/' + insert_id,
                type: 'POST',
                data: {
                    id: insert_id,
                    print: 'yes'
                },
                success: function(result) {
                    popup(result);
                }
            });
        }

        function get_ePatientDetails(id) {
            var base_url = "https://demo.smart-hospital.in/backend/images/loading.gif";
            $("#ajax_load").html("<center><img src='" + base_url + "'/>");
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/patientDetails',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res) {
                        console.log(res);
                        $("#evajax_load").html("");
                        $("#evpatientDetails").show();
                        $('#evpatient_unique_id').html(res.patient_unique_id);
                        $('#evlistname').html(res.patient_name + " (" + res.id + ")");
                        $('#evpatients_id').val(res.id);
                        $('#evguardian').html(res.guardian_name);
                        $('#evlistnumber').html(res.mobileno);
                        $('#evemail').html(res.email);
                        $("#evage").html(res.patient_age);
                        $('#evdoctname').val(res.name + " " + res.surname);
                        $("#evbp").html(res.bp);
                        $("#esymptoms").html(res.symptoms);
                        $("#evknown_allergies").html(res.known_allergies);
                        $("#evaddress").html(res.address);
                        $("#evnote").html(res.note);
                        $("#evgenders").html(res.gender);
                        $("#evmarital_status").html(res.marital_status);
                        $("#evblood_group").html(res.blood_group_name);
                        $("#evallergies").html(res.known_allergies);
                        $("#evimage").attr("src", 'https://demo.smart-hospital.in/' + res.image +
                        '?1718275779');
                    } else {
                        $("#evajax_load").html("");
                        $("#evpatientDetails").hide();
                    }
                }
            });
        }

        $(document).ready(function(e) {
            $('#add_prescription,#patient_discharge').modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });

            $("#formeditrecord").on('submit', (function(e) {
                $("#formeditrecordbtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/ipd_update',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#formeditrecordbtn").button('reset');
                    },
                    error: function() {

                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#formdishrecord").on('submit', (function(e) {
                $("#formdishrecordbtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/add_discharged_summary',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#formdishrecordbtn").button('reset');
                    },
                    error: function() {

                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#alot_bed_form").on('submit', (function(e) {
                $("#alotbedbtn").button('loading');

                e.preventDefault();
                var bedid = $("#alotbedoption").val();

                var ipdid = '97';
                var patient_id = '980';
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/updatebed',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            revert(patient_id, bedid, ipdid);
                        }
                        $("#alotbedbtn").button('reset');
                    },
                    error: function() {

                    }
                });
            }));
        });

        function editRecord(id, opdid) {
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/opd_details',
                type: "POST",
                data: {
                    recordid: id,
                    opdid: opdid
                },
                dataType: 'json',
                success: function(data) {
                    $("#patientid").val(data.patient_unique_id);
                    $("#patientname").val(data.patient_name);
                    $("#appointmentdate").val(data.appointment_date);
                    $("#edit_case").val(data.case_type);
                    $("#edit_symptoms").val(data.symptoms);
                    $("#edit_casualty").val(data.casualty);
                    $("#edit_knownallergies").val(data.known_allergies);
                    $("#edit_refference").val(data.refference);
                    $("#edit_consdoctor").val(data.cons_doctor);
                    $("#edit_amount").val(data.amount);
                    $("#edit_tax").val(data.tax);
                    $("#edit_paymentmode").val(data.payment_mode);
                    $("#edit_opdid").val(opdid);
                },
            });
        }

        $(document).ready(function(e) {
            $("#add_payment").on('submit', (function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/payment/create',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,

                    beforeSend: function() {
                        $("#add_paymentbtn").button("loading");
                    },
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#add_paymentbtn").button("reset");
                    },
                    error: function() {
                        $("#add_paymentbtn").button('reset');
                    },

                    complete: function() {
                        $("#add_paymentbtn").button('reset');
                    }
                });
            }));
        });


        $(document).ready(function(e) {
            $("#add_medication").on('submit', (function(e) {
                e.preventDefault();
                $("#add_medicationbtn").button('loading');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/addmedicationdose',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#add_medicationbtn").button('loading');
                    },
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = data.message;
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#add_medicationbtn").button('reset');
                    },
                    error: function() {
                        $("#add_medicationbtn").button('reset');
                    },

                    complete: function() {
                        $("#add_medicationbtn").button('reset');
                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#add_medicationdose").on('submit', (function(e) {
                e.preventDefault();
                $("#add_medicationdosebtn").button('loading');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/addmedicationdose',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#add_medicationdosebtn").button('loading');
                    },
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = data.message;
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#add_medicationdosebtn").button('reset');
                    },
                    error: function() {
                        $("#add_medicationdosebtn").button('reset');
                    },

                    complete: function() {
                        $("#add_medicationdosebtn").button('reset');
                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#update_medication").on('submit', (function(e) {
                e.preventDefault();
                $("#update_medicationbtn").button('loading');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/updatemedication',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#update_medicationbtn").button('loading');
                    },
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#update_medicationbtn").button('reset');
                    },
                    error: function() {
                        $("#update_medicationbtn").button('reset');
                    },

                    complete: function() {
                        $("#update_medicationbtn").button('reset');
                    }
                });
            }));
        });


        $(document).ready(function(e) {
            $("#formedit").on('submit', (function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/opd_detail_update',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                    },
                    error: function() {}
                });
            }));
        });

        function getBed(bed_group, bed = '', active, htmlid = null) {

            if (htmlid != null) {
                htmlid = htmlid;
            } else {
                htmlid = 'bed_nos';
            }

            var div_data = "";
            $('#' + htmlid).html("<option value='l'>Loading...</option>");
            $("#" + htmlid).select2("val", 'l');
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/setup/bed/getbedbybedgroup',
                type: "POST",
                data: {
                    bed_group: bed_group,
                    bed_id: bed,
                    active: active
                },
                dataType: 'json',
                success: function(res) {
                    $.each(res, function(i, obj) {
                        var sel = "";
                        if (bed == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option " + sel + " value=" + obj.id + ">" + obj.name +
                        "</option>";
                    });
                    $("#" + htmlid).html("<option value=''>Select</option>");
                    $('#' + htmlid).append(div_data);
                    $("#" + htmlid).select2().select2('val', bed);
                }
            });
        }

        $(document).ready(function(e) {
            $("form#form_prescription button[type=submit]").click(function() {
                $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
                $(this).attr("clicked", "true");
            });


            $("#form_prescription").on('submit', (function(e) {
                e.preventDefault();

                var sub_btn_clicked = $("button[type=submit][clicked=true]");
                var sub_btn_clicked_name = sub_btn_clicked.attr('name');


                var clicked_btn = $("button[type=submit]");
                var btn = clicked_btn;
                $.ajax({
                    url: base_url + 'admin/patient/add_ipdprescription',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        sub_btn_clicked.button('loading');
                    },
                    success: function(data) {
                        sub_btn_clicked.button('reset');
                        if (data.status == 0) {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);

                            if (sub_btn_clicked_name === "save_print") {
                                printprescription(data.ipd_prescription_basic_id, true);
                            } else if (sub_btn_clicked_name === "save") {

                                window.location.reload(true);
                            }

                        }
                    },
                    error: function() {
                        sub_btn_clicked.button('reset');
                    },

                    complete: function() {
                        sub_btn_clicked.button('reset');
                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#form_diagnosis").on('submit', (function(e) {
                e.preventDefault();
                $("#form_diagnosisbtn").button('loading');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/add_diagnosis',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#form_diagnosisbtn").button('reset');
                    },
                    error: function() {}
                });
            }));
        });

        $(document).ready(function(e) {
            $("#form_doctor").on('submit', (function(e) {
                e.preventDefault();
                $("#form_doctorbtn").button('loading');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/addipddoctor',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#form_doctorbtn").button('reset');
                    },
                    error: function() {}
                });
            }));
        });

        $(document).ready(function(e) {
            $("#form_editdiagnosis").on('submit', (function(e) {
                e.preventDefault();
                $("#form_editdiagnosisbtn").button('loading');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/update_diagnosis',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#form_diagnosisbtn").button('reset');
                    },
                    error: function() {}
                });
            }));
        });

        $(document).on('select2:select', '.medicine_category', function() {
            getMedicine($(this), $(this).val(), 0);
            selected_medicine_category_id = $(this).val();
            var medicine_dosage = getDosages(selected_medicine_category_id);
            $(this).closest('tr').find('.medicine_dosage').html(medicine_dosage);

        });

        $(document).on('select2:select', '.medicine_category_medication', function() {

            var medicine_category = $(this).val();

            $('.medicine_name_medication').html("<option value=''>Loading...</option>");
            getMedicineForMedication(medicine_category, "");
            getMedicineDosageForMedication(medicine_category);
        });

        function getMedicineForMedication(medicine_category, medicine_id) {
            var div_data = "<option value=''>Select</option>";
            if (medicine_category != "") {
                $.ajax({
                    url: base_url + 'admin/pharmacy/get_medicine_name',
                    type: "POST",
                    data: {
                        medicine_category_id: medicine_category
                    },
                    dataType: 'json',
                    success: function(res) {

                        $.each(res, function(i, obj) {
                            var sel = "";
                            div_data += "<option value='" + obj.id + "'>" + obj.medicine_name +
                                "</option>";

                        });
                        $('.medicine_name_medication').html(div_data);
                        $(".medicine_name_medication").select2("val", medicine_id);
                        $("#mmedicine_edit_id").val(medicine_id).trigger("change");
                    }
                });
            }
        }


        function getMedicineDosageForMedication(medicine_category) {

            var div_data = "<option value=''>Select</option>";
            if (medicine_category != "") {
                $.ajax({
                    url: base_url + 'admin/pharmacy/get_medicine_dosage',
                    type: "POST",
                    data: {
                        medicine_category_id: medicine_category
                    },
                    dataType: 'json',
                    success: function(res) {

                        $.each(res, function(i, obj) {
                            var sel = "";
                            div_data += "<option value='" + obj.id + "'>" + obj.dosage + " " + obj
                                .unit + "</option>";

                        });
                        $('.dosage_medication').html(div_data);
                        $(".dosage_medication").select2("val", '');

                    }
                });
            }
        }

        function get_dosagename(id) {
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/pharmacy/get_dosagename',
                type: "POST",
                data: {
                    dosage_id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res) {
                        $('#medicine_dosage_medication').val(res.dosage_unit);
                    } else {

                    }
                }
            });
        }

        function getMedicine(med_cat_obj, val, medicine_id) {
            var medicine_colomn = med_cat_obj.closest('tr').find('.medicine_name');
            medicine_colomn.html("");
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/pharmacy/get_medicine_name',
                type: "POST",
                data: {
                    medicine_category_id: val
                },
                dataType: 'json',
                beforeSend: function() {
                    medicine_colomn.html("<option value=''>Loading...</option>");

                },
                success: function(res) {
                    var div_data = "<option value=''>Select</option>";
                    $.each(res, function(i, obj) {
                        var sel = "";
                        if (medicine_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.medicine_name +
                            "</option>";

                    });

                    medicine_colomn.html(div_data);
                    medicine_colomn.select2("val", medicine_id);

                }


            });
        }



        function getMedicineDosage(id) {

            var category_selected = $("#medicine_cat" + id).val();
            var arr = category_selected.split('-');
            var category_set = arr[0];
            div_data = '';

            $("#search-dosage" + id).html("<option value='l'>Loading...</option>");
            $('#search-dosage' + id).select2("val", +id);

            $.ajax({
                type: "POST",
                url: base_url + "admin/pharmacy/get_medicine_dosage",
                data: {
                    'medicine_category_id': category_selected
                },
                dataType: 'json',
                success: function(res) {

                    $.each(res, function(i, obj) {
                        var sel = "";
                        div_data += "<option value='" + obj.dosage + "'>" + obj.dosage + "</option>";

                    });
                    $("#search-dosage" + id).html("<option value=''>Select</option>");
                    $('#search-dosage' + id).append(div_data);
                    $('#search-dosage' + id).select2("val", '');

                }
            });

        }

        function editDiagnosis(id) {

            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/editDiagnosis',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $("#eid").val(data.id);
                    $("#epatient_id").val(data.patient_id);
                    $("#ereporttype").val(data.report_type);
                    $("#ereportcenter").val(data.report_center);
                    $("#ereportdate").val(data.report_date);
                    $("#edescription").val(data.description);
                    holdModal('edit_diagnosis');

                },
            });
        }
        var prescription_rows = 2;
        /*$(document).on('click','.add-record',function(){
                var table = document.getElementById("tableID");
                var table_len = (table.rows.length);
                var id = parseInt(table_len);

                var rowCount = $('#tableID tr').length;
                var cat_row="" ;
                var medicine_row="";
                var dose_row="";
                var dose_interval_row="" ;
                var dose_duration_row="";
                var instruction_row="" ;

                    if(table_len==0)
                    {
                       cat_row ="<label>Medicine Category</label>";
                       medicine_row ="<label>Medicine</label>";
                       dose_row =" <label>Dose</label>";
                       dose_interval_row =" <label>Dose Interval</label>";
                       dose_duration_row =" <label>Dose Duration</label>";
                       instruction_row =" <label>Instruction</label>";
                    }

                var div = "<input type='hidden' name='rows[]' value='"+prescription_rows+"' autocomplete='off'><div id=row1><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div'>"+cat_row+"<select class='form-control select2 medicine_category'  name='medicine_cat_"+prescription_rows+"'  id='medicine_cat" + prescription_rows + "'><option value=''>Select</option><option value='1'>Syrup</option><option value='2'>Capsule</option><option value='3'>Injection</option><option value='4'>Ointment</option><option value='5'>Cream</option><option value='6'>Surgical</option><option value='7'>Drops</option><option value='8'>Inhalers</option><option value='9'>Implants / Patches</option><option value='10'>Liquid</option><option value='11'>Preparations</option><option value='12'>Diaper	</option><option value='13'>Tablet</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>"+medicine_row+"<select class='form-control select2 medicine_name' data-rowId='"+prescription_rows+"'  name='medicine_"+prescription_rows+"' id='search-query" + prescription_rows + "'><option value='l'>Select</option></select><small id='stock_info_"+prescription_rows+"''> </small></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>"+dose_row+" <select  class='form-control select2 medicine_dosage' name='dosage_"+prescription_rows+"' id='search-dosage" + prescription_rows + "'><option value='l'>Select</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>"+dose_interval_row+" <select  class='form-control select2 interval_dosage' name='interval_dosage_"+prescription_rows+"' id='search-interval-dosage" + prescription_rows + "'><option value=''>Select</option><option value='1'>Only one a day</option><option value='2'>2 times in a day</option><option value='3'>3 time  in day</option><option value='4'>4 times a day</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>"+dose_duration_row+"<select class='form-control select2 duration_dosage' name='duration_dosage_"+prescription_rows+"' id='search-duration-dosage" + prescription_rows + "'><option value=''>Select</option><option value='1'>1 Week</option><option value='2'>2 Week</option><option value='3'>1 Month</option><option value='4'>Afternoon</option><option value='5'>Morning</option><option value='6'>Evening</option><option value='7'>Night</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>"+instruction_row+"<textarea style='height:28px' name='instruction_"+prescription_rows+"' class=form-control id=description></textarea></div></div></div>";
                   var table_row= "<tr id='row" + prescription_rows + "'><td>" + div + "</td><td><label class='displayblock'>&nbsp;</label><button type='button' data-row-id='"+prescription_rows+"' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
                //$(table).append(table_row);
                 $('#tableID').append(table_row).

         $('.modal-body',"#add_prescription").find('table#tableID').find('.select2').select2();
                prescription_rows++;
            });*/


        $(document).on('click', '.add-record', function() {

            var rowCount = $('#tableID tr').length;
            var cat_row = "";
            var medicine_row = "";
            var dose_row = "";
            var dose_interval_row = "";
            var dose_duration_row = "";
            var instruction_row = "";
            var closebtn_row = "";
            if (rowCount == 0) {
                cat_row = "<label>Medicine Category</label>";
                medicine_row = "<label>Medicine</label> ";
                dose_row = " <label>Dose</label>";
                dose_interval_row = " <label>Dose Interval</label>";
                dose_duration_row = " <label>Dose Duration</label>";
                instruction_row = " <label>Instruction</label>";
                closebtn_row = " <label>&nbsp;</label>";
            }

            var div = "<input type='hidden' name='rows[]' value='" + prescription_rows +
                "' autocomplete='off'><div id=row1><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div col-sm-2 col-xs-6 '>" +
                cat_row + " <select class='form-control select2 medicine_category'  name='medicine_cat_" +
                prescription_rows + "'  id='medicine_cat" + prescription_rows +
                "'><option value=''>Select</option><option value='1'>Syrup</option><option value='2'>Capsule</option><option value='3'>Injection</option><option value='4'>Ointment</option><option value='5'>Cream</option><option value='6'>Surgical</option><option value='7'>Drops</option><option value='8'>Inhalers</option><option value='9'>Implants / Patches</option><option value='10'>Liquid</option><option value='11'>Preparations</option><option value='12'>Diaper	</option><option value='13'>Tablet</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
                medicine_row + " <select class='form-control select2 medicine_name' data-rowId='" +
                prescription_rows + "'  name='medicine_" + prescription_rows + "' id='search-query" +
                prescription_rows + "'><option value='l'>Select</option></select><small id='stock_info_" +
                prescription_rows +
                "''> </small></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" + dose_row +
                "<select  class='form-control select2 medicine_dosage' name='dosage_" + prescription_rows +
                "' id='search-dosage" + prescription_rows +
                "'><option value='l'>Select</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
                dose_interval_row + "<select  class='form-control select2 interval_dosage' name='interval_dosage_" +
                prescription_rows + "' id='search-interval-dosage" + prescription_rows +
                "'><option value=''>Select</option><option value='1'>Only one a day</option><option value='2'>2 times in a day</option><option value='3'>3 time  in day</option><option value='4'>4 times a day</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div> " +
                dose_duration_row + "<select class='form-control select2 duration_dosage' name='duration_dosage_" +
                prescription_rows + "' id='search-duration-dosage" + prescription_rows +
                "'><option value=''>Select</option><option value='1'>1 Week</option><option value='2'>2 Week</option><option value='3'>1 Month</option><option value='4'>Afternoon</option><option value='5'>Morning</option><option value='6'>Evening</option><option value='7'>Night</option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
                instruction_row + "<textarea style='height:28px' name='instruction_" + prescription_rows +
                "' class=form-control id=description></textarea></div></div></div>";

            var row = "<tr id='row" + prescription_rows + "'><td>" + div + "</td><td>" + closebtn_row +
                "<button type='button' onclick='delete_row(" + prescription_rows + ")' data-row-id='" +
                prescription_rows + "' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
            $('#tableID').append(row).find('.select2').select2();
            prescription_rows++;
        });


        $(document).on('click', '.delete_row', function(e) {

            var del_row_id = $(this).data('rowId');
            $("#row" + del_row_id).remove();



        });


        $(document).ready(function(e) {
            $("#add_timeline").on('submit', (function(e) {
                var patient_id = $("#patient_id").val();
                e.preventDefault();
                $("#add_timelinebtn").button('loading');
                $.ajax({
                    url: "https://demo.smart-hospital.in/admin/timeline/add_patient_timeline",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            $.ajax({
                                url: 'https://demo.smart-hospital.in/admin/timeline/patient_timeline/' +
                                    patient_id,
                                success: function(res) {
                                    $('#timeline_list').html(res);
                                    $('#myTimelineModal').modal('toggle');
                                },
                                error: function() {
                                    alert("Fail")
                                }
                            });
                            window.location.reload(true);
                        }
                        $("#add_timelinebtn").button('reset');
                    },
                    error: function(e) {
                        alert("Fail");

                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#add_bill").on('submit', (function(e) {
                if (confirm('')) {
                    $("#save_button").button('loading');
                    e.preventDefault();
                    $.ajax({
                        url: "https://demo.smart-hospital.in/admin/payment/addbill",
                        type: "POST",
                        data: new FormData(this),
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == "fail") {
                                var message = "";
                                $.each(data.error, function(index, value) {
                                    message += value;
                                });
                                errorMsg(message);
                            } else {
                                successMsg(data.message);
                                window.location.href =
                                    'https://demo.smart-hospital.in/admin/patient/discharged_patients';
                            }
                            $("#save_button").button('reset');

                        },
                        error: function(e) {
                            alert("Fail");

                        }
                    });
                } else {
                    return false;
                }

            }));
        });


        function delete_timeline(id) {
            var patient_id = $("#patient_id").val();
            if (confirm('Delete Confirm?')) {
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/timeline/delete_patient_timeline/' + id,
                    success: function(res) {
                        $.ajax({
                            url: 'https://demo.smart-hospital.in/admin/timeline/patient_timeline/' +
                                patient_id,
                            success: function(res) {
                                $('#timeline_list').html(res);
                                toastr.success(
                                    'Record Deleted Successfully',
                                    '', {
                                        timeOut: 1000,
                                        fadeOut: 1000,
                                        onHidden: function() {
                                            window.location.reload(true);
                                        }
                                    }
                                );
                            },
                            error: function() {
                                alert("Fail")
                            }
                        });
                    },
                    error: function() {
                        alert("Fail")
                    }
                });
            }
        }
        $(document).ready(function(e) {

            $(function() {
                var hash = window.location.hash;
                hash && $('ul.nav-tabs a[href="' + hash + '"]').tab('show');

                $('.nav-tabs a').click(function(e) {
                    $(this).tab('show');
                    var scrollmem = $('body').scrollTop();
                    window.location.hash = this.hash;
                    $('html,body').scrollTop(scrollmem);
                });
            });


        });

        function editTimeline(id) {

            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/editTimeline',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    var date_format = 'MM/dd/yyyy';
                    var dt = new Date(data.timeline_date).toString(date_format);
                    $("#etimelineid").val(data.id);
                    $("#epatientid").val(data.patient_id);
                    $("#etimelinetitle").val(data.title);
                    $("#etimelinedate").val(dt);

                    $("#timelineedesc").val(data.description);
                    if (data.status == '') {

                    } else {
                        $("#evisible_check").attr('checked', true);
                    }

                    holdModal('myTimelineEditModal');

                },
            });
        }

        function editNursenote(id) {

            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/editNursenote',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $("#nurse_id").val(data.nid);
                    $("#endate").val(data.note_date);
                    $("#enote").val(data.note);
                    $("#ecomment").val(data.comment);
                    $('select[id="edit_nurse"] option[value="' + data.staff_id + '"]').attr("selected",
                        "selected");
                    $("#edit_nurse").select2().select2('val', data.staff_id);
                    $('#customfieldnurse').html(data.custom_fields_value);
                    holdModal('nursenoteEditModal');
                },
            });
        }

        function editConsultantRegister(id) {

            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/editConsultantRegister',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $("#instruction_id").val(data.id);
                    // $("#ecpatient_id").val(data.patient_id);
                    $("#ecdate").val(data.date);
                    $("#ecinsdate").val(data.ins_date);
                    $("#ecinstruction").val(data.instruction);
                    $('select[id="editdoctor_field"] option[value="' + data.cons_doctor + '"]').attr("selected",
                        "selected");
                    $("#editdoctor_field").select2().select2('val', data.cons_doctor);
                    $('#customfieldconsult').html(data.custom_fields_value);
                    holdModal('edit_instruction');
                },
            });
        }

        function addcommentNursenote(id, ipdid) {
            $("#nurse_noteid").val(id);
            holdModal('nursenoteCommentModal');
        }

        $(document).ready(function(e) {
            $("#form_operationtheatre").on('submit', (function(e) {
                var did = $("#consultant_doctorid").val();
                $("#consultant_doctorname").val(did);
                $("#form_operationtheatrebtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/operationtheatre/addipdot',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#form_operationtheatrebtn").button('reset');
                    },
                    error: function() {}
                });
            }));
        });

        $(document).ready(function(e) {
            $("#edit_timeline").on('submit', (function(e) {
                $("#edit_timelinebtn").button('loading');
                var patient_id = $("#patient_id").val();
                e.preventDefault();
                $.ajax({
                    url: "https://demo.smart-hospital.in/admin/timeline/edit_patient_timeline",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#edit_timelinebtn").button('reset');
                    },
                    error: function(e) {
                        alert("Fail");
                        console.log(e);
                    }
                });
            }));
        });

        function editot(id) {
            var date_format = 'mm/dd/yyyy';
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/operationtheatre/getotDetails',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $("#otid").val(data.id);
                    $('#eoperation_category').select2().select2('val', data.category_id);

                    getcategory(data.category_id, data.operation_id);
                    $("#edate").val(data.otdate);
                    $("#eass_consultant_1").val(data.ass_consultant_1);
                    $("#eass_consultant_2").val(data.ass_consultant_2);
                    $("#eanesthetist").val(data.anesthetist);
                    $("#eanaethesia_type").val(data.anaethesia_type);
                    $("#eot_technician").val(data.ot_technician);
                    $("#eot_assistant").val(data.ot_assistant);
                    $("#custom_field_ot").html(data.custom_fields_value);
                    $("#eot_remark").val(data.remark);
                    $("#eot_result").val(data.result);
                    $("#edit_operationtheatre #econsultant_doctorid").select2().select2('val', data
                        .consultant_doctor);
                    $("#edit_operationtheatre #eoperation_name").select2().select2('val', data.operation_id);

                    holdModal('edit_operationtheatre');

                },
            });
        }

        $(document).ready(function(e) {
            $("#form_editoperationtheatre").on('submit', (function(e) {
                $("#form_editoperationtheatrebtn").button('loading');
                var cons = $("#cons_doctor").val();
                $("#cons_name").val(cons);
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/operationtheatre/update',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#form_editoperationtheatrebtn").button('reset');
                    },
                    error: function() {}
                });
            }));
        });

        $(document).ready(function(e) {
            $("#edit_nursenote").on('submit', (function(e) {
                $("#edit_nursenotebtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: "https://demo.smart-hospital.in/admin/patient/updatenursenote",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#edit_nursenotebtn").button('reset');
                    },
                    error: function(e) {
                        alert("Fail");
                        console.log(e);
                    }
                });
            }));
        });

        $(document).ready(function(e) {
            $("#comment_nursenote").on('submit', (function(e) {
                $("#comment_nursenotebtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: "https://demo.smart-hospital.in/admin/patient/addnursenotecomment",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.reload(true);
                        }
                        $("#comment_nursenotebtn").button('reset');
                    },
                    error: function(e) {
                        alert("Fail");
                        console.log(e);
                    }
                });
            }));
        });

        function edit_prescription(id) {
            $('#edit_prescription_title').html('Edit Prescription');
            $.ajax({
                url: base_url + 'admin/prescription/editipdPrescription',
                dataType: 'JSON',
                data: {
                    'prescription_id': id
                },
                type: "POST",
                beforeSend: function() {

                },
                success: function(res) {
                    $('#prescriptionview').modal('hide');
                    $('.modal-body', "#add_prescription").html(res.page);
                    var medicineTable = $('.modal-body', "#add_prescription").find('table#tableID');
                    $('.select2').select2();
                    medicineTable.find('.select2').select2();
                    $('.modal-body', "#add_prescription").find('.multiselect2').select2({
                        placeholder: 'Select',
                        allowClear: false,
                        minimumResultsForSearch: 2
                    });

                    prescription_rows = medicineTable.find('tr').length + 1;

                    medicineTable.find("tbody tr").each(function() {

                        var medicine_category_obj = $(this).find("td select.medicine_category");
                        var post_medicine_category_id = $(this).find(
                            "td input.post_medicine_category_id").val();
                        var post_medicine_id = $(this).find("td input.post_medicine_id").val();
                        var dosage_id = $(this).find("td input.post_dosage_id").val();
                        var medicine_dosage = getDosages(post_medicine_category_id, dosage_id);

                        $(this).find('.medicine_dosage').html(medicine_dosage);
                        $(this).find('.medicine_dosage').select2().select2('val', dosage_id);

                        getMedicine(medicine_category_obj, post_medicine_category_id, post_medicine_id);

                    });
                    $('#add_prescription').modal('show');
                },

                complete: function() {
                    $(function() {
                        $("#compose-textareas,#compose-textareanew").wysihtml5({
                            toolbar: {
                                "image": false,
                            }
                        });
                    });

                },
                error: function(xhr) { // if error occured
                    alert("Error Occurred, Please Try Again");


                }
            });
        }

        function view_prescription(id, ipdid, discharged = '') {

            $.ajax({
                url: base_url + 'admin/prescription/getIPDPrescription/',
                dataType: 'JSON',
                data: {
                    'prescription_id': id
                },
                type: "POST",
                beforeSend: function() {


                },
                success: function(res) {
                    $("#getdetails_prescription").html(res.page);
                },
                error: function(xhr) { // if error occured
                    alert("Error Occurred, Please Try Again");


                },
                complete: function() {

                }
            });

            if (discharged != "yes") {
                $('#edit_deleteprescription').html("<a href='#prescription' onclick='printprescription(" + id +
                    ")' data-toggle='tooltip'  data-original-title='Print'><i class='fa fa-print'></i></a><a href='#prescription' onclick='edit_prescription(" +
                    id +
                    ")' data-target='#edit_prescription'  data-toggle='tooltip' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a onclick='delete_prescription(" +
                    id + ")'    data-toggle='tooltip'  data-original-title='Delete'><i class='fa fa-trash'></i></a>");
            } else {
                $('#edit_deleteprescription').html("<a href='#prescription' onclick='printprescription(" + id +
                    ")'  data-toggle='tooltip' data-original-title='Print'><i class='fa fa-print'></i></a>");
            }


            holdModal('prescriptionview');
        }


        $(document).on('change', '.charge_type', function() {
            var charge_type = $(this).val();

            $('.charge_category').html("<option value=''>Loading...</option>");

            getcharge_category(charge_type, "");

        });


        function getcharge_category(charge_type, charge_category) {

            var div_data = "";
            if (charge_type != "") {

                $.ajax({
                    url: base_url + 'admin/charges/get_charge_category',
                    type: "POST",
                    data: {
                        charge_type: charge_type
                    },
                    dataType: 'json',
                    success: function(res) {
                        $.each(res, function(i, obj) {
                            var sel = "";
                            div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";
                        });
                        $('.charge_category').html("<option value=''>Select</option>");
                        $('.charge_category').append(div_data);
                        $('.charge_category').select2("val", charge_category);
                        $('#editcharge_category').select2("val", charge_category);
                    }
                });
            }
        }

        $(document).on('select2:select', '.charge_category', function() {

            var charge_category = $(this).val();

            $('.charge').html("<option value=''>Loading...</option>");
            getchargecode(charge_category, "");
        });

        $(document).on('select2:select', '.medicine_name', function() {

            var row_id_val = $(this).data('rowid');
            $.ajax({
                type: "POST",
                url: base_url + "admin/pharmacy/get_medicine_stockinfo",
                data: {
                    'pharmacy_id': $(this).val()
                },
                dataType: 'json',
                success: function(res) {
                    $('#stock_info_' + row_id_val).html(res);
                }
            });

        });

        function delete_prescription(prescription_id) {

            if (confirm('Are You Sure You Want To Delete This?')) {
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/prescription/deleteopdPrescription/' +
                        prescription_id,
                    success: function(res) {
                        window.location.reload(true);
                    },
                    error: function() {
                        alert("Fail")
                    }
                });

            }
        }

        function getchargecode(charge_category, charge_id) {
            // console.log(charge_id);
            var div_data = "<option value=''>Select</option>";
            if (charge_category != "") {
                $.ajax({
                    url: base_url + 'admin/charges/getchargeDetails',
                    type: "POST",
                    data: {
                        charge_category: charge_category
                    },
                    dataType: 'json',
                    success: function(res) {

                        $.each(res, function(i, obj) {
                            var sel = "";
                            div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";

                        });
                        $('.charge').html(div_data);
                        $(".charge").select2("val", charge_id);
                        $("#editcharge_id").select2("val", charge_id);

                    }
                });
            }
        }

        $(document).on('click', '.print_charge', function() {


            var $this = $(this);
            var record_id = $this.data('recordId')
            $this.button('loading');
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/printCharge',
                type: "POST",
                data: {
                    'id': record_id,
                    'type': 'ipd'
                },
                dataType: 'json',
                beforeSend: function() {
                    $this.button('loading');

                },
                success: function(res) {
                    popup(res.page);
                },
                error: function(xhr) { // if error occured
                    alert("Error Occurred, Please Try Again");
                    $this.button('reset');

                },
                complete: function() {
                    $this.button('reset');

                }
            });
        });

        $(document).on('select2:select', '.charge', function() {
            var charge = $(this).val();
            var orgid = $('#organisation_id').val();
            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/getChargeById',
                type: "POST",
                data: {
                    charge_id: charge,
                    organisation_id: orgid
                },
                dataType: 'json',
                success: function(res) {

                    if (res) {
                        var quantity = $('#qty').val();
                        $('#apply_charge').val(parseFloat(res.standard_charge) * quantity);
                        $('#standard_charge').val(res.standard_charge);
                        $('#schedule_charge').val(res.org_charge);
                        $('#charge_tax').val(res.percentage);
                        var standard_charge = res.standard_charge;
                        var schedule_charge = res.org_charge;
                        var discount_percent = 0;
                        var total_charge = (schedule_charge == "" || schedule_charge == "0.00") ?
                            standard_charge : schedule_charge;
                        var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 :
                            parseFloat(total_charge) * parseFloat(quantity);
                        var discount_amount = (apply_charge * discount_percent) / 100;
                        // $('#discount').val(discount_amount);
                        $('#apply_charge').val(apply_charge);
                        var final_amount = apply_charge - discount_amount;
                        $('#tax').val(((final_amount * res.percentage) / 100));

                        $('#final_amount').val(final_amount + ((final_amount * res.percentage) / 100));


                    }
                }
            });
        });



        $(document).on('change keyup input paste', '#qty', function() {
            var quantity = $(this).val();
            var standard_charge = $('#standard_charge').val();
            var schedule_charge = $('#schedule_charge').val();
            var tax_percent = $('#charge_tax').val();
            var total_charge = (schedule_charge == "") ? standard_charge : schedule_charge;
            var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 : parseFloat(
                total_charge) * parseFloat(quantity);
            $('#apply_charge').val(apply_charge.toFixed(2));

            var discount_percent = 0;
            var discount_amount = isNaN((apply_charge * discount_percent) / 100) ? 0 : (apply_charge *
                discount_percent) / 100;
            var final_amount = apply_charge - discount_amount;

            $('#discount').val(discount_amount);
            $('#tax').val(((final_amount * tax_percent) / 100).toFixed(2));
            $('#final_amount').val((final_amount + ((final_amount * tax_percent) / 100)).toFixed(2));
        });
        $(document).on('change keyup input paste', '#editqty', function() {
            var quantity = $(this).val();
            var standard_charge = $('#editstandard_charge').val();
            var schedule_charge = $('#editschedule_charge').val();
            var tax_percent = $('#editcharge_tax').val();
            var total_charge = (schedule_charge == "") ? standard_charge : schedule_charge;
            var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 : parseFloat(
                total_charge) * parseFloat(quantity);
            $('#editapply_charge').val(apply_charge.toFixed(2));

            var discount_percent = 0;
            var discount_amount = isNaN((apply_charge * discount_percent) / 100) ? 0 : (apply_charge *
                discount_percent) / 100;
            var final_amount = apply_charge - discount_amount;

            $('#discount').val(discount_amount);
            $('#edittax').val(((final_amount * tax_percent) / 100).toFixed(2));
            $('#editfinal_amount').val((final_amount + ((final_amount * tax_percent) / 100)).toFixed(2));
        });

        function calculate() {

            var discount_percent = $("#discount_percent").val();
            var tax_percent = $("#tax_percent").val();
            var other_charge = $("#other_charge").val();
            var paid_amount = $("#paid_amountpa").val();

            var total_amount = $("#total_amount").val();

            var subtotal_amount = parseFloat(total_amount) + parseFloat(other_charge);
            if (discount_percent != '') {
                var discount = (subtotal_amount * discount_percent) / 100;
                $("#discount").val(discount.toFixed(2));
            } else {
                var discount = $("#discount").val();

            }

            if (tax_percent != '') {
                var tax = ((subtotal_amount - discount) * tax_percent) / 100;
                $("#tax").val(tax.toFixed(2));
            } else {
                var tax = $("#tax").val();
            }

            var gross_total = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
            var net_amount = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
            var net_amount_payble = parseFloat(net_amount) - parseFloat(paid_amount);
            $("#gross_total").val(gross_total.toFixed(2));

            $("#grass_amount").val(net_amount.toFixed(2));
            $("#grass_amount_span").html(net_amount.toFixed(2));
            $("#net_amount").val(net_amount_payble.toFixed(2));
            $("#net_amount_span").html(net_amount_payble.toFixed(2));
            $("#net_amount_payble").val(net_amount_payble.toFixed(2));
            $("#save_button").show();
            $("#printBill").show();
        }

        function revert(patient_id, billid, bedid, ipdid) {


            $.ajax({
                url: 'https://demo.smart-hospital.in/admin/patient/revertBill',
                type: "POST",
                data: {
                    patient_id: patient_id,
                    bill_id: billid,
                    bed_id: bedid,
                    ipdid: ipdid
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == "fail") {
                        var message = "";
                        errorMsg(res.message);
                    } else {
                        successMsg(res.message);
                        window.location.href = 'https://demo.smart-hospital.in/admin/patient/ipdsearch';
                    }
                }
            });

        }



        function checkbed(patient_id, billid, bedid, ipdid) {
            var v = 'false';
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/setup/bed/checkbed',
                    type: "POST",
                    data: {
                        bed_id: bedid
                    },
                    dataType: 'json',
                    success: function(res) {

                        if (res.status == "fail") {
                            $("#alot_bed").modal('show');
                        } else {
                            revert(patient_id, billid, bedid, ipdid)
                        }

                    }
                });

            }

        }

        $(document).ready(function(e) {
            $("#consultant_register_form").on('submit', (function(e) {

                var doctor_id = $("#doctor_field").val();
                $("#doctor_set").val(doctor_id);


                $("#consultant_registerbtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/add_consultant_instruction',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        if (data.status == "fail") {

                            var message = "";
                            $.each(data.error, function(index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {

                            successMsg(data.message);
                            window.location.reload(true);

                        }
                        $("#consultant_registerbtn").button('reset');

                    },
                    error: function() {

                    }
                });


            }));
        });

        $(document).ready(function(e) {
            $("#editconsultant_register_form").on('submit', (function(e) {

                var doctor_id = $("#editdoctor_field").val();
                $("#editdoctor_set").val(doctor_id);


                $("#editconsultant_registerbtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/update_consultant_instruction',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        if (data.status == "fail") {

                            var message = "";
                            $.each(data.error, function(index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {

                            successMsg(data.message);
                            window.location.reload(true);

                        }
                        $("#editconsultant_registerbtn").button('reset');

                    },
                    error: function() {

                    }
                });


            }));
        });

        $(document).ready(function(e) {
            $("#nurse_note_form").on('submit', (function(e) {

                var nurse_id = $("#nurse_field").val();
                $("#nurse_set").val(nurse_id);
                $("#nurse_notebtn").button('loading');
                e.preventDefault();
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/add_nurse_note',
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        if (data.status == "fail") {

                            var message = "";
                            $.each(data.error, function(index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {

                            successMsg(data.message);
                            window.location.reload(true);

                        }
                        $("#nurse_notebtn").button('reset');

                    },
                    error: function() {

                    }
                });


            }));
        });


        function delete_consultant_row(id) {

            var table = document.getElementById("constableID");
            var rowCount = table.rows.length;
            $("#row" + id).html("");

        }
    </script>
    <script type="text/javascript">
        function deleteIpdPatient(ipdid) {
            if (confirm('Delete Confirm?')) {
                $.ajax({
                    url: base_url + 'admin/patient/deleteIpdPatient/',
                    type: 'POST',
                    data: {
                        ipdid: ipdid
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == "fail") {

                            var message = "";
                            $.each(data.error, function(index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            window.location.href = 'https://demo.smart-hospital.in/admin/patient/ipdsearch';

                        }
                    }
                });
            }
        }

        function printBill(patientid, ipdid) {
            var total_amount = $("#total_amount").val();
            var discount = $("#discount").val();
            var other_charge = $("#other_charge").val();
            var gross_total = $("#gross_total").val();
            var tax = $("#tax").val();
            var net_amount = $("#net_amount").val();
            var status = $("#status").val();
            var base_url = 'https://demo.smart-hospital.in/';
            $.ajax({
                url: base_url + 'admin/payment/getBill/',
                type: 'POST',
                data: {
                    patient_id: patientid,
                    ipdid: ipdid,
                    total_amount: total_amount,
                    discount: discount,
                    other_charge: other_charge,
                    gross_total: gross_total,
                    tax: tax,
                    net_amount: net_amount,
                    status: status
                },
                success: function(result) {
                    $("#testdata").html(result);
                    popup(result);
                }
            });
        }

        function popup(data, print = false) {
            var base_url = base_url;
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({
                "position": "absolute",
                "top": "-1000000px"
            });
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[
                0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html>');
            frameDoc.document.write('<head>');
            frameDoc.document.write('<title></title>');
            frameDoc.document.write('</head>');
            frameDoc.document.write('<body>');
            frameDoc.document.write(data);
            frameDoc.document.write('</body>');
            frameDoc.document.write('</html>');
            frameDoc.document.close();
            setTimeout(function() {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
                if (print) {
                    window.location.reload(true);
                }
            }, 500);
            return true;
        }

        function holdModal(modalId) {
            $('#' + modalId).modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        function delete_record(url, Msg) {
            if (confirm('Delete Confirm?')) {
                $.ajax({
                    url: url,
                    success: function(res) {
                        successMsg(Msg);
                        window.location.reload(true);
                    }
                })
            }
        }

        //function delete_record_dosage(id) {
        $(document).on('click', '.delete_record_dosage', function() {

            if (confirm('Delete Confirm?')) {
                var id = $(this).data('recordId');
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/patient/deletemedicationdosage',
                    type: "POST",
                    data: {
                        medication_id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        successMsg('Record Deleted Successfully');
                        window.location.reload(true);
                    }
                })
            }
        });

        function printprescription(id, print_status = false) {
            var base_url = 'https://demo.smart-hospital.in/';
            $.ajax({
                url: base_url + 'admin/prescription/printIPDPrescription/' + id,
                type: 'POST',
                data: {
                    prescription_id: id,
                    print: 'yes'
                },
                dataType: "json",
                success: function(result) {
                    $("#testdata").html(result.page);
                    popup(result.page, print_status);
                }
            });
        }

        $(function() {
            $("#compose-textareas,#compose-textareanew").wysihtml5({
                toolbar: {
                    "image": false,
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('change', '.chgstatus_dropdown', function() {
            $(this).parent('form.chgstatus_form').submit()

        });

        $("form.chgstatus_form").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 0) {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);

                        window.location.reload(true);
                    }
                }
            });
        });


        $('#myaddMedicationModal').on('hidden.bs.modal', function() {
            $('#add_medication').find('input:text, input:password, input:file, textarea').val('');
            $('#add_medication').find('select option:selected').removeAttr('selected');
            $('#add_medication').find('input:checkbox, input:radio').removeAttr('checked');
            $('.medicine_category_medication').select2("val", "");;
            $('.medicine_name_medication').select2("val", "");;
            $('.dosage_medication').select2("val", "");;
            $('#mtime').val('12:00 PM');
        });



        $(".addnursenote").click(function() {
            $('#nurse_note_form').trigger("reset");

        });


        $(".adddiagnosis").click(function() {
            $('#form_diagnosis').trigger("reset");
            //$(".dropify-clear").trigger("click");
            $('#add_diagnosis .filestyle').dropify();
        });

        $(".addtimeline").click(function() {
            $('#add_timeline').trigger("reset");
            $(".dropify-clear").trigger("click");
        });

        $(".addpayment").click(function() {
            $('#add_payment').trigger("reset");
            $(".dropify-clear").trigger("click");
        });


        $(".addcharges").click(function() {
            $('#add_charges').trigger("reset");
            $('#select2-charge_category-container').html("");
            $('#select2-code-container').html("");
        });

        $(document).on('click', '.addprescription', function() {
            $.ajax({
                url: base_url + 'admin/prescription/addipdPrescription',
                dataType: 'JSON',
                data: {
                    'ipd_id': ipd_id
                },
                type: "POST",
                beforeSend: function() {},
                success: function(res) {
                    $('#edit_prescription_title').html('Add Prescription');
                    $('.modal-body', "#add_prescription").html(res.page);
                    $('.modal-body', "#add_prescription").find('table').find('.select2').select2();
                    $('.select2').select2();
                    $('.modal-body', "#add_prescription").find('.multiselect2').select2({
                        placeholder: 'Select',
                        allowClear: false,
                        minimumResultsForSearch: 2
                    });

                    $('#add_prescription').modal('show');
                },
                complete: function() {
                    $("#compose-textareasadd,#compose-textareanewadd").wysihtml5({
                        toolbar: {
                            "image": false,
                        }
                    });
                },
                error: function(xhr) { // if error occured
                    alert("Error Occurred, Please Try Again");


                }
            });
        });

        function deleteot(id) {
            if (confirm('Delete Confirm?')) {
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/operationtheatre/delete/' + id,
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        successMsg('Record Deleted Successfully');
                        window.location.reload(true);
                    }
                })
            }
        }


        $(document).on('change', '.payment_mode', function() {
            var mode = $(this).val();
            if (mode == "Cheque") {
                $('.cheque_div').css("display", "block");
            } else {
                $('.cheque_div').css("display", "none");
            }
        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '.edit_charge', function() {
            var edit_charge_id = $(this).data('recordId');
            var createModal = $('#myChargeseditModal');
            var $this = $(this);
            $this.button('loading');
            $.ajax({
                url: base_url + 'admin/patient/getCharge',
                type: "POST",
                data: {
                    'id': edit_charge_id
                },
                dataType: 'json',
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(res) {

                    $('#editstandard_charge').val(res.result.standard_charge);
                    if (res.result.tpa_charge > 0) {
                        $('#editschedule_charge').val(res.result.tpa_charge);
                    }

                    $('#patient_charge_id').val(res.result.id);
                    $('#editqty').val(res.result.qty);
                    $('#editapply_charge').val(res.result.apply_charge);
                    $('#editfinal_amount').val(res.result.amount);
                    $('#editcharge_date').val(res.result.charge_date);

                    $('#editcharge_tax').val(res.result.percentage);
                    var tax_charge = (res.result.apply_charge * res.result.percentage) / 100;
                    $('#edittax').val(tax_charge.toFixed(2));
                    $('#editpatient_charge_id').val(res.result.id);
                    $('textarea#enote').val(res.result.note);

                    $('#edit_charge_type').select2('val', res.result.charge_type_master_id);
                    $('#myChargeseditModal').modal({
                        backdrop: 'static'
                    });
                    getcharge_category(res.result.charge_type_master_id, res.result.charge_category_id);
                    getchargecode(res.result.charge_category_id, res.result.charge_id);

                },
                error: function(xhr) { // if error occured
                    alert("Error Occurred, Please Try Again");
                    $this.button('reset');

                },
                complete: function() {
                    $this.button('reset');

                }
            });
        });

        $(document).ready(function(e) {
            $("#add_charges button[type=submit]").click(function() {
                $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
                $(this).attr("clicked", "true");
            });

            $("#add_charges").on('submit', (function(e) {
                e.preventDefault();
                var $this = $("button[type=submit][clicked=true]");
                var form = $(this);
                var form_data = form.serializeArray();
                var button_val = $this.attr('value');
                form_data.push({
                    name: "add_type",
                    value: button_val
                });
                $.ajax({
                    url: 'https://demo.smart-hospital.in/admin/charges/add_ipdcharges',
                    type: "post",
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function() {
                        $("#add_chargesbtn").button('loading');

                    },
                    success: function(res) {
                        if (res.status == "fail") {
                            var message = "";
                            $.each(res.error, function(index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else if (res.status == "new_charge") {
                            var data = res.data;
                            var row_id = makeid(8);
                            var charge = '<tr id="' + row_id + '"><td>' + data.date +
                                '<input type="hidden" name="pre_date[]" value="' + data
                                .date + '"></td><td>' + data.charge_type_name +
                                '</td><td>' + data.charge_category + '</td><td>' + data
                                .charge_name +
                                '<input type="hidden" name="pre_tax_percentage[]" value="' +
                                data.tax_percentage +
                                '"><input type="hidden" name="pre_charge_id[]" value="' +
                                data.charge_id + '"><br><h6>' + data.note +
                                '<input type="hidden" name="pre_note[]" value="' + data
                                .note + '"></h6></td><td>' + data.standard_charge +
                                '<input type="hidden" name="pre_standard_charge[]" value="' +
                                data.standard_charge + '"></td><td>' + data.tpa_charge +
                                '<input type="hidden" name="pre_tpa_charges[]" value="' +
                                data.tpa_charge + '"></td><td>' + data.qty +
                                '<input type="hidden" name="pre_qty[]" value="' + data.qty +
                                '"></td><td>' + data.amount +
                                '<input type="hidden" name="pre_total[]" value="' + data
                                .amount + '"></td><td>' + data.tax +
                                '<input type="hidden" name="pre_tax[]" value="' + data.tax +
                                '"><input type="hidden" name="pre_apply_charge[]" value="' +
                                data.apply_charge + '"></td><td>' + data.net_amount +
                                '<input type="hidden" name="pre_net_amount[]" value="' +
                                data.net_amount +
                                '"></td><td><label class=""></label><button type="button" class="closebtn delete_row" data-row-id="' +
                                row_id +
                                '" autocomplete="off"><i class="fa fa-remove"></i></button></td></tr>';
                            $('#preview_charges').append(charge);
                            charge_reset();
                        } else {
                            successMsg(res.message);
                            window.location.reload(true);
                        }

                    },
                    error: function() {
                        $("#add_chargesbtn").button('reset');
                    },
                    complete: function() {
                        $("#add_chargesbtn").button('reset');
                    }
                });
            }));
        });
        $(document).on('click', '.delete_row', function(e) {

            var del_row_id = $(this).data('rowId');
            var del_record_id = $(this).data('recordId');
            var result = confirm("Delete Confirm?");
            if (result) {
                $('#' + del_row_id).remove();
            }
        });

        function makeid(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() *
                    charactersLength));
            }
            return result;
        }

        function charge_reset() {
            $("#charge_category").select2("val", '');
            $("#add_charge_type").select2("val", '');
            $("#charge_id").select2("val", '');
            $("#standard_charge").val('');
            $("#schedule_charge").val('');
            $("#qty").val('');
            $("#charge_date").val('');
            $("#edit_note").val('');
            $("#charge_tax").val('');
            $("#tax").val('');
            $("#final_amount").val('');
            $("#apply_charge").val('');

        }
    </script>
    <script type="text/javascript">
        var hidWidth;
        var scrollBarWidths = 20;

        var widthOfList = function() {
            var itemsWidth = 0;
            $('.list li').each(function() {
                var itemWidth = $(this).outerWidth();
                itemsWidth += itemWidth;
            });
            return itemsWidth;
        };

        var widthOfHidden = function() {
            return (($('.wrappernav').outerWidth()) - widthOfList() - getLeftPosi()) - scrollBarWidths;
        };

        var getLeftPosi = function() {
            return $('.list').position().left;
        };

        var reAdjust = function() {
            if (($('.wrappernav').outerWidth()) < widthOfList()) {
                $('.scroller-right').show();
            } else {
                $('.scroller-right').hide();
            }

            if (getLeftPosi() < 0) {
                $('.scroller-left').show();
            } else {
                $('.item').animate({
                    left: "-=" + getLeftPosi() + "px"
                }, 'slow');
                $('.scroller-left').hide();
            }
        }

        reAdjust();

        $(window).on('resize', function(e) {
            reAdjust();
        });

        $('.scroller-right').click(function() {

            $('.scroller-left').fadeIn('slow');
            $('.scroller-right').fadeOut('slow');

            $('.list').animate({
                left: "+=" + widthOfHidden() + "px"
            }, 'slow', function() {

            });
        });

        $('.scroller-left').click(function() {

            $('.scroller-right').fadeIn('slow');
            $('.scroller-left').fadeOut('slow');

            $('.list').animate({
                left: "-=" + getLeftPosi() + "px"
            }, 'slow', function() {

            });
        });
    </script>
    <script type="text/javascript">
        function getDosages(medicine_category_id, selected_dosage = "") {

            var dosage_opt = "<option value=''>Select</option>";
            var category_dosage_json =
                '{"1":[{"id":"1","medicine_category_id":"1","dosage":"1 ","charge_units_id":"1","created_at":"2021-10-25 12:17:51","medicine_category":"Syrup","unit":"(ML)"}],"2":[{"id":"2","medicine_category_id":"2","dosage":"1","charge_units_id":"8","created_at":"2021-10-25 12:18:58","medicine_category":"Capsule","unit":"MG"}],"5":[{"id":"3","medicine_category_id":"5","dosage":"1","charge_units_id":"1","created_at":"2021-10-25 12:20:16","medicine_category":"Cream","unit":"(ML)"}],"7":[{"id":"4","medicine_category_id":"7","dosage":"1","charge_units_id":"4","created_at":"2021-10-25 12:20:24","medicine_category":"Drops","unit":"Day"}],"13":[{"id":"5","medicine_category_id":"13","dosage":"1\/2","charge_units_id":"4","created_at":"2021-10-25 12:20:42","medicine_category":"Tablet","unit":"Day"},{"id":"6","medicine_category_id":"13","dosage":"1","charge_units_id":"5","created_at":"2021-10-25 12:20:49","medicine_category":"Tablet","unit":"Hour"}],"3":[{"id":"7","medicine_category_id":"3","dosage":"0.5","charge_units_id":"1","created_at":"2021-11-01 06:57:22","medicine_category":"Injection","unit":"(ML)"},{"id":"8","medicine_category_id":"3","dosage":"1","charge_units_id":"4","created_at":"2021-11-01 06:59:19","medicine_category":"Injection","unit":"Day"}]}';
            var category_dosage_array = JSON.parse(category_dosage_json);

            if (category_dosage_array[medicine_category_id]) {
                $.each(category_dosage_array[medicine_category_id], function(key, item) {
                    var sel = "";
                    if (selected_dosage == item.id) {
                        sel = "selected";
                    }

                    dosage_opt += "<option value='" + item.id + "' " + sel + ">" + item.dosage + "" + item.unit +
                        "</option>";
                });

            }
            return dosage_opt;
        }
    </script>
@endpush
