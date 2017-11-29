
<div class="form_style" >

    <?php

        if($logo_image){
            print '<img src="'.$logo_image.'"><br clear="all">'; 
        }
    ?>

    <form class="form " id="wpadm-login-form" method="post" action="<?php print get_permalink(); ?>" id="loginform">


        <div>
            <input class="input_style" value=""  type="text" name="log" placeholder="<?php echo __( 'Username', '' ); ?>">
        </div>

        <div class="password-input-box">
            <input class="input_style  password-input"  type="password" name="pwd" placeholder="<?php echo __( 'Password', '' ); ?>">
        </div>

        <?php if (WPA_LOGIN_PRO) {
            login_form_pro::getHTMLCaptcha(true);
        }?>

        <input  class="button button-primary button-large submit_style" type="submit" value="<?php echo __( 'Log in', '' ); ?>" name="submit">
        <input type="hidden" name="action" value="<?=$action_value?>">

        <div class="">
            <input type="checkbox" name="rememberme" value="forever">
            <label><?php echo __( 'Remember?', '' ); ?></label>
        </div>


    </form>
    <style> 
        .form_style input {
            margin:10px 5px !important;  
        }
        .form_style .input_style, .form_style .submit_style { 
            width:272px !important;
        }


        <?php print $style; ?>
    </style>
    </div>
    