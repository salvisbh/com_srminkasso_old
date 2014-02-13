<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Allgemeiner Controller der Komponente mythings
 *
 * @package    MyThings txt
 * @subpackage Backend
 * @author     chmst.de, webmechanic.biz
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;
JLoader::import('joomla.application.component.controller');

/* helperklasse dem JLoader melden, bei Bedarf wird sie schell geladen */
JLoader::register('SrmInkassoHelper', JPATH_COMPONENT . '/helpers/srminkasso.php');

/**
 * Erweiterung der Basisklasse JController
 */
class SrmInkassoController extends JController
{
	/**
	 * @var string Standardview
	 */
	protected $default_view = 'positions';

	/**
	 * Ausgabe der View leistungsarten.
	 * @inherit
	 */
	public function display($cachable = false, $urlparams = false)
	{
		/* @var $input JInput Unsere Einnahmequelle */
		$input = JFactory::getApplication()->input;

		// alle Variablen mit Vorgabewerten initialisieren
		$view   = $input->get('view', $this->default_view);
		$layout = $input->get('layout', 'default');
		$id     = $input->get('id');

		// Bevor die View aufgebaut wird, erstellt die Helperklasse
		// ein Untermenü zum Wechseln zwischen Categories und Things
		SrmInkassoHelper::addSubmenu($view);
		
		if ($view == 'leistungsart' && $layout == 'edit')
		{
			// checkEditId() ist eine Methode von JController, die den Kontext prüft
			if (!$this->checkEditId('com_srminkasso.edit.leistungsart', $id)) {
				// Kommentarlos zurück zur default-view
				$this->setRedirect(JRoute::_('index.php?option=com_srminkasso&view=leistungsarts', false));
				return false;
			}
		}

		// Alles geprüft und ok, die View kann ausgegeben werden
		return parent::display($cachable, $urlparams);
	}
}
