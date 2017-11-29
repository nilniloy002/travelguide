<?php

    wp_register_style( 'horizontal-form', WPA_LOGIN_DIR_URL . "css/horizontal-form.css" );
    wp_enqueue_style( 'horizontal-form' );


?>


<div class="form_style horizontal-form" >		

    <?php

        if($logo_image){
            print '<img src="'.$logo_image.'"><br clear="all">'; 
        }
    ?>

    <form class="form" id="wpadm-login-form" method="post" action="<?php print get_permalink(); ?>">

        <input class="input_style" value="" type="text" name="log" placeholder="<?php echo __( 'Username', '' ); ?>">

        <input class="input_style password-input"  type="password" name="pwd" placeholder="<?php echo __( 'Password', '' ); ?>">
        <?php if (WPA_LOGIN_PRO) {
            login_form_pro::getHTMLCaptcha($demo);
        }?>
        <input  class="button button-primary button-large submit_style" type="submit" value="<?php echo __( 'Log in', '' ); ?>" name="submit">
        <div class="forgetmenot"> 
            <input type="hidden" name="action" value="<?=$action_value?>">

            <label><input type="checkbox" name="rememberme" value="forever"><?php echo __( 'Remember?', '' ); ?></label>
        </div>


    </form>
    <style> 
        <?php print $style; ?>
    </style>

</div>
