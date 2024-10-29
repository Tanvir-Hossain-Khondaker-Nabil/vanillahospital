<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/20/2019
 * Time: 4:37 PM
 */

namespace App\Acme\pdfGenerator;

use Dompdf\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Knp\Snappy\Pdf as PDF;

abstract class GeneratePdf
{
    /**
     * @var string
     */
    public $size = 'a4';

    /**
     * @var string
     */
    public $orientation = 'portrait';

    /**
     * @var
     */
    public $html;

    /**
     * @var
     */
    public $filename;

    /**
     * @var
     */
    public $filepage;

    /**
     * @var
     */
    public $pdf;

    /**
     * @var bool
     */
    public $isForce = false;

    /**
     * Get file of generate
     */
    public function getOrGenerateFile()
    {
        if ($this->isForce) {
            $this->generatePdf();
        } else {
            if (!$this->hasFile()) {
                $this->generatePdf();
            }
        }

        return $this;
    }

    /**
     * Generate pdf
     */
    private function generatePdf()
    {
        try {
            $pdf = PDF::loadHTML($this->html)
                ->setPaper($this->size, $this->orientation)
                ->setWarnings(false)
                ->save(storage_path('app/public/'. $this->filename));
        } catch (Exception $e) {
            $pdf = null;

            Log::error('Error in pdf file generation');
        }

        $this->pdf = $pdf;
    }

    /**
     * Download pdf
     */
    public function downloadPdf()
    {
        return PDF::loadHTML($this->html)
            ->setPaper($this->size, $this->orientation)
            ->setWarnings(false)
            ->download($this->filename);
    }

    /**
     * @return mixed
     */
    abstract public function save();

    /**
     * Get pdf file
     *
     * @return mixed
     */
    public function getFile()
    {
        return $this->pdf;
    }

    /**
     * Get file full storage url
     *
     * @return string
     */
    public function getFilePath()
    {
        return storage_asset($this->filename);
    }

    /**
     * Set orientation of the paper
     *
     * @param string $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * Set html to be generated for pdf
     *
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * Set size of the paper
     *
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Set filename
     *
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Set filepage
     *
     * @param mixed $filepage
     */
    public function setFilepage($filepage)
    {
        $this->filepage = $filepage;
    }


    /**
     * Check if any file exists with given filename
     *
     * @return mixed
     */
    public function hasFile()
    {
        return Storage::disk('public')->exists($this->filename);
    }

    /**
     * Set isForce variable value
     *
     * @param bool $isForce
     */
    public function setIsForce($isForce)
    {
        $this->isForce = (bool) $isForce;
    }
}
