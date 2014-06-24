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
JLoader::register('SrmInkassoTablePositions', JPATH_COMPONENT . '/tables/positions.php');

/**
 * Erweiterung der Basisklasse JModelAdmin
 */
class SrmInkassoModelUser extends JModelAdmin
{

    public function getForm($data = array(), $loadData = true)
    {
        return null;
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
        $posCreated = 0;

        if (! empty ( $commands ['leistungs_id'] )) {

            $leistungs_id = $commands ['leistungs_id'];

            $tblPositions = SrmInkassoTablePositions::getInstance();

            // Positionen erstellen

            foreach ( $pks as $pk ) {
                $tblPositions->addPosition($pk,$leistungs_id,0);
                $posCreated++;
            }

            $done = true;
        }

        if (! $done) {
            $this->setError ( JText::_ ( 'JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION' ) );
            return false;
        }

        // Clear the cache
        $this->cleanCache ();

        //return true;
        return $done;
    }
}

