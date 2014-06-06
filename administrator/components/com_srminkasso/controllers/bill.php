<?php
/**
 * SRM Inkasso - Leistungsverrechnung
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
JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');
JLoader::register('UserFakturaHelper', JPATH_COMPONENT . '/helpers/userfakturahelper.php');

/**
 * Der Controller MyThingsController erbt alles von JController
 */
class SrmInkassoControllerBill extends JControllerForm
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

        //BillItem laden
        $tblBill = SrmInkassoTableBills::getInstance();
        $result = $tblBill->load($billId);

        //pdf-klasse erstellen
        $ufHelper = new UserFakturaHelper();

        $pdfFileWithPath = $ufHelper->createUserFaktura($tblBill->fk_faktura,$tblBill->fk_userid);
        PdfDocument::sendPdfToBrowser($pdfFileWithPath);
    }
}
