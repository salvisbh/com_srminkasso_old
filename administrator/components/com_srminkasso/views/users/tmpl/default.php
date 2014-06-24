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

/* Nach dieser Spalte wird die Tabelle sortiert */
$listOrder = $this->escape($this->state->get('list.ordering'));

/* Die Sortierrichtung - aufsteigend oder absteigend */
$listDirn = $this->escape($this->state->get('list.direction'));

$trainingsGruppen = $this->trainingsGruppen;

?>

<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&view=users'); ?>"
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
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Nachname', 'nachname', $listDirn, $listOrder); ?></th>
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Vorname', 'vorname', $listDirn, $listOrder); ?></th>
            <th width="20%"><?php echo JHtml::_('grid.sort', 'Strasse', 'strasse', $listDirn, $listOrder); ?></th>
            <th width="5%"><?php echo JHtml::_('grid.sort', 'Plz', 'plz', $listDirn, $listOrder); ?></th>
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Ort', 'ort', $listDirn, $listOrder); ?></th>
            <th width="20%"><?php echo JHtml::_('grid.sort', 'Trainingsgruppe', 'cb_trainingsgruppe', $listDirn, $listOrder); ?>
                <br/>
                <select name="filter_trainingsgruppe" class="inputbox" onchange="this.form.submit()">
                    <option value="ungefiltert"><?php echo JText::_('ungefiltert');?></option>
                    <option value="irgendeine"><?php echo JText::_('irgendeine');?></option>
                    <?php echo JHTML::_('select.options', $trainingsGruppen, 'cb_trainingsgruppe', 'cb_trainingsgruppe', $this->state->get('filter.trainingsgruppe'));?>
                </select>
            </th>
			<th width="15%"><?php echo JHtml::_('grid.sort', 'Geb.Datum', 'geburtsdatum', $listDirn, $listOrder); ?></th>
            <th width="5%"><?php echo JHtml::_('grid.sort', 'Lizenz', 'lizenznummer', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
		</tfoot>
		<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center"><?php echo JHtml::_('grid.id', $i, $item->user_id); ?></td>
			<td><?php echo $this->escape($item->nachname); ?></td>
            <td><?php echo $this->escape($item->vorname); ?></td>
			<td><?php echo $this->escape($item->strasse); ?></td>
			<td><?php echo $this->escape($item->plz); ?></td>
			<td><?php echo $this->escape($item->ort); ?></td>
            <td><?php echo $this->escape($item->cb_trainingsgruppe); ?></td>
            <td><?php echo $this->escape($item->geburtsdatum); ?></td>
            <td><?php echo $this->escape($item->lizenznummer); ?></td>
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
