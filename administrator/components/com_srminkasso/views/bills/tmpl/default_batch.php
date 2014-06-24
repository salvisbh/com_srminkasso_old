<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$fakturaStatus = $this->fakturaStatus;
$bezahlDatum = date('Y-m-d');
$name='batch[bezahlDatum]';
$id = 'kalender';

// $published = $this->state->get('filter.published');
//TODO: Auswahl des Datums funktioniert noch nicht
?>
<fieldset class="batch">
	<legend><?php echo JText::_('Massenoperationen mit einzelnen Rechnungen');?></legend>
	<p><?php echo JText::_('Die nachfolgende Aktion wird auf alle ausgewählten Rechnungen angewendet.'); ?></p>
	<label id="batch-status-id-lbl" for="batch[status_id]" class="hasTip" title>Rechnungsstatus / Datum</label>
	<select name="batch[status_id]" class="inputbox" id="batch-status-id">
		<option value=""><?php echo JText::_('- Status wählen -');?></option>
		<?php echo JHTML::_('select.options', $fakturaStatus, 'id', 'status');?>
	</select>
    <?php echo JHtml::_('calendar', $bezahlDatum, $name, $id);?>
	<button type="submit" onclick="Joomla.submitbutton('bill.batch');">
		<?php echo JText::_('ausführen'); ?>
	</button>
</fieldset>
