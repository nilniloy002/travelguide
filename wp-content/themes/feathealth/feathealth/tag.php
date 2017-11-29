<?php 
	
	global $SMTheme;
	
?>
		
		<?php 	get_header(); ?>
		
		<h1 class="page-title"><?php printf( $SMTheme->_( 'tagarchive' ), single_tag_title( '', false ) ); ?></h1>
			
		<?php get_template_part('theloop'); ?>
         
		<?php get_footer(); ?>