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


<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

		<ul class="fields">
			<li>
				<label for="title">Title</label>
				<div class="input">
					
					<?php echo form_input("title", set_value("title", $title)); ?>
				</div>
			</li>
			<li>
				<label for="description">Description</label>
				<div class="input">
					
					<?php echo form_input("description", set_value("description", $description)); ?>
				</div>
			</li>
			<li>
				<label for="image">Image</label>
				<div class="input">
					<img src="<?php echo base_url().'uploads/default/portfolio/200x250/'.$image; ?>" />
				</div>
			</li>
		</ul>

	</div>

	<div class="buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

	<?php echo form_close(); ?>
</div>
</section>
  <script>
     $(function() {
       $( ".date" ).datepicker({
       	dateFormat: "yy-mm-dd"
       });
     });
  </script>