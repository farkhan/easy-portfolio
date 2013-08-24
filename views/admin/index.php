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

{{ theme:partial name="breadcrumbs.html" }}

<section class="item">
	<div class="content">
	<?php echo form_open('admin/'.$this->module.'/delete');?>
	<?php if (!empty($portfolio)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('portfolio:title'); ?></th>
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
				<?php foreach( $portfolio as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->Title; ?></td>
					<td class="actions">
						<?php echo
						//anchor('portfolio', lang('portfolio:view'), 'class="button" target="_blank"').' '.
						anchor('admin/'.$this->module.'/edit/'.$item->id, lang('portfolio:edit'), 'class="btn blue"').' '.
						anchor('admin/'.$this->module.'/delete/'.$item->id, 	lang('portfolio:delete'), array('class'=>'btn red')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('portfolio:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>