<?php
/**
* SrmInkasso Tabelle Status-Tabellendefinition.
*
* Tabelle status
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
class SrmInkassoTableStates extends JTable
{
    /**
     * Gibt eine Instanz eines Tabellenobjekts zurueck.
     * @return SrmInkassoTableActivities
     */
    public static function getInstance($type='states', $prefix='SrmInkassoTable', $config=array() ){
        return Jtable::getInstance($type,$prefix,$config);
    }

	/**
	* @var int $id Primärschlüssel
	*/
	public $id;

	/**
	* @var string $status - Der Titel
	*/
	public $status;
	
	/**
	 * @var int $typ - Statustyp
	 */
	public $typ;

	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_status', 'id', $db);
	}

    /**
     * Gibt eine Liste mit den Stati zum gewuenschten Typ zurueck.
     * @param $typ Filterkriterium der Spalte Typ (1=Positionsstatus, 2=Rechnungsstatus)
     * @return mixed
     */
    public function getStatus($typ){

        /* Referenz auf das Datenbankobjekt */
        $db	= $this->getDbo();

        /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
        $query	= $db->getQuery(true);

        /* Select-Abfrage in der Standardform aufbauen */
        $query->select('id, status')->from('#__srmink_status');
        $query->where('typ='.$typ);
        $query->order('id');

        $db->setQuery($query);
        $statusList = $db->loadObjectList();

        return $statusList;
    }
}
