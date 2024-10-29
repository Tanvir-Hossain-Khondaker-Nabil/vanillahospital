<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from codervent.com/dashrock/color-admin/authentication-dark-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 10 Feb 2019 04:23:10 GMT -->
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{  human_words(config('app.name')) }}</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('public/login_assets/images/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('public/login_assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <!-- animate CSS-->
    <link href="{{ asset('public/login_assets/css/animate.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Icons CSS-->
    <link href="{{ asset('public/login_assets/css/icons.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Custom Style-->
    <link href="{{ asset('public/login_assets/css/app-style.css') }}" rel="stylesheet"/>

</head>

<body class="authentication-bg">
<!-- Start wrapper-->
<div id="wrapper">
    <div class="card card-authentication1 mx-auto mt-10 animated zoomIn bg-dark">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="text-center">
{{--                    <img src="assets/images/logo-icon.png"/>--}}
                </div>
                <div class="card-title text-uppercase text-center py-2 text-white">Sign In</div>
                    <form method="POST" action="{{ route('login') }}"  class="color-form">
                        @csrf
                    <div class="form-group">
                        <div class="position-relative has-icon-left">
                            <label for="exampleInputUsername" class="sr-only">Email</label>
                            <input type="text" id="exampleInputUsername" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"  placeholder="Email" autocomplete="off"/>
                            <div class="form-control-position">
                                <i class="icon-user"></i>
                            </div>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="position-relative has-icon-left">
                            <label for="exampleInputPassword" class="sr-only">Password</label>
                            <input type="password" id="exampleInputPassword" class="show_password form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" autocomplete="off" name="password" placeholder="Password">
                            <div class="form-control-position">
                                <i class="icon-lock"></i>
                            </div>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row mr-0 ml-0">
{{--                        <div class="form-group col-6">--}}
{{--                            <div class="icheck-material-primary">--}}
{{--                                <input type="checkbox" id="user-checkbox" class="filled-in chk-col-danger" checked="" />--}}
{{--                                <label for="user-checkbox">Remember me</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--<div class="form-group col-12 text-right">--}}
                            {{--<input type="checkbox" id="show_password" value="1" style="height: 15px; padding: 0 15px; width: auto;" />--}}
                            {{--<label for="name" class="text-warning">Show Password</label>--}}
                        {{--</div>--}}
                        <div class="form-group col-12 text-right">
                            @if (Route::has('password.request'))
                                <a  href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-block waves-effect waves-light">Sign In</button>
                    </div>
                        <div class="form-group col-12 text-left">
                            @if (Route::has('register'))
                                <a  href="{{ route('register') }}">
                                    {{ __('Create Account') }}
                                </a>
                            @endif
                        </div>
                </form>
            </div>
        </div>
    </div>
</div><!--wrapper-->

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('public/login_assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/login_assets/js/popper.min.js') }}"></script>
{{--<script src="{{ asset('public/login_assets/js/bootstrap.min.js') }}"></script>--}}
<!-- waves effect js -->
<script src="{{ asset('public/login_assets/js/waves.js') }}"></script>
<!-- Custom scripts -->
<script src="{{ asset('public/login_assets/js/app-script.js') }}"></script>

</body>

</html>
