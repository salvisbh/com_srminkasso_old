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
JLoader::register('CbUserHelper', JPATH_COMPONENT . '/helpers/cbuserhelper.php');
JLoader::register('FormatHelper', JPATH_COMPONENT . '/helpers/formathelper.php');
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');
JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');
JLoader::register('SrmInkassoTableUserfakturas', JPATH_COMPONENT . '/tables/userfakturas.php');

class UserFakturaHelper {

    public function createUserFaktura($billId, $userId, SrmInkassoTableBills $tblBill = null,SrmInkassoTablePositions $tplPositionen = null){

        //billItem laden, falls nur mit billid und userid aufgerufen
        if(is_null($tblBill)){
            $tblBill = SrmInkassoTableBills::getInstance()->load($billId);
        }

        if(is_null($tplPositionen)){
            $tplPositionen = SrmInkassoTablePositions::getInstance();
        }

        //Rechnungsreccord holen
        $tblUserFaktura = SrmInkassoTableUserfakturas::getInstance();
        $tblUserFaktura->createOrLoadUserFakturaForBill($userId,$billId);

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($tblBill->fk_template);

        //Variablen, welche in Methode ueberschrieben werden
        $total = 0;     //Gesamtbetrag
        $posHtml = '';  //HTML-Text der Positionszeilen

        //Positionen fuellen
        $this->fillPositionTemplate($billId, $userId, $tplPositionen, $pdfDoc, $total, $posHtml);

        //Body erstellen...
        $htmlContent = $this->getFakturyBody($userId,$tblBill, $pdfDoc, $tblUserFaktura, $posHtml, $total);

        //...und PDF-Seite erstellen
        $pdfDoc->addPage($htmlContent);

        //pdf schreiben
        $fileName = 'bill_' . $tblUserFaktura->fk_faktura . '_' . $tblUserFaktura->id . '.pdf';
        $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.$fileName;
        $pdfDoc->writePdf($fileNameWithPath,'F');

        //userfaktura aktualisieren
        $tblUserFaktura->totalbetrag=3.60;
        $tblUserFaktura->pdfname=$fileName;
        $tblUserFaktura->updateUserFakturaForBill($tblUserFaktura);

        return $fileNameWithPath;   //Voller Pfad fuer Merge zurueckgeben
    }

    /**
     * @param $billId
     * @param $userId
     * @param SrmInkassoTablePositions $tblPositionen
     * @param $pdfDoc
     * @param $total
     * @param $posHtml
     */
    private function fillPositionTemplate($billId, $userId, SrmInkassoTablePositions $tblPositionen, $pdfDoc, &$total, &$posHtml)
    {
        $positionTemplate = $pdfDoc->getPositonTemplate();

        //Positionen lesen
        $posList = $tblPositionen->getPositionsForUserBill($userId, $billId);

        foreach ($posList as $pos) {
            $posPdf['datum'] = FormatHelper::formatDate($pos->datum);
            $posPdf['position'] = $pos->titel; //todo beschreibung anhaengen

            if(! is_null($pos->kommentar) && strlen($pos->kommentar) > 0){
                $posPdf['kommentar'] = '<br>' . $pos->kommentar;
            }
            else{
                $posPdf['kommentar']='';
            }

            $posPdf['betrag'] = FormatHelper::formatWaehrung($pos->preis); //todo individueller Preis holen
            $total += $pos->preis;
            $posHtml .= $pdfDoc->replaceContentParameters($posPdf, $positionTemplate);
        }
    }

    /**
     * @param SrmInkassoTableBills $tblBills
     * @param $pdfDoc
     * @param $tblUserFaktura
     * @param $posHtml
     * @param $total
     * @return mixed
     */
    private function getFakturyBody($userId,SrmInkassoTableBills $tblBills, $pdfDoc, $tblUserFaktura, $posHtml, $total)
    {
        $bodyTemplate = $pdfDoc->getMainTemplate();

        //Rechnungsparameter setzen
        $op_Itval['rechnungsnummer'] = $tblUserFaktura->id;
        $op_Itval['rechnungsdatum'] = FormatHelper::formatDate($tblBills->datum);
        $op_Itval['titel'] = $tblBills->titel;
        $op_Itval['kopftext'] = $tblBills->kopftext;
        $op_Itval['fusstext'] = $tblBills->fusstext;
        $op_Itval['template_items'] = $posHtml;
        $op_Itval['totalbetrag'] = FormatHelper::formatWaehrung($total,2);

        //Benutzer lesen
        $cbUserHelper = new CbUserHelper();
        $cbUser = $cbUserHelper->getCbUser($userId);

        $op_Itval['name'] = $cbUser->nachname;
        $op_Itval['vorname'] = $cbUser->vorname;
        $op_Itval['strasse'] = $cbUser->strasse;
        $op_Itval['plz'] = $cbUser->plz;
        $op_Itval['ort'] = $cbUser->ort;

        $htmlContent = $pdfDoc->replaceContentParameters($op_Itval, $bodyTemplate);
        return $htmlContent;
    }
} 