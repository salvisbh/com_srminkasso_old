<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Controller für die View MyThing (Formular)
 *
 * @package    SrmInkasso
* @subpackage Backend
* @author     Hp. Salvisberg
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * Der Controller MyThingsController erbt alles von JController
 */
class SrmInkassoControllerPositionimport extends JControllerForm
{

	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel();
		$data  = JRequest::getVar('jform', array(), 'post', 'array');
		$task = $this->getTask();
	
		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
	
			$this->setRedirect(
					JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_list
							. $this->getRedirectToListAppend(), false
					)
			);
	
			return false;
		}
	
		// Attempt to save the data.
		if (!$model->save($data))
		{
		// Save the data in the session.
			$app->setUserState($context . '.data', $validData);
	
			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
	
				$this->setRedirect(
						JRoute::_(
								'index.php?option=' . $this->option . '&view=' . $this->view_item
								. $this->getRedirectToItemAppend($recordId, $urlVar), false
						)
			);
	
			return false;
		}
		
	
		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		//TODO: Filter handhaben
		$link = 'index.php?option=com_srminkasso&view=positions';
		$this->setRedirect( $link );
		
		return true;
	}

    /**
     * Method to cancel an edit.
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     *
     * @since   11.1
     */
    public function cancel($key = null)
    {

        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&view=positions'
                . $this->getRedirectToListAppend(), false
            )
        );

        return true;
    }

}
