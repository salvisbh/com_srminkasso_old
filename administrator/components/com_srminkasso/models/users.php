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
JLoader::register('SrmInkassoTableActivities', JPATH_COMPONENT . '/tables/activities.php');

/**
 * Erweiterung der Klasse JModelList, abgeleitet von JModel
 */
class SrmInkassoModelUsers extends JModelList
{

	/**
	 * Konstruktor - legt die Filter-Felder fest, die bei Sortierung
	 * und Suche verwendet werden
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'vorname', 'nachname', 'ort'
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
	protected function populateState($ordering = 'nachname', $direction = 'DESC')
	{
		/* Suchbegriff aus vorheriger Eingabe ermitteln */
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
	
		/* Suchbegriff fuer diese Ausgabe setzen */
		$this->setState('filter.search', $search);

        /* fakturastatus in State Objekt legen, default auf 'offen' setzen*/
        $trainingsGruppe = $this->getUserStateFromRequest($this->context.'.filter.trainingsgruppe', 'filter_trainingsgruppe', '');
        $this->setState('filter.trainingsgruppe', $trainingsGruppe);

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
        $id .= ':'.$this->getState('filter.trainingsgruppe');
	
		return parent::getStoreId($id);
	}

    public function getLeistungen(){
        $tblActivities = SrmInkassoTableActivities::getInstance();
        $leistungen = $tblActivities->getActiveActivities();
        return $leistungen;
    }

    public function getTrainingsGruppen(){

        /* Referenz auf das Datenbankobjekt */
        $db		= $this->getDbo();

        /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
        $query	= $db->getQuery(true);
        $query->select('cb_trainingsgruppe')->from('#__comprofiler');
        $query->where("cb_trainingsgruppe is not null and trim(cb_trainingsgruppe) <> ''");
        $query->group('cb_trainingsgruppe');
        $query->order('cb_trainingsgruppe');
        $db->setQuery($query);
        $trainingsGruppen = $db->loadObjectList();
        return $trainingsGruppen;
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

    /* Select-ueber ganzen Join zusammenstellen */

      $select = <<<EOD
        user_id,
        firstname vorname,
        lastname nachname,
        cb_strasse strasse,
        cb_plz plz,
        cb_ortschaft ort,
        cb_trainingsgruppe,
        cb_geburtsdatum geburtsdatum,
        cb_lizenznummer lizenznummer
EOD;
    $query->select($select)->from('#__comprofiler AS cb');
    
    /* Joomla-User anhaengen, um gesperrte ausklammern zu koennen */
	$query->join('LEFT', '#__users u ON  cb.user_id = u.id');

      $query->where('u.block=0');

    /* Falls eine Eingabe im Filterfeld steht: Abfrage um eine WHERE-Klausel ergänzen */
    $search = $this->getState('filter.search');
    if (!empty($search)) {
    	$s = $db->quote('%'.$db->escape($search, true).'%');
    	
    	$query->where('(firstname LIKE ' .$s . ' or lastname LIKE ' .$s . ' or cb_ortschaft LIKE ' .$s .')');
    }

    $filterTrainingsGruppe=$this->getState('filter.trainingsgruppe');

    if($filterTrainingsGruppe == 'irgendeine'){
        $query->where("(cb_trainingsgruppe is not null and trim(cb_trainingsgruppe) <> '')");
    }elseif($filterTrainingsGruppe == 'ungefiltert'){
        //
    }else{
        $where = '(cb_trainingsgruppe LIKE ' . $db->quote($filterTrainingsGruppe)  .')';
        $query->where($where);
    }
    /* Abfrage um die Sortierangaben ergaenzen, Standardwert ist angegeben */
    $sort  = $this->getState('list.ordering', 'lastname');
    $order = $this->getState('list.direction', 'ASC');
    $query->order($db->escape($sort).' '.$db->escape($order));
    
    
    /* Fertig ist die Abfrage */
    return $query;
  }
}
