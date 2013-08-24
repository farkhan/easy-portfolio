
<div id="portfolio">

		<div id="portfolio-list">
			<ul class="categories">
				<?php foreach ($categories as $category): ?>
					<li class="category-item <?php if ($this->uri->uri_string() == $this->module.'/'.$category->slug ): ?>active<?php endif; ?>">
						<a href="<?php echo base_url().'index.php/'.$this->module.'/'.$category->slug; ?>">
							<?php echo $category->title; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div id="portfolio-content">
			<?php foreach ($items as $item): ?>
				<div class="portfolio_item">
					<a href="<?php echo current_url().'/'.$item->item_slug; ?>">
						<div class="portfolio_item_image">
							<?php echo img('uploads/default/portfolio/200x250/'.$item->item_image); ?>
						</div>
						<div class="portfolio_item_title">
							<?php echo $item->item_title; ?>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

</div>