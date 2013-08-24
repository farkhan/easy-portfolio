<div id="portfolio">

		<?php foreach ($categories as $category): ?>
		<div class="category">
			<a href="<?php echo $this->module.'/'.$category->slug; ?>">
				<div class="transparent-overlay">
					<img src='<?php echo base_url();?>addons/shared_addons/themes/cbd/img/portfolio-transparent-overlay.png'>
				</div>
				<div class="text-overlay">
					<h4> <?php echo $category->title; ?> </h4>
				</div>
				<img src="<?php echo base_url().'uploads/default/'.$this->module.'/categories/'.$category->v; ?>" />
			</a>
			<div class="category-num-items hidden"><?php echo $category->num_items; ?></div>
		</div>
		<?php endforeach; ?>
		

</div>

<script type="text/javascript">
$(document).ready(function() {

    $('.category').each(function(index, domElement)
    {
    	var num = $(this).find('.category-num-items').text();
    	if (num == 0)
    	{
    		$(this).css('opacity', 0.4);
    		$(this).find('a').removeAttr('href');
    		console.log($(this));
    	}
    	//console.log(num);
    })
});
</script>
