<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/20/2019
 * Time: 5:27 PM
 */

namespace App\Acme\pdfGenerator;


use Illuminate\Support\Facades\View;

class Transactions extends GeneratePdf
{
    /**
     * @var Transaction
     */
    private $transaction = [];

    /**
     * Transaction constructor.
     *
     * @param Transaction $transaction
     */
    public function __construct($data)
    {
        $this->transaction = $data;
        $this->filename = $this->setFilename($data['filename'].'.pdf');
        $this->filepage = $this->setFilepage($data['filepage']);
    }

    /**
     * @return $this
     */
    public function save()
    {
        $data = $this->transaction;
dd($this->filepage);
        $html = View::make($this->filepage, $data)->render();

        // Page setup
        $this->setFilename($this->filename);
        $this->setHtml($html);

        return $this->downloadPdf();
    }
};
