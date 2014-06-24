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
class SrmInkassoControllerUser extends JControllerForm
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
        $model	= $this->getModel('user', '', array());

        // Preset the redirect
        $this->setRedirect(JRoute::_('index.php?option=com_srminkasso&view=positions' . $this->getRedirectToListAppend(), false));
        return parent::batch($model);

    }
}
