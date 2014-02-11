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
        $query->select('fk_userid')->from($this->getTableName());
        $query->where('fk_faktura=' .(int)$billId);
        $query->group('fk_userid');
        $db->setQuery($query);
        $userIds = $db->loadObjectList();

        return $userIds;
    }

    public function getPositionsForUserBill($userid,$billId){

        $db	= $this->getDbo();
        $query	= $db->getQuery(true);

        $query->select('p.individual_preis')->from($this->getTableName() .' p');
        $query->select('l.datum,l.titel,l.beschreibung,l.preis');
        $query->join('LEFT', '#__srmink_leistungen AS l ON p.fk_leistung = l.id');

        $query->where('p.fk_userid=' . (int)$userid, 'AND');
        $query->where('p.fk_faktura=' .(int)$billId);

        $db->setQuery($query);
        $positions = $db->loadObjectList();

        return $positions;

    }
}
