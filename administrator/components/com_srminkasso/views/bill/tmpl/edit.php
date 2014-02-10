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
      method="post" name="adminForm" id="bill-form" class="form-validate">

	<fieldset class="adminform">
		<legend><?php echo JText::_('Rechnungslauf'); ?></legend>
		<ul class="adminformlist">
			<li>
				<?php echo $this->form->getLabel('datum'); ?>
				<?php echo $this->form->getInput('datum'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('fk_fakturastatus'); ?>
				<?php echo $this->form->getInput('fk_fakturastatus'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('titel'); ?>
				<?php echo $this->form->getInput('titel'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('faellig'); ?>
				<?php echo $this->form->getInput('faellig'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('kopftext'); ?>
				<?php echo $this->form->getInput('kopftext'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('fusstext'); ?>
				<?php echo $this->form->getInput('fusstext'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('fk_template'); ?>
				<?php echo $this->form->getInput('fk_template'); ?>
			</li>
		</ul>
	</fieldset>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
