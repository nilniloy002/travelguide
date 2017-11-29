<?php 
	global $SMTheme;	
?>

		<?php 	get_header(); ?>
		
		<h1 class='page-title'><?php echo sprintf($SMTheme->_('searchresults'),get_search_query()); ?></h1>
		
		<?php get_template_part('theloop'); ?>
		
		<?php	get_footer(); ?>