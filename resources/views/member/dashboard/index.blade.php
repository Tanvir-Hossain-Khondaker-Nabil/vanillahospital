<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */
 $route =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";
   $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Dashboard',
        'href' => $route,
    ]
];

$data['data'] = [
    'name' => 'Dashboard',
    'title'=>'Dashboard',
    'heading' => 'Dashboard',
];


?>
@extends('layouts.back-end.master', $data)


@push('styles')

    @include('member.dashboard.style')

        {{-- <style>
            .overlay {
                position: fixed;
                width: 100%;
                height: 100vh;
                top: 0;
                left: 0;
                background: #000000db;
                z-index: 9999;
            }

            .mainContent {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999999;
                flex-direction: column;
            }

            .middleContent {
                display: flex;
                width: 70%;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
                list-style-type: none;
                margin: 0;
                padding: 0;
                background: white;
                padding: 20px 0;
                border-radius: 10px;
            }

            .middleContent li:hover a {
                /* border: 1px solid; */
                /* border: 1px solid; */
                transition: all .5s;
                box-shadow: 0 0 6px 4px #00000020;
            }

            .middleContent li:hover a img {
                animation: myAnim 2s ease 0s 1 normal forwards;
            }

            @keyframes myAnim {
                0% {
                    animation-timing-function: ease-out;
                    transform: scale(1);
                    transform-origin: center center;
                }

                10% {
                    animation-timing-function: ease-in;
                    transform: scale(0.91);
                }

                17% {
                    animation-timing-function: ease-out;
                    transform: scale(0.98);
                }

                33% {
                    animation-timing-function: ease-in;
                    transform: scale(0.87);
                }

                45% {
                    animation-timing-function: ease-out;
                    transform: scale(1);
                }
            }

            .middleContent li:hover a span {
                animation: myAnim1 2s ease 0s 1 normal forwards;
            }

            @keyframes myAnim1 {
                0% {
                    animation-timing-function: ease-in;
                    opacity: 1;
                    transform: translateY(-45px);
                }

                24% {
                    opacity: 1;
                }

                40% {
                    animation-timing-function: ease-in;
                    transform: translateY(-24px);
                }

                65% {
                    animation-timing-function: ease-in;
                    transform: translateY(-12px);
                }

                82% {
                    animation-timing-function: ease-in;
                    transform: translateY(-6px);
                }

                93% {
                    animation-timing-function: ease-in;
                    transform: translateY(-4px);
                }

                25%,
                55%,
                75%,
                87% {
                    animation-timing-function: ease-out;
                    transform: translateY(0px);
                }

                100% {
                    animation-timing-function: ease-out;
                    opacity: 1;
                    transform: translateY(0px);
                }
            }

            .middleContent li {
                flex: 1 0 30%;
                max-width: 28%;
                transition: all .5s;
                /* background: red; */
            }

            .middleContent li a {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                background: white;
                padding: 10px;
                border-radius: 20px;
                border: 1px solid white;
            }

            .middleContent li a .img {
                width: 80px;
                order: 1;
            }

            .middleContent li a .img img {
                width: 100%;
                filter: drop-shadow(13px 13px 5px #00000030);
            }

            .middleContent li a span {
                order: 2;
                font-size: 21px;
                font-weight: 700;
                color: #009688;
                display: block;
                margin-top: 5px;
                text-shadow: 4px 4px 4px #00000020;
            }

            @media(max-width:775px) {
                .middleContent {
                    width: 80%;
                }
            }

            @media(max-width:575px) {
                .middleContent {
                    width: 90%;
                }

                .middleContent li a .img {
                    width: 60px;
                    order: 1;
                }

                .middleContent li a span {
                    font-size: 18px;
                }
            }

            @media(max-width:475px) {
                .middleContent {
                    width: 100%;
                }

                .middleContent li a .img {
                    width: 35px;
                    order: 1;
                }

                .middleContent li a span {
                    font-size: 15px;
                }
            }
        </style>
    @endpush


@section('contents')

    <div class="overlay">
    </div>


    <div class="mainContent">
        <ul class="middleContent">
            <li><a href="#">
                    <span>
                        Inventory
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/material-management.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        Attendance
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/immigration.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        HR
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/hr.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        Account
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/accounting.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        Proqurement
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/accounting.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        Requisation
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/project-management.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        Quotation
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/request.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        Pos
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/pos-terminal.png') }}" alt="">
                    </div>
                </a></li>
            <li><a href="#">
                    <span>
                        All
                    </span>
                    <div class="img">
                        <img src="{{ asset('public/icons/all.png') }}" alt="">
                    </div>
                </a></li>
        </ul>
    </div> --}}


    @section('contents')
<!-- Small boxes (Stat box) -->
   <div class="row">
    <div class="col-lg-3 col-xs-6">
        {{--<!-- small box -->--}}
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>150</h3>

                <p>New Orders</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{--<!-- ./col -->--}}
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Bounce Rate</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{--<!-- ./col -->--}}
    <div class="col-lg-3 col-xs-6">
        {{--<!-- small box -->--}}
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>44</h3>

                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{--<!-- ./col -->--}}
    <div class="col-lg-3 col-xs-6">
        {{--<!-- small box -->--}}
        <div class="small-box bg-red">
            <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{--<!-- ./col -->--}}
  </div>

@endsection

@push('scripts')

    @include('member.dashboard.scripts')

@endpush
