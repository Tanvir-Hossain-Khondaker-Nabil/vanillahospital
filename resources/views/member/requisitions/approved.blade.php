<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */


 $route = \Auth::user()->can(['member.requisition.index']) ?route('member.requisition.index'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Requisitions',
        'href' => $route,
    ],
    [
        'name' => 'Approve',
    ],
];

$data['data'] = [
    'name' => 'Approve requisition',
    'title'=> 'Approve requisition',
    'heading' => 'Approve requisition',
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
                <div class="box-header with-border">
                    <h3 class="box-title"> requisition</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.requisition.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

                <div class="box-body">

                    @include('member.requisitions._edit_form')
                    <div class="card">
                        <h4 class="text-center">Supplier List</h4>
                        <table class="table table-borderd">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="form-check-input check-box-all"/></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" class="form-check-input"/></td>
                                    <td>Tanvir Jaber</td>
                                    <td>Tanvir@gmail.com</td>
                                    <td>+8801876826168</td>
                                    <td>+8801876826168</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="form-check-input"/></td>
                                    <td>Tanvir Jaber</td>
                                    <td>Tanvir@gmail.com</td>
                                    <td>+8801876826168</td>
                                    <td>+8801876826168</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="submit" class="btn btn-primary">Send Mail</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('#notation').parent().parent().remove()
        $('.edit-button').remove()
        $('.delete-field').remove()
        $('.add-row').remove()
        $('input, select, textarea').attr('readonly','')
    })
    $(".check-box-all").change(function() {
        if(this.checked) {
            $('.form-check-input').not(this).prop('checked',this.checked);
        }else{
            $('.form-check-input').not(this).prop('checked',false);
        }
    });
</script>
@endpush
