<?php
/**
 * SRM_Inkasso korrigiert
 *
 * Controller für die View MyThing (Formular)
 *
 * @package    SrmInkasso
* @subpackage Backend
* @author     Hp. Salvisberg
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

JLoader::register('PdfDocument', JPATH_COMPONENT . '/helpers/pdfdocument.php');
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');
JLoader::register('SrmInkassoTableBillRuns', JPATH_COMPONENT . '/tables/billruns.php');
JLoader::register('UserFakturaHelper', JPATH_COMPONENT . '/helpers/userfakturahelper.php');
JLoader::register('SrmInkassoTableUserfakturas', JPATH_COMPONENT . '/tables/userfakturas.php');

/**
 * Der Controller MyThingsController erbt alles von JController
 */
class SrmInkassoControllerBillRun extends JControllerForm
{
    /**
     * TODO braucht es diese ueberschriebene Methode ueberhaupt noch?
     * @param null $model
     * @return bool
     */
    public function batch($model = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Set the model
        $model	= $this->getModel('billrun', '', array());

        // Preset the redirect
        $this->setRedirect(JRoute::_('index.php?option=com_srminkasso&view=billruns' . $this->getRedirectToListAppend(), false));
        return parent::batch($this->billRunModel);
    }

    /**
     * Method to edit an existing record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key
     * (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if access level check and checkout passes, false otherwise.
     *
     * @since   11.1
     */
    public function exportPdf($key = null)
    {
        //Requestdaten holen
        $jinput = JFactory::getApplication()->input;
        $billRunId =  $jinput->getInt('id',0);

        //Tabelle fuer Positionszugriff
        $tblPositions = SrmInkassoTablePositions::getInstance();

        //user holen, um zu schauen, ob es ueberhaupt Rechnungen zu generieren gibt
        $billableUserIds = $tblPositions->getUserIdsForBill($billRunId);

        if(count($billableUserIds) == 0){
            $message = JText::sprintf('Für diesen Rechnungslauf bestehen keine Positionen.');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }

        //BillRunItem laden
        $tblBillRuns = SrmInkassoTableBillRuns::getInstance();
        $tblBillRuns->load($billRunId);

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($tblBillRuns->fk_template);
        $ufHelper = new UserFakturaHelper();

        //Fuer jeden Nutzer Rechnung erstellen
        foreach ( $billableUserIds as $userId ) {

            $fk_userid = $userId->fk_userid;
            $usrFaktId = $ufHelper->appendUserFaktura($tblBillRuns,$fk_userid,$tblPositions,$pdfDoc);
        }

        //PDF's mergen und an Browser senden
        $pdfNameWithPath = $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.'Bills_' . $billRunId .'.pdf';
        $pdfDoc->writePdf($pdfNameWithPath,'F');
        PdfDocument::sendPdfToBrowser($pdfNameWithPath);
    }

    public function exportSummary($key = null){

        //Requestdaten holen
        $jinput = JFactory::getApplication()->input;
        $billrunId =  $jinput->getInt('id',0);

        //Tabelle fuer Positionszugriff
        $tblPositions = SrmInkassoTablePositions::getInstance();

        //user holen, um zu schauen, ob es ueberhaupt Rechnungen zu generieren gibt
        $billableUserIds = $tblPositions->getUserIdsForBill($billrunId);

        if(count($billableUserIds) == 0){
            $message = JText::sprintf('Für diesen Rechnungslauf bestehen keine Positionen.');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }

        //BillRunItem laden, wird nur fuer Titel gebraucht
        $tblBillRuns = SrmInkassoTableBillRuns::getInstance();
        $tblBillRuns->load($billrunId);

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($tblBillRuns->fk_template);

        $tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2">
<thead>
 <tr style="background-color:#FFFF00;color:#0000FF;">
  <td width="40"><b>Nr</b></td>
  <td width="130"><b>Empfänger</b></td>
  <td width="180"> <b>Tel. / eMail</b></td>
  <td width="50"><b>Total</b></td>
  <td width="200"><b>Kontierung</b></td>
 </tr>
</thead>
EOD;

        //userbills holen
        $tblUserFakturas = SrmInkassoTableUserfakturas::getInstance();
        $userBills = $tblUserFakturas->getBillsWithEmpfaengerForBillRun($billrunId);

        foreach($userBills as $userBill){

            //Einzelzeile erstellen
            $zeile = '<tr>';

            $zeile = $zeile . "<td width=\"40\">" .$userBill->fakturaId .'</td>';
            $zeile = $zeile . "<td width=\"130\">" .$userBill->nachname .' ' . $userBill->vorname . '<br/>' .$userBill->ort . '</td>';
            $zeile = $zeile . "<td width=\"180\">" .$userBill->telefon . '<br/>' .$userBill->email .'</td>';
            $zeile = $zeile . "<td width=\"50\">" .$userBill->totalbetrag .'</td>';

            //Daten fuer Kontierung holen und anhaengen
            $lpSummary = $tblPositions->getLeistungsartenSummaryForUserBill($userBill->userId,$billrunId);
            $zeile = $zeile . "<td width=\"200\">";

            $i = 0;
            foreach($lpSummary as $kto){

                if( $i > 1){
                    $zeile = $zeile . '<br/>';
                }

                $zeile = $zeile . $kto->titel . ' (' . $kto->konto . '): ' . $kto->summeLeistungsart;
                $i++;
            }

            $zeile = $zeile . '</td>';
            $zeile = $zeile . '</tr>';

            //...und Zeile an HTML anhaengen
            $tbl = $tbl . $zeile;
        }

        $pdfDoc->addPage($tbl);

        $pdfNameWithPath = $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.'Zusammenfassung_' . $billrunId.'.pdf';
        $pdfDoc->writePdf($pdfNameWithPath,'F');
        PdfDocument::sendPdfToBrowser($pdfNameWithPath);

    }

}
