<?php
/**
 * Represents the view for the user info.
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */
?>
<div class="login" id="spiral-member-login<?php $template->the_template_num(); ?>">
	<?php $template->the_user_name(); ?>
	<?php $template->the_user_links(); ?>
	<?php do_action( 'sml_user_info' ); ?>
</div>
