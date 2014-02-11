<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Das Model Mything liefert Daten für die View MyThing
 *
 * @package    SrmInkasso
 * @subpackage Backend
 * @author     Hp. Salvisberg
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

JLoader::register('SrmInkassoTableBills', JPATH_COMPONENT . '/tables/bills.php');

/**
 * Erweiterung der Basisklasse JModelAdmin
 */
class SrmInkassoModelBill extends JModelAdmin
{
  /**
   * Methode getTable überschreiben (JModel), um ein
   * Objekt für unsere Tabelle `bills` zu instanziieren.
   *
   * @return SrmInkassoTableBills
   */
  public function getTable($type = 'bills', $prefix = 'SrmInkassoTable', $config = array())
  {
      return SrmInkassoTableBills::getInstance($type,$prefix, $config);
  }

  /**
   * Abstrakte Methode getForm() überschreiben, um Formularfelder
   * anhand der XML-Beschreibung (/forms/mything.xml) dieses Models
   * zu generieren.
   *
   * @return JForm oder false wenn das XML fehlt/nicht korrekt ist
   * @uses JModelForm::loadForm()
   */
  public function getForm($data = array(), $loadData = true)
  {
  	// Angaben zu den HTML-Elementen
    $options = array('control' => 'jform', 'load_data' => $loadData);
    $form    = $this->loadForm('srminkasso', 'bill', $options);
    if (empty($form)) {
      return false;
    }

    return $form;
  }

  /**
   * Ermittelt die Daten für das Formular aus einem vorherigen
   * Dialogschritt (passiert bei einem Eingabefehler) oder dem
   * aktuellen Datensatz.
   *
   * @return object Datensatz oder Eingaben aus vorherigem Dialogschritt
   */
  protected function loadFormData()
  {
    /* Daten aus dem Sitzungsspeicher holen sofern vorhanden */
    $app  = JFactory::getApplication();
    $data = $app->getUserState('com_srminkasso.edit.bill.data', array());

    /* ggf. Datensatz aus der Tabelle einlesen */
    if (empty($data)) {
      $data = $this->getItem();
    }

    return $data;
  }
}

