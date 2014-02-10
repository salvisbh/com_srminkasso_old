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

<form action="<?php echo JRoute::_('index.php?option=com_srminkasso&view=templates'); ?>"
      method="post" name="adminForm" id="adminForm">
	<div class="clr"></div>
	<table class="adminlist">
		<thead>
		<tr>
			<th width="5">
				<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)"/>
			</th>
		
			<th width="85%"><?php echo JHtml::_('grid.sort', 'Titel', 'titel', $listDirn, $listOrder); ?></th>
			<th width="5%"><?php echo JHtml::_('grid.sort', 'Aktiv', 'aktiv', $listDirn, $listOrder); ?></th>
			<th width="5%"><?php echo JHtml::_('grid.sort', 'Id', 'id', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($this->items as $i => $item) : ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
			<td><?php
				/* Link zum Formular */
				$mylink = JRoute::_("index.php?option=com_srminkasso&task=template.edit&id=" . $item->id);
				echo '<a href="' . $mylink . '">' . $this->escape($item->titel) . '</a>';
				?>
			</td>
			<td><?php echo $this->escape($item->aktiv); ?></td>
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
