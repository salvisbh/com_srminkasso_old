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

}
