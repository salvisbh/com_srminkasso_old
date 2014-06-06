<?php
/**
 * View der Liste mit den Billruns.
 *
 * Standard-Ansicht im Backend.
 *
 * @package    SrmInkasso
 * @subpackage Backend
 * @author     Hp. Salvisberg
 * @license    GNU/GPLv2 or later
 */
defined('_JEXEC') or die;

/* Import der Basisklasse JView */
jimport('joomla.application.component.view');

/**
 * Erweiterung der Basisklasse JView
 */
class SrmInkassoViewBillRuns extends JView
{
	/**
	 * Die Tabellenzeilen fuer den mittleren Teil der View
	 * @var object $items
	 */
	protected $items;

	/**
	 * Die Daten fuer die Blaetterfunktion
	 * @var object $pagination
	 */
	protected $pagination;
	
	/**
	 * Die Daten der aktuellen Session
	 * @var object $state
	 */
	protected $state;
	
	/**
	 * Überschreiben der Methode display
	 *
	 * @param string $tpl Alternative Layoutdatei, leer = 'default'
	 */
	function display($tpl = null)
	{
		/* JView holt die Daten vom Model */

		/* Die Datensaetze aus der Tabelle mythings */
		$this->items = $this->get('Items');

		/* Statusinformationen fuer die Sortierung */
		$this->state		= $this->get('State');
		
		/* Daten fuer die Blaetterfunktion  */
		$this->pagination	= $this->get('Pagination');
		
		/* Aufnbau der Toolbar */
		$this->addToolbar();

		/* View ausgeben - zurueckdelegiert an die Elternklasse */
		parent::display($tpl);
	}

	/**
	 * Aufbau der Toolbar, es werden nur die Buttons eingefügt,
	 * fuer die der Benutzer eine Berechtigung hat.
	 */
	protected function addToolbar()
	{
		/* Links oben der Titel */
		JToolBarHelper::title(JText::_('Fakturierungsläufe'));

		/* Button addNew;  Ein Datensatz, daher Controller leistungsart, task add */
		JToolBarHelper::addNew('billrun.add', 'JTOOLBAR_NEW');

		/* Button editList;  Ein Datensatz, daher Controller leistungsart, task edit */
		JToolBarHelper::editList('billrun.edit', 'JTOOLBAR_EDIT');

		/* Button delete, kann sich auf mehrere Datensaetze beziehen, daher leistungsarten */
		JToolBarHelper::deleteList('Fakturierungsläufe löschen und Referenzen auf Leistungspositionen zurücksetzen?', 'billruns.delete', 'JTOOLBAR_DELETE');
	}

}
