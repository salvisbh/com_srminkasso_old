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
      method="post" name="adminForm" id="activity-form" class="form-validate">

	<fieldset class="adminform">
		<legend><?php echo JText::_('Leistung'); ?></legend>
		<ul class="adminformlist">
			<li>
				<?php echo $this->form->getLabel('datum'); ?>
				<?php echo $this->form->getInput('datum'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('fk_leistungsart'); ?>
				<?php echo $this->form->getInput('fk_leistungsart'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('preis'); ?>
				<?php echo $this->form->getInput('preis'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('titel'); ?>
				<?php echo $this->form->getInput('titel'); ?>
			</li>

		</ul>
	</fieldset>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
