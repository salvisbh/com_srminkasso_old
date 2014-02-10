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

/**
 * Der Controller MyThingsController erbt alles von JController
 */
class SrmInkassoControllerPosition extends JControllerForm
{
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Set the model
		$model	= $this->getModel('position', '', array());
	
		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_srminkasso&view=positions' . $this->getRedirectToListAppend(), false));
	
		return parent::batch($model);
	}
}
