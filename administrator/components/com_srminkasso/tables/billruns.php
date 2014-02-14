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
JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');

/**
* Erweiterung der Klasse JTable
*/
class SrmInkassoTableBillRuns extends JTable
{
    /**
     * Gibt eine Instanz eines Tabellenobjekts zurueck.
     * @return SrmInkassoTableBillRuns
     */
    public static function getInstance($type='billruns', $prefix='SrmInkassoTable', $config=array() ){
        return Jtable::getInstance($type,$prefix,$config);
    }

	/**
	* @var int $id Primärschlüssel
	*/
	public $id;
	
	/**
	* @var string $titel - Der Kurztitel
	*/
	public $titel;
	
	/**
	 * @var string $beschreibung - Zusatzkommentar
	 */
	public $kopftext;

	/**
	 * @var string $beschreibung - Zusatzkommentar
	 */
	public $fusstext;
	
	
	/**
	* @var datum $datum - Das Datum der Leistung
	*/
	public $datum;
	
	/**
	 * @var datum $datum - Das Datum der Leistung
	 */
	public $faellig;
	
	public $fk_fakturastatus;
	
	public $fk_template;
	
	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_fakturierungen', 'id', $db);
	}

    /**
     * Stellt vor dem Loeschen sicher, dass die Referenzen in den Leistungspositionen aufgeloest
     * und zugehoerige Userfaktura geloescht sind.
     * @param null $pk
     * @return bool
     */
    public function delete($pk = null)
    {
        //userfaktura loeschen
        $tblBills = SrmInkassoTableBills::getInstance();
        $tblBills->deleteBillsFromBillRun($pk);

        //Referenzen auf Positionen zuruecksetzen
        $tblPositions = SrmInkassoTablePositions::getInstance();
        $tblPositions->removeBillRunReference($pk);

        return parent::delete($pk); //
    }


}
