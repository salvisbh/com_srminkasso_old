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
class SrmInkassoTableUserfakturas extends JTable
{
    public static $STATUS_OFFEN=4;

    /**
     * Gibt eine Instanz eines Tabellenobjekts zurueck.
     * @return SrmInkassoTableUserfakturas
     */
    public static function getInstance($type='userfakturas', $prefix='SrmInkassoTable', $config=array()){
        return Jtable::getInstance($type,$prefix,$config);
    }

	/**
	* @var int $id Primärschlüssel
	*/
	public $id;
	
	/**
	* @var string $titel - Der Kurztitel
	*/
	public $fk_userid;
	
	/**
	 * @var string $beschreibung - Zusatzkommentar
	 */
	public $totalbetrag;

	/**
	 * @var string $beschreibung - Zusatzkommentar
	 */
	public $fk_faktura;
	
	
	/**
	* @var datum $datum - Das Datum der Leistung
	*/
	public $zahlungsdatum;
	
	public $status;

	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_userfaktura', 'id', $db);
	}

    /**
     * Positioniert auf einer UserFaktura oder erstellt diese, falls sie nicht existiert.
     * @param $userid
     * @param $billId
     * @return bool
     */
    public function createOrLoadUserFakturaForBill($userid,$billId){

        $bResult = $this->loadUserFakturaForBill($userid,$billId);

        //nicht gefunden, neu anlegen
        if(!$bResult){

            $db	= $this->getDbo();
            $obj = new stdClass();
            $obj->fk_userid=$userid;
            $obj->fk_faktura=$billId;
            $obj->status=self::$STATUS_OFFEN;
            $result = $db->insertObject($this->getTableName(),$obj);

            //...und nochmals laden
            $bResult = $this->loadUserFakturaForBill($userid,$billId);
        }

        return $bResult;
    }

    /**
     * Positioniert auf UserFaktura fuer einen bestimmten Rechnungslauf.
     * @param $userid die UserID
     * @param $billId die ID des Rechnungslaufes
     * @return bool, falls Laden erfolgreich
     */
    public function loadUserFakturaForBill($userid,$billId){

        $loadOk = false;

        $db	= $this->getDbo();
        $query	= $db->getQuery(true);
        $query->select('id')->from($this->getTableName());

        $query->where('fk_userid=' . (int)$userid, 'AND');
        $query->where('fk_faktura=' .(int)$billId);

        $db->setQuery($query);
        $idObj = $db->loadObject();

        if(!is_null($idObj)){
            $loadOk = $this->load($idObj->id);
        }

        return $loadOk;

    }

    public function updateUserFakturaForBill($fakturaItem){
        $db	= $this->getDbo();
        $result = $db->updateObject($this->getTableName(),$fakturaItem,$this->getKeyName());
        return $result;
    }

    /**
     * Gibt fuer einen BillRun alle Rechnungen, sortiert nach Rechnungsnummer, zurueck.
     * Diese Funktion wird verwendet fuer das Generieren des PDF-Belegs eines Billruns.
     * @param $billRunId
     */
    public function getBillsWithEmpfaengerForBillRun($billRunId){
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $select = <<<EOD
        uf.id fakturaId,
        uf.fk_userId userId,
        uf.totalbetrag,
        c.lastname nachname,
        c.firstname vorname,
        c.cb_ortschaft ort,
        c.cb_telefon telefon,
        c.cb_handy handy,
        u.email email
EOD;
        $query->select($select)->from('#__srmink_userfaktura uf');
        $query->join('LEFT','#__comprofiler c on uf.fk_userid = c.user_id');
        $query->join('LEFT','#__users u on uf.fk_userid = u.id');
        $query->where('uf.fk_faktura=' .(int)$billRunId);
        $query->order('uf.id');
        $db->setQuery($query);
        $userBills = $db->loadObjectList();

        return $userBills;

    }
}
