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
?>
<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="leistungsart-form" class="form-validate">

	<fieldset class="adminform">
		<legend><?php echo JText::_('Leistungsart'); ?></legend>
		<ul class="adminformlist">
			<li>
				<?php echo $this->form->getLabel('titel'); ?>
				<?php echo $this->form->getInput('titel'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('konto'); ?>
				<?php echo $this->form->getInput('konto'); ?>
			</li>
		</ul>
	</fieldset>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
