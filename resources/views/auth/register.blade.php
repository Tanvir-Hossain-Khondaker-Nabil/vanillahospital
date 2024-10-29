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
<div class="container">
    <div class="card   mt-10 animated zoomIn bg-dark">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="text-center">
                    @include('common._alert')
                    @include('common._error')
                    {{--                    <img src="assets/images/logo-icon.png"/>--}}
                </div>
                <div class="card-title text-uppercase text-center py-2 text-white">Register</div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('common.company_name') }}</label>

                        <div class="col-md-6">
                            <input id="company" type="text" class="form-control" name="company_name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="company_address" class="col-md-4 col-form-label text-md-right">{{ __('common.company_address') }}</label>

                        <div class="col-md-6">
                            <input id="company_address" type="text" class="form-control" name="company_address" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_phone" class="col-md-4 col-form-label text-md-right">{{ __('Company Phone') }}</label>

                        <div class="col-md-6">
                            <input id="company_phone" type="text" class="form-control" name="phone" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="company_email" class="col-md-4 col-form-label text-md-right">{{ __('Company Email') }}</label>

                        <div class="col-md-6">
                            <input id="company_email" type="email" class="form-control" name="company_email" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="display_name" class="col-md-4 col-form-label text-md-right">Country <span class="text-red"> * </span> </label>
                        <div class="col-md-6">{!! Form::select('country_id',$countries,null,['id'=>'country','class'=>'form-control select2','placeholder'=>'Select Country','required']); !!}
                        </div>
                    </div>



                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4 text-right">
                            <a  href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                        </div>
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



