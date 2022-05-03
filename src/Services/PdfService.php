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
    public function ShowPdfFile($html) {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("invoice.pdf", [
        'Attachement' => true
            ]);
    }
    // POUR CREER UN PDF ATTACHER A UN MAIL PAR EXEMPLE
    public function generateBinaryPdf($html){
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        // RETOURNE LE PDF EN STRING
        $this->domPdf->output();
    }

    public function generateHTMLAndFile($html) {
// Configure Dompdf according to your needs
        $pdfOptions = new Options(['isRemoteEnabled' => true]);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("ma-facture.pdf", [
            "Attachment" => false
        ]);
    }
}