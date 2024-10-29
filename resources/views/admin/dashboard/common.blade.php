@push('styles')

    <style>
        .overlay {
            position: fixed;
            width: 100%;
            height: 100vh;
            top: 0;
            left: 0;
            background: #000000;
            z-index: 9999;
        }

        .mainContent {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            flex-direction: column;
        }

        .middleContent {
            display: flex;
            width: 70%;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            list-style-type: none;
            margin: 0;
            padding: 0;
            background: white;
            padding: 20px 0;
            border-radius: 10px;
        }

        .middleContent li:hover a {
            /* border: 1px solid; */
            /* border: 1px solid; */
            transition: all .5s;
            box-shadow: 0 0 6px 4px #00000020;
        }

        .middleContent li:hover a img {
            animation: myAnim 2s ease 0s 1 normal forwards;
        }

        @keyframes myAnim {
            0% {
                animation-timing-function: ease-out;
                transform: scale(1);
                transform-origin: center center;
            }

            10% {
                animation-timing-function: ease-in;
                transform: scale(0.91);
            }

            17% {
                animation-timing-function: ease-out;
                transform: scale(0.98);
            }

            33% {
                animation-timing-function: ease-in;
                transform: scale(0.87);
            }

            45% {
                animation-timing-function: ease-out;
                transform: scale(1);
            }
        }

        .middleContent li:hover a span {
            animation: myAnim1 2s ease 0s 1 normal forwards;
        }

        @keyframes myAnim1 {
            0% {
                animation-timing-function: ease-in;
                opacity: 1;
                transform: translateY(-45px);
            }

            24% {
                opacity: 1;
            }

            40% {
                animation-timing-function: ease-in;
                /*transform: translateY(-24px);*/
            }

            65% {
                animation-timing-function: ease-in;
                /*transform: translateY(-12px);*/
            }

            82% {
                animation-timing-function: ease-in;
                /*transform: translateY(-6px);*/
            }

            93% {
                animation-timing-function: ease-in;
                /*transform: translateY(-4px);*/
            }

            25%,
            55%,
            75%,
            87% {
                animation-timing-function: ease-out;
                transform: translateY(0px);
            }

            100% {
                animation-timing-function: ease-out;
                opacity: 1;
                transform: translateY(0px);
            }
        }

        .middleContent li {
            flex: 1 0 30%;
            max-width: 28%;
            transition: all .5s;
            /* background: red; */
        }

        .middleContent li a {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: white;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid white;
        }

        .middleContent li a .img {
            width: 80px;
            order: 1;
        }

        .middleContent li a .img img {
            width: 100%;
            filter: drop-shadow(13px 13px 5px #00000030);
        }

        .middleContent li a span {
            order: 2;
            font-size: 21px;
            font-weight: 700;
            color: #009688;
            display: block;
            margin-top: 5px;
            text-shadow: 4px 4px 4px #00000020;
        }

        @media (max-width: 775px) {
            .middleContent {
                width: 80%;
            }
        }

        @media (max-width: 575px) {
            .middleContent {
                width: 90%;
            }

            .middleContent li a .img {
                width: 60px;
                order: 1;
            }

            .middleContent li a span {
                font-size: 18px;
            }
        }

        @media (max-width: 475px) {
            .middleContent {
                width: 100%;
            }

            .middleContent li a .img {
                width: 35px;
                order: 1;
            }

            .middleContent li a span {
                font-size: 15px;
            }
        }
    </style>
@endpush
<div class="overlay">
</div>
<div class="mainContent">
    <ul class="middleContent">
        <li>
            <a href="{{ route('menu_change','inventory') }}">
                    <span>
                        {{__('common.inventory')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/material-management.png') }}" alt="">
                </div>
            </a>
        </li>
        <li><a href="{{ route('menu_change','inventory') }}">
                    <span>
                        {{__('common.pos')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/pos-terminal.png') }}" alt="">
                </div>
            </a>
        </li>
        <li><a href="{{ route('menu_change','accounts') }}">
                    <span>
                        {{__('common.accounts')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/accounting.png') }}" alt="">
                </div>
            </a>
        </li>
        <li><a href="{{ route('menu_change','project') }}">
                    <span>
                        {{__('common.project')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/project.png') }}" alt="">
                </div>
            </a>
        </li>
        {{--<li><a href="{{ route('menu_change','hr') }}">--}}
                    {{--<span>--}}
                        {{--Procurement--}}
                    {{--</span>--}}
                {{--<div class="img">--}}
                    {{--<img src="{{ asset('public/icons/procurement.png') }}" alt="">--}}
                {{--</div>--}}
            {{--</a>--}}
        {{--</li>--}}
        <li><a href="{{ route('menu_change','requisition') }}">
                    <span>
                        {{__('common.requisition')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/project-management.png') }}" alt="">
                </div>
            </a>
        </li>
        <li><a href="{{ route('menu_change','quotation') }}">
                    <span>
                        {{__('common.quotation')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/request.png') }}" alt="">
                </div>
            </a>
        </li>
        <li><a href="{{ route('menu_change','hr') }}">
                    <span>
                        {{__('common.attendance')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/immigration.png') }}" alt="">
                </div>
            </a>
        </li>
        <li>
            <a href="{{ route('menu_change','hr') }}">
                    <span>
                        {{__('common.hr')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/hr.png') }}" alt="">
                </div>
            </a>
        </li>
        <li><a href="{{ route('menu_change','all') }}">
                    <span>
                        {{__('common.all')}}
                    </span>
                <div class="img">
                    <img src="{{ asset('public/icons/all.png') }}" alt="">
                </div>
            </a>
        </li>
    </ul>
</div>
