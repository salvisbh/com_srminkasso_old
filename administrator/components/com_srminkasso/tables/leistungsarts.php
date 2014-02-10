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
class SrmInkassoTableLeistungsarts extends JTable
{
	/**
	* @var int $id Primärschlüssel
	*/
	public $id;

	/**
	* @var string $owner - Jedes Ding hat einen Besitzer
	*/
	public $titel;

	/**
	* @var string $category - Ein Oberbegriff für das Ding
	*/
	public $konto;

	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_leistungsarten', 'id', $db);
	}

}
