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
   * Objekt für unsere Tabelle `billruns` zu instanziieren.
   *
   * @return SrmInkassoTableBillRuns
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

    /**
     * Method to perform batch operations on an item or a set of items.
     *
     * @param array $commands
     *        	An array of commands to perform.
     * @param array $pks
     *        	An array of item ids.
     * @param array $contexts
     *        	An array of item contexts.
     *
     * @return boolean Returns true on success, false on failure.
     *
     * @since 11.1
     */
    public function batch($commands, $pks, $contexts) {
        // Sanitize user ids.
        $pks = array_unique ( $pks );
        JArrayHelper::toInteger ( $pks );

        // Remove any values of zero.
        if (array_search ( 0, $pks, true )) {
            unset ( $pks [array_search ( 0, $pks, true )] );
        }

        if (empty ( $pks )) {
            $this->setError ( JText::_ ( 'JGLOBAL_NO_ITEM_SELECTED' ) );
            return false;
        }

        $done = false;

        if (! empty ( $commands ['status_id'] )) {

            $status_id = $commands ['status_id'];
            $zahlungsdatum = ($status_id == 5) ? $commands ['bezahlDatum'] : NULL;

            // Zuordnung zu Rechnung erstellen
            $table = $this->getTable ();

            foreach ( $pks as $pk ) {
                $table->reset ();
                $table->load ( $pk );

                if ($status_id >0 ) {
                    $table->status = $status_id;
                    $table->zahlungsdatum = $zahlungsdatum;
                }

                if (! $table->store ()) {
                    $this->setError ( $table->getError () );
                    return false;
                }
            }

            $done = true;
        }

        if (! $done) {
            $this->setError ( JText::_ ( 'JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION' ) );
            return false;
        }

        // Clear the cache
        $this->cleanCache ();

        return true;
    }
}

