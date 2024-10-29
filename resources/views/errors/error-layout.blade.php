<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{--    <title>{{  human_words(config('app.name')) }}</title>--}}

    <title>Vanilla Thunder ({{__('errors.manage_your_accounts_from_anywhere_in_any_device')}})</title>

    @include('landing.partials.styles')

    <style>

        .font-sans {
            font-family: Nunito, sans-serif;
        }

        .font-light {
            font-weight: 300;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-black {
            font-weight: 900;
        }

        .text-white {
            color: #ffffff;
        }

        .text-black {
            color: #22292f;
        }

        .text-grey-darkest {
            color: #3d4852;
        }

        .text-grey-darker {
            color: #606f7b;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .text-5xl {
            font-size: 3rem;
        }
        .uppercase {
            text-transform: uppercase;
        }

    </style>
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
    <div class="error-section bg_img" data-background="{{ asset('public/frontend_assets/images/account-bg.jpg') }}">
        <div class="container">
        <div class="row" style="height: 200px;">
            <div class="col-md-12">
                <div class="max-w-sm m-8">
                    <div>
                        <h1 class="text-5xl text-white font-sans" >@yield('code', __('Oh no'))</h1>
                    </div>

                    <div class="w-16 h-1 bg-purple-light my-3 md:my-6"></div>

                    <p class="text-white text-2xl md:text-3xl font-light mb-8 leading-normal">
                        @yield('message')
                    </p>

                    <a href="{{ app('router')->has('home') ? route('home') : url('/') }}">
                        <button class="bg-transparent text-white  font-bold uppercase tracking-wide py-2 px-3 border-2 border-grey-light hover:border-grey rounded-lg">
                            {{ __('errors.go_home') }}
                        </button>
                    </a>
                    <a href="{{ url()->previous() }}">
                        <button class="bg-transparent text-white  font-bold uppercase tracking-wide py-2 px-3 border-2 border-grey-light hover:border-grey rounded-lg">
                            {{ __('errors.go_back') }}
                        </button>
                    </a>
                </div>
            </div>

            <div class="relative pb-full md:flex md:pb-0 md:min-h-screen w-full md:w-1/2">
                @yield('image')
            </div>
        </div>
        </div>
    </div>


        <!--============= Footer Section Starts Here =============-->
        @include('landing.partials.footer')
        <!--============= Footer Section Ends Here =============-->

    </body>

    @include('landing.partials.scripts')

</html>

