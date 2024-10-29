<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/23/2020
 * Time: 6:07 PM
 */

?>


{!! Form::open(['route' => 'member.settings.store', 'method' => 'POST', 'role'=>'form' ]) !!}


<div class="box-body">
    <div class="col-md-6">
        <div class="form-group">
            <label for="cash_id"> Utility Cost  </label>
            {!! Form::number('utility_cost', config('settings.utility_cost'),['class'=>'form-control','placeholder'=>'utility cost set ', 'required']); !!}
        </div>
    </div>
</div>
<div class="box-footer">
    <div class="col-md-12">
        <button type="submit" class="btn btn-success form-group"> Update </button>
    </div>
</div>
<!-- /.box-body -->
{!! Form::close() !!}
