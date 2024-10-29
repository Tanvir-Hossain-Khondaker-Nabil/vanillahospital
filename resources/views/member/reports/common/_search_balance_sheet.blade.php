<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/6/2020
 * Time: 3:58 PM
 */
?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Search</h3>
    </div>

    {!! Form::open(['route' => ['member.report.balance_sheet'],'method' => 'GET', 'role'=>'form' ]) !!}

    <div class="box-body">
        <div class="row">
            @if(Auth::user()->hasRole(['super-admin', 'developer']))
                <div class="col-md-3">
                    <label>  Select Company </label>
                    {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                </div>
            @endif
            <div class="col-md-3">
                <label>  Fiscal Year </label>
                {!! Form::select('fiscal_year', $fiscal_year, null ,['class'=>'form-control select2','placeholder'=>'Select All']); !!}
            </div>
            <div class="col-md-2">
                <label> Year </label>
                <input class="form-control year" name="year" value="" autocomplete="off"/>
            </div>
            <div class="col-md-2">
                <label> From Date </label>
                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
            </div>
            <div class="col-md-2">
                <label> To Date</label>
                <input class="form-control date" name="to_date" value="" autocomplete="off"/>
            </div>
            <div class="col-md-12">
                <div class="col-md-3 margin-top-23">
                    <input type="checkbox" name="t_based_view" value="1" {{ $t_based_view ? "checked" : "" }}/>
                    <label> T Based View </label>
                </div>

            </div>
            <div class="col-md-2 margin-top-23">
                <label></label>
                <input class="btn btn-sm btn-info" value="Search" type="submit"/>
                <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

            </div>
        </div>
        <!-- /.row -->
    </div>

    {!! Form::close() !!}
</div>


@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
            @if($set_company_fiscal_year)
            var $setDate = new Date( '{{ str_replace("-", "/", $set_company_fiscal_year->start_date) }}' );
            var today = new Date($setDate.getFullYear(), $setDate.getMonth(), $setDate.getDate(), 0, 0, 0, 0);
            @endif
            // console.log(new Date());
            $('.year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                endDate: '+0d',
                setDate: today
            });

            $('.date').change( function (e) {
                $('.date').attr('required', true);
            });

            $(".account_type_view").click( function (e) {
                e.preventDefault();

                var $view = $(this).data('id');
                $view.show();
            });


        });
    </script>
@endpush
