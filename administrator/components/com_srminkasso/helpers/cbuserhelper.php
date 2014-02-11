<?php
/**
 * Created by PhpStorm.
 * User: hps
 * Date: 11.02.14
 * Time: 17:21
 */

defined('_JEXEC') or die('Restricted access');

class CbUserHelper {

    /**
     * Gibt den Benutzer zu einer userId zurueck.
     * @param $userId
     * @return stdClass
     */
    public function getCbUser($userId){

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('cb.lastname AS nachname, cb.firstname as vorname,cb.cb_strasse as strasse, cb.cb_plz as plz, cb.cb_ortschaft as ort')
            ->from('#__comprofiler as cb');
        $query->where('cb.user_id=' . (int)$userId);
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }
} 