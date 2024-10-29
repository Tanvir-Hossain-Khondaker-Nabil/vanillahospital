<?php


namespace App\ExcelCollection;


use Maatwebsite\Excel\Facades\Excel;

class SharerBalanceByManagerExcel implements ExportInterface
{

    public function make($fileName, $data)
    {
        $columns = array(
            'SL#',
            'Particulars',
            'Phone',
            'Address',
            'Opening Balance',
            'Debit',
            'Credit',
            'Closing Balance',
        );


        return Excel::create($fileName, function ($excel) use ($data, $columns) {
            // Set the title
            $excel->setTitle('Party Balance Report by Manager');

            // Chain the setters
            $excel->setCreator($data['company_name'])
                ->setCompany($data['company_name']);

            $excel->sheet('Sheet1', function ($sheet) use ($data, $columns) {

                $sheet->appendRow(array(
                    $data['report_title']." ".$data['report_date']
                ));
                $sheet->mergeCells('A1:H1');
                $sheet->cell('A1:H1', function ($cell) {
                    $cell->setFont(array(
                        'size' => '13',
                        'bold' => true
                    ));
                });

                $total_dr = 0;
                $total_cr = 0;
                $total_opening =0;

                $sheet->row(2, $columns);

                $sheet->cell('A2:H2', function ($cell) {
                    $cell->setFont(array(
                        'size' => '11',
                        'bold' => true
                    ));
                });


                foreach ($data['modal'] as  $key => $value) {

                    if($value->opening_balance > 0 || $value->total_dr > 0 || $value->total_cr  > 0 || $value->closing_balance){
                        $sheet->appendRow(array(
                            $key+1,
                            $value->name,
                            $value->phone,
                            $value->address,
                            $value->opening_balance ? ($value->opening_balance > 0 ? create_money_format($value->opening_balance) . " Dr" : ($value->opening_balance < 0 ? create_money_format($value->opening_balance * (-1)) . " Cr" : "")) : "",
                            $value->total_dr > 0 ? create_money_format($value->total_dr) . " Dr" : "",
                            $value->total_cr  > 0 ? create_money_format($value->total_cr) . " Cr" : "",
                            $value->closing_balance
                        ));

                    }


                    $total_dr += $value->total_dr;
                    $total_cr += $value->total_cr;
                    $total_opening += $value->opening_balance;

                }

                $sheet->appendRow(array(
                    '',
                    '',
                    '',
                    'Total:',
                    create_money_format($total_opening).($total_opening  > 0 ? " Dr" : "Cr"),
                    $total_dr  > 0 ? create_money_format($total_dr) . " Dr" : "",
                    $total_cr > 0 ? create_money_format($total_cr) . " Cr" : "",
                    $data['total_closing_balance']<0 ? create_money_format($data['total_closing_balance']*(-1))." Cr" : create_money_format($data['total_closing_balance'])." Dr",
                ));

                $count = count($data['modal']) + 3;
                $sheet->cell("A$count:H$count", function ($cell) {
                    $cell->setFont(array(
                        'size' => '11',
                        'bold' => true
                    ));
                });
            });


        })->download('xlsx');
    }
}
