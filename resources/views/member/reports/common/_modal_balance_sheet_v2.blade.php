<div class="modal fade" id="inventories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog profit-loss-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($inventory_no) }} Inventories  <a
                        href="{{ route('member.report.head_inventory') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">

                    <tr>
                        <th colspan="3">Inventory</th>
                    </tr>

                    <tr>
                        <th>Product name </th>
                        <th class="text-center "> Quantity X Price</th>
                        <th class="text-right"> Total Price</th>
                    </tr>
                    @foreach($inventories as $key=>$value)

                        @if($value->pre_qty != 0)
                            <tr>
                                <td>{{ $value->product_name }}</td>
                                <td class="text-center">{{ $value->pre_qty }}{{ $value->unit }} X {{ create_money_format($value->pre_item_price) }}</td>
                                <td class="text-right">{{ create_money_format($value->pre_qty*$value->pre_item_price) }}</td>
                            </tr>
                        @endif

                    @endforeach
                    <tr>
                        <th colspan="2">Total Opening Stock</th>
                        <th class="dual-line text-right"> {{ create_money_format($pre_total_inventory) }} </th>
                    </tr>

                    <tr>
                        <td colspan="3" class="border-top-1" height="30px"></td>
                    </tr>

                    @foreach($inventories as $key=>$value)
                        @if($value->qty != 0)
                            <tr>
                                <td>{{ $value->product_name }}</td>
                                <td class="text-right padding-right-50">{{ $value->qty }}{{ $value->unit }} X {{ create_money_format($value->item_price) }}</td>
                                <td class="text-right">{{ create_money_format($value->qty*$value->item_price) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <th colspan="2">Total Closing Stock</th>
                        <th class="dual-line text-right"> {{ create_money_format($total_inventory) }} </th>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="fixed_assets" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($fixed_asset_no) }} Fixed Assets <a
                        href="{{ route('member.report.fixed_asset_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>

                    @foreach($fixed_assets as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Fixed Assets</th>
                        <th class="dual-line text-right">{{ ($pre_total_fixed < 0 ? "(" : '').create_money_format( $pre_total_fixed).($pre_total_fixed < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_fixed < 0 ? "(" : '').create_money_format( $total_fixed).($total_fixed < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="current_assets" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($current_asset_no) }} Others Current Assets <a
                        href="{{ route('member.report.current_asset_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Current Assets</th>
                    </tr>
                    @foreach($current_assets as $key=>$value)
                        <tr>
                            <td colspan="2"
                                class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Current Assets</th>
                        <th class="dual-line text-right">{{ ($pre_total_current_asset < 0 ? "(" : '').create_money_format( $pre_total_current_asset).($pre_total_current_asset < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_current_asset < 0 ? "(" : '').create_money_format( $total_current_asset).($total_current_asset < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cash_and_banks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($cash_bank_no) }} Cash And Banks <a
                        href="{{ route('member.report.cash_bank_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Cash & Bank</th>
                    </tr>
                    @foreach($cash_banks as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Cash & Bank</th>
                        <th class="dual-line text-right">{{ ($pre_total_cash_bank < 0 ? "(" : '').create_money_format( $pre_total_cash_bank).($pre_total_cash_bank < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_cash_bank < 0 ? "(" : '').create_money_format( $total_cash_bank).($total_cash_bank < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="trade_debtors" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($trade_debtors_no) }} Trade Debtors <a
                        href="{{ route('member.report.trade_debtor_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Trade Debtors</th>
                    </tr>
                    @foreach($trade_debtors as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Trade Debtor</th>
                        <th class="dual-line text-right">{{ ($pre_total_trade_debtor < 0 ? "(" : '').create_money_format( $pre_total_trade_debtor).($pre_total_trade_debtor < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_trade_debtor < 0 ? "(" : '').create_money_format( $total_trade_debtor).($total_trade_debtor < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="advance_prepayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($advance_prepayment_no) }} Advance Deposits & Prepayments
                    <a href="{{ route('member.report.advance_prepayment_report') }}" class="pull-right"
                       id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Advance Deposits & Prepayments</th>
                    </tr>
                    @foreach($advance_prepayments as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_advance_prepayment < 0 ? "(" : '').create_money_format( $pre_total_advance_prepayment).($pre_total_advance_prepayment < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_advance_prepayment < 0 ? "(" : '').create_money_format( $total_advance_prepayment).($total_advance_prepayment < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="fixed_deposits_receipt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($fixed_deposits_receipt_no) }} Fixed Deposits Receipts <a
                        href="{{ route('member.report.fixed_deposits_receipts_report') }}"
                        class="pull-right" id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Fixed Deposits Receipts</th>
                    </tr>
                    @foreach($fixed_deposits_receipts as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $pre_total_fixed_deposits_receipt).($pre_total_fixed_deposits_receipt < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $total_fixed_deposits_receipt).($total_fixed_deposits_receipt < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="due_companies" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($due_affiliated_company_no) }} Due from Affiliated
                    Company <a href="{{ route('member.report.due_companies_report') }}"
                               class="pull-right" id="btn-print"><i class="fa fa-print"></i> Print </a>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Due from Affiliated Company</th>
                    </tr>
                    @foreach($due_companies as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_due_affiliated_company < 0 ? "(" : '').create_money_format( $pre_total_due_affiliated_company).($pre_total_due_affiliated_company < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_due_affiliated_company < 0 ? "(" : '').create_money_format( $total_due_affiliated_company).($total_due_affiliated_company < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="account_payable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($account_payable_no) }} Account Payable <a
                        href="{{ route('member.report.account_payable_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Account Payable</th>
                    </tr>
                    @foreach($account_payables as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ create_money_format( $value['pre_balance'] ) }}</td>
                            <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Account Payable</th>
                        <th class="dual-line text-right">{{ create_money_format( $pre_total_account_payables) }}</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_account_payables) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="account_receivable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($account_receivable_no) }} Account Receivable <a
                        href="{{ route('member.report.account_receivable_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Account Receivable</th>
                    </tr>
                    @foreach($account_receivables as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ create_money_format( $value['pre_balance'] ) }}</td>
                            <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Account Receivable</th>
                        <th class="dual-line text-right">{{ create_money_format( $pre_total_account_receivables) }}</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_account_receivables) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sundry_creditors" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($sundry_creditor_no) }} Sundry Creditors <a
                        href="{{ route('member.report.sundry_creditor_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Sundry Creditors</th>
                    </tr>
                    @foreach($sundry_creditors as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">  {{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }} </td>
                            <td class="text-right">  {{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }} </td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ create_money_format( $pre_total_sundry_creditors ) }}</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_sundry_creditors ) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="overdraft_banks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($over_bank_no) }} Bank Overdraft <a
                        href="{{ route('member.report.bank_overdraft_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Bank Overdraft</th>
                    </tr>
                    @foreach($over_banks as $key=>$value)

                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ create_money_format( $value['pre_balance']*(-1) ) }}</td>
                            <td class="text-right">{{ create_money_format( $value['balance']*(-1) ) }}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total Bank Overdraft</th>
                        <th class="dual-line text-right">{{ create_money_format( $pre_total_over_bank) }}</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_over_bank) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="non_current_liabilities" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($non_current_liability_no) }} Non-Current Liabilities <a
                        href="{{ route('member.report.long_term_liability_report') }}"
                        class="pull-right" id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>
                    <tr>
                        <th colspan="4">Non-Current Liabilities</th>
                    </tr>
                    @foreach($non_current_liabilities as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Non-Current Liabilities</th>
                        <th class="dual-line text-right">{{ ($pre_total_non_current_liability < 0 ? "(" : '').create_money_format( $pre_total_non_current_liability).($pre_total_non_current_liability < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_non_current_liability < 0 ? "(" : '').create_money_format( $total_non_current_liability).($total_non_current_liability < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="current_liabilities" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($current_liability_no) }} Current Liabilities <a
                        href="{{ route('member.report.current_liability_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>

                    @foreach($current_liabilities as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total Current Liabilities</th>
                        <th class="dual-line text-right">{{ ($pre_total_current_liability < 0 ? "(" : '').create_money_format( $pre_total_current_liability).($pre_total_current_liability < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_current_liability < 0 ? "(" : '').create_money_format( $total_current_liability).($total_current_liability < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="liabilities_expense" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($liabilities_expenses_no) }} Liabilities Expenses <a
                        href="{{ route('member.report.liability_for_expense_report') }}"
                        class="pull-right" id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>

                    @foreach($liabilities_expenses as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_liabilities_expenses < 0 ? "(" : '').create_money_format( $pre_total_liabilities_expenses).($pre_total_liabilities_expenses < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_liabilities_expenses < 0 ? "(" : '').create_money_format( $total_liabilities_expenses).($total_liabilities_expenses < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="income_tax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($income_tax_no) }} Income Tax Payable <a
                        href="{{ route('member.report.income_tax_payable_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>

                    @foreach($income_tax_payables as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_income_tax_payable < 0 ? "(" : '').create_money_format( $pre_total_income_tax_payable).($pre_total_income_tax_payable < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_income_tax_payable < 0 ? "(" : '').create_money_format( $total_income_tax_payable).($total_income_tax_payable < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="due_affiliated_no" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($due_affiliated_no) }} Due to Affiliated Company <a
                        href="{{ route('member.report.due_to_affiliated_company_report') }}"
                        class="pull-right" id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>

                    @foreach($due_to_affiliated_companies as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_due_affiliated < 0 ? "(" : '').create_money_format( $pre_total_due_affiliated).($pre_total_due_affiliated < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_due_affiliated < 0 ? "(" : '').create_money_format( $total_due_affiliated).($total_due_affiliated < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="net_profit_loss" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($purchase_no) }} Purchases</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-striped" id="dataTable">
                    <thead>

                    <tr>
                        <th colspan="4" style="border: none !important; padding-bottom: 20px;" class="text-center">
                            <h3> Profit And Loss </h3>
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                        <td class="text-uppercase report-head-tag width-100 border-1 "> Notes</td>
                        <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Previous
                            Taka
                        </td>
                        <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">1. Sales(net)</th>
                        <th class="text-center width-100">{{ $sale_no }} </th>
                        <th class="text-right">{{ create_money_format( $pre_total_sales) }}</th>
                        <th class="text-right">{{ create_money_format( $total_sales) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase" colspan="4">2. Less: Cost of Goods Sold</th>
                    </tr>
                    <tr>
                        <td class="padding-left-40" colspan="2">A. Opening Stock</td>
                        <td class="text-right">{{ create_money_format($pre_openingStock) }}</td>
                        <td class="text-right">{{ create_money_format($openingStock) }}</td>
                    </tr>
                    <tr>
                        <td class="padding-left-40">B. Purchase</td>
                        <td class="text-center width-100">{{ $purchase_no }}</td>
                        <td class="text-right">{{ create_money_format($pre_total_purchases) }}</td>
                        <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                    </tr>
                    <tr>
                        <th class="padding-left-40 text-uppercase " colspan="2">Total(A+B)</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_openingStock+$pre_total_purchases) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases) }}</th>
                    </tr>
                    <tr>
                        <td class="padding-left-40" colspan="2">C. Closing Stock</td>
                        <td class="text-right">({{ create_money_format($pre_total_inventory) }})</td>
                        <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                    </tr>
                    <tr>
                        <th class="padding-left-40 text-uppercase " colspan="2">Total(A+B-C)</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_openingStock+$pre_total_purchases-$pre_total_inventory) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases-$total_inventory) }}</th>
                    </tr>

                    @foreach($cost_of_sold_items as $key => $value)
                        <tr>
                            <td class="padding-left-40" colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ create_money_format($value['pre_balance']) }}</td>
                            <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="padding-left-40 text-uppercase" colspan="2">Cost of Goods Sold</th>
                        <th class="single-line text-right">{{ create_money_format($pre_total_cost_of_sold) }}</th>
                        <th class="single-line text-right">{{ create_money_format($total_cost_of_sold) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase  border-top-1" colspan="2">3. Gross Profit (1-2)</th>
                        <th class=" border-top-1 text-right">{{ create_money_format($pre_total_sales-$pre_total_cost_of_sold) }}</th>
                        <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase" colspan="3">4. Less: Administrative and general Expenses</th>
                    </tr>
                    @foreach($expenses as $key => $value)
                        <tr>
                            <td colspan="2"
                                class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ create_money_format($value['pre_balance']) }}</td>
                            <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <th class="padding-left-40 text-uppercase " colspan="2">Total</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_total_expenses) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($total_expenses) }}</th>
                    </tr>

                    <tr>
                        <th class="text-uppercase" colspan="3">5. Income</th>
                    </tr>
                    @foreach($incomes as $key => $value)
                        <tr>
                            <td colspan="2"
                                class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ create_money_format($value['pre_balance']) }}</td>
                            <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <th class="padding-left-40 text-uppercase " colspan="2">Total</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_total_incomes) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($total_incomes) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase  border-top-1" colspan="2"> Net {{ $net_profit<0? "Loss":"Profit" }}
                            (3-4+5)
                        </th>
                        <th class=" border-top-1 text-right">{{ $pre_net_profit<0 ? "(".create_money_format((-1)*$pre_net_profit).")" : create_money_format($pre_net_profit ) }}</th>
                        <th class=" border-top-1 text-right">{{  $net_profit<0 ? "(".create_money_format((-1)*$net_profit).")" : create_money_format($net_profit ) }}</th>
                    </tr>

                    </tbody>
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="equity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ create_money_format($equity_no) }} Equity <a
                        href="{{ route('member.report.equity_report') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <table class="table">
                    <tr>
                        <th colspan="2">Particulars</th>
                        <th class="text-right">Previous Taka</th>
                        <th class="text-right">Taka</th>
                    </tr>

                    @foreach($equities as $key=>$value)
                        <tr>
                            <td colspan="2">{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ ($value['pre_balance'] < 0 ? "(" : '').create_money_format( $value['pre_balance'] ).($value['pre_balance'] < 0 ? ")":'') }}</td>
                            <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ ($pre_total_equity < 0 ? "(" : '').create_money_format( $pre_total_equity).($pre_total_equity < 0 ? ")" : '') }}</th>
                        <th class="dual-line text-right">{{ ($total_equity < 0 ? "(" : '').create_money_format( $total_equity).($total_equity < 0 ? ")" : '') }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
