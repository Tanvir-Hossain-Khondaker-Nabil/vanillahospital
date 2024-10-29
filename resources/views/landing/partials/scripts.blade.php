<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/29/2020
 * Time: 1:45 PM
 */
?>


<script src="{{ asset('public/frontend_assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/modernizr-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/plugins.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/wow.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/waypoints.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/nice-select.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/owl.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/counterup.min.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/paroller.js') }}"></script>
<script src="{{ asset('public/frontend_assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function(){
        $("#show_password").click( function () {

            if($(this).is(":checked"))
            {
                $('.show_password').attr('type', 'text') ;
            }else{
                $('.show_password').attr('type', 'password');
            }
        });
    });
</script>

@stack('scripts')
