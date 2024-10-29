<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/8/2019
 * Time: 2:14 PM
 */
?>

{!! Form::open(['route' => 'member.settings.set_print_page_setup', 'method' => 'POST', 'role'=>'form' ]) !!}

<div class="box-body">
    <div class="col-md-6">
        <div class="form-group">
            <label for="fiscal_year_id">Page Setup Option </label>
            {!! Form::select('print_page_setup',$print_page_option, config('settings.print_page_setup'),['class'=>'form-control select2','placeholder'=>'Select Page Setup Option']); !!}
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
