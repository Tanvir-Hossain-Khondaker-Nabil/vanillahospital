<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/21/2019
 * Time: 5:01 PM
 */
?>


<div class="box">
    <div class="box-body">
        <div class="col-md-6">
            <div class="form-group">
                <label for="date"> Bank Full Name </label>
                {!! Form::text('display_name', null,['id'=>'display_name', 'class'=>'form-control','required']); !!}
            </div>
            {{--<div class="form-group">--}}
                {{--<label for="date"> Short Name </label>--}}
                {{--{!! Form::text('short_name', null,['id'=>'short_name', 'class'=>'form-control','required']); !!}--}}
            {{--</div>--}}
            <div class="form-group">
                <label for="bank_id"> Status </label>
                {!! Form::select('status', [ 'active'=>'Active', 'inactive'=>'inactive'], null,['id'=>'status', 'class'=>'form-control']); !!}
            </div>
        </div>
    </div>
</div>
