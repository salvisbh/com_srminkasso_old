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

    public $pdfname;

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
}
