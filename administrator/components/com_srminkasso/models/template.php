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
defined ( '_JEXEC' ) or die ();
jimport ( 'joomla.application.component.modeladmin' );

/**
 * Erweiterung der Basisklasse JModelAdmin
 */
class SrmInkassoModelTemplate extends JModelAdmin {
	/**
	 * Methode getTable überschreiben (JModel), um ein
	 * Objekt für unsere Tabelle `leistungsart` zu instanziieren.
	 *
	 * @return SrmInkassoTableLeistungsarts
	 */
	public function getTable($type = 'templates', $prefix = 'SrmInkassoTable', $config = array()) {
		return JTable::getInstance ( $type, $prefix, $config );
	}
	
	/**
	 * Abstrakte Methode getForm() überschreiben, um Formularfelder
	 * anhand der XML-Beschreibung (/forms/mything.xml) dieses Models
	 * zu generieren.
	 *
	 * @return JForm oder false wenn das XML fehlt/nicht korrekt ist
	 * @uses JModelForm::loadForm()
	 */
	public function getForm($data = array(), $loadData = true) {
		// Angaben zu den HTML-Elementen
		$options = array (
				'control' => 'jform',
				'load_data' => $loadData 
		);
		$form = $this->loadForm ( 'srminkasso', 'template', $options );
		if (empty ( $form )) {
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
	protected function loadFormData() {
		/* Daten aus dem Sitzungsspeicher holen sofern vorhanden */
		$app = JFactory::getApplication ();
		$data = $app->getUserState ( 'com_srminkasso.edit.template.data', array () );
		
		/* ggf. Datensatz aus der Tabelle einlesen */
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
		
		return $data;
	}
	
}

