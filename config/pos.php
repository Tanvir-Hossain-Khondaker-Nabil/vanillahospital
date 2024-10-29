<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/27/2020
 * Time: 10:51 AM
 */



return [

    /*
   |--------------------------------------------------------------------------
   | POS Setup
   |--------------------------------------------------------------------------
   |  This setup use for clients system control. If clients don't have this feature then it
   |  will control by this Config file.
   |
   *  If clients have this feature then True otherwise False.
   |
   */

    'feature' => true,

    'pos' => [
        'sale' => true,
        'purchase' => true,
        'unit' => true,
        'product' => true,
        'category' => true,
        'import' => true,
        'supplier' => true,
        'customer' => true,
    ],

    'sales'=>[
        'whole' => true,
        'pos' => true,
        'create' => true,
        'list' => true,
        'due' => true,
        'return' => true
    ],

    'purchases'=>[
        'create' => true,
        'list' => true,
        'due' => true,
        'return' => true
    ],

    'requisition'=>[
        'sales' => true,
        'purchases' => true,
        'create' => true,
        'list' => true,
        'purchase_form' => true,
        'user_based' => true,
    ],

    'report' => [
        'sale' => true,
        'purchase' => true,
        'stock' => true,
    ],

    'check_stock_update' => true,

    'purchase_others_cost_manage_ledger' => true


];
