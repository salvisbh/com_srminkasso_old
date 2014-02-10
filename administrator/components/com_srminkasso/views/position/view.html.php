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
class SrmInkassoViewPosition extends JView
{
	/* Der Datensatz, der zu bearbeiten ist */
	protected $item;

	/* Das Eingabeformular */
	protected $form;

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

		/* Ausgabe des View-Templates delegieren an die Elternklasse */
		parent::display($tpl);
	}

	/**
	 * Seitentitel und Werkzeugleiste aufbauen
	 */
	protected function addToolbar()
	{
		/* Der Toolbar-Titel wird gesetzt: Neuaufnahme oder Änderung */
		if ($this->item->id == 0) {
			JToolBarHelper::title(JText::_('Neue Leistungsposition'));
		} else {
			JToolBarHelper::title(JText::_('Leistungsposition mutieren'));
		}

        /* Speichern */
		JToolBarHelper::apply('position.apply', 'JTOOLBAR_APPLY');

		/* Speichern und Schließen Controller mything */
		JToolBarHelper::save('position.save', 'JTOOLBAR_SAVE');

		/* Button speichern und neu. Kein spezielles Icon ausgewählt */
		JToolBarHelper::save2new('position.save2new');

		/* Button "als Kopie speichern". Kein spezielles Icon ausgewählt */
		JToolBarHelper::save2copy('position.save2copy');

		/* Button cancel; Controller mything */
		JToolBarHelper::cancel('position.cancel', 'JTOOLBAR_CANCEL');
	}

}
