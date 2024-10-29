<?php

use Maatwebsite\Excel\Facades\Excel;


class DailySheetExport
{
    public function make($fileName, $data){

            $columns = array(
                '------- Credit -------',
                '------- Debit -------',
            );

        Excel::create($fileName, function ($excel) use ($data, $columns) {
                // Set the title
                $excel->setTitle('Consignments');

                // Chain the setters
                $excel->setCreator($data['report_title'])
                    ->setCompany($data['companies']->company_name);

                $excel->sheet('Sheet1', function ($sheet) use ($data, $columns) {
                    $sheet->row(1, $columns);

                    $sheet->cell('A1:AC1', function ($cell) {
                        $cell->setFont(array(
                            'size' => '11',
                            'bold' => true
                        ));
                    });


                    foreach ($data['credits'] as $value) {

                        $total = 0;
                        $transaction_details_id = $value->transaction_details_id;
                        $transactions_id = $value->transactions_id;
                        $account_name = $value->account_name;
                        //$sharer_name = $value->sharer_name;
                        $credit = $value->td_amount;

                        if($value->item_name != $last_item || $value->item_name == null)
                        {
                            $sale_print = 0;
                            $price_text = true;
                        }



                        $sheet->appendRow(array(
                                $value->transactions_id,
                                $value->invoice
                            )
                        );

                    }

                    $sheet->appendRow(array(
                        '',
                        'Total:',
                        $total,
                        '',
                        '',
                        '',
                    ));

                    $count = count($data['credits']) + 2;
                    $sheet->cell("A$count:Z$count", function ($cell) {
                        $cell->setFont(array(
                            'size' => '11',
                            'bold' => true
                        ));
                    });
                });


        })->download('xlsx');

    }
}
