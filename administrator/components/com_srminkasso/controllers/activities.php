<?php
/**
 * SRM-Inkassosystem - Leistungsverrechnung
 *
 * Controller für die Listenansicht mythings
 *
 *@package    SrmInkasso
* @subpackage Backend
* @author     Hp. Salvisberg
* @license    GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');

/**
 * Erweiterung der Klasse JControllerForm
 */
class SrmInkassoControllerActivities extends JControllerAdmin
{
  /**
   * Verbindung zu MyThingsModelMyThing, damit die dort
   * enthaltenen Methoden zum Lesen von Datensaetzen
   * verwendet werden koennen.
   *
   * @return MyThingsModelMyThings Das Model fuer die Listenansicht
   */
  public function getModel($name = 'Activity', $prefix = 'SrmInkassoModel', $config = array())
  {
    // Model nicht automatisch mit Inhalten aus dem Request befuellen
    $config['ignore_request'] = true;

    // restliche Arbeit der Elternklasse ueberlassen
    return parent::getModel($name, $prefix, $config);
  }

  /**
   * Import Workbook definiton(s) from XML file
   * @return void
   */
  public function import()
  {
  	$link = 'index.php?option=com_srminkasso&view=positionimport';
  	$this->setRedirect( $link );
  }
}
