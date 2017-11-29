<?php


    // color picker 

    function add_admin_iris_scripts( $hook ){
        // подключаем IRIS
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

    }
    add_action( 'admin_enqueue_scripts', 'add_admin_iris_scripts' );


    // установка файл пикера

    function wpalogin_admin_scripts() {

        if ( isset($_GET['page']) && $_GET['page'] == 'main-setting'){

            wp_enqueue_script('jquery');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_register_script('login-form-js', WPA_LOGIN_DIR_URL . 'js/file_chooser.js', array('jquery','media-upload','thickbox'));
            wp_enqueue_script('login-form-js');
        }
    }

    function wpalogin_admin_styles(){

        if (isset($_GET['page']) && $_GET['page'] == 'main-setting') {

            wp_enqueue_style('thickbox'); 
            wp_register_style('login-form-css', WPA_LOGIN_DIR_URL . 'css/admin.css', array('thickbox')); 
            wp_enqueue_style('login-form-css'); 
        }
    }


    add_action('admin_print_scripts', 'wpalogin_admin_scripts');
    add_action('admin_print_styles', 'wpalogin_admin_styles');






    // Hook for adding admin menus
    add_action('admin_menu', 'wpalogin_add_pages');

    // action function for above hook
    function wpalogin_add_pages() {

        // Add a new submenu under Options:
        add_options_page('Wpadm-login-title', 'Login Form', 'activate_plugins', 'main-setting', 'wpalogin_main_setting');

    } 

    $wpadm_login->checkPay();

    function wpalogin_main_setting() {


        global $wpadm_login; 

        $parts = $wpadm_login->getParts(); 
        $hidden_field_name = 'mt_submit_hidden'; 


        if( !empty($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) { 


            foreach($parts as $part) {
                if (isset($part['field'])) {
                    foreach($part['field'] as $field) {

                        if( ! empty($field['type']) && $field['type'] == 'title'){

                            continue;
                        }

                        if( empty( $field['save_function'] )){ 


                            if( ! isset($_POST[$field['name']] )){

                                continue; 
                            }

                            $value_for_update = $_POST[$field['name']];
                        }
                        else {

                            $value_for_update = $field['save_function']();

                        }


                        update_option( $field['name'], $value_for_update );
                    }
                }
            }


        ?>
        <div class="updated"><p><strong><?php _e('Settings was saved successfully', 'wpadm-login-setting-saving-success' ); ?></strong></p></div>
        <?php

        }

        // Now display the options editing screen

        echo '<div class="wrap">';


        // header
        $title_page = __( 'Login Form Main Settings', 'wpadm-login-setting-title' ) ;
        if (WPA_LOGIN_PRO) {
            login_form_pro::getStylesAndScripts();
            $title_page = __( 'Login Form Main Settings PRO', 'wpadm-login-setting-title' ) ;
            $title_page .= login_form_pro::updatePROHTML();
        }

        echo "<h1>" . $title_page . "</h1>";
    ?> 
    <?php if (!WPA_LOGIN_PRO) { ?>
        <div class="login-form-pro-buy">
            <div class="login-form-logo">
                <img src="<?php echo WPA_LOGIN_DIR_URL . 'img/login-form.png';?>" alt="<?php _e('Logo Custom Login Form');?>" title="<?php _e('Logo Custom Login Form');?>">
            </div>

            <div style="width:250px;float:left; padding:7px; border:3px solid #8484ff; background: #dffede; cursor: pointer; margin-top: 3px;margin-left:10px" onclick="window.open('https://wordpress.org/support/view/plugin-reviews/login-form?filter=5')">
                <div style="text-align: center; ">
                    <div style="font-size: 18px; font-weight: 500; line-height: 10px;"><?php _e('Please, leave your review!', 'login-form')?></div>
                    <div style="margin-top: 12px;"><img src="<?php echo WPA_LOGIN_DIR_URL . '/img/stars-5.png'?>" alt="<?php _e('5 star', 'login-form')?>" /></div>
                    <div style="font-size: 15px; line-height: 20px;">
                        <?php echo __(' Your <a href="javascript:void(0)">5-star review</a> support us, <br /> make this plugin much better!', 'login-form');?>
                    </div>
                </div>
            </div>

            <div class="login-form-pro-description">
                <div class="login-form-pro-description-title">
                    <?php _e('Use Professional version of "Login Form" plugin and get: ')?>
                </div>
                <div style="float:left;">
                    <ul class="login-form-pro-list">
                        <li>
                            <img src="<?php echo WPA_LOGIN_DIR_URL . 'img/ok.png';?>" alt="">
                            <span class=""><?php _e('More Design plugin settings', 'login-form');?></span>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <img src="<?php echo WPA_LOGIN_DIR_URL . 'img/ok.png';?>" alt="">
                            <span class=""><?php _e('More Security settings (IP blocking, Captcha, etc.)', 'login-form');?></span>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <img src="<?php echo WPA_LOGIN_DIR_URL . 'img/ok.png';?>" alt="">
                            <span class=""><?php _e('Priority support for PRO version', 'login-form');?></span>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <img src="<?php echo WPA_LOGIN_DIR_URL . 'img/ok.png';?>" alt="">
                            <span class=""><?php _e('One year free updates', 'login-form');?></span>
                            <div class="clear"></div>
                        </li>
                    </ul>
                </div>
                <div style="text-align: center; float: right; margin-top:53px; margin-left: 40px;"> 
                    <form id="login_form_pro" name="login_form_pro" method="post" action="<?php echo WPA_SERVER_URL?>api/">
                        <input type="hidden" name="site" value="<?php echo home_url(); ?>">
                        <input type="hidden" name="actApi" value="<?php echo 'proBackupPay'?>">
                        <input type="hidden" name="email" value="<?php echo get_option('admin_email'); ?>">
                        <input type="hidden" name="plugin" value="<?php echo 'login-form'?>">
                        <input type="hidden" name="success_url" value="<?php echo admin_url("admin.php?page=main-setting&pay=success"); ?>">
                        <input type="hidden" name="cancel_url" value="<?php echo admin_url("admin.php?page=main-setting&pay=cancel"); ?>">
                        <input class="button-buy" type="submit" value="<?php _e('Get PRO', 'login-form')?>">
                    </form>
                </div>
            </div>

            <div class="clear"></div>
        </div>
        <?php } ?>
    <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <div style="width:50%;float:left">
            <?php
                foreach($parts as $key => $part) { 
                    if (isset($part['title']) && isset($part['field'])) {

                    ?>
                    <div class="wpadm-login-parts" part-id="<?php echo $key?>">
                        <div class="wpadm-login-parts-title">
                            <?php _e($part['title'], 'login-form');?>
                            <span class="button-part dashicons <?php echo isset($part['default_view']) && $part['default_view'] === true ? 'dashicons-arrow-up' : 'dashicons-arrow-down'; ?>"></span>
                        </div>
                        <div class="wpadm-login-parts-body" style="display: <?php echo isset($part['default_view']) && $part['default_view'] === true ? 'block' : 'none'; ?>;">
                            <table class="form-table">
                                <?php
                                    foreach($part['field'] as $field) {
                                        $attr = '';

                                        if ( ! empty($field['attr'])) {

                                            $attr = $field['attr'];
                                        }

                                    ?>

                                    <tr valign="top" id="<?php  if ( !empty($field['name'])) print $field['name'].'-field-tr'; ?>" <?php echo isset($field['tr_attr']) ? $field['tr_attr'] : '' ?> >
                                        <th valign="top" style="vertical-align: top" <?php echo !empty( $field['type']) && $field['type'] == 'title' ? 'colspan="2"' : ''?> >
                                            <?php

                                                if ( !empty( $field['type']) && $field['type'] == 'title' ) {

                                                    print '<h3>' . __( $field['title'], 'wpadmm-setting-' . $field['name'] ) .'</h3>';
                                                }
                                                else {

                                                    print __( $field['title'], 'wpadmm-setting-' . $field['name'] );

                                                } 

                                            ?>
                                        </th>
                                        <?php if ( !empty( $field['type']) && $field['type'] != 'title' || empty( $field['type']) ) { ?>
                                            <td  <?php echo isset($field['td_attr']) ? $field['td_attr'] : 'valign="top" class="field_input_box"' ?> > 

                                                <?php

                                                    if ( ! empty($field['name'])){

                                                        if( isset($field['default_value'])) {
                                                            $value =  get_option( $field['name'], $field['default_value'] ); 
                                                        }
                                                        else {
                                                            $value =  get_option( $field['name'] ); 
                                                        }

                                                    } 

                                                    if ( empty( $field['type']) or $field['type'] == 'text' ) {


                                                    ?>
                                                    <input type="text" name="<?=$field['name']?>" value="<?php echo $value ?>" <?php print $attr?> />
                                                    <?php
                                                        echo isset($field['desc_value']) ? $field['desc_value'] : '';
                                                    } 
                                                    else if($field['type'] == 'file'){

                                                        ?>
                                                        <input type="text"  name="<?=$field['name']?>" value="<?php echo $value ?>" />
                                                        <input type="button" class="file_chooser button" value="<?=__( 'Upload file', 'Upload-file')?>" />
                                                        <input type="button" class="prev_reset button" value="<?=__( 'Reset', 'reset-file')?>" /> 
                                                        <?php
                                                        }
                                                        else if($field['type'] == 'image'){

                                                            ?>
                                                            <input type="text"  name="<?=$field['name']?>" value="<?php echo $value ?>" />
                                                            <input type="button" class="image_chooser button" value="<?=__( 'Upload file', 'Upload-file')?>" />
                                                            <input type="button" class="prev_reset button" value="<?=__( 'Reset', 'reset-file')?>" /> 
                                                            <?php
                                                            }
                                                            else if ($field['type'] == 'select') {

                                                                ?>
                                                                <select name="<?=$field['name']?>">

                                                                    <?php

                                                                        if ( ! empty($field['values']) ) {

                                                                            foreach($field['values'] as $f_key => $f_value ){

                                                                                print '<option value="'.$f_key.'"';

                                                                                if($f_key == $value){

                                                                                    print ' selected';
                                                                                }

                                                                                print '>'. __( $f_value, 'wpadmm-setting-' . $field['name'] . '-select-option-' . $f_key ) .'</option>';
                                                                        }
                                                                    }


                                                            ?>


                                                        </select>
                                                        <?php
                                                        }
                                                        else if ($field['type'] == 'radio') {


                                                                if ( ! empty($field['values']) ) {

                                                                    foreach($field['values'] as $f_key => $f_value ){



                                                                        print '<input type="radio" name="'.$field['name'].'" value="'.$f_key .'" id="' . $field['name'] . '_' . $f_key . '" ' . (isset($field['attr']) ? $field['attr'] : '');
                                                                        if($f_key == $value){

                                                                            print ' checked';
                                                                        }

                                                                        print ' />';

                                                                    print '<label for="' . $field['name'] . '_' . $f_key . '">'. __( $f_value, 'wpadmm-setting-' . $field['name'] . '-select-option-' . $f_key ) .'</label>';

                                                                    print '<br />';

                                                                }
                                                            } 
                                                    }
                                                    else if($field['type'] == 'custom'){


                                                            if( $field['show_function'] && function_exists($field['show_function'])){


                                                                $field['show_function']($field, $value); 
                                                            }


                                                    }  

                                                    if ( ! empty( $field['description']) ) {

                                                        print '<p class="description">' . __( $field['description'], 'wpadmm-setting-' . $field['name'] . '-description' ) . '</p>';
                                                    }

                                                ?>
                                            </td>
                                            <?php } ?>
                                    </tr>
                                    <?php 
                                    } 
                                ?>
                            </table>
                        </div>
                    </div>
                    <?php 

                    }

                }

            ?> 


            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

            <hr />

            <p class="submit">
                <input type="submit" name="Submit" class="button button-primary button-large" value="<?php _e('Save Settings', 'wpadmm-setting-save-button' ) ?>" />
            </p>
        </div>


        <?php

            // preview

            $preview_style = 'width:300px';

            if( get_option('form-orientation') == 'horizontal' ){

                $preview_style = 'width:640px;';
            }

        ?>


        <div style="width:30%;float:left; margin-left: 30px; margin-top: 10px; position: relative; ">
            <div style="">
                <div id="test"></div>
                <div style="<?=$preview_style?>;float:left; padding:50px; margin-bottom:20px; border:1px solid black; background-color: white; ">

                    <h3><?php _e('Preview', 'wpadmm-form-review-title' )?></h3>

                    <?php print $wpadm_login->form(true) ?>

                </div>
                <input type="submit" class="button button-primary button-large" name="Submit" value="<?php _e('Save Settings', 'wpadmm-setting-save-button' ) ?>" />
            </div>
        </div>

    </form>
    </div>

    <style>
        /*#form-no-password-is-activate-field-tr .field_input_box {
        border:1px solid;
        border-bottom: 0;

        }
        #form-no-password-user-role-field-tr .field_input_box {
        border:1px solid;
        border-top:0;
        } */
        .nopadding {
            padding:0 !important;
        }
    </style>

    <?php


    require_once  WPA_LOGIN_DIR .   '/include/admin/file_chooser.php';
}




function wpadm_login_add_admin_media_chooser( $hook ){

    wp_enqueue_media( );

}

function wpadm_login_remove_media_tab($tabs) {
    unset($tabs['type']);
    unset($tabs['type_url']);
    unset($tabs['gallery']);
    unset($tabs['library']); 
    return $tabs;
}
add_filter('media_upload_tabs','wpadm_login_remove_media_tab', 99);

add_action( 'admin_enqueue_scripts', 'wpadm_login_add_admin_media_chooser' );

$_default_tabs = array(
		'type' => __('From Computer'), // handler action suffix => tab text
		'type_url' => __('From URL'),
		'gallery' => __('Gallery'),
		'library' => __('Media Library')
    );

 
# apply_filters( 'media_upload_tabs', $_default_tabs );