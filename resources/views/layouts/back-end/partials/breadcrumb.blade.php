<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:39 PM
 */
?>

<!-- Content Header (Page header) -->

@isset($breadcrumb)

<section class="content-header">
    <!-- BEGIN PAGE BAR -->

        @if( isset($data['heading']) )
            <h1 class="page-title">
                {{ $data['heading'] ? $data['heading'] : $data['name']}}
            </h1>
        @endif

    <ol class="breadcrumb">
        @foreach($breadcrumb as $b)
            <li class="text-capitalize {{ $loop->last ? 'active' : '' }}">
                @if(isset($b['href']))
                    <a href="{{$b['href']}}">
                        <i class="{{ isset($b['icon']) ? $b['icon'] : '' }}"></i>
                        {{$b['name']}}
                    </a>
                @else
                    <strong>{{$b['name']}}</strong>
                @endif
            </li>
        @endforeach
    </ol>
</section>

@endisset
<!-- END PAGE TITLE-->
