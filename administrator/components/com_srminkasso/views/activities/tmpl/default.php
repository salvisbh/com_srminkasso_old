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

$nullDate = JFactory::getDbo()->getNullDate();

/* Nach dieser Spalte wird die Tabelle sortiert */
$listOrder = $this->escape($this->state->get('list.ordering'));

/* Die Sortierrichtung - aufsteigend oder absteigend */
$listDirn = $this->escape($this->state->get('list.direction'));

?>

<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&view=activities'); ?>"
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

            <th width="5%"><?php echo JHtml::_('grid.sort', 'Archiviert', 'archiviert', $listDirn, $listOrder); ?></th>
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Datum', 'datum', $listDirn, $listOrder); ?></th>
			<th width="45%"><?php echo JHtml::_('grid.sort', 'Titel', 'titel', $listDirn, $listOrder); ?></th>
			<th width="5%"><?php echo JHtml::_('grid.sort', 'Preis', 'preis', $listDirn, $listOrder); ?></th>
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Leistungsart', 'leistungsart', $listDirn, $listOrder); ?></th>
			<th width="10%"><?php echo JHtml::_('grid.sort', 'ID', 'id', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
		</tfoot>
		<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
            <td class="center"><?php echo $this->escape($item->archiviert); ?></td>
            <td><?php echo $this->escape($item->datum); ?></td>
			<td><?php
				/* Link zum Formular */
				$mylink = JRoute::_("index.php?option=com_srminkasso&task=activity.edit&id=" . $item->id);
				echo '<a href="' . $mylink . '">' . $this->escape($item->titel) . '</a>';
				?>
			</td>
			<td><?php echo $this->escape($item->preis); ?></td>
			<td><?php echo $this->escape($item->leistungsart); ?></td>
			<td class="center"><?php echo (int)$item->id; ?></td>
		</tr>
	<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
