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
class SrmInkassoTableActivities extends JTable
{
    /**
     * Gibt eine Instanz eines Tabellenobjekts zurueck.
     * @return SrmInkassoTableActivities
     */
    public static function getInstance($type='activities', $prefix='SrmInkassoTable', $config=array() ){
        return Jtable::getInstance($type,$prefix,$config);
    }

	/**
	* @var int $id Primärschlüssel
	*/
	public $id;
	
	/**
	 * @var int $fk_leistungsart Fremdschlüssel auf Leistungsart
	 */
	public $fk_leistungsart;
	
	/**
	 * @var int $fk_fakturierung Fremdschlüssel auf Fakturierung
	 */
	public $fk_fakturierung;

	/**
	* @var string $titel - Der Kurztitel
	*/
	public $titel;
	
	/**
	 * @var string $beschreibung - Zusatzkommentar
	 */
	public $beschreibung;

	/**
	* @var datum $datum - Das Datum der Leistung
	*/
	public $datum;
	
	/**
	 * @var preis $preis - Der Preis der Leistung
	 */
	public $preis;

    /**
     * @var boolean archivierte Leistungen werden in Dropdowns nicht mehr angezeigt.
     */
    public $archiviert;

	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_leistungen', 'id', $db);
	}

    public function getActiveActivities(){

        /* Referenz auf das Datenbankobjekt */
        $db	= $this->getDbo();

        /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
        $query	= $db->getQuery(true);

        /* Select-Abfrage in der Standardform aufbauen */
        $query->select("id, concat(titel,' (', datum, ')') titel")->from('#__srmink_leistungen');
        $query->where('archiviert=0');
        $query->order('datum');

        $db->setQuery($query);
        $activitiesList = $db->loadObjectList();

        return $activitiesList;
    }
}
