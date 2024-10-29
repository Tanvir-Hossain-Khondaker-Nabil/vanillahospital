<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 05-Dec-19
 * Time: 6:28 PM
 */
?>

@include('member.reports.print_head')

<style>
    table.border{
        border:1px solid black;
    }
    .border_bottom{
        border-bottom:1px solid black;
    }
    .border_left{
        border-left:1px solid black;
    }
    .border_right{
        border-right:1px solid black;
    }
    .border_top{
        border-top:1px solid black;
    }
    .padding{
        padding:20px;
    }
    .margin{
        margin:20px;
    }
</style>
<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <div class="box" style="overflow: hidden;">
            <div class="box-header with-border">

                {{--<h3 class="box-title" style="margin: 10px 0;"> Pay Slip - {{ ucfirst($month) . ' ' . $year }} </h3>--}}

                <table class="border" border="0" cellspacing="0" cellpadding="3" align="center">
                    <tr >
                        <td align="left" style="font-size:12px;">Name</td>
                        <td style="font-size:12px;" align="left" class="margin">{{ $emp_salary->emp_name }}</td>
                    </tr>
                    <tr >
                        <td align="left" style="font-size:12px;">Emp ID.</td>
                        <td style="font-size:12px;" align="left" class="margin">{{ $emp_salary->employee->employeeID }}</td>
                    </tr>
                    <tr >
                                <td align="left" style="font-size:12px;">Designation</td>
                        <td style="font-size:12px;" align="left" class="margin">{{ $emp_salary->emp_designation }}</td>
                    </tr>
                    <tr >
                        <td  ></td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="border_top border_right" align="center" style="font-size:12px;"><strong>Earnings</strong></td>
                        <td class="border_top border_bottom" align="center" style="font-size:12px;"><strong>Days</strong></td>
                    </tr>
                    <tr >
                        <td class="border_top border_right">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;"><strong>Gross Salary </strong></td>
                                    <td align="right"  style="font-size:12px;"><strong>{{ create_money_format($emp_salary->base_salary )}}</strong></td>
                                </tr>
                            </table>
                        </td>
                        <td class="border_top border_right">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Present</td>
                                    <td align="right"  style="font-size:12px;"> {{ $emp_salary->total_present }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr >
                        <td style="font-size:12px;" align="right" class="">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">E/D (OT) Amount</td>
                                    <td align="right"  style="font-size:12px;">  {{ create_money_format($emp_salary->OT_amount) }} </td>
                                </tr>
                            </table>

                        </td>
                        <td class=" border_left">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">E/D (OT) </td>
                                    <td align="right"  style="font-size:12px;"> {{ create_money_format($emp_salary->extra_work) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr >
                        <td style="font-size:12px;" align="right" class="" >
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Bonus</td>
                                    <td align="right"  style="font-size:12px;">{{ create_money_format($emp_salary->festival_bonus) }}</td>
                                </tr>
                            </table>
                        </td>
                        <td class=" border_left">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Absent </td>
                                    <td align="right"  style="font-size:12px;">{{ ($emp_salary->total_absent) }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr >
                        <td style="font-size:12px;" align="right" class="" >
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Daily Salary</td>
                                    <td align="right"  style="font-size:12px;">{{ create_money_format($emp_salary->daily_salary) }}</td>
                                </tr>
                            </table>
                        </td>
                        <td class=" border_left">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Penalty (Day) </td>
                                    <td align="right"  style="font-size:12px;"> {{ ($emp_salary->p_day) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr >
                        <td style="font-size:12px;" align="right" class="" >
                            <!--
                            <table>
                                     <tr>
                                         <td width="8px"></td>
                                         <td align="left" width="60%" style="font-size:12px;">Daily </td>
                                         <td align="right"  style="font-size:12px;">'.number_format($data['daily_salary'],2).'</td>
                                     </tr>
                                 </table>
                                 -->
                        </td>
                        <td class=" border_left">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;"><strong>Total (W/D)</strong> </td>
                                    <td align="right"  style="font-size:12px;"> <strong>{{ ($emp_salary->total_work_day) }} </strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <tr >
                        <td class=" border_right">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;"></td>
                                    <td align="center"  style="font-size:12px;"></td>
                                </tr>
                            </table>
                        </td>
                        <td style="font-size:12px;" align="left" class="margin"></td>
                    </tr>
                    <tr >
                        <td class=" border_right">

                        </td>
                        <td class="border_top border_bottom" align="center" style="font-size:12px;"><strong>Deduction</strong></td>
                    </tr>
                    <tr >
                        <td class=" border_right">

                        </td>
                        <td class="">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Advance Salary</td>
                                    <td align="right"  style="font-size:12px;">{{ create_money_format($emp_salary->advance_payment) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr >
                        <td class=" border_right">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;"></td>
                                    <td align="center"  style="font-size:12px;"></td>
                                </tr>
                            </table>
                        </td>
                        <td class=" border_right">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Absent Deduction</td>
                                    <td align="right"  style="font-size:12px;">{{ create_money_format($emp_salary->Absent_deduction) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr >
                        <td class=" border_right">
                            <!--
                                <table>
                                    <tr>
                                        <td width="8px"></td>
                                        <td align="left" width="60%" style="font-size:12px;">Total Amount</td>
                                        <td align="right"  style="font-size:12px;"><strong>'.number_format($gross_salary,0).'</strong></td>
                                    </tr>
                                </table>
                                -->
                        </td>
                        <td class=" border_right">
                            <table>
                                <tr>
                                    <td width="8px"></td>
                                    <td align="left" width="60%" style="font-size:12px;">Penalty Deduction</td>
                                    <td align="right"  style="font-size:12px;">{{ create_money_format($emp_salary->penalty_deduction) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="border_top border_right" align="right" style="font-size:12px;"><strong>Net Payable </strong></td>
                        <td class="border_top" align="right" style="font-size:12px;"><strong> {{ create_money_format($emp_salary->net_payable) }}  (&euro;) </strong></td>
                    </tr>
                    <tr>
                        @php
                        $taxamount = 11;
                        $tax = $emp_salary->base_salary*$taxamount/100;
                        @endphp
                        <td class="border_top border_right" align="right" style="font-size:12px;"><strong>Tax ({{$taxamount}}%)</strong></td>
                        <td class="border_top" align="right" style="font-size:12px;"><strong> {{ create_money_format($tax) }}   (&euro;) </strong></td>
                    </tr>
                    <tr>
                        <td class="border_top border_right" align="right" style="font-size:12px;"><strong>Actual Receive</strong></td>
                        <td class="border_top" align="right" style="font-size:12px;"><strong> {{ create_money_format($emp_salary->net_payable-$tax) }}   (&euro;) </strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left" class="border_top" style="font-size:10px; text-transform: capitalize;">&nbsp; &nbsp;<strong>Amount in words: {{ AmountInWords($emp_salary->net_payable) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
