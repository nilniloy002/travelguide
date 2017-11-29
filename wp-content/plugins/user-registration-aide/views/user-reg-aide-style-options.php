<?php

/**
 * User Registration Aide Pro - Stylesheet Settings Customize Plugin Styles
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-pro-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

class URA_STYLESHEET{

	public static $instance;
	
	public function __construct() {
		$this->URA_STYLESHEET(); 
	}
	
	function URA_STYLESHEET(){
		
		self::$instance = $this;
		
	}
	
	/* returns array of border collapse styles
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return $collapse array of all table border collapse styles and descriptions
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function ura_border_collapse_array(){
		$collapse = array(
			"separate"	=>	__( 'Borders are detached (border-spacing and empty-cells properties will not be ignored). This is default', 'csds_userRegAide' ),
			"collapse"	=>	__( 'Borders are collapsed into a single border when possible (border-spacing and empty-cells properties will be ignored)', 'csds_userRegAide' ),
			"initial"	=>	__( 'Sets this property to its default value', 'csds_userRegAide' ),
			"inherit"	=>	__( 'Inherits this property from its parent element', 'csds_userRegAide' )
		);
		
		return $collapse;			
			
	}
	
	/* returns array of border styles
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return $border_styles array of all table border styles and descriptions
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function ura_border_style_array(){
		$border_styles = array(
			"none"			=>	__( 'Default value. Specifies no border', 'csds_userRegAide' ),
			"hidden"		=>	__( 'The same as none, except in border conflict resolution for table elements', 'csds_userRegAide' ),
			"dotted"		=>	__( 'Specifies a dotted border', 'csds_userRegAide' ),
			"dashed"		=>	__( 'Specifies a dashed border', 'csds_userRegAide' ),
			"solid"			=>	__( 'Specifies a solid border', 'csds_userRegAide' ),
			"double"		=>	__( 'Specifies a double border', 'csds_userRegAide' ),	
			"groove"		=>	__( 'Specifies a 3D grooved border. The effect depends on the border-color value', 'csds_userRegAide' ),
			"ridge"			=>	__( 'Specifies a 3D ridged border. The effect depends on the border-color value', 'csds_userRegAide' ),
			"inset"			=>	__( 'Specifies a 3D inset border. The effect depends on the border-color value', 'csds_userRegAide' ),
			"outset"		=>	__( 'Specifies a 3D outset border. The effect depends on the border-color value', 'csds_userRegAide' ),
			"initial"		=>	__( 'Sets this property to its default value', 'csds_userRegAide' ),
			"inherit"		=>	__( 'Inherits this property from its parent element', 'csds_userRegAide' )			
		);
		return $border_styles;
	}
	
	/* Handles Displays settings and options for plugin style settings updates
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function settings_options_page_view(){
		
		if ( isset( $_POST['ura_update_style'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$options = get_option('csds_userRegAide_Options');
				$options['border-style'] = sanitize_text_field( $_POST['ura_tbl_border_style'] );
				$options['border-collapse'] = sanitize_text_field( $_POST['ura_tbl_border_collapse'] );
				$options['tbl_border-width'] = sanitize_text_field( $_POST['ura_tbl_border_width'] );
				$options['border-color'] = sanitize_text_field( $_POST['ura_border_color_picker'] );
				$options['tbl_background_color'] = sanitize_text_field( $_POST['ura_tbl_bckgrd_color_picker'] );
				$options['tbl_color'] = sanitize_text_field( $_POST['ura_tbl_color_picker'] );
				$options['border-spacing'] = sanitize_text_field( $_POST['ura_tbl_border_spacing'] );
				$options['div_stuffbox_bckgrd_color'] = sanitize_text_field( $_POST['ura_div_color_picker'] );
				$options['tbl_padding']  = sanitize_text_field( $_POST['ura_tbl_padding'] );
				update_option("csds_userRegAide_Options", $options);
			}
		}
		do_action( 'style_view' );
	}
	
	/* Handles Display settings and options for plugin style settings and allows user to change selected style settings
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function custom_style_view(){
		$options = get_option('csds_userRegAide_Options');
		$span = array( 'regForm', __( 'Choose Custom URA Style Options Here:', 'csds_userRegAide' ), 'csds_userRegAide' );
		do_action( 'start_mini_wrap',  $span );
		?>
		<table class="style">
			<tr>
				<th colspan="4" class="style">
				<?php
				echo __( 'Choose Custom CSS Table Settings Here: ', 'csds_userRegAide' );
				?>
				</th>
			</tr>
			<tr>
				<td class="style">
				<?php
				
				$border_styles = $this->ura_border_style_array();
				$collapsed = $this->ura_border_collapse_array();
				$border_color = $options['border-color'];
				$collapse = $options['border-collapse'];
				$bckgrd_color = $options['tbl_background_color'];
				$tbl_color = $options['tbl_color'];
				$border_width = $options['tbl_border-width'];
				$border_style = $options['border-style'];
				$border_spacing = $options['border-spacing'];
				$div_color = $options['div_stuffbox_bckgrd_color'];
				$padding = $options['tbl_padding'];
				?>
				<label for="ura_tbl_border_style"> <?php _e( 'Select Table Border Style Here: ', 'csds_userRegAide' ); ?> </label>
				<select name="ura_tbl_border_style" id="ura_tbl_border_style" title=" <?php _e('Select Table Border Style Here', 'csds_userRegAide'); ?>" size="8" multiple style="height:50px">
				<?php
				foreach($border_styles as $style	=> $desc){ 
					if( $style == $border_style){
						$selected = "selected=\"selected\"";
					}else{
						$selected = NULL;
					}
					echo "<option title=\"$desc\" value=\"$style\" $selected >$style</option>";
				}
				?>
				</select>
				</td>
				<td class="style">
				<label for="ura_tbl_border_collapse"> <?php _e( 'Select Table Border Collapse Style Here: ', 'csds_userRegAide' ); ?> </label>
				<select name="ura_tbl_border_collapse" id="ura_tbl_border_collapse" title=" <?php _e('Select Table Border Collapse Style Here', 'csds_userRegAide'); ?>" size="8" multiple style="height:50px">
				<?php
				foreach($collapsed as $ckey	=>	$cdesc){
					if( $ckey == $collapse){
						$selected = "selected=\"selected\"";
					}else{
						$selected = NULL;
					}
					echo "<option title=\"$cdesc\" value=\"$ckey\" $selected >$ckey</option>";
				}
				?>
				</select>
				</td>
				<td class="style">
				<label for="ura_tbl_border_width"> <?php _e( 'Select Table Border Width Here: ', 'csds_userRegAide' ); ?> </label>
				<select name="ura_tbl_border_width" id="ura_tbl_border_width" title=" <?php _e( 'Select Table Border Width Here', 'csds_userRegAide' ); ?>" size="8" multiple style="height:50px">
				<?php 
				for( $wcnt = 1; $wcnt <= 10; $wcnt += 1){
					if( $wcnt == $border_width){
						$selected = "selected=\"selected\"";
					}else{
						$selected = NULL;
					}
					$wcnt = $wcnt.'px';
					echo "<option title=\"$wcnt\" value=\"$wcnt\" $selected >$wcnt</option>";
				}
				?>
				</select>
				</td>
				<td class="style">
				<label for="ura_tbl_padding"> <?php _e( 'Select Table Padding Here: ', 'csds_userRegAide' ); ?> </label>
				<select name="ura_tbl_padding" id="ura_tbl_padding" title=" <?php _e( 'Select Table Padding Here', 'csds_userRegAide' ); ?>" size="8" multiple style="height:50px">
				<?php 
				for( $wcnt = 1; $wcnt <= 10; $wcnt += 1){
					if( $wcnt == $padding){
						$selected = "selected=\"selected\"";
					}else{
						$selected = NULL;
					}
					$wcnt = $wcnt.'px';
					echo "<option title=\"$wcnt\" value=\"$wcnt\" $selected >$wcnt</option>";
				}
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td class="style">
				
				<script type='text/javascript'>
					jQuery(document).ready(function($) {
						$('#ura_border_color_picker').wpColorPicker();
					});
				</script>
				
				<label for="ura_border_color_picker"> <?php _e( 'Select Table Border Color Here: ', 'csds_userRegAide' ); ?> </label>
				<br/>
				<input type="text" id="ura_border_color_picker" name="ura_border_color_picker" value=" <?php echo $border_color; ?> " />
				</td>	
				<td class="style">
				
				<script type='text/javascript'>
					jQuery(document).ready(function($) {
						$('#ura_tbl_bckgrd_color_picker').wpColorPicker();
					});
				</script>
				
				<label for="ura_tbl_bckgrd_color_picker"> <?php _e( 'Select Table Background Color Here: ', 'csds_userRegAide' ); ?> </label>
				<br/>
				<input type="text" id="ura_tbl_bckgrd_color_picker" name="ura_tbl_bckgrd_color_picker" value=" <?php echo $bckgrd_color; ?> " />
				</td>
				<td class="style">
				
				<script type='text/javascript'>
					jQuery(document).ready(function($) {
						$('#ura_tbl_color_picker').wpColorPicker();
					});
				</script>
				
				<label for="ura_tbl_color_picker"> <?php _e( 'Select Table Text Color Here: ', 'csds_userRegAide' ); ?> </label>
				<br/>
				<input type="text" id="ura_tbl_color_picker" name="ura_tbl_color_picker" value=" <?php echo $tbl_color; ?> " />
				</td>
				<td class="style">
				
				<script type='text/javascript'>
					jQuery(document).ready(function($) {
						$('#ura_div_color_picker').wpColorPicker();
					});
				</script>
				
				<label for="ura_div_color_picker"> <?php _e( 'Select Outside Table Background Color Here: ', 'csds_userRegAide' ); ?> </label>
				<br/>
				<input type="text" id="ura_div_color_picker" name="ura_div_color_picker" value=" <?php echo $div_color; ?> " />
				</td>
			</tr>
			<tr>
				<td class="style">
				<label for="ura_tbl_border_spacing"> <?php _e( 'Select Table Border Spacing Here: ', 'csds_userRegAide' ); ?> </label>
				<select name="ura_tbl_border_spacing" id="ura_tbl_border_spacing" title=" <?php _e( 'Select Table Border Spacing Here', 'csds_userRegAide' ); ?>" size="8" multiple style="height:50px">
				<?php 
				for( $spcnt = 1; $spcnt <= 10; $spcnt += 1){
					if( $spcnt == $border_spacing){
						$selected = "selected=\"selected\"";
					}else{
						$selected = NULL;
					}
					$spcnt = $spcnt.'px';
					echo "<option title=\"$spcnt\" value=\"$spcnt\" $selected >$spcnt</option>";
				}
				?>
				</select>
				</td>
				
				<td colspan="3" class="style">
					<div class="submit">
					<input type="submit" class="button-primary" name="ura_update_style" value="<?php _e('Update Plugin Table Style Settings', 'csds_userRegAide');?>"/>
					</div>
				</td>
			</tr>
		</table>
		
		<?php 
		do_action( 'end_mini_wrap' );
	}


}
?>