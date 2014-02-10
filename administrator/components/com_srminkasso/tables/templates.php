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
class SrmInkassoTableTemplates extends JTable
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
	public $body;

	/**
	 * @var string $beschreibung - Zusatzkommentar
	 */
	public $position;
	
	
	/**
	* @var datum $datum - Das Datum der Leistung
	*/
	public $aktiv;

    public $rand_links;
    public $rand_rechts;
    public $rand_oben;
    public $rand_unten;
	
	/**
	* Konstruktor setzt Tabellenname, Primärschlüssel und das
	* übergebene Datenbankobjekt.
	*/
	public function __construct($db)
	{
		parent::__construct('#__srmink_templates', 'id', $db);
	}

}
