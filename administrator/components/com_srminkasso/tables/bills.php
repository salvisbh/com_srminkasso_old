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
class SrmInkassoTableBills extends JTable
{
    /**
     * Gibt eine Instanz eines Tabellenobjekts zurueck.
     * @return SrmInkassoTableBills
     */
    public static function getInstance($type='bills', $prefix='SrmInkassoTable', $config=array() ){
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
	public $status;
	
	/**
	 * @var datum $datum - Das Datum der Leistung
	 */
	public $zahlungsdatum;

	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_userfaktura', 'id', $db);
	}

    /**
     * Loescht die Userfakturas zu einem Rechnungslauf.
     * @param $fk_faktura
     * @return bool
     */
    public function deleteBillsFromBillRun($fk_faktura){
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('fk_faktura') . '=' .$fk_faktura
        );

        $query->delete($db->quoteName($this->getTableName()));
        $query->where($conditions);

        $db->setQuery($query);
        $result = $db->query();

        return $result;
    }


}
