<?php
 

global $current_user; 
get_currentuserinfo();
 
 
?>

<?php echo __( 'Hello', 'hello' ); ?>, <?php print $current_user->user_login  ?>