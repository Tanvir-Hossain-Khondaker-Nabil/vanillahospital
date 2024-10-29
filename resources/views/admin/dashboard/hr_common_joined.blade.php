<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 9/19/2023
 * Time: 11:47 AM
 */

?>

<div class="box box-solid bg-blue-gradient">
    <div class="box-header  text-center">
        <h3 class="box-title"><a class="text-white month-join mr-3 py-2 px-3" data-target="previous"
                                 href="javascript:void(0)" data-content="{{ $show_month  }}"  data-title="{{ $show_year }}"> <i
                    class="fa fa-angle-left"></i></a> <span id="title-joined"> {{ date("m") == $show_month && date("Y") == $show_year ? trans('common.this_month') : $show_month_name }}  {{__('common.joined')}} ({{ $count_employee_joins }}) </span>
            <a class="text-white {{ date("m") == $show_month && date("Y") == $show_year ? '' : 'month-join' }}  ml-3  py-2 px-3" data-target="next" data-content="{{ $show_month  }}" data-title="{{ $show_year  }}"
               href="javascript:void(0)"> <i class="fa fa-angle-right"></i></a></h3>
    </div>

    <div class="box-footer text-black">
        <div class="row">
            @foreach($employee_joins as $date)
                <div class="col-sm-12">
                    <!-- Progress bars -->
                    <div class="clearfix">
                        <span class="pull-left h6">{{ $date->employee_name_id }}</span>
                        <small
                            class="pull-right h6">{{  \Carbon\Carbon::parse($date->join_date)->toFormattedDateString() }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- /.row -->
    </div>

</div>

