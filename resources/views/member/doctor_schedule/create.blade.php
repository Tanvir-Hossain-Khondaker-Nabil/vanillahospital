<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.doctor_schedule.index']) ? route('member.doctor_schedule.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Doctor',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Doctor Schedule',
    'title'=>'Create Doctor Schedule',
    'heading' => 'Doctor Schedule',
];

?>



@extends('layouts.back-end.master', $data)
@push('styles')

    {{-- <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
     --}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.css" integrity="sha512-BB0bszal4NXOgRP9MYCyVA0NNK2k1Rhr+8klY17rj4OhwTmqdPUQibKUDeHesYtXl7Ma2+tqC6c7FzYuHhw94g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('contents')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Doctor Schedule</h3>
            </div>
            <div class="box-header">
                {{-- <div class="box-header">
                    @if (\Auth::user()->can(['member.doctor_schedule.index']))
                        <a href="{{ route('member.doctor_schedule.index') }}" class="btn btn-info"> Doctor Schedule List</a>
                    @endif

                </div> --}}
            </div>

            {{-- {!! Form::open(['route' => 'member.doctor_schedule.store','method' => 'POST','files'=>'true','role'=>'form' ]) !!} --}}

            <div class="box-body">

                @include('member.doctor_schedule._form')

            </div>


            <!-- /.box-body -->

            {{-- {!! Form::close() !!} --}}
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection


