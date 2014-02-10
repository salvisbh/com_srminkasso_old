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
	
	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_positionen', 'id', $db);
	}

}
