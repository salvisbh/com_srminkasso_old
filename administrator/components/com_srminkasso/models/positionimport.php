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

/**
 * Erweiterung der Basisklasse JModelAdmin
 */
class SrmInkassoModelPositionimport extends JModelAdmin
{
	var $_fk_leistung;
	var $_usergroup;
	
  /**
   * Methode getTable überschreiben (JModel), um ein
   * Objekt für unsere Tabelle `leistungsart` zu instanziieren.
   *
   * @return SrmInkassoTableLeistungsarts
   */
//   public function getTable($type = 'activities', $prefix = 'SrmInkassoTable', $config = array())
//   {
//     return JTable::getInstance($type, $prefix, $config);
//   }

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
    $form    = $this->loadForm('srminkasso', 'positionimport', $options);
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
    $data = $app->getUserState('com_srminkasso.edit.positionimport.data', array());

    /* ggf. Datensatz aus der Tabelle einlesen */
    if (empty($data)) {
//       $data = $this->getItem();
    	$properties = array("id" => 1, "fk_leistung" => 0);
    	$data = JArrayHelper::toObject($properties, 'JObject');
    }

    return $data;
  }
  
  public function save($data)
  {

  	//TODO: Hier erstellen
  	$this->_fk_leistung = $data['fk_leistung'];
  	$this->_usergroup = $data['usergroup'];	
 
  	$db	= $this->getDbo();
  	
  	/* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
  	$query	= $db->getQuery(true);
  	$query->select('*')->from('#__user_usergroup_map'); 
  	$query->where('group_id = ' .$this->_usergroup);
  	$db->setQuery($query);
  	$usergroups = $db->loadObjectList();

  	$pos = new stdClass();
  	foreach ( $usergroups as $ug ) {
  		
  		$pos->fk_userid = $ug->user_id;
  		$pos->fk_leistung = $this->_fk_leistung;
  		$result = $db->insertObject('#__srmink_positionen', $pos);
  	}
  	
  	
  	// Clean the cache.
	$this->cleanCache();
   
  	return true;
  }
  
}

