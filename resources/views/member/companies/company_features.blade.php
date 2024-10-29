<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */


 $route =  \Auth::user()->can(['member.company.index']) ? route('member.company.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Company',
        'href' => $route,
    ],
    [
        'name' => 'Set Feature',
    ],
];

$data['data'] = [
    'name' => 'Company',
    'title'=>'Set Company Feature',
    'heading' => 'Set Company Feature',
];

?>



@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title"> Set Company Feature </h3>--}}
                {{--</div>--}}
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.company.feature_store','method' => 'POST','files'=>'true','role'=>'form' ]) !!}

                <div class="box-body">
                    <h4 >Features</h4>
                    <hr>
                    @if(count($companies)>0)
                    <div class="form-group">
                        <label for="display_name">Companies <span class="text-red"> * </span> </label>
                        {!! Form::select('company_id',$companies,null,['id'=>'company','class'=>'form-control select2','placeholder'=>'Select company','required']); !!}
                    </div>
                    @endif

                    @foreach($features as $key => $value)
                        <div class="col-md-3 mt-3 text-capitalize">
                            <label>
                                @if(isset($activeFeatures))
                                    <input type="checkbox" class="" value="{{ $value }}" {{ in_array($value, $activeFeatures) ? 'checked' : '' }} name="features[]"> {{ str_replace( '_', ' ', $value) }}
                                @else
                                    <input type="checkbox" class="" value="{{ $value }}" name="features[]"> {{ str_replace( '_', ' ', $value) }}
                                @endif
                            </label>
                        </div>
                    @endforeach


                </div>

                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection


