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
JLoader::register('SrmInkassoTableActivities', JPATH_COMPONENT . '/tables/activities.php');
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');

/**
 * Erweiterung der Basisklasse JModelAdmin
 */
class SrmInkassoModelPositionimport extends JModelAdmin
{
	var $_fk_leistung;
	var $_usergroup;
    var $_trainingsgruppe;

    /* @var $tblActivities SrmInkassoTableActivities */
    private $tblActivities;

    private $positions;
    private $tblNamePos;

    public function __construct($config = array()){
        parent::__construct($config);

        $this->tblActivities = SrmInkassoTableActivities::getInstance();
        $this->positions = SrmInkassoTablePositions::getInstance();
        $this->tblNamePos = $this->positions->getTableName();
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

  	$this->_fk_leistung = $data['fk_leistung'];
  	$this->_usergroup = $data['usergroup'];
    $this->_trainingsgruppe = $data['trainingsgruppe'];

    $usergroup = intval($this->_usergroup);

      //Preis der Aktivitaet lesen
      $this->tblActivities->load($this->_fk_leistung);

      if($usergroup > 0){
          $this->assignUserGroup($usergroup);
      }elseif($this->_trainingsgruppe != ''){
          $this->assignTrainingsgruppe($this->_trainingsgruppe);
      }else{
          //nicht machen
      }

  	
  	// Clean the cache.
	$this->cleanCache();
   
  	return true;
  }

    private function assignUserGroup($uGrpId){

        $db	= $this->getDbo();

        /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
        $query	= $db->getQuery(true);
        $query->select('ug.user_id')->from('#__user_usergroup_map ug');
        $query->join('LEFT','#__users as u ON ug.user_id = u.id');
        $query->where('ug.group_id = ' .(int)$this->_usergroup,'AND');
        $query->where('u.block=0');
        $db->setQuery($query);
        $usergroups = $db->loadObjectList();

        foreach ( $usergroups as $ug ) {

            $result = $this->positions->addPosition($ug->user_id,
                $this->_fk_leistung,
                        0);
        }

    }

    private function assignTrainingsgruppe($tg){

        $db	= $this->getDbo();

        /* Ein neues, leeres JDatabaseQuery-Objekt anfordern */
        $query	= $db->getQuery(true);
        $query->select('cb.user_id')->from('#__comprofiler cb');
        $query->join('LEFT','#__users as u ON cb.user_id = u.id');
        $query->where('cb.cb_trainingsgruppe = \'' .$tg  . '\'','AND');
        $query->where('u.block=0');
        $db->setQuery($query);
        $users = $db->loadObjectList();

        foreach ( $users as $user ) {

            $result = $this->positions->addPosition($user->user_id,
                $this->_fk_leistung,
                0);
        }

    }

}

