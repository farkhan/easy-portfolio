<section class="title">
	<p class="breadcrumbs">
		        {{ template:breadcrumbs }}
					{{ if uri }}
						{{ url:anchor segments=uri title=name }}&nbsp;>&nbsp;
					{{ else }}
						<span class="current">{{ name }}</span>
					{{ endif }}
				{{ /template:breadcrumbs }}
	</p>
</section>

<div id="inner-shortcuts">
	<?php echo anchor('admin/'.$this->module.'/items/add_item_images/'.$id, 'Add Item Images', 'class="btn orange"'); ?>
</div>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/'.$this->module.'/items/delete_item_images');?>
	<?php if (!empty($item_images)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('items:title'); ?></th>
					<th><?php echo lang('items:image'); ?></th>
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
				
				<?php foreach( $item_images as $item ): ?>

				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->title; ?></td>
					<td><img src="<?php echo base_url('uploads/default/portfolio/thumb/'.$item->image); ?>" /></td>
					<td></td>
					<td class="actions">
						<?php echo
						anchor('admin/'.$this->module.'/items/images/edit/'.$item->id, lang('items:edit'), 'class="btn blue"').' '.
						anchor('admin/'.$this->module.'/items/delete_item_images/'.$item->id, 	lang('items:delete'), array('class'=>'btn red')); 
						?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('items:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
	</div>
</section>