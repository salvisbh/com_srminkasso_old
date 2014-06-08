<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * View Mything - Formularansicht zur Bearbeitung eines Items
 * @package    MyThings
 * @subpackage Backend
 * @author     chmst.de, webmechanic.biz
 * @license	  GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


/* Erweitern der Basisklasse JView */
class SrmInkassoViewBill extends JView
{
	/* Der Datensatz, der zu bearbeiten ist */
	protected $item;

	/* Das Eingabeformular */
	protected $form;

    /* Die Form-ID des elements it im Parent - form-element */
    protected $formId = 'bill-form';

	/**
	 * Die Methode display wird überschrieben, um den für die
	 * Formularansicht verwendeten Datensatz bereitzustellen.
	 *
	 * @param string $tpl Alternative Layoutdatei, leer = 'default'
	 */
	public function display($tpl = null)
	{
		/* Sperren des Hauptmenus */
		JFactory::getApplication()->input->set('hidemainmenu', true);

		/* Das Form-Objekt wird aufgebaut */
		$this->form = $this->get('Form');

		/* Bei Änderung: Der Datensatz wird aus der Datenbank geholt*/
		$this->item = $this->get('Item');


		/* Aufruf der Funktion für die Toolbar*/
		$this->addToolbar();

        /* fuehrt zum Laden von edit_fullpage.php im zusaeztlichen Templatepath */
        parent::addTemplatePath($this->_basePath . '/views/srminkasso');
        parent::display('fullpage');

	}

	/**
	 * Seitentitel und Werkzeugleiste aufbauen
	 */
	protected function addToolbar()
	{
		/* Der Toolbar-Titel wird gesetzt: Nur Änderung moeglich */
		JToolBarHelper::title(JText::_('Fakturierung mutieren'));

        /* Speichern */
		JToolBarHelper::apply('bill.apply', 'JTOOLBAR_APPLY');

		/* Speichern und Schließen Controller mything */
		JToolBarHelper::save('bill.save', 'JTOOLBAR_SAVE');

		/* Button cancel; Controller mything */
		JToolBarHelper::cancel('bill.cancel', 'JTOOLBAR_CANCEL');
	}

}
