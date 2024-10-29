<?php

$route = \Auth::user()->can(['member.patient_registration.index']) ? route('member.patient_registration.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Patient',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Patient',
    'title' => 'Add Services',
    'heading' => 'Add Services',
];

?>
@php
$cart = session()->get('cart', []);
// dd($cart);
@endphp
@extends('layouts.back-end.master', $data)

@push('style')
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
    <style>
        @media (max-width: 1366px) {
            table.dataTable {
                width: 100% !important;
            }
        }
    </style>
@endpush

@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-7">
                    <div class="box">

                        <div class="box-body py-4">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Service Name</th>
                                        <th>Doctor</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($services as $key => $list)
                                        <tr class="row{{ $list->id }}">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $list->title }}</td>
                                            <td>
                                                <select name="doctor_id" class="form-control select2" id="doctor_id">
                                                    <option value=""></option>
                                                    @foreach ($doctors as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $list->price }}</td>
                                            <td>
                                                <button onclick="addService('{{ $list->id }}',this)"
                                                    class="btn btn-sm btn-info">Add</button>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <form action="{{ route('member.service_store') }}" method="post">
                        @csrf
                        <div class="box mb-5">

                            <div class="box-body py-4">
                                <div class="form-group">
                                    <input type="hidden" name="marketing_officer_id" readonly value="{{ $marketingOfficerId }}">
                                    <label for="name">Patient ID  </label>
                                    <input id="patient_id" value="{{ $patient->patient_info_id }}" class="form-control"  required="" name="patient_id1" type="text">
                                    <input id="" value="{{ $patient->id }}" class="form-control"  required="" name="patient_id" type="hidden">
                                </div>
                                <div class="form-group">
                                    <label for="name">Patient Name  </label>
                                    <input id="name" value="{{ $patient->patient_name }}" class="form-control" readonly name="name" type="text">
                                </div>

                            </div>
                        </div>
                        <div class="box">

                            <div class="box-body py-4">

                                <table id="service" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#SL</th>
                                            <th>Service</th>
                                            <th>Doctor</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count = 0;
                                        @endphp
                                        @foreach ($cart as $key => $item)
                                            <tr>
                                                <td>{{ ++$count }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>
                                                    <input type="hidden" name="doctor_id[]"
                                                        value="{{ $item['doctor_id'] }}">
                                                    <input type="hidden" name="service[]"
                                                        value="{{ $item['id'] }}">
                                                    {{ $item['doctor_name'] }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="price[]" value='{{ $item['price'] }}'>
                                                    {{ $item['price'] }}</td>
                                                <td><input width="25%" type="text"
                                                        onkeyup="qtyChange(this,'{{ $item['id'] }}',{{ $item['price'] }})"
                                                        class="form-control" value="{{ $item['qty'] }}"></td>
                                                <td><a class="btn btn-danger mt-0 "
                                                        onclick="removeItem('{{ $item['id'] }} ')"><i
                                                            class="fa fa-trash"></i>
                                                    </a></td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                                <div class="text-rignt mt-2" id="saveBtn" style="display: none">
                                    <button class="btn btn-success float-right" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();
        function showAndHideSaveBtn(){
          let tr =  $("table#service tbody tr").length;
          if(tr>0){
            $("#saveBtn").show();
          }else{
            $("#saveBtn").hide();
          }
        }
        showAndHideSaveBtn()
        function qtyChange(e, id, price) {
            let qty = $(e).val();
            // let total = price * qty;
            // $(`.price${id} input.price`).val(total);
            // $(`.price${id} span`).html(total);
            $.ajax({
                type: 'Post',
                url: "{{ route('member.qtyChange') }}",
                data: {
                    item_id: id,
                    qty: qty,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {

                    $("table#service tbody").html(response.data.content);
                    showAndHideSaveBtn()

                    // $("#table#service tbody").html(response);
                    // console.log(response);
                },
                error: function(response) {
                    console.log(response)
                }
            });

        }

        function removeItem(id) {

            $.ajax({
                type: 'Post',
                url: "{{ route('member.removeItem') }}",
                data: {
                    item_id: id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("table#service tbody").html(response.data.content);
                    showAndHideSaveBtn()
                },
                error: function(response) {
                    console.log(response)
                }
            });

        }

        function addService(id, e) {
            let doc_id = $(e).parents('tr').find('select').val();
            console.log(doc_id);
            $.ajax({
                type: 'Post',
                url: "{{ route('member.addItem') }}",
                data: {
                    item_id: id,
                    doctor_id: doc_id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    // console.log(response);

                        $("table#service tbody").html(response.data.content);
                        showAndHideSaveBtn()

                    // calculateTotalAmount();
                    // console.log(response);
                },
                error: function(response) {
                    console.log(response)
                }
            });

        }
        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });

        });
        $(".date").datepicker({
            autoclose: true,
        });
    </script>
@endpush
