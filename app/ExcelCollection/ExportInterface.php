<?php


namespace App\ExcelCollection;


interface ExportInterface
{
    public function make($fileName, $data);
}
