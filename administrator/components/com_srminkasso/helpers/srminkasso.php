<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Helperklasse für die Komponente SrmInkasso
 *
 * @package    MyThings
 * @subpackage Backend
 * @author     Hp. Salvisberg
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;

class SrmInkassoHelper {

    /**
     * In der Listenansicht im Contenberich ein Submenü aufbauen
     * Damit ist ein Wechsel zwischen Kategorien und Things möglich.
     *
     * @param type $name
     */
    public static function addSubmenu($name) {

        /* Tab "SrmInkasso" */
        JSubMenuHelper::addEntry(
            JText::_('Leistungspositionen'),
            'index.php?option=com_srminkasso&view=positions', $name == 'positions'
        );
        
        /* Tab "SrmInkasso" */
        JSubMenuHelper::addEntry(
        	JText::_('Leistungen'),
        	'index.php?option=com_srminkasso&view=activities', $name == 'activities'
        );

        /* Tab "SrmInkasso" */
        JSubMenuHelper::addEntry(
        	JText::_('Fakturierungen'),
        	'index.php?option=com_srminkasso&view=bills', $name == 'bills'
        );
        
        /* Tab "SrmInkasso" */
        JSubMenuHelper::addEntry(
        	JText::_('Leistungsarten'),
        	'index.php?option=com_srminkasso&view=leistungsarts', $name == 'leistungsarts'
        );
        
        /* Tab "SrmInkasso" */
        JSubMenuHelper::addEntry(
        JText::_('Templates'),
        'index.php?option=com_srminkasso&view=templates', $name == 'templates'
        		);
        
    }


}
