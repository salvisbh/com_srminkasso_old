<?php
/**
 * Created by PhpStorm.
 * User: hps
 * Date: 08.02.14
 * Time: 12:42
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('TCPDF', JPATH_COMPONENT . '/assets/tcpdf/tcpdf.php');
JLoader::register('PdfMerger', JPATH_COMPONENT . '/helpers/pdfmerger.php');
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

    /**
     * Schreibt das PDF an die Zieldestination.
     * @param $file_path der Zielname
     * @param $destination der Ausgabekanal, default = 'F'
     * @return bool
     */
    public function writePdf($file_path, $destination='F'){
        //Close and output PDF document
        $this->pdf->Output($file_path, $destination);
        return true;
    }

    /**
     * Fuegt mehrere PDF's zu einer Datei zusammen und sendet diese zurueck zum Browser.
     * @param array $PdfsWithPath
     * @param $targetFile
     */
    public static function sendMultiplePdfToBrowser(array $PdfsWithPath,$targetFile){
        $merger = new PdfMerger();
        $merger->concat($PdfsWithPath,$targetFile);
        PdfDocument::sendPdfToBrowser($targetFile);
    }

    /**
     * Sendet eine einzelne Datei vom Server  zurueck zum Browser.
     * @param $file_path
     */
    public static function sendPdfToBrowser($file_path){
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
        header("Content-Disposition: attachment; filename=\"pdfFromServer.pdf\"");
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
                $contentToReplace = preg_replace('#{if:\!' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '', $contentToReplace);
                $contentToReplace = preg_replace('#{if:' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '\1', $contentToReplace);
            } else {
                $contentToReplace = preg_replace('#{if:\!' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '\1', $contentToReplace);
                $contentToReplace = preg_replace('#{if:' . preg_quote($op_vals, '#') . '}(.*?){/if}#s', '', $contentToReplace);
            }

            $find = "{".$op_vals."}";
            $replace = $value;
            $contentToReplace = str_replace(trim($find),trim($replace),$contentToReplace);
        }

        return $contentToReplace;
    }
} 