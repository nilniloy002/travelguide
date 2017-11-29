<?php
/**
 * The template for displaying a "No posts found" message
 *
 */
 global $SMTheme;
?>

<header class="page-header">
	<h1 class="page-title"><?php echo $SMTheme->_( 'nothingfound' ); ?></h1>
</header>

<div class="page-content">
	<?php get_search_form(); ?>
</div><!-- .page-content -->
