<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 10/29/2022
 * Time: 12:34 PM
 */

namespace App\Services;


class CompanyFeature
{
    public function options()
    {
        $data = [
            'pos',
            'inventory',
            'accounts',
            'banks',
            'areas',
            'imports',
            'multi_company',
            'sale_profit_by_quotation',
//            'previous_year_profit_balance_sheet',
            'quotation',
            'requisition',
            'designation',
            'HR',
            'store',
            'employee',
            'project',
            'variant',
            'repair',
            'warehouse'
        ];

        return $data;
    }

    public function defaultOptions()
    {
        $data = [
//            'inventory',
//            'accounts',
//            'previous_year_profit_balance_sheet',
        ];

        return $data;
    }

}
