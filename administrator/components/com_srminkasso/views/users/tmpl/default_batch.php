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

$leistungen = $this->leistungen;

// $published = $this->state->get('filter.published');
//TODO: Auswahl des Datums funktioniert noch nicht
?>
<fieldset class="batch">
	<legend><?php echo JText::_('Leistungspositionen erfassen');?></legend>
	<p><?php echo JText::_('Die nachfolgende Leistung wird als neue Rechnungsposition für alle ausgewählten Rechnungsempfänger erstellt.'); ?></p>
	<label id="batch-status-id-lbl" for="batch[leistungs_id]" class="hasTip" title>Leistung</label>
	<select name="batch[leistungs_id]" class="inputbox" id="batch-leistungs-id">
		<option value=""><?php echo JText::_('- Leistung wählen -');?></option>
		<?php echo JHTML::_('select.options', $leistungen, 'id', 'titel');?>
	</select>
	<button type="submit" onclick="Joomla.submitbutton('user.batch');">
		<?php echo JText::_('Positionen erstellen'); ?>
	</button>
</fieldset>
