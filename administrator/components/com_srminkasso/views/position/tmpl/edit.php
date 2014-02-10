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
      method="post" name="adminForm" id="position-form" class="form-validate">

	<fieldset class="adminform">
		<legend><?php echo JText::_('Leistungsposition'); ?></legend>
		<ul class="adminformlist">
			<li>
				<?php echo $this->form->getLabel('fk_leistung'); ?>
				<?php echo $this->form->getInput('fk_leistung'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('fk_userid'); ?>
				<?php echo $this->form->getInput('fk_userid'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('fk_faktura'); ?>
				<?php echo $this->form->getInput('fk_faktura'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('individual_preis'); ?>
				<?php echo $this->form->getInput('individual_preis'); ?>
			</li>
			
		</ul>
	</fieldset>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
