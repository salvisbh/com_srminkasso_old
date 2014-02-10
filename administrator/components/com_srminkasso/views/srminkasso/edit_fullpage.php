<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * HTML-Formular zum Anlegen und Bearbeiten eines Datensatzes.
 *
 * @package    MyThings
 * @subpackage Backend
 * @author     chmst.de, webmechanic.biz
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;

// lädt JavaScript-Helfer für Tooltips, Eingabeprüfung
// und zum Aufrechterhalten der Session bei Untätigkeit,
// um Datenverluste während dem Bearbeiten zu vermeiden.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$form = $this->form;

?>
<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="<?php echo $this->formId?>" class="form-validate">

      <!-- normal fieldsets -->
	<div class="width-60 fltlft">
	    <?php
	    // Iterate through the normal form fieldsets and display each one.
// 	    foreach ($form->getFieldsets("main") as $fieldsets => $fieldset):
	    foreach ($form->getFieldsets() as $fieldsets => $fieldset):
	    ?>
	    <fieldset class="adminform">
	        <legend>
	            <?php echo JText::_($fieldset->name); ?>
	        </legend>
	        
	        <dl>
			<?php
			// Iterate through the fields and display them.
			foreach($form->getFieldset($fieldset->name) as $field):
			    // If the field is hidden, only use the input.
			    if ($field->hidden):
			        echo $field->input;
			    else:
			    ?>
			    <dt>
			        <?php echo $field->label; ?>
			    </dt>
			    <dd<?php echo ($field->type == 'Editor' || $field->type == 'Textarea') ? ' style="clear: both; margin: 0;"' : ''?>>
			    	<?php $fieldname=$field->fieldname; ?>
			    	<?php echo $field->input ?>
			    </dd>
			    <?php
			    endif;
			endforeach;
			?>
			</dl>
	        
	    </fieldset>
	    <?php
	    endforeach;
	    ?>
	</div>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
