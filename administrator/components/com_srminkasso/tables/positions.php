<?php
/**
* Joomla! 2.5 - Erweiterungen programmieren
*
* Tabelle Mythings
*
* @package    SrmInkasso
* @subpackage Backend
* @author     Hp. Salvisberg
* @license    GNU/GPL
*/
defined('_JEXEC') or die;

/**
* Erweiterung der Klasse JTable
*/
class SrmInkassoTablePositions extends JTable
{
    /**
     * Gibt eine Instanz eines Tabellenobjekts zurueck.
     * @return SrmInkassoTablePositions
     */
    public static function getInstance($type='positions', $prefix='SrmInkassoTable', $config=array()){
        return Jtable::getInstance($type,$prefix,$config);
    }

	/**
	* @var int $id Primärschlüssel
	*/
	public $id;
	
	/**
	 * @var int $fk_leistungsart Fremdschlüssel auf Leistungsart
	 */
	public $fk_leistung;
	
	/**
	 * @var int $fk_fakturierung Fremdschlüssel auf Fakturierung
	 */
	public $fk_userid;

	/**
	 * @var int $individual_preis Individueller Preis in Abweichung zum Leistungspreis
	 */
	public $individual_preis;
	
	public $fk_faktura;

    public $kommentar;

	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_positionen', 'id', $db);
	}

    /**
     * Gibt die Liste der UserIDs der Positionen fuer einen Rechnungslauf zurueck.
     * @param $billId
     */
    public function getUserIdsForBill($billId){

        /* Referenz auf das Datenbankobjekt */
        $db	= $this->getDbo();

        /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
        $query	= $db->getQuery(true);

        /* Select-Abfrage in der Standardform aufbauen */
        $query->select('p.fk_userid')->from($this->getTableName() .' p');

        /* Join aus Name, um Rechnungen alphabetisch sortieren zu koennen*/
        $query->join('LEFT','#__comprofiler as c ON p.fk_userid = c.user_id');

        $query->where('fk_faktura=' .(int)$billId);
        $query->group('fk_userid');
        $query->order('c.lastname');
        $db->setQuery($query);

        $userIds = $db->loadObjectList();

        return $userIds;
    }

    public function getPositionsForUserBill($userid,$billId){

        $db	= $this->getDbo();
        $query	= $db->getQuery(true);

        $query->select('l.preis,p.individual_preis,p.kommentar')->from($this->getTableName() .' p');
        $query->select('l.datum,l.titel,l.beschreibung');
        $query->join('LEFT', '#__srmink_leistungen AS l ON p.fk_leistung = l.id');

        $query->where('p.fk_userid=' . (int)$userid, 'AND');
        $query->where('p.fk_faktura=' .(int)$billId);
        $query->order('l.datum');

        $db->setQuery($query);
        $positions = $db->loadObjectList();

        return $positions;

    }

    /**
     * Gibt die Zusammenfassung nach Leistungsart einer UserBill zurueck.
     * @param $userId die UserId.
     * @param $billRunId die BillRunID.
     * @return mixed
     */
    public function getLeistungsartenSummaryForUserBill($userId,$billRunId){

        $db	= $this->getDbo();
        $query	= $db->getQuery(true);

        /* Select ueber ganzen Join zusammenstellen */
        $select = <<<EOD
            la.id,
            la.titel,
            la.konto,
            sum(if(p.individual_preis > 0,p.individual_preis, l.preis)) summeLeistungsart
EOD;

        $query->select($select)->from($this->getTableName() . ' p');
        $query->join('LEFT','#__srmink_leistungen as l on p.fk_leistung = l.id');
        $query->join('LEFT','#__srmink_leistungsarten as la on l.fk_leistungsart = la.id');
        $query->where('p.fk_faktura=' .(int)$billRunId,'AND');
        $query->where('p.fk_userid=' .(int)$userId);
        $query->group('la.titel');

        $db->setQuery($query);
        $leistungsArten = $db->loadObjectList();

        return $leistungsArten;
    }

    /**
     * Entfernt bei Positionen die Referenz auf einen Rechnungslauf.
     * @param $fk_faktura die ID des Rechnungslaufes
     * @return bool true, falls Referenzen geloest werden konnten, sonst false.
     */
    public function removeBillRunReference($fk_faktura){

        $db		= $this->getDbo();
        $query	= $db->getQuery(true);

        $fields = array($db->quoteName('fk_faktura') .'=null'
        );

        $conditions = array($db->quoteName('fk_faktura') .'=' . $fk_faktura);

        $query->update($db->quoteName($this->getTableName()))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->query();

        return $result;
    }

    public function addPosition($userId,$fk_leistung,$individualPreis){
        $pos = new stdClass();
        $pos->fk_userid = $userId;
        $pos->fk_leistung = $fk_leistung;
        $pos->individual_preis = $individualPreis;
        $result = $this->getDbo()->insertObject($this->getTableName(), $pos);

        return $result;
    }

}
