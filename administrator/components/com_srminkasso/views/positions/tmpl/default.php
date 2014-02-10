<?php
/**
 * Joomla! 2.5 - Erweiterungen programmieren
 *
 * Das HTML-Layout zur tabellarischen MyThings-Übersicht
 *
 * @package    SrmInkasso
* @subpackage Backend
* @author     Hp. Salvisberg
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

$nullDate = JFactory::getDbo()->getNullDate();

/* Nach dieser Spalte wird die Tabelle sortiert */
$listOrder = $this->escape($this->state->get('list.ordering'));

/* Die Sortierrichtung - aufsteigend oder absteigend */
$listDirn = $this->escape($this->state->get('list.direction'));

$activities = $this->activities;

?>

<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&view=positions'); ?>"
      method="post" name="adminForm" id="adminForm">
      <fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl">
				<?php echo JText::_('Suchfilter'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search"
			       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
			       title="<?php echo JText::_('suchen'); ?>"/>
			<button type="submit"><?php echo JText::_('suchen'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('löschen'); ?>
			</button>
		</div>
	</fieldset>
	<div class="clr"></div>
	<table class="adminlist">
		<thead>
		<tr>
			<th width="5">
				<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)"/>
			</th>
		
			<th><?php echo JHtml::_('grid.sort', 'Datum', 'datum', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'Nachname', 'nachname', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'Vorname', 'vorname', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'strasse', 'strasse', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'ort', 'ort', $listDirn, $listOrder); ?></th>
			
			<th><?php echo JHtml::_('grid.sort', 'Leistung', 'leistung', $listDirn, $listOrder); ?>
				<br />
				<select name="filter_activity_id" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('- Alle Leistungen -');?></option>
					<?php echo JHTML::_('select.options', $activities, 'id', 'titel', $this->state->get('filter.activity_id'));?>
				</select>
			</th>
			
			<th><?php echo JHtml::_('grid.sort', 'Preis', 'preis', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'Sonderpreis', 'individual_preis', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'Faktura (F)', 'rechnung', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'F-Datum', 'fakturadatum', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'F-Status', 'status', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'ID', 'id', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
		</tfoot>
		<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
			<td><?php echo $this->escape($item->datum); ?></td>
			<td><?php echo $this->escape($item->nachname); ?></td>
			<td><?php
				/* Link zum Formular */
				$mylink = JRoute::_("index.php?option=com_srminkasso&task=position.edit&id=" . $item->id);
				echo '<a href="' . $mylink . '">' . $this->escape($item->vorname) . '</a>';
				?>
			</td>
			<td><?php echo $this->escape($item->strasse); ?></td>
			<td><?php echo $this->escape($item->ort); ?></td>
			<td><?php echo $this->escape($item->leistung); ?></td>
			<td><?php echo $this->escape($item->preis); ?></td>
			<td><?php echo $item->individual_preis > 0 ? $this->escape($item->individual_preis) : "&nbsp;"; ?></td>
			<td><?php echo $this->escape($item->rechnung); ?></td>
			<td><?php echo $this->escape($item->fakturadatum); ?></td>
			<td><?php echo $this->escape($item->status); ?></td>
			<td class="center"><?php echo (int)$item->id; ?></td>
		</tr>
	<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->loadTemplate('batch'); ?>
	
	<input type="hidden" name="task"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
