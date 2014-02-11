<?php
/**
 * Created by PhpStorm.
 * User: hps
 * Date: 11.02.14
 * Time: 14:13
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('PdfDocument', JPATH_COMPONENT . '/helpers/pdfdocument.php');
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');
JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');
JLoader::register('SrmInkassoTableUserfakturas', JPATH_COMPONENT . '/tables/userfakturas.php');

class UserFakturaHelper {

    public function createUserFaktura($billId, $userId, SrmInkassoTableBills $billITable = null,SrmInkassoTablePositions $posTable = null){

        //billItem laden, falls nur mit billid und userid aufgerufen
        if(is_null($billITable)){
            $billITable = SrmInkassoTableBills::getInstance()->load($billId);
        }

        if(is_null($posTable)){
            $posTable = SrmInkassoTablePositions::getInstance();
        }

        //Rechnungsreccord holen
        $ufTable = SrmInkassoTableUserfakturas::getInstance();
        $ufTable->createOrLoadUserFakturaForBill($userId,$billId);

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($billITable->fk_template);

        //pdf Elemente holen
        $bodyTemplate = $pdfDoc->getMainTemplate();
        $positionTemplate = $pdfDoc->getPositonTemplate();

        //Positionen lesen
        $posList = $posTable->getPositionsForUserBill($userId,$billId);

        $total = 0;
        $posHtml = "";

        foreach($posList as $pos){
            $posPdf['datum']=$pos->datum;
            $posPdf['position']=$pos->titel;   //todo beschreibung anhaengen
            $posPdf['betrag']=$pos->preis;     //todo individueller Preis holen
            $total += $pos->preis;
            $posHtml .= $pdfDoc->replaceContentParameters($posPdf,$positionTemplate);
        }
        //Rechnungsparameter setzen
        $op_Itval['rechnungsnummer']=$ufTable->id;
        $op_Itval['titel']=$billITable->titel;
        $op_Itval['kopftext']=$billITable->kopftext;
        $op_Itval['fusstext']=$billITable->fusstext;
        $op_Itval['template_items']=$posHtml;
        $op_Itval['totalbetrag']=$total;

        $htmlContent = $pdfDoc->replaceContentParameters($op_Itval,$bodyTemplate);
        $pdfDoc->addPage($htmlContent);

        //pdf schreiben
        $fileName = 'bill_' . $ufTable->fk_faktura . '_' . $ufTable->id . '.pdf';
        $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.$fileName;
        $pdfDoc->writePdf($fileNameWithPath,'F');

        //userfaktura aktualisieren und zurueckgeben
        $ufTable->totalbetrag=3.60;

        //userfaktura aktualisieren
        $ufTable->updateUserFakturaForBill($ufTable);

//        return $fileName;
        return $fileNameWithPath;
    }
} 