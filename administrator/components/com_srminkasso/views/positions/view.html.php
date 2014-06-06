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
class SrmInkassoViewPositions extends JView
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
	 * Aktivitaeten fuer Filterung der Liste
	 * @var unknown
	 */
	protected $activities;

    /**
     * Status auf dem Billrun zur Filterung anhand Versandstatus.
     * @var
     */
    protected $versandStatus;
	
	/**
	 * Rechnungen fuer Filterung der Liste und fuer Batch-Operationen.
	 * @var unknown
	 */
	protected $billruns;
	
	/**
	 * Überschreiben der Methode display
	 *
	 * @param string $tpl Alternative Layoutdatei, leer = 'default'
	 */
	function display($tpl = null)
	{
		
		//Layout pimpen
		JHTML::stylesheet( 'activities.css', 'administrator/components/com_srminkasso/assets/' );
		
		/* JView holt die Daten vom Model */

		/* Die Datensaetze aus der Tabelle mythings */
		$this->items = $this->get('Items');

		/* Statusinformationen fuer die Sortierung */
		$this->state		= $this->get('State');
		
		/* Daten fuer die Blaetterfunktion  */
		$this->pagination	= $this->get('Pagination');
		
		/* Activities fuer Filter holen, ruft getActivities im Model */
		$this->activities = $this->get("activities");

        /* Versandstatus fuer Filter holen, ruft getVersandStatus im Model*/
        $this->versandStatus = $this->get("versandStatus");

		$this->billruns = $this->get("billruns");
		
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
		JToolBarHelper::title(JText::_('Leistungspositionen'));

		/* Button addNew;  Ein Datensatz, daher Controller leistungsart, task add */
		JToolBarHelper::addNew('position.add', 'JTOOLBAR_NEW');
		
		/* Button fuer das Hinzufuegen von Positionen fuer eine ganze Gruppe*/
		JToolBarHelper::custom( 'activities.import', 'import.png', 'import.png', 'Positionen generieren', false );

		/* Button editList;  Ein Datensatz, daher Controller leistungsart, task edit */
		JToolBarHelper::editList('position.edit', 'JTOOLBAR_EDIT');

		/* Button delete, kann sich auf mehrere Datensaetze beziehen, daher leistungsarten */
		JToolBarHelper::deleteList('Leistungspositionen loeschen?', 'positions.delete', 'JTOOLBAR_DELETE');

	}

}
