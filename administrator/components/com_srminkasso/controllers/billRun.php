<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
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

        //BillRunItem laden
        $tblBillRuns = SrmInkassoTableBillRuns::getInstance();
        $tblBillRuns->load($billrunId);

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($tblBillRuns->fk_template);

        $tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2">
<thead>
 <tr style="background-color:#FFFF00;color:#0000FF;">
  <td width="20" align="center"><b>Nr</b></td>
  <td width="70" align="center"><b>Name</b></td>
  <td width="70" align="center"><b>Vorname</b></td>
  <td width="80" align="center"> <b>Ort</b></td>
  <td width="100" align="center"> <b>Telefon</b></td>
  <td width="140" align="center"> <b>email</b></td>
  <td width="20" align="center"><b>Total</b></td>
 </tr>
</thead>
EOD;

        $db = JFactory::getDbo();

        //Fuer jeden Nutzer Rechnung erstellen
        foreach ( $billableUserIds as $userId ) {

            $fk_userid = $userId->fk_userid;
            $userSummary = $this->getUserSummaryForBillRun($tblBillRuns,$fk_userid,$db);

            $tbl = $tbl . $userSummary;
        }

        $pdfDoc->addPage($tbl);

        $pdfNameWithPath = $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.'Zusammenfassung_' . $billrunId.'.pdf';
        $pdfDoc->writePdf($pdfNameWithPath,'F');
        PdfDocument::sendPdfToBrowser($pdfNameWithPath);

    }

    /**
     * @param SrmInkassoTableBillRuns $tblBillRuns
     * @param $fk_userid
     * @param JDatabase $db
     * @return string
     */
    private function getUserSummaryForBillRun(SrmInkassoTableBillRuns $tblBillRuns,$fk_userid,JDatabase $db){

        //Rechnungsreccord holen fuer Zugriff auf die Rechnungsnummer
        $tblUserFaktura = SrmInkassoTableUserfakturas::getInstance();
        $tblUserFaktura->createOrLoadUserFakturaForBill($fk_userid, $tblBillRuns->id);

        $ret = '<tr>';

        $query = $db->getQuery(true);

        $select = <<<EOD
        sum(p.individual_preis) totalbetrag,
        c.lastname nachname,
        c.firstname vorname,
        c.cb_ortschaft ort,
        c.cb_telefon telefon,
        c.cb_handy handy,
        u.email email
EOD;
        $query->select($select)->from('#__srmink_positionen p');
        $query->join('LEFT','#__comprofiler c on p.fk_userid = c.user_id');
        $query->join('LEFT','#__users u on p.fk_userid = u.id');
        $query->where('p.fk_faktura=' . $tblBillRuns->id,'AND');
        $query->where('p.fk_userid=' .$fk_userid);

        $db->setQuery($query);
        $res = $db->loadObject();

        $ret = $ret . "<td width='20'>" .$tblUserFaktura->id .'</td>';
        $ret = $ret . '<td>' .$res->nachname .'</td>';
        $ret = $ret . '<td>' .$res->vorname .'</td>';
        $ret = $ret . '<td>' .$res->ort .'</td>';
        $ret = $ret . '<td>' .$res->telefon .'</td>';
        $ret = $ret . '<td>' .$res->email .'</td>';
        $ret = $ret . '<td>' .$res->totalbetrag .'</td>';
        $ret = $ret . '</tr>';
        return $ret;
    }
}
