<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
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
class SrmInkassoControllerTemplates extends JControllerAdmin
{
  /**
   * Verbindung zu MyThingsModelMyThing, damit die dort
   * enthaltenen Methoden zum Lesen von Datensaetzen
   * verwendet werden koennen.
   *
   * @return MyThingsModelMyThings Das Model fuer die Listenansicht
   */
  public function getModel($name = 'Template', $prefix = 'SrmInkassoModel', $config = array())
  {
    // Model nicht automatisch mit Inhalten aus dem Request befuellen
    $config['ignore_request'] = true;

    // restliche Arbeit der Elternklasse ueberlassen
    return parent::getModel($name, $prefix, $config);
  }

}
