<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
{{--    <title>{{  human_words(config('app.name')) }}</title>--}}

    <title>Vanilla Thunder (Manage Your Accounts From Anywhere in any Device)</title>

    @include('landing.partials.styles')

    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
</head>

<body>
<!--============= ScrollToTop Section Starts Here =============-->
<div class="preloader">
    <div class="preloader-inner">
        <div class="preloader-icon">
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
<div class="overlay"></div>
<!--============= ScrollToTop Section Ends Here =============-->


<!--============= Header Section Starts Here =============-->
    @include('landing.partials.header')
<!--============= Header Section Ends Here =============-->

    @yield('content')

<!--============= Footer Section Starts Here =============-->
    @include('landing.partials.footer')
<!--============= Footer Section Ends Here =============-->

</body>

    @include('landing.partials.scripts')

</html>
