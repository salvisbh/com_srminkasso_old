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

$fakturaStatus = $this->fakturaStatus;

?>

<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&view=bills'); ?>"
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
            <th width="5%"><?php echo JHtml::_('grid.sort', 'Id', 'id', $listDirn, $listOrder); ?></th>
            <th width="5">
            <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)"/>
            </th>

			<th width="8%"><?php echo JHtml::_('grid.sort', 'Datum', 'datum', $listDirn, $listOrder); ?></th>
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Name', 'name', $listDirn, $listOrder); ?></th>
			<th width="10%"><?php echo JHtml::_('grid.sort', 'Vorname', 'vorname', $listDirn, $listOrder); ?></th>
			<th width="10%"><?php echo JHtml::_('grid.sort', 'Ort', 'ort', $listDirn, $listOrder); ?></th>
			<th width="10%"><?php echo JHtml::_('grid.sort', 'Titel', 'titel', $listDirn, $listOrder); ?></th>
            <th width="5%"><?php echo JHtml::_('grid.sort', 'Betrag', 'betrag', $listDirn, $listOrder); ?></th>
            <th width="10%"><?php echo JHtml::_('grid.sort', 'Aktionen', 'aktionen', $listDirn, $listOrder); ?></th>
            <th width="5%"><?php echo JHtml::_('grid.sort', 'Status', 'status', $listDirn, $listOrder); ?>
                <br/>
                <select name="filter_fakturastatus_id" class="inputbox" onchange="this.form.submit()">
                    <option value="0"><?php echo JText::_('- Alle Status -');?></option>
                    <?php echo JHTML::_('select.options', $fakturaStatus, 'id', 'status', $this->state->get('filter.fakturastatus_id'));?>
                </select>
            </th>
            <th width="8%"><?php echo JHtml::_('grid.sort', 'Fällig', 'fdatum', $listDirn, $listOrder); ?></th>
            <th width="8%"><?php echo JHtml::_('grid.sort', 'Zahlungsdatum', 'zdatum', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
		</tfoot>
		<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">
            <td class="center"><?php
                /* Link zum Formular */
                $mylink = JRoute::_("index.php?option=com_srminkasso&task=bill.edit&id=" . $item->id);
                echo '<a href="' . $mylink . '">' . $this->escape($item->id) . '</a>';
                ?>
            </td>
			<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
			<td><?php echo $this->escape($item->datum); ?></td>
            <td><?php echo $this->escape($item->name); ?></td>
			<td><?php echo $this->escape($item->vorname); ?></td>
			<td><?php echo $this->escape($item->ort); ?></td>
			<td><?php echo $this->escape($item->titel); ?></td>
            <td><?php echo $this->escape($item->betrag); ?></td>
            <td align="center">
                <a href='index.php?option=com_srminkasso&task=bill.exportPdf&id=<?php print $item->id?>&fk_userId=<?php print $item->fk_userId?>&fk_billRunId=<?php print $item->fk_billRunId?>'>
                    <img src='components/com_srminkasso/assets/images/icon-16-print.png'
                         title="Rechnung als PDF anzeigen"></a>
            </td>
            <td><?php echo $this->escape($item->status); ?></td>
            <td><?php echo $this->escape($item->fdatum); ?></td>
            <td><?php echo $this->escape($item->zdatum); ?></td>
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
