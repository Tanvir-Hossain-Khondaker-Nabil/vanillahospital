
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hisebi System Users - {{  human_words(config('app.name')) }} | Accounting </title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/AdminLTE.min.css')}}">
</head>
<body class="hold-transition skin-blue sidebar-mini" style="margin: 0; padding: 0;">
<div class="wrapper">

        <!-- Main content -->
        <section class="content m-0 p-0">


                <div class="box " style="border: none;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Hisebi System Users List</h3>
                    </div>
                    <div class="box-body">
                            <!-- BEGIN SAMPLE FORM PORTLET-->

                            <div class="table-responsive">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                </div>


        </section>

</div>
@include('layouts.back-end.partials.scripts')

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
{!! $dataTable->scripts() !!}
</body>
</html>
