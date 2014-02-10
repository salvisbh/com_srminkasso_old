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

        //Model fuer Zugriff auf Leistungspositionen
        $positionsModel = $this->getModel('positions');

        //user holen, um zu schauen, ob es ueberhaupt Rechnungen zu generieren gibt
        $billableUserIds = $positionsModel->getUserIdsForBill($billId);

        if(count($billableUserIds) == 0){
            $message = JText::sprintf('Für diesen Rechnungslauf bestehen keine Positionen.');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        }

        //Fakturalauf-Daten holen
        //Zugriff auf einzelne Fakturalaeufe
        $billModel = $this->getModel('bill', '', array());;
        $billItem = $billModel->getItem($billId);

        //Zugriff auf Userrechnungen
        $userfakturaModel = $this->getModel('userfaktura');

        //pdf-klasse erstellen
        $pdfDoc = new PdfDocument($billItem->fk_template);

        //Fuer jeden Nutzer Rechnung erstellen
        foreach ( $billableUserIds as $userId ) {

            //Rechnungsreccord holen
            $ufTable = $userfakturaModel->getOrCreateUserFakturaForBill($userId->fk_userid,$billId);
            $ufTable = $this->createUserFaktura($pdfDoc,$userId->fk_userid,$ufTable,$billItem,$positionsModel);

            //userfaktura aktualisieren
            $userfakturaModel->updateUserFakturaForBill($ufTable);
        }

        $file_path = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'files'.DS.'pdf'.DS.'rechnungslauf' .$billId .'.pdf';

        $pdfDoc->writePdf($file_path,'F');
        $pdfDoc->sendPdfToBrowser($file_path);

    }

    private function createUserFaktura($pdfDoc,$userId,$ufTable,$billItem,$positionsModel){

        //pdf Elemente holen
        $bodyTemplate = $pdfDoc->getMainTemplate();
        $positionTemplate = $pdfDoc->getPositonTemplate();

        //Positionen lesen
        $posList = $positionsModel->getPositionsForUserBill($userId,$billItem->id);

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
        $op_Itval['titel']=$billItem->titel;
        $op_Itval['kopftext']=$billItem->kopftext;
        $op_Itval['fusstext']=$billItem->fusstext;
        $op_Itval['template_items']=$posHtml;
        $op_Itval['totalbetrag']=$total;

        $htmlContent = $pdfDoc->replaceContentParameters($op_Itval,$bodyTemplate);
        $pdfDoc->addPage($htmlContent);

        //userfaktura aktualisieren und zurueckgeben
        $ufTable->totalbetrag=3.60;
        return $ufTable;
    }
}
