<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.fetch.sub_tes_group']) ? route('member.fetch.sub_tes_group') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Set Report Template',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Set Report Template ',
    'title' => 'Set Report Template',
    'heading' => 'Set Report Template',
];

?>

@extends('layouts.back-end.master', $data)
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css" rel="stylesheet">

    <style type="text/css" media="screen">

        .bio-chemestry {
            border: 1px solid #111111;
            width: 90%;
            margin: 15px auto;
            padding-bottom: 0px;
            height: auto;
        }





        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .farhana-table-4 {

            width: 100%;
            margin: 0 auto;

            border-collapse: collapse;

            font-size: 18px;
        }




        .farhana-table-4 tr th {
            border: 1px solid #111111;
            border-top: 0px solid #ffffff !important;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            background: transparent;
            word-spacing: 2px;
            text-transform: uppercase;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px
        }

        .farhana-table-4 tr th:first-child {
            text-align: left;
            width: 45%;
            border-left: none !important;
        }

        .farhana-table-4 tr th:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr th:last-child {
            text-align: center;
            width: 100%;
            border-right: none !important;
        }

        .farhana-table-4 tr td:nth-child(3) {
            text-align: center;

        }

        .farhana-table-4 tr td:first-child {
            text-align: left;
            border-left: none !important;
            width: 40%;
        }

        .farhana-table-4 tr td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr td:last-child {
            text-align: left;
            border-right: none !important;
            width: 30%;
        }

        .farhana-table-4 tr td {
            border: 1px solid #111111;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            border-bottom: 0px solid #fff;
        }


        .tranform-text {
            font-size: 22px !important;
            font-weight: bold;
            transform: rotate(-30deg);
            text-align: center;
            vertical-align: middle;
            width: 57%;
        }

        .unit-class {
            font-size: 16px;
            padding: 30px 0px;
        }

        .delivery {
            font-size: 10px;
        }

        .last-p {
            padding: 4px;
            font-size: 16px;
            border: 1px solid #111111;
            border-radius: 13px;
            width: 163px;
            margin: 10px 0px;
        }

        .print {
            font-size: 16px;
        }

        .authorize {
            font-size: 16px;
            text-decoration: overline;
            text-align: right;
        }

        .blank {
            height: 10px !important;
        }
    </style>

    <style type="text/css" media="screen">



        .bio-chemestry {
            border: 1px solid #111111;
            width: 90%;
            margin: 15px auto;
            padding-bottom: 0px;
            height: auto;
        }



        .table-3-text-right {
            text-align: right !important;
        }


        .farhana-table-5 {
            margin: 0 auto;
            width: 90% !important;
            margin-top: 10px;
            border: 1px solid #111111;
            border-radius: 8px;
        }

        .doctor-name {
            font-size: 16px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .farhana-table-4 {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 18px;
        }


        .farhana-table-4 tr th {
            border: 1px solid #111111;
            border-top: 0px solid #ffffff !important;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            background: transparent;
            word-spacing: 2px;
            text-transform: uppercase;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px
        }

        .farhana-table-4 tr th:first-child {
            text-align: left;
            width: 45%;
            border-left: none !important;
        }

        .farhana-table-4 tr th:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr th:last-child {
            text-align: center;
            width: 100%;
            border-right: none !important;
        }

        .farhana-table-4 tr td:nth-child(3) {
            text-align: center;

        }

        .farhana-table-4 tr td:first-child {
            text-align: left;
            border-left: none !important;
            width: 40%;
        }

        .farhana-table-4 tr td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr td:last-child {
            text-align: left;
            border-right: none !important;
            width: 30%;
        }

        .farhana-table-4 tr td {
            border: 1px solid #111111;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            border-bottom: 0px solid #fff;
        }



    </style>

    <style type="text/css" media="screen">


        .first-h1 {
            font-size: 22px;
            color: #111111;
            text-align: left;
            font-weight: 600;
        }

        .first-p {
            font-size: 16px;
            color: #111111;
            text-align: left;
            margin-top: -13px;

        }

        .first-p-1 {
            font-size: 16px;
            color: #111111 !important;
            text-align: left;
            margin-top: -10px;
            font-family: 'BenchNine', sans-serif;

        }

        .farhana-table-2 {
            width: 100%;

        }



        .bio-chemestry {
            border: 1px solid #111111;
            width: 90%;
            margin: 15px auto;
            padding-bottom: 0px;
            height: auto;
        }



        .farhana-table-3 {
            margin: 0 auto;
            width: 90%;
            margin-top: 10px;
            border: 1px solid #111111;
            border-radius: 8px;
        }





        .doctor-name {
            font-size: 16px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .farhana-table-4 {

            width: 100%;
            margin: 0 auto;

            border-collapse: collapse;

            font-size: 18px;

        }



        .farhana-table-4-tr {
            border-top-left-radius: 8px;
        }



        .farhana-table-4 tr th {
            /* border: 1px solid #111111; */
            border-top: 0px solid #ffffff !important;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            background: transparent;
            word-spacing: 2px;
            text-transform: uppercase;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px
        }

        .farhana-table-4 tr th:first-child {
            text-align: center;
            width: 100%;
            border-left: none !important;
        }

        .farhana-table-4 tr th:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr th:last-child {
            text-align: center;
            width: 100%;
            border-right: none !important;
        }

        .farhana-table-4 tr td:nth-child(3) {
            text-align: center;

        }

        .farhana-table-4 tr td:first-child {
            text-align: center;
            border-left: none !important;
            width: 100%;
        }

        .farhana-table-4 tr td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr td:last-child {
            text-align: left;
            border-right: none !important;
            width: 30%;
        }

        .farhana-table-4 tr td {
            border: 1px solid #111111;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            border-bottom: 0px solid #fff;
        }




        .tranform-text {
            font-size: 22px !important;
            font-weight: bold;
            transform: rotate(-30deg);
            text-align: center;
            vertical-align: middle;
            width: 57%;
        }

        .unit-class {
            font-size: 16px;
            padding: 30px 0px;
        }

        .delivery {
            font-size: 10px;
        }

        .last-p {
            padding: 4px;
            font-size: 16px;
            border: 1px solid #111111;
            border-radius: 13px;
            width: 163px;
            margin: 10px 0px;
        }

        .print {
            font-size: 16px;
        }

        .authorize {
            font-size: 16px;
            text-decoration: overline;
            text-align: right;
        }

        .blank {
            height: 10px !important;
        }
    </style>

    <style type="text/css" media="screen">


        .bio-chemestry {
            border: 1px solid #111111;
            width: 90%;
            margin: 15px auto;
            padding-bottom: 0px;
            height: auto;
        }

        .table-1-col-1 {
            width: 33%;
            text-align: center;
        }

        .table-1-col-1 p {

            font-weight: bold;
            text-align: center;
            font-size: 16px;
            text-decoration: underline;
        }


        .doctor-name {
            font-size: 16px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .farhana-table-4 {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 18px;
        }


        .farhana-table-4-tr {
            border-top-left-radius: 8px;
        }



        .farhana-table-4 tr th {
            border: 1px solid #111111;
            border-top: 0px solid #ffffff !important;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            background: transparent;
            word-spacing: 2px;
            text-transform: uppercase;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px
        }

        .farhana-table-4 tr th:first-child {
            /* text-align: left; */
            width: 100%;
            border-left: none !important;
        }

        .farhana-table-4 tr th:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr th:last-child {
            text-align: center;
            width: 100%;
            border-right: none !important;
        }

        .farhana-table-4 tr td:nth-child(3) {
            text-align: center;

        }

        .farhana-table-4 tr td:first-child {
            text-align: left;
            border-left: none !important;
            width: 40%;
        }

        .farhana-table-4 tr td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .farhana-table-4 tr td:last-child {
            text-align: center;
            border-right: none !important;
            width: 100%;
        }

        .farhana-table-4 tr td {
            border: 1px solid #111111;
            border-collapse: collapse !important;
            text-align: center;
            padding: 2px;
            padding-left: 7px;
            border-bottom: 0px solid #fff;
        }



        .tranform-text {
            font-size: 22px !important;
            font-weight: bold;
            transform: rotate(-30deg);
            text-align: center;
            vertical-align: middle;
            width: 57%;
        }

        .unit-class {
            font-size: 16px;
            padding: 30px 0px;
        }

        .delivery {
            font-size: 10px;
        }

        .last-p {
            padding: 4px;
            font-size: 16px;
            border: 1px solid #111111;
            border-radius: 13px;
            width: 163px;
            margin: 10px 0px;
        }

        .print {
            font-size: 16px;
        }

        .authorize {
            font-size: 16px;
            text-decoration: overline;
            text-align: right;
        }

        .blank {
            height: 10px !important;
        }
    </style>
@endpush
@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        {!! Form::open([
                            'route' => 'member.pathology_reports.store',
                            'method' => 'POST',
                            'files' => 'true',
                            'role' => 'form',
                        ]) !!}


                        <div class="box" style="margin-top: 20px">

                            <div class="box-body" style="margin-top: 20px">
                                <div class="col-md-1">
                                    <span> Test Name:</span>
                                </div>
                                <div class="col-md-5">
                                    <input class="form-control" readonly value="{{ $models->title }}" name="">
                                    <input type="hidden" value="{{ $models->id }}" name="sub_test_group_id">

                                </div>

                                <div style="margin-top: 100px">

                                    <textarea id="summernote" class=" form-control" name="description">

                                        <div class="bio-chemestry" style="margin-bottom: 15px">
                                          <table class="farhana-table-4" >
                                            <tbody>
                                            <tr  style="text-align: center">
                                                <th colspan="4">
                                                    Test Name : {{ $models->title }}

                                                </th>
                                            </tr>

                                            <tr style="background-color: rgb(240 240 240);">
                                              <td>
                                                TEST DESCRIPTION
                                            </td>
                                              <td>RESULT<br></td>
                                              <td style="width: 20%;">REF. RANGE</td>
                                              <td>UNIT</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                  {{ $models->title }}
                                              </td>
                                                <td>1<br></td>
                                                <td>u/l</td>
                                                <td>
                                                1</td>
                                              </tr>
                                          </tbody>
                                        </table>
                                        </div>

                                    </textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>

            <div>

            </div>

        </div>


    </div>
@endsection



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script> --}}
    <script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.js"></script>

    <script type="text/javascript">
        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });

        $('#summernote').summernote({
            placeholder: 'Please Write Here',
            // tabsize: 2,
            height: 300
        });
    </script>

    <script>
        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });

        });
    </script>
@endpush
