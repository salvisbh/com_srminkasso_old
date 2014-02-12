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
JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');
JLoader::register('UserFakturaHelper', JPATH_COMPONENT . '/helpers/userfakturahelper.php');

/**
 * Der Controller MyThingsController erbt alles von JController
 */
class SrmInkassoControllerBill extends JControllerForm
{
    public function batch($model = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Set the model
        $model	= $this->getModel('bill', '', array());

        // Preset the redirect
        $this->setRedirect(JRoute::_('index.php?option=com_srminkasso&view=bills' . $this->getRedirectToListAppend(), false));
        return parent::batch($this->billModel);
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
        $billId =  $jinput->getInt('id',0);

        //Tabelle fuer Positionszugriff
        $tblPositions = SrmInkassoTablePositions::getInstance();

        //user holen, um zu schauen, ob es ueberhaupt Rechnungen zu generieren gibt
        $billableUserIds = $tblPositions->getUserIdsForBill($billId);

        if(count($billableUserIds) == 0){
            $message = JText::sprintf('Für diesen Rechnungslauf bestehen keine Positionen.');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }

        //BillItem laden
        $tblBill = SrmInkassoTableBills::getInstance();
        $tblBill->load($billId);

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($tblBill->fk_template);
        $ufHelper = new UserFakturaHelper();

        //Fuer jeden Nutzer Rechnung erstellen
        foreach ( $billableUserIds as $userId ) {

            $fk_userid = $userId->fk_userid;
            $usrFaktId = $ufHelper->appendUserFaktura($tblBill,$fk_userid,$tblPositions,$pdfDoc);
        }

        //PDF's mergen und an Browser senden
        $pdfNameWithPath = $fileNameWithPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.'Bills_' . $billId .'.pdf';
        $pdfDoc->writePdf($pdfNameWithPath,'F');
        PdfDocument::sendPdfToBrowser($pdfNameWithPath);
    }

}
