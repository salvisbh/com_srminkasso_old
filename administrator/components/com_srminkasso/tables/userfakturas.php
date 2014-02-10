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

}
