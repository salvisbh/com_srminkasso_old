<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Das Model Leistungsarten liefert Daten fuer die Uebersicht
 *
 * @package    SrmInkasso
 * @subpackage Backend
 * @author     Hp. Salvisberg
 * @license	   GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Erweiterung der Klasse JModelList, abgeleitet von JModel
 */
class SrmInkassoModelTemplates extends JModelList
{

    /**
	 * Konstruktor - legt die Filter-Felder fest, die bei Sortierung
	 * und Suche verwendet werden
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'titel'
			);
		}
	
		parent::__construct($config);
	}
	
	/*
	 * Ergaenzungen zum Setzen des "Datenzustandes" (state) des Models
	* damit der Suchfilter nicht verloren geht. Standard: sortiert
	* nach title, aufsteigend
	*
	* @param string $ordering  Tabellenspalte nach der sortiert wird
	* @param string $direction Sortierrichtung, ASC = aufsteigend
	* @see JModelList::populateState()
	*/
	protected function populateState($ordering = 'datum', $direction = 'DESC')
	{
		/* Suchbegriff aus vorheriger Eingabe ermitteln */
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
	
		/* Suchbegriff fuer diese Ausgabe setzen */
		$this->setState('filter.search', $search);
	
		/* Sortieren wird netterweise von der Elternklasse übernommen */
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Ident-Schluessel fuer den aktuellen Datenzustand anpassen,
	 * damit eine gleichzeitige Suche in anderen Fenstern nicht
	 * zu Verwechslungen fuehrt.
	 *
	 * @see JModelList::getStoreId()
	 */
	protected function getStoreId($id = '')
	{
		$id	.= ':'.$this->getState('filter.search');
	
		return parent::getStoreId($id);
	}
	
  /**
   * Datenbankabfrage fuerr die Listenansicht aufbauen.
   * Suchfilter und Sortierung werden beruecksichtigt, ansonsten
   * wird aufsteigend nach `bezeichnung` sortiert.
   *
   * @return JDatabaseQuery
   */
  protected function getListQuery()
  {
    /* Referenz auf das Datenbankobjekt */
    $db		= $this->getDbo();

    /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
    $query	= $db->getQuery(true);

    /* Select-Abfrage in der Standardform aufbauen */
    $query->select('id,titel,aktiv')->from('#__srmink_templates');

    /* Abfrage um die Sortierangaben ergaenzen, Standardwert ist angegeben */
//     $sort  = $this->getState('list.ordering', 'datum');
//     $order = $this->getState('list.direction', 'DESC');
//     $query->order($db->escape($sort).' '.$db->escape($order));
    
    
    /* Fertig ist die Abfrage */
    return $query;
  }
}
