<div id="portfolio">
		<div class="slider-wrapper">
			<!-- Responive layout. To make it static, remove %. For example: data-width="940"-->
			<!-- See http://fotorama.io/customize/ for more options. -->
			<div id="portfolio-slider" class="fotorama" data-width="100%" data-ratio="940/480" data-fit="cover" data-allow-full-screen="true">

				<?php foreach ($item_images as $item): ?>
					<img src="<?php echo base_url().'uploads/default/portfolio/'.$item['image']; ?>" data-caption="<?php echo $item['title']; ?>"/>
				<?php endforeach; ?>
			</div>
		</div>
		<div id="portfolio-content">
			<div class="title">
				<h3><?php echo $instance->title; ?></h3>
			</div>
			<div class="description">
				<?php echo $instance->description; ?>
			</div>
		</div>
		<div id="portfolio-list">
			<div class="title">
				<h3>Related Items</h3>
			</div>
			<div class="description">
				<ul class="unstyled">
					<?php foreach ($related_items as $item): ?>
						<li><a href="<?php echo base_url().$this->module.'/'.$item->category_slug.'/'.$item->item_slug; ?>"> 
								<?php echo $item->item_title; ?>
							</a> 
						</li>	
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
</div>