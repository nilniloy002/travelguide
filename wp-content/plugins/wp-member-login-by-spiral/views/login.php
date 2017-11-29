<?php
/**
 * Represents the view for the login form.
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */
?>
<div class="login" id="spiral-member-login<?php $template->the_template_num(); ?>">
	<?php $template->the_errors(); ?>
	<form name="loginform" id="loginform<?php $template->the_template_num(); ?>" action="<?php $template->the_auth_form_url(); ?>" method="post">
		<p>
			<label for="user_login<?php $template->the_template_num(); ?>"><?php _e( 'Username' ); ?></label>
			<input type="text" name="login_id" id="user_login<?php $template->the_template_num(); ?>" class="input" value="<?php $template->the_posted_value( 'login_id' ); ?>" size="20" />
		</p>
		<p>
			<label for="user_pass<?php $template->the_template_num(); ?>"><?php _e( 'Password' ); ?></label>
			<input type="password" name="password" id="user_pass<?php $template->the_template_num(); ?>" class="input" value="" size="20" />
		</p>

<!--
		<p class="forgetmenot">
			<input name="rememberme" type="checkbox" id="rememberme<?php $template->the_template_num(); ?>" value="forever" />
			<label for="rememberme<?php $template->the_template_num(); ?>"><?php esc_attr_e( 'Remember Me' ); ?></label>
		</p>
-->
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_template_num(); ?>" value="<?php esc_attr_e( 'Log In' ); ?>" />
			<input type="hidden" name="template_num" value="<?php $template->the_template_num(); ?>" />
			<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url(); ?>" />
			<input type="hidden" name="action" value="login" />
			<input type="hidden" name="detect" value="判定" />
		</p>
	</form>
	<?php $template->the_action_links(); ?>
</div>
