<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   Spiral_Member_Login
 * @author    PIPED BITS Co.,Ltd.
 */
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
		<?php
			settings_fields( $this->options_key );
			do_settings_sections( $this->options_key );
			submit_button();
		?>
	</form>

</div>
