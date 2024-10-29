<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/4/2019
 * Time: 4:23 PM
 */
?>

<div style="width: 100%;">
    <table style="margin-bottom: 10px; width: 100%;  margin-top: 0mm; border: 0;">
        {{--<tr>--}}
            {{--<td style="text-align: center; border: 0 !important;" width="100%" >--}}
                {{--<h2 style="font-size: 25px; margin-bottom: 5px;"> {{ config('company.name') }} </h2>--}}
            {{--</td>--}}
        {{--</tr>--}}
        <tr style="border: 0 !important;" class="border-0">
            @if($company_logo)
                <th style="text-align: right; border: 0 !important;" width="30%">
                    <img height="100px" width="100px" src="{{ $company_logo }}" alt="{{ $company_name }}" id="logo"/>
                </th>
            @endif
            <td style="text-align: {{ $company_logo ? 'left; padding-left: 70px':  'center' }};  border: 0 !important;" width="{{ $company_logo ? 70 : 100 }}%" >
                <h3 style="font-size: 22px; margin-bottom: 5px;"> {{ $company_name }} </h3>
                <p> {{__('common.address')}}: {{ $company_address }}</p>
                <p> {{ $company_city.', '.$company_country }}</p>
                <p> {{__('common.phone')}}: {{ $company_phone }}</p>
            </td>
        </tr>
        <tr style="border: 0 !important;" class="border-0">
            <th colspan="{{$company_logo ? 2 :'' }}" style="border: 0 !important; padding-top: 5px; text-align: center;">
                <h2>{!! $report_title !!}  </h2>
                @if(isset($from_date))
                <h3 style="margin-top: 5px;"> {{__('common.date')}}: {{$from_date}} to {{isset($to_date) && !empty($to_date) ? $to_date: \Carbon\Carbon::today()->format(config('app.date_format'))}}</h3>

                @endif
            </th>
        </tr>
    </table>
</div>
