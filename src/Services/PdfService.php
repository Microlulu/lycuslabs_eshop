<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private Dompdf $domPdf;

    public function __construct(){
        $pdfOptions = new Options();
        $pdfOptions->setDefaultPaperSize('A4');
        $pdfOptions->setDefaultPaperOrientation('portrait');
        $pdfOptions->setDefaultFont('Inter');
        $titleInvoice = 'invoice.pdf';
        $titleLicense = 'software_infos.pdf';
        $this->domPdf = new Dompdf($pdfOptions);

    }
    // POUR MONTRER LE PDF DE LA FACTURE DANS LE NAVIGATEUR et le TELECHARGER
    public function ShowPdfFile($html){
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("invoice.pdf", [
        'Attachement' => false
            ]);
    }
    // POUR CREER UN PDF ATTACHER A UN MAIL PAR EXEMPLE
    public function generateBinaryPdf($html){
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        // RETOURNE LE PDF EN STRING
        $this->domPdf->output();
    }
}