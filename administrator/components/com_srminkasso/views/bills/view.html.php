<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Standard-Ansicht com_mythings im Backend.
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
class SrmInkassoViewBills extends JView
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
     * Status auf der Rechnung zur Filterung
     * @var
     */
    protected $fakturaStatus;

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

        /* Fakturastatus fuer Filter holen, ruft getFakturaStatus im Model*/
        $this->fakturaStatus = $this->get("fakturaStatus");

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
		JToolBarHelper::title(JText::_('Rechnungen'));

		/* Button addNew;  Ein Datensatz, daher Controller leistungsart, task add */
//		JToolBarHelper::addNew('activity.add', 'JTOOLBAR_NEW');

		/* Button editList;  Ein Datensatz, daher Controller leistungsart, task edit */
//		JToolBarHelper::editList('bill.edit', 'JTOOLBAR_EDIT');

		/* Button delete, kann sich auf mehrere Datensaetze beziehen, daher leistungsarten */
		JToolBarHelper::deleteList('Rechnung löschen?', 'bills.delete', 'JTOOLBAR_DELETE');

	}

}
