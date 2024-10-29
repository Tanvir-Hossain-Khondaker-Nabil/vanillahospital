<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 11:41 AM
 */
?>
<link rel="stylesheet" href="{{ asset('public/adminLTE/icons.css')}}">
{{-- <link rel="stylesheet" href="{{ asset('public/adminLTE/all.css')}}"> --}}
<link rel="stylesheet" href="{{ asset('public/adminLTE/fontawesome.css')}}">
{{-- <link rel="stylesheet" href="{{ asset('public/adminLTE/style.main.css')}}"> --}}
<!-- Google Font -->
<link rel="preload" as="font" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"  crossorigin="anonymous">
<link rel="preload" as="font"  href="{{ asset('public/adminLTE/bower_components/font-awesome/fonts/fontawesome-webfont.eot')}}" crossorigin="anonymous">
<link rel="preload" as="font"  href="{{ asset('public/adminLTE/bower_components/font-awesome/fonts/fontawesome-webfont.eot')}}" crossorigin="anonymous">
<link rel="preload" as="font"  href="{{ asset('public/adminLTE/bower_components/font-awesome/fonts/fontawesome-webfont.woff2')}}"  crossorigin="anonymous">
<link rel="preload" as="font"  href="{{ asset('public/adminLTE/bower_components/font-awesome/fonts/fontawesome-webfont.woff')}}"  crossorigin="anonymous">
<link rel="preload" as="font"  href="{{ asset('public/adminLTE/bower_components/font-awesome/fonts/fontawesome-webfont.ttf')}}"  crossorigin="anonymous">
<link rel="preload" as="font"  href="{{ asset('public/adminLTE/bower_components/font-awesome/fonts/fontawesome-webfont.svg')}}"  crossorigin="anonymous">

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/font-awesome/css/font-awesome.min.css')}}">
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/fontawesome.min.css">--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/solid.min.css">--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/brands.min.css">--}}
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

@stack('styles')

<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/AdminLTE.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/plugins/fileupload/dropify.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/custom.css')}}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/top-menu.css')}}">
<!-- public/adminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/skins/_all-skins.min.css')}}">



<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style type="text/css">

    .username{
        text-transform: capitalize;
    }

    div.dt-button-background{
        position: relative !important;
    }
</style>


<link rel="stylesheet" href="{{ asset('public/adminLTE/desktop.css')}}">
