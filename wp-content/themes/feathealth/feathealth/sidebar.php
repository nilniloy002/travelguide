<?php 
	global $SMTheme, $post;
	
			if ( !in_array($SMTheme->layout, array(1,3,6) ) )
				get_sidebar('right'); 
			if ( !in_array($SMTheme->layout, array(1,2) ) )
				get_sidebar('left');
			if ( $SMTheme->layout == 6 ) {
				get_sidebar('right'); 
			}
?>

		