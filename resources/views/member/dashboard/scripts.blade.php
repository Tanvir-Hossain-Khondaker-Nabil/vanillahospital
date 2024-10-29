<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:46 PM
 */
?>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('public/adminLTE/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Morris.js charts -->
<script src="{{ asset('public/adminLTE/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/bower_components/morris.js/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('public/adminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('public/adminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('public/adminLTE/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
