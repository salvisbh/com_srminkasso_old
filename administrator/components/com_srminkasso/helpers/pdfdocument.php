<?php
/**
 * Created by PhpStorm.
 * User: hps
 * Date: 08.02.14
 * Time: 12:42
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
//require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."assets".DS."tcpdf".DS.'tcpdf.php');
//require_once(JPATH_COMPONENT_ADMINISTRATOR . '/assets/tcpdf/config/tcpdf_config.php');
//Jloader::import('assets.tcpdf.tdpdf',JPATH_COMPONENT);
JLoader::register('TCPDF', JPATH_COMPONENT . '/assets/tcpdf/tcpdf.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_srminkasso'.DS.'tables');

class PdfDocument {

    private $pdf;
    private $templRow;

    public function __construct($templateId){
        $this->templRow =& Jtable::getInstance('templates','SrmInkassoTable');
        $this->templRow->load($templateId);

        //pdf Objekt erstellen und initialisieren
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false)   ;
        $this->pdf->SetCreator('SRM-Inkassosystem');
        $this->pdf->SetAuthor('http://www.srm-murten.ch');
        $this->pdf->SetTitle('Elektronische Rechnung SRM');
        $this->pdf->SetSubject('Leistungsverrechnung');
        $this->pdf->SetKeywords('Leistungen, Rechnung, SRM');

        //Raender einstellen
        $this->pdf->setMargins($this->templRow->rand_links,
            $this->templRow->rand_oben,
            $this->templRow->rand_rechts,
            $this->templRow->rand_unten);
    }

    public function getMainTemplate(){
        return $this->templRow->body;
    }

    public function getPositonTemplate(){
        return $this->templRow->position;
    }

    public function addPage($htmlContent){
        // add a page
        $this->pdf->AddPage('P','A4');
        $this->pdf->writeHTML($htmlContent, true, false, false, false, '');
        $this->pdf->lastPage();
    }

    public function writePdf($file_path, $destination){
        //Close and output PDF document
        $this->pdf->Output($file_path, $destination);
        return true;
    }

    public function sendPdfToBrowser($file_path){
        $cont=file_get_contents($file_path);
        $fsize = filesize($file_path);
        $mtype = "application/force-download";

        // Browser will try to save file with this filename, regardless original filename.
        // You can override it if needed.
        // set headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: $mtype");
        header("Content-Disposition: attachment; filename=\"myfile.pdf\"");
        echo $cont;
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fsize);

        // download
        @readfile($file_path);
        die();
    }

    public function replaceContentParameters($paramArray,$contentToReplace){

        foreach($paramArray as $op_vals=>$value)
        {
            if ($value) {
                $template = preg_replace('#{if:\!' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '', $template);
                $template = preg_replace('#{if:' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '\1', $template);
            } else {
                $template = preg_replace('#{if:\!' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '\1', $template);
                $template = preg_replace('#{if:' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '', $template);
            }

            $find = "{".$op_vals."}";
            $replace = $value;
            $contentToReplace = str_replace(trim($find),trim($replace),$contentToReplace);
        }

        return $contentToReplace;
    }
} 