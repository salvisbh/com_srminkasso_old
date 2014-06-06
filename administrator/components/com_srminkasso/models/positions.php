<?php
/**
 * SRM Inkassosystem - Model der Tabelle mit den Leistungspositionen.
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
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');
JLoader::register('SrmInkassoTableActivities', JPATH_COMPONENT . '/tables/activities.php');

/**
 * Erweiterung der Klasse JModelList, abgeleitet von JModel
 */
class SrmInkassoModelPositions extends JModelList
{
    //Das Model einer einzelnen Position
    private $positionRow;

    /**
	 * Konstruktor - legt die Filter-Felder fest, die bei Sortierung
	 * und Suche verwendet werden
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'datum','nachname','ort', 'leistung','preis','rechnung','fakturadatum','status'
			);
		}

		parent::__construct($config);

        // Set the model
        $this->positionRow =& SrmInkassoTablePositions::getInstance();
	}

	public function getActivities()
	{
		/* Referenz auf das Datenbankobjekt */
		$db	= $this->getDbo();
	
		/* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
		$query	= $db->getQuery(true);
	
		/* Select-Abfrage in der Standardform aufbauen */
		$query->select('id, titel')->from(SrmInkassoTableActivities::getInstance()->getTableName());
	
		//TODO: nur fakturierungen von nicht archivierten Leistungen
// 		$query->where('fk_fakturierung is null');
	
		$db->setQuery($query);
		$activities = $db->loadObjectList();
// 		$activities = $db->loadAssocList();
		
		return $activities;
	
	}
	
	public function getBillruns()
	{
		/* Referenz auf das Datenbankobjekt */
		$db	= $this->getDbo();
		
		/* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
		$query	= $db->getQuery(true);
		
		/* Select-Abfrage in der Standardform aufbauen */
		$query->select('id, titel')->from('#__srmink_fakturierungen');
		
		$query->where('fk_fakturastatus = 1');
		
		$db->setQuery($query);
		$bills = $db->loadObjectList();

		return $bills;
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
	
		/* Auswahl des Benutzers in der Kategorie-Auswahl, übertragen in das state-Objekt */
		$activityId = $this->getUserStateFromRequest($this->context.'.filter.activity_id', 'filter_activity_id', '');
		$this->setState('filter.activity_id', $activityId);
		
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
		$id .= ':'.$this->getState('filter.activity_id');
	
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
    $db	= $this->getDbo();
    
    /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
    $query	= $db->getQuery(true);

    /* Select-Abfrage in der Standardform aufbauen */
    $query->select('p.*')->from('#__srmink_positionen AS p');
    
    /* Leistungsart zu Leistung aus #__srmink_leistungsarten ermitteln mit left join*/
	$query->select('l.titel AS leistung, l.datum as datum,l.preis as preis');
	$query->join('LEFT', '#__srmink_leistungen AS l ON p.fk_leistung = l.id');

	/* Rechnung */
	$query->select('f.datum as fakturadatum,f.titel as rechnung');
	$query->join('LEFT', '#__srmink_fakturierungen as f ON p.fk_faktura = f.id');
	
	/* Rechnungsstatus */
	$query->select('s.status');
	$query->join('LEFT', '#__srmink_status as s ON f.fk_fakturastatus = s.id');
	
	/* Community-Builder User anhaengen */
	$query->select('cb.lastname AS nachname, cb.firstname as vorname,cb.cb_strasse as strasse, cb.cb_ortschaft as ort');
	$query->join('LEFT', '#__comprofiler AS cb ON p.fk_userid = cb.user_id');
		
    /* Falls eine Eingabe im Filterfeld steht: Abfrage um eine WHERE-Klausel ergänzen */
    $search = $this->getState('filter.search');
    if (!empty($search)) {
    	$s = $db->quote('%'.$db->escape($search, true).'%');
    	
    	$query->where('cb.lastname LIKE ' .$s .' OR cb.firstname LIKE ' .$s .' OR cb.cb_ortschaft LIKE ' .$s);
    }
    
    /* Auswahl des Anwenders im Leistungen-Filter ermitteln */
    $activityId = $this->getState('filter.activity_id');
    
    /*
     * Wenn der Anwender eine Leistung gewählt hat, ist der Wert numerisch
    * Suche einschränken auf diese Leistung_id
    */
    if (is_numeric($activityId)) {
    	$query->where('l.id = '.(int) $activityId);
    }
    
    /* Abfrage um die Sortierangaben ergaenzen, Standardwert ist angegeben */
    $sort  = $this->getState('list.ordering', 'datum');
    $order = $this->getState('list.direction', 'DESC');
    $query->order($db->escape($sort).' '.$db->escape($order));
    
    
    /* Fertig ist die Abfrage */
    return $query;
  }

}
