<?php



    function short_code_show(){

    ?>
    <input type="text" onclick="this.select();" value="[wpadm-login]">
    <br/>  
    <p class="description">
    <?php
        print __('Please, copy and past this short code in any Post or Page of your website to show the WordPress Login Form on it.', 'wpadm-login-short-code-helper' );  

        print '</p>';
    }

    function form_no_password_enable ($field, $value){


    ?>
    <div>
        <input type="radio" name="form-no-password-is-activate" id="form-no-password-is-activate_no" value="no" <?php if($value == 'no') print ' checked'; ?> >
        <label for="form-no-password-is-activate_no">
            <?php _e('Disabled'); ?> 
        </label>
       &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="form-no-password-is-activate" id="form-no-password-is-activate_yes" value="yes" <?php if($value == 'yes') print ' checked'; ?>>
        <label for="form-no-password-is-activate_yes">
            <?php _e('Enabled'); ?> 
        </label>
        <br> 

    </div>
    <script>

        jQuery(document).ready(function(){

            function change_role_box(){

                var value =  jQuery('input[name="form-no-password-is-activate"]:checked').val(); 

                if( value === 'yes' ) {
                    jQuery("#form-no-password-user-role-field-tr").removeClass('display-none').addClass('display-tr');
                    jQuery("#form-no-password-days_num_for_clear-field-tr").removeClass('display-none').addClass('display-tr');
                }
                else {
                    jQuery("#form-no-password-user-role-field-tr").removeClass('display-tr').addClass('display-none');
                    jQuery("#form-no-password-days_num_for_clear-field-tr").removeClass('display-tr').addClass('display-none');
                }
            }

            jQuery('input[name="form-no-password-is-activate"]').change(function(){

                change_role_box();
            })

            change_role_box();
        })
    </script>

    <?php
    }

    function form_no_password_user_select_show($field, $value){

        if (!function_exists('wp_roles')) {
            global $wp_roles;

            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
        } else {
            $wp_roles = wp_roles();
        }

        krsort( $wp_roles->roles );

        print '<select name="'.$field['name'].'">';



        foreach($wp_roles->roles as $role_name => $details  ){


            if( $role_name == $value ){

                $checked = ' selected'; 
            } 
            else {

                $checked = '';
            }

        ?>
        <option value="<?php print $role_name?>" <?php print $checked ?>><?php print translate_user_role($details['name'])?></option> 
        <?php

        }

        print '</select>';

    }

    function form_role_everyrole_url_show ($field, $value){

        if($value){
            $values = unserialize($value);
        }
        else {
            $values = array('');
        }

        if (!function_exists('wp_roles')) {
            global $wp_roles;

            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
        } else {
            $wp_roles = wp_roles();
        }



        foreach($wp_roles->roles as $role_name => $details  ){

            $url = '';

            if( in_array( $role_name, array_keys( $values )) && isset($values[$role_name] ) ){

                $url = $values[$role_name]; 
            } 

        ?>
        <div class="form_role_role_url_show-box">

            <?php print translate_user_role($details['name'])?> <br/>

            <input type="text" name="<?php print $field['name']?>[<?php print $role_name?>]" value="<?php echo $url ?>" placeholder="http://" />

            <br/>
            <br/>
        </div>
        <?php

        }

    }

    function form_role_everyrole_url_save(){


        if( ! empty( $_POST['form-role-everyrole-url'] ) ){

            if( is_array($_POST['form-role-everyrole-url'])) {

                return serialize($_POST['form-role-everyrole-url']);
            }
        }
        else {

            return '';
        }

    }

    function form_stealth_random_show($field, $value){


        if($value){
            $values = unserialize($value);
        }
        else {
            $values = array('');
        }

        $style = ' style="display:none"';

        foreach($values as $row){

        ?>
        <div class="form_stealth_random-box">
            <input type="text" name="<?=$field['name']?>[]" value="<?php echo $row ?>" /><input type="button" <?=$style?> class="button form_stealth_random-del" value="-"/><br/>
        </div>
        <?php

            $style = '';
        }

    ?>
    <input type="button" class="button form_stealth_random-add" value="+"/> <br/>


    <script>
        jQuery(document).ready(function (){

            jQuery(".form_stealth_random-add").click(function(){ 

                jQuery(".form_stealth_random-box:last").clone().insertBefore( ".form_stealth_random-box:last" );
                jQuery(".form_stealth_random-box:last input[type='text']").val('');
                jQuery(".form_stealth_random-box:last input").show();

            });

            jQuery(".form_stealth_random-del").live ('click', function(){  

                jQuery(this).parent().remove();
            });

        })
    </script>
    <?php

}

function form_stealth_random_save(){

    if( ! empty( $_POST['form-stealth-random-words'] ) ){

        if( is_array($_POST['form-stealth-random-words'])) {

            return serialize($_POST['form-stealth-random-words']);
        }
    }
    else {

        return '';
    }

}