<div class="modal fade" id="fixed_assets" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($fixed_asset_no) }} Fixed
                    Assets</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">
                    @php
                        $data['accounts'] = $fixed_assets;
                    @endphp
                    @include('member.reports.common._modal_component', $data)
                    <tr>
                        <th>Total Fixed Assets</th>

                        <th class="dual-line text-right" colspan="2">{{ create_money_format_with_dr_cr($pre_total_fixed)  }}</th>
                        <th class="text-right">{{ create_money_format($fixed_total_dr) }} </th>
                        <th class="text-right">{{ create_money_format($fixed_total_cr) }} </th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_fixed) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($current_asset_no) }} Others
                    Current Assets</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $current_assets;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total Current Assets</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_current_asset) }}</th>
                        <th class=" text-right">{{ create_money_format( $current_assets_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $current_assets_total_cr) }}</th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_current_asset) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($cash_bank_no) }} Cash And
                    Banks</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $cash_banks;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total Cash & Bank</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_cash_bank) }}</th>
                        <th class=" text-right">{{ create_money_format( $cash_banks_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $cash_banks_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_cash_bank)}}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($trade_debtors_no) }} Trade
                    Debtors</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $trade_debtors;
                    @endphp
                    @include('member.reports.common._modal_component', $data)


                    <tr>
                        <th>Total Trade Debtor</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_trade_debtor) }}</th>
                        <th class=" text-right">{{ create_money_format( $trade_debtors_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $trade_debtors_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_trade_debtor)}}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($advance_prepayment_no) }}
                    Advance Deposits & Prepayments</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $advance_prepayments;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_advance_prepayment) }}</th>
                        <th class=" text-right">{{ create_money_format( $advance_prepayments_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $advance_prepayments_total_cr) }}</th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_advance_prepayment) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($fixed_deposits_receipt_no) }}
                    Fixed Deposits Receipts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $fixed_deposits_receipts;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_fixed_deposits_receipt) }}</th>
                        <th class=" text-right">{{ create_money_format( $fixed_deposits_receipts_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $fixed_deposits_receipts_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_fixed_deposits_receipt) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($due_affiliated_company_no) }}
                    Due from Affiliated Company </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $due_companies;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_due_affiliated_company) }}</th>
                        <th class=" text-right">{{ create_money_format( $due_companies_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $due_companies_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_due_affiliated_company) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($account_payable_no) }}
                    Account Payable</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $account_payables;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total Account Payable</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_account_payables*(-1)) }}</th>

                        <th class=" text-right">{{ create_money_format( $account_payables_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $account_payables_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_account_payables*(-1)) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($account_receivable_no) }}
                    Account Receivable</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $account_receivables;
                    @endphp
                    @include('member.reports.common._modal_component', $data)
                    <tr>
                        <th>Total Account Receivable</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_account_receivables) }}</th>
                        <th class=" text-right">{{ create_money_format( $account_receivables_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $account_receivables_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_account_receivables) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($sundry_creditor_no) }} Sundry
                    Creditors</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $sundry_creditors;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_sundry_creditors*(-1) ) }}</th>
                        <th class=" text-right">{{ create_money_format( $sundry_creditors_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $sundry_creditors_total_cr) }}</th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_sundry_creditors*(-1) ) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($over_bank_no) }} Bank
                    Overdraft</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $over_banks;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total Bank Overdraft</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_over_bank*(-1)) }}</th>
                        <th class=" text-right">{{ create_money_format( $over_banks_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $over_banks_total_cr) }}</th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_over_bank*(-1)) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($non_current_liability_no) }}
                    Non-Current Liabilities</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $non_current_liabilities;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total Non-Current Liabilities</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_non_current_liability*(-1)) }}</th>
                        <th class=" text-right">{{ create_money_format( $non_current_liabilities_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $non_current_liabilities_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_non_current_liability*(-1)) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($current_liability_no) }}
                    Current Liabilities</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $current_liabilities;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total Current Liabilities</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_current_liability*(-1))}}</th>
                        <th class=" text-right">{{ create_money_format( $current_liabilities_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $current_liabilities_total_cr) }}</th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_current_liability*(-1)) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($liabilities_expenses_no) }}
                    Liabilities Expenses </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $liabilities_expenses;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_liabilities_expenses*(-1)) }}</th>
                        <th class=" text-right" >{{ create_money_format( $liabilities_expenses_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $liabilities_expenses_total_cr) }}</th>
                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_liabilities_expenses*(-1)) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($income_tax_no) }} Income Tax
                    Payable </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $income_tax_payables;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_income_tax_payable*(-1)) }}</th>

                        <th class=" text-right">{{ create_money_format( $income_tax_payables_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $income_tax_payables_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_income_tax_payable*(-1)) }}</th>
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
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($due_affiliated_no) }} Due to
                    Affiliated Company </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $due_to_affiliated_companies;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_due_affiliated*(-1)) }}</th>

                        <th class=" text-right">{{ create_money_format( $due_to_affiliated_companies_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $due_to_affiliated_companies_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_due_affiliated*(-1)) }}</th>
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
<div class="modal fade" id="expenses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($expense_no) }} Expenses </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $expenses;
                        $data['income_expense'] = true;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th colspan="2"></th>
{{--                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($pre_total_expenses) }}</th>--}}

                        <th class=" text-right">{{ create_money_format( $expense_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $expense_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_expenses) }}</th>
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
<div class="modal fade" id="incomes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($income_no) }} Incomes </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $incomes;
                        $data['income_expense'] = true;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th colspan="2"></th>
{{--                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($pre_total_incomes) }}</th>--}}

                        <th class=" text-right">{{ create_money_format( $income_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $income_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_incomes*(-1)) }}</th>
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
<div class="modal fade" id="equity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog balance-sheet-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($equity_no) }} Equity </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">

                    @php
                        $data['accounts'] = $equities;
                    @endphp
                    @include('member.reports.common._modal_component', $data)

                    <tr>
                        <th>Total</th>
                        <th class="dual-line text-right"  colspan="2">{{ create_money_format_with_dr_cr($pre_total_equity) }}</th>

                        <th class=" text-right">{{ create_money_format( $equities_total_dr) }}</th>
                        <th class=" text-right">{{ create_money_format( $equities_total_cr) }}</th>

                        <th class="dual-line text-right">{{ create_money_format_with_dr_cr($total_equity) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
