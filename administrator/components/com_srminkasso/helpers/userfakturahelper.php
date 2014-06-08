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
JLoader::register('SrmInkassoTableBillRuns', JPATH_COMPONENT . '/tables/billruns.php');
JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');

class UserFakturaHelper {

      /**
     * Erstellt zu einem Empfaenger und einem Fakturalauf eine Rechnung und gibt deren HTML-Text zurueck.
     * @param $tblBill die Tabelle mit den Rechnungsdaten, positioniert auf dem aktuellen Rechnungslauf.
     * @param $userId die UserId des Empfaengers.
     * @param SrmInkassoTablePositions $tblPositionen das Table-objekt mit den Daten der Rechnungspositionen, noch nicht geladen.
     * @param PdfDocument $pdfDoc das PdfDocument, welchem eine Seite angehaengt werden soll.
     * @return int, Rechnungsnummer, falls die Rechnung erfolgreich angehaengt werden konnte, sonst 0.
     */
    public function appendUserFaktura(SrmInkassoTableBillRuns $tblBill,$userId,SrmInkassoTablePositions $tblPositionen,PdfDocument $pdfDoc){

        //Rechnungsreccord holen
        $tblBills = SrmInkassoTableBills::getInstance();
        $tblBills->createOrLoadUserFakturaForBill($userId, $tblBill->id);

        //Variablen, welche in Methode ueberschrieben werden
        $total = 0;     //Gesamtbetrag
        $posHtml = '';  //HTML-Text der Positionszeilen

        //Positionen fuellen, $total und $posHtml werden per Referenz abgefuellt.
        $this->fillPositionTemplate($tblBill->id, $userId, $tblPositionen, $pdfDoc, $total, $posHtml);

        //Body erstellen...
        $htmlContent = $this->getFakturyBody($userId,$tblBill, $pdfDoc, $tblBills, $posHtml, $total);

        //...und PDF-Seite anhaengen
        $pdfDoc->addPage($htmlContent);

        //userfaktura aktualisieren
        $tblBills->status=SrmInkassoTableBills::$STATUS_OFFEN;
        $tblBills->updateUserFakturaForBill($tblBills);

        return $tblBills->id;        //todo Fehler pruefen und im Fehlerfall 0 zurueckgeben.
    }

    /**
     * Erstellt zu einem Empfaenger und einem Fakturalauf eine Rechnung, speichert diese ab und gibt den Pfad der erstellten PDF-Datei zurueck.
     * @param $billRunId die ID des Fakturierungslaufes
     * @param $userId die UserId des Empfaengers
     * @param SrmInkassoTableBillRuns $tblBillRuns das Table-Objekt mit den Daten zum Rechnungslauf. Falls null, wird instanziert und geladen.
     * @param SrmInkassoTablePositions $tblPositionen das Table-objekt mit den Daten der Rechnungspositionen, noch nicht geladen.
     * @return string der absolute Dateinamen mit Pfad der generierten PDF-Datei.
     */
    public function createUserFaktura($billRunId, $userId, SrmInkassoTableBillRuns $tblBillRuns = null,SrmInkassoTablePositions $tblPositionen = null){

        //billItem laden, falls nur mit billid und userid aufgerufen
        if(is_null($tblBillRuns)){
            $tblBillRuns = SrmInkassoTableBillRuns::getInstance();
            $tblBillRuns->load($billRunId);
        }

        if(is_null($tblPositionen)){
            $tblPositionen = SrmInkassoTablePositions::getInstance();
        }

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($tblBillRuns->fk_template);

        $userFaktId = $this->appendUserFaktura($tblBillRuns,$userId,$tblPositionen,$pdfDoc);


        //pdf schreiben
        $fileName = 'bill_' . $billRunId . '_' . $userFaktId . '.pdf';
        $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.$fileName;
        $pdfDoc->writePdf($fileNameWithPath,'F');

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
        $preisEff = 0;

        foreach ($posList as $pos) {
            $posPdf['datum'] = FormatHelper::formatDate($pos->datum);
            $posPdf['position'] = $pos->titel;

            if(! is_null($pos->kommentar) && strlen($pos->kommentar) > 0){
                $posPdf['kommentar'] = '<br>' . $pos->kommentar;
            }
            else{
                $posPdf['kommentar']='';
            }

            if($pos->individual_preis > 0){
                $preisEff=$pos->individual_preis;
            }else{
                $preisEff=$pos->preis;
            }

            $posPdf['betrag'] = FormatHelper::formatWaehrung($preisEff);
            $total += $preisEff;
            $posHtml .= $pdfDoc->replaceContentParameters($posPdf, $positionTemplate);
        }
    }

    /**
     * @param SrmInkassoTableBillRuns $tblBills
     * @param $pdfDoc
     * @param $tblUserFaktura
     * @param $posHtml
     * @param $total
     * @return mixed
     */
    private function getFakturyBody($userId,SrmInkassoTableBillRuns $tblBills, $pdfDoc, $tblUserFaktura, $posHtml, $total)
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