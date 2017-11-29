<?php global $SMTheme; ?>


<?php
	$slides=$SMTheme->get_slides();
	if (!is_array($slides)||count($slides)==0) exit;
?>
	
	
<div class='slider-container'>
	<img src="<?php echo get_template_directory_uri().'/images/slider-top.png' ;?>" class="slider-top"/>
	<img src="<?php echo get_template_directory_uri().'/images/slider-bottom.png' ;?>" class="slider-bottom"/>				
	<div class="slider">
			<div class="fp-slides">
				<?php foreach ($slides as $num=>$slide) { ?>
							<div class="fp-slides-item">
								<div class="back-shadow"></div>
								<div class="fp-thumbnail">
									<?php if ($SMTheme->get('slider', 'showthumbnail')) { ?>
									<a href="<?php echo $slide['link']?>" title=""><img src="<?php echo $slide['img']?>" alt="<?php echo $slide['ttl']?>" /></a>
									<?php } ?>
								</div>
								<?php if ($SMTheme->get('slider', 'showtext')||$SMTheme->get('slider', 'showttl')) { ?>
								<div class="fp-content-wrap">
									<div class="fp-content">
									
										<?php if ($SMTheme->get('slider', 'showttl')) { ?>
										<h3 class="fp-title"><a href="<?php echo $slide['link']?>" title=""><?php echo $slide['ttl']?></a></h3>
										<?php } ?>
										
										<?php if ($SMTheme->get('slider', 'showtext')) { ?>
										<p><?php echo $slide['content']?></p>
										<?php } ?>
										
										<?php if ($SMTheme->get('slider', 'showhrefs')) { ?>
											<a class="fp-more" href="<?php echo $slide['link']?>"><?php echo $SMTheme->_('readmore');?></a>
										<?php } ?>
										
									</div>
								</div>
								<?php } ?>
							</div>
				<?php } ?>
			</div>
			
			<div class=" container fp-prev-next-wrap">			
				<a href="#fp-prev" class="fp-prev"></a>
				<a href="#fp-next" class="fp-next"></a>
			</div>			
			
	</div>
</div>