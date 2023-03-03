<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ConvertController extends Controller
{
    public function index()
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('template-word.docx');
        $name_doc = 'document.docx';

        $phpWord->setValues([
            'type' => 'meeting',
            'date' => '2023-03-02',
            'name' => 'Irfan Martien',
            'address' => 'Cakung',
            'company' => 'LJR',
            'location' => 'Jakarta',
        ]);

        $phpWord->saveAs($name_doc);

        $domPdfPath = base_path('vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        $Content = \PhpOffice\PhpWord\IOFactory::load(public_path($name_doc));
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content, 'PDF');

        $folder = '/documents/pdf';
        $path = public_path($folder);
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $namepdf = '/' . strtotime(now()) . '.pdf';
        $PDFWriter->save($path . $namepdf);
        File::delete(public_path('document.docx'));
        return 'link = ' . $folder . $namepdf;
    }
}
