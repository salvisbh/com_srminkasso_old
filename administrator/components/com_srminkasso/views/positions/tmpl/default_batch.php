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

$billruns = $this->billruns;

// $published = $this->state->get('filter.published');
?>
<fieldset class="batch">
	<legend><?php echo JText::_('Massenoperationen mit einzelnen Leistungen');?></legend>
	<p><?php echo JText::_('Die nachfolgende Aktion wird auf alle ausgewählten Leistungspositionen angewendet.'); ?></p>
	<label id="batch-bill-id-lbl" for="batch[bill_id]" class="hasTip" title>Rechnungszuordnung</label>
	<select name="batch[bill_id]" class="inputbox" id="batch-bill-id">
		<option value=""><?php echo JText::_('- Rechnung wählen -');?></option>
		<option value="-1"><?php echo JText::_('- Rechnungszuordnung aufheben -');?></option>
		<?php echo JHTML::_('select.options', $billruns, 'id', 'titel');?>
	</select>
	<button type="submit" onclick="Joomla.submitbutton('position.batch');">
		<?php echo JText::_('ausführen'); ?>
	</button>
</fieldset>
