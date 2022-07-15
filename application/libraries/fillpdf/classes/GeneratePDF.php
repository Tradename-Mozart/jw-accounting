<?php
namespace Classes;

if(!defined('ACCESSCHECK')) {
      die('Direct access not permitted');
}


use mikehaertl\pdftk\Pdf;

class GeneratePDF {


           public function generate($data)
           {      

                  try {

                        $filename = 'pdf_' . rand(2000,1200000) . '.pdf';

                        $pdf = new Pdf('./test.pdf');
                        $pdf->fillForm($data)
                        ->flatten()
                        ->saveAs( './completed/' . $filename);
                        //->send( $filename . '.pdf');

                        return $filename;
   
                  }
                  catch(Exception $e)
                  {
                        return $e->getMessage();
                  }
      

           }

           public function customGenerate($data, $pdfTemplate, $savePath, $filename=NULL)
           {      

                  try {
                        if($filename == NULL)
                        {
                              $filename = 'pdf_' . rand(2000,1200000) . '.pdf';
                        }                        

                        $pdf = new Pdf($pdfTemplate);
                        $pdf->fillForm($data)
                        ->flatten()
                        ->saveAs( $savePath . $filename);
                        //->send( $filename . '.pdf');

                        return $filename;
   
                  }
                  catch(Exception $e)
                  {
                        return $e->getMessage();
                  }
      

           }
}