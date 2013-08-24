<section class="title">
	<h4><?php echo lang('categories:item_list'); ?></h4>
</section>

<div id="inner-shortcuts">
	<?php echo anchor('admin/'.$this->module.'/categories/create/', 'Create Category', 'class="btn orange"'); ?>
</div>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/'.$this->module.'/categories/delete');?>
	<?php if (!empty($categories)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('categories:title'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>  
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $categories as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->title; ?></td>
					<td class="actions">
						<?php echo
						//anchor('portfolio', lang('portfolio:view'), 'class="button" target="_blank"').' '.
						anchor('admin/'.$this->module.'/categories/edit/'.$item->id, lang('categories:edit'), 'class="btn blue"').' '.
						anchor('admin/'.$this->module.'/categories/delete/'.$item->id, 	lang('categories:delete'), array('class'=>'btn red')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('categories:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>