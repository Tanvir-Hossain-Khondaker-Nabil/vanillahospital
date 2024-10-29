<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/28/2019
 * Time: 2:12 PM
 */
// dd(session('status'))
?>
@if (session('status'))
    <div class="alert alert-{{ session('status')['type'] }} alert-out text-left">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> {{__('common.'.session('status')['type'])}}</h4>
        {{-- <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('status')['type']) }}</h4> --}}
        <span class="text-capitalize">{{ session('status')['message'] }}</span>
    </div>
@endif

