<?php


namespace App\ExcelCollection;


use Maatwebsite\Excel\Facades\Excel;

class LedgerBookExport implements ExportInterface
{
    public function make($fileName, $data)
    {
        $columns = array(
            'Date',
            'Transaction Code',
            'Particulars',
            'Remarks',
            'DR. TK.',
            'CR. TK.',
            'Balance',
        );



        return Excel::create($fileName, function ($excel) use ($data, $columns) {
            // Set the title
            $excel->setTitle('Ledger Book');

            // Chain the setters
            $excel->setCreator($data['company_name'])
                ->setCompany($data['company_name']);

            $excel->sheet('Sheet1', function ($sheet) use ($data, $columns) {

                $total_dr = 0.00;
                $total_cr = 0.00;
                $balance = 0.00;

                $sheet->row(1, $columns);

                $sheet->cell('A1:G1', function ($cell) {
                    $cell->setFont(array(
                        'size' => '11',
                        'bold' => true
                    ));
                });


                if(isset($data['bf_balance']) && $data['bf_balance'] !=0 )
                {
                    $total_dr += $data['bf_balance']>0 ? $data['bf_balance'] : 0;
                    $total_cr += $data['bf_balance']<0 ? $data['bf_balance']*(-1) : 0;
                    $balance = $data['bf_balance'];


                    $sheet->appendRow(array(
                        db_date_month_year_format($data['bf_date']),
                        '',
                        'Balance B/F',
                        'B/F',
                        $data['bf_balance']>0 ? create_money_format($data['bf_balance']) : "-",
                        $data['bf_balance']<0 ? create_money_format($data['bf_balance']*(-1)) : "-",
                        create_money_format($balance)
                    ));
                }


                foreach ($data['modal'] as  $key2 => $value2) {

                    if($value2->transaction_type=='dr'){
                        $total_dr += $value2->td_amount;
                    }else{
                        $total_cr += $value2->td_amount;
                    }
                    $balance = $value2->transaction_type=='dr' ? $balance+$value2->td_amount : $balance-$value2->td_amount;

                    $sheet->appendRow(array(
                        db_date_month_year_format($value2->date),
                        $value2->transaction_code,
                        $value2->against_account_name,
                        $value2->remarks,
                        $value2->transaction_type=="dr" ? create_money_format($value2->td_amount) : "-",
                        $value2->transaction_type=="cr" ? create_money_format($value2->td_amount) : "-",
                        create_money_format($balance)
                    ));


                }

                $sheet->appendRow(array(
                    '',
                    '',
                    '',
                    'Total:',
                    create_money_format($total_dr),
                    create_money_format($total_cr),
                    create_money_format($balance),
                ));

                $count = count($data['modal']) + 3;
                $sheet->cell("A$count:G$count", function ($cell) {
                    $cell->setFont(array(
                        'size' => '11',
                        'bold' => true
                    ));
                });
            });


        })->download('xlsx');
    }
}
