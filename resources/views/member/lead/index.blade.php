<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.lead.index']) ? route('member.lead.index') : '#';
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
    'name' => 'Lead',
    'title' => 'List Of Lead',
    'heading' => 'List Of Lead',
];

?>

@extends('layouts.back-end.master', $data)

<style>
    .cke_contents{
    height: 100px !important;
}
</style>
@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    <div class="box-header">
                        @if (\Auth::user()->can(['member.lead.create']))
                            <a href="{{ route('member.lead.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add Lead
                                </i></a>
                        @endif
                        @if (\Auth::user()->can(['member.label.create']))
                            <a href="#" onclick="manageLabel('lead')" class="btn btn-success btn-sm"> <i
                                    class="fa fa-gear"></i> Manage Labels </a>
                        @endif
                    </div>



                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="showLeadModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div style="max-width: 900px !important" class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-success" id="">Lorem Ipsum is not simply random text
                                </h4>

                                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="mt-3">
                                    <div class="mt-3">
                                        <strong class="">Client Name:</strong>
                                        <span id="client-name"></span>
                                    </div>

                                    <div class="mt-3">
                                        <strong class="mt-3">Company Name:</strong>
                                        <span id="company-name"></span>
                                    </div>
                                    <div class="mt-3">
                                        <strong class="mt-3">Lead Category:</strong>
                                        <span id="lead-category"></span>
                                    </div>
                                    <div class="mt-3">
                                        <strong class="mt-3">Status:</strong>
                                        <span id="lead-status"></span>
                                    </div>
                                    <div class="mt-3">
                                        <strong class="mt-3">Label:</strong>
                                        <span id="lead-label"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="LeadStatusChangeModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div style="max-width: 900px !important" class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-success" id="">Add Lead Comment</h4>

                                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="" class="col-form-label">Lead Status</label>
                                        <select class="form-control" id="lead-status" onchange="leadStatusChange(this)">
                                            <option value=''>Change Status</option>
                                            <option value='Qualified'>Qualified</option>
                                            <option value='Discussion'>Discussion</option>
                                            <option value='Negotiation'>Negotiation</option>
                                            <option value='Won'>Won</option>
                                            <option value='Lost'>Lost</option>
                                            <option value='Canceled'>Canceled</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Lead Comment</label>
                                        <textarea type="text" rows="10" cols="10" name="lead_comment" id="lead_comment" class="form-control" id="recipient-name"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="save-lead-comment">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}

    @include('common.label_script');
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script>
    $(function() {

        CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align',
            'bidi', '-',
            'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
        ]];
        CKEDITOR.replace('lead_comment', {
            toolbar: 'MA'
        });
    });
</script>
@endpush
