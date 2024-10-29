<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/27/2020
 * Time: 10:51 AM
 */

/*
 *  Account System use for Accounts feature Control.
 *  If clients have this feature then True otherwise true.
 */

return [

    'feature' => true,

    'accounts' => [
        'area' => true,

        'bank' => true,

        'gl_account' => true,

        'reconciliation' => true,

        'fiscal_year' => true,

        'journal_entry' => true,

        'transaction' => true,

        'general_ledger' => true,

        'cheque' => true,

        'supplier' => true,

        'customer' => true,
    ],

    'report' => [
        'transaction' => true,
        'summary' => true
    ],

    'pos' => [
        'brand' => true
    ],


    'balance_adjustment' => true,

];
