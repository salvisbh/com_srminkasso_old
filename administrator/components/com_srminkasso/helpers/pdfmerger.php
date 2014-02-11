<?php
/**
 * Created by PhpStorm.
 * User: hps
 * Date: 11.02.14
 * Time: 14:37
 */
require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_srminkasso'.DS."assets".DS."tcpdf".DS.'tcpdf.php');
require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_srminkasso'.DS."assets".DS."fpdi".DS.'fpdi.php');

class PdfMerger extends FPDI{

    function concat(array $files, $outputFileName) {
        foreach($files AS $file) {

            $pagecount = $this->setSourceFile($file);

            for ($page = 1; $page <= $pagecount; $page++) {

                $importedPage = $this->ImportPage($page);
                $size = $this->getTemplatesize($importedPage);

                $this->AddPage('P', array($size['w'], $size['h']));
                    $this->useTemplate($importedPage);
               }
          }

        $this->Output($outputFileName,'F');

    }

}