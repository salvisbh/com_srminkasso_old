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
JLoader::register('SrmInkassoTableStates', JPATH_COMPONENT . '/tables/status.php');

/**
 * Erweiterung der Klasse JModelList, abgeleitet von JModel
 */
class SrmInkassoModelBills extends JModelList
{

	/**
	 * Konstruktor - legt die Filter-Felder fest, die bei Sortierung
	 * und Suche verwendet werden
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'name', 'titel','status','ort'
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

        /* fakturastatus in State Objekt legen, default auf 'offen' setzen*/
        $fakturaStatusId = $this->getUserStateFromRequest($this->context.'.filter.fakturastatus_id', 'filter_fakturastatus_id', '');
        if(!is_numeric($fakturaStatusId)){
            $fakturaStatusId = 4;
        }

        $this->setState('filter.fakturastatus_id', $fakturaStatusId);

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
        $id .= ':'.$this->getState('filter.fakturastatus_id');
	
		return parent::getStoreId($id);
	}

    public function getFakturaStatus(){

        $tblStatus = SrmInkassoTableStates::getInstance();
        $fakturaStatusListe = $tblStatus->getStatus(2);
        return $fakturaStatusListe;
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

      /* Select ueber ganzen Join zusammenstellen */
      $select = <<<EOD
        sum(if(p.individual_preis > 0,p.individual_preis, l.preis)) as betrag,
        f.id,
        f.zahlungsdatum as zdatum,
        fl.id as fk_billRunId,
        fl.titel,
        fl.datum,
        fl.faellig as fdatum,
        s.status,
        cb.user_id as fk_userId,
        cb.lastname as name,
        cb.firstname as vorname,
        cb.cb_ortschaft as ort
EOD;

      /* Alle Positionen als Master, um Summe ermitteln zu koennen*/
      $query->select($select)->from('#__srmink_positionen p');

      /* Leistung fuer Normalpreis*/
      $query->join('LEFT','#__srmink_leistungen AS l ON p.fk_leistung = l.id');

      /* Details der Userfaktura */
      $query->join('LEFT','#__srmink_userfaktura f on p.fk_faktura = f.fk_faktura and f.fk_userid=p.fk_userid');

    /* Fakturierungslauf */
    $query->join('LEFT', '#__srmink_fakturierungen AS fl ON p.fk_faktura = fl.id');

      /* Rechnungsstatus */
      $query->join('LEFT', '#__srmink_status as s ON f.status = s.id');

    /* Empfänger */
	$query->join('LEFT', '#__comprofiler AS cb ON p.fk_userid = cb.user_id');

    $query->group('f.id');

        //Nur Positionen, welche Fakturierungslauf zugeordnet haben
      $query->where('p.fk_faktura > 0');

    /* Falls eine Eingabe im Filterfeld steht: Abfrage um eine WHERE-Klausel ergänzen */
    $search = $this->getState('filter.search');
    if (!empty($search)) {
    	$s = $db->quote('%'.$db->escape($search, true).'%');
    	
    	$query->where('(cb.lastname LIKE ' .$s . ' or cb.firstname LIKE ' .$s. ' or cb.cb_ortschaft LIKE ' .$s.' or fl.titel LIKE ' .$s. ')');
    }

      /* auswahl des anwenders im Statusfilter ermitteln */
      $fakturaStatusId = $this->getState('filter.fakturastatus_id');
      if(is_numeric($fakturaStatusId) && $fakturaStatusId > 0){
          $query->where('f.status='.(int)$fakturaStatusId);
      }

    /* Abfrage um die Sortierangaben ergaenzen, Standardwert ist angegeben */
    $sort  = $this->getState('list.ordering', 'name');
    $order = $this->getState('list.direction', 'DESC');
    $query->order($db->escape($sort).' '.$db->escape($order));
    
    
    /* Fertig ist die Abfrage */
    return $query;
  }
}
