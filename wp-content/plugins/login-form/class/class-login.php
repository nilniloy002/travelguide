<?php

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    class wpadm_login {

        var $preview = false;

        var $fields = array (
        array( 
        'title' => 'Design settings',
        'default_view' => false, 
        'field' => array( 
        array(

        'name' => 'form-short_code_show',
        'title' => 'Short code<br/><br/>', 
        'type' => 'custom',
        'show_function' => 'short_code_show',
        ),

        array(

        'name' => 'form-orientation',
        'title' => 'Login Form orientation', 
        'type' => 'radio',
        'default_value' => 'vertical', 
        'values' => array(
        'horizontal' => 'Horizontal',
        'vertical' => 'Vertical'
        )),


        array(
        'name' => 'form-border-size',
        'title' => 'Login Form border size',
        'default_value' => '1',
        'description' => 'values: 1-10'),

        array(
        'name' => 'form-border-radius',
        'title' => 'Login Form radius of border corners',
        'default_value' => '10',
        'description' => 'values: 1-99'),

        array(
        'name' => 'form-border-color',
        'title' => 'Login Form border color',
        'default_value' => '#578fbf',
        'description' => 'RGB code (example: #C0FFEE)',
        'attr' => 'class="colorpicker" object=".form_style" style_name="border-color" '),

        array(
        'name' => 'form-shadow',
        'title' => 'Login Form shadow',
        'default_value' => 'no',
        'type' => 'radio',
        'values' => array(
        'no' => 'Disabled',
        'yes' => 'Enabled'
        )
        ),    
        array(
        'name' => 'form-background-opacity',
        'title' => 'Login Form background transparency',
        'type' => 'select',
        'default_value' => '0',
        'values' => array(
        '0' => '0%',
        '10' => '10%',
        '20' => '20%',
        '30' => '30%',
        '40' => '40%',
        '50' => '50%',
        '60' => '60%',
        '70' => '70%',
        '80' => '80%',
        '90' => '90%',
        '90' => '90%',  

        )
        ),

        array(
        'name' => 'form-background-image',
        'title' => 'Login Form background image',
        'type' => 'image'),

        array(
        'name' => 'form-background-color',
        'title' => 'Login Form background color',
        'default_value' => '#70d659',
        'description' => 'RGB code (example: #C0FFEE)',
        'attr' => 'class="colorpicker" object=".form_style" style_name="background-color"'),

        array(
        'name' => 'form-logo-image',
        'type' => 'image',
        'title' => 'Login Form logo image'),

        array(
        'name' => 'form-input-color',
        'title' => 'Login Form input lines color',
        'default_value' => '#ededb8',
        'description' => 'RGB code (example: #C0FFEE)',
        'attr' => 'class="colorpicker" object=".input_style" style_name="background-color"'),

        array(
        'name' => 'form-submit-color',
        'title' => 'Login Form button color',
        'default_value' => '#006799', 
        'description' => 'RGB code (example: #C0FFEE)',
        'attr' => 'class="colorpicker"  object=".submit_style" style_name="background-color"'),
        )
        ),

        /***********
        * access no password
        */
        array(
        'title' => 'Access "no password"',
        'field' => array(
        array(

        'name' => 'form-no-password-is-activate',
        'title' => 'Login Form "no password" access activation', 
        'type' => 'custom',
        'default_value' => 'no',
        'show_function' => 'form_no_password_enable'),

        array(

        'name' => 'form-no-password-user-role',
        'title' => 'User without password will be logged using this role', 
        'type' => 'custom',
        'show_function' => 'form_no_password_user_select_show',
        'tr_attr' =>'class="display-none"',
        ),

        array(

        'name' => 'form-no-password-days_num_for_clear',
        'title' => 'How much time the temporary users will be saved', 
        'type' => 'text',
        'description' => 'Due authorization process the new temporary user will be created. After this time was expired, the temporary user will be deleted automatically.',
        'default_value' => 1,
        'desc_value' => 'days',
        'tr_attr' =>'class="display-none"',
        ),

        ),
        ),

        #################
        #
        #  Stealth login
        # 
        #################
        array(
        'title' => 'Security settings',
        'field' => array(
        array(

        'title' => 'Stealth login (Login without password)',
        'name' => 'Stealth-login-title',
        'type' => 'title'),

        array(

        'name' => 'form-stealth-hide-wplogin',
        'title' => 'Hide /wp-login.php', 
        'type' => 'radio',
        'default_value' => 0,
        'values' => array(
        '0' => 'No',
        '1' => 'Yes')
        ),

        array(

        'name' => 'form-stealth-incorect-login-action',
        'title' => 'What to do, if username or password was typed incorrectly?', 
        'type' => 'radio',
        'default_value' => 'no',
        'values' => array(
        'no' => 'Shutdown',
        'redirect' => 'Make redirection',
        'show_random_words' => 'Show random words')
        ),

        array(

        'name' => 'form-stealth-incorect-login-redirect-url',
        'title' => 'URL redirections', 
        'type' => 'text'),

        array(

        'name' => 'form-stealth-key',
        'title' => 'Use GET-parameter to show the login form', 
        'type' => 'text',
        'description' => 'If it\'s filled, then by logging without GET parameter the Login Form will not showing to user. Use some string as GET parameter. Example: showmeform'),


        array(

        'name' => 'form-stealth-random-words',
        'title' => 'Random words for stealth login', 
        'type' => 'custom',
        'description' => '',
        'decode' => 'serialize', 
        'show_function' => 'form_stealth_random_show',
        'save_function' => 'form_stealth_random_save'
        ),


        #################
        #
        #  Role redirect
        # 
        #################   


        array(

        'title' => 'User role redirections',
        'name' => 'role-redirect-title',
        'type' => 'title'),

        array(

        'name' => 'form-role-enable',
        'title' => 'Login Form role activation', 
        'type' => 'radio',
        'default_value' => 'no',
        'values' => array(
        'no' => 'Disabled',
        'yes' => 'Enabled')
        ),

        array(

        'name' => 'form-role-main-url',
        'title' => 'Main URL for redirection', 
        'type' => 'text', 
        ),

        array(

        'name' => 'form-role-everyrole-url',
        'title' => 'Redirection URL for every user role', 
        'type' => 'custom',
        'show_function' => 'form_role_everyrole_url_show',
        'save_function' => 'form_role_everyrole_url_save'
        ),

        )
        )

        ); 


        function can_show(){

            $key = get_option('form-stealth-key');  


            if ( empty($key) ){

                return true;
            }



            if ( isset($_GET[$key]) ) {

                return true;
            }
            else {

                return false;
            }

        }
        function initSession()
        {
            if (@session_id() == '') {
                @session_start();
            }
        }

        function notice()
        {
            if (isset($_GET['page']) && $_GET['page'] == 'main-setting') {
                $this->initSession();                                               
                if(isset($_SESSION['clf_notice']) && !empty($_SESSION['clf_notice'])) {
                    echo $_SESSION['clf_notice'];
                    unset($_SESSION['clf_notice']);
                }
            }

        }

        function checkPay()
        {
            if (isset($_GET['pay']) && !file_exists(WPA_LOGIN_DIR . '/class/class-pro.php')) {
                $this->initSession();
                switch($_GET['pay']) {
                    case 'cancel' :
                        $_SESSION['clf_notice'] = $this->getNoticeMessage('Checkout was canceled', 'error');;
                        break;
                    case 'success' :
                        $check = $this->updatePlugin();
                        if (file_exists(WPA_LOGIN_DIR . '/class/class-pro.php') && filesize(WPA_LOGIN_DIR . '/class/class-pro.php') > 0 && $check === true) {
                            $_SESSION['clf_notice'] = $this->getNoticeMessage('The "Custom Login Form PRO" version was successfully installed to your website. Refresh page for the entry into force of the settings.');
                        } else {
                            $_SESSION['clf_notice'] = $this->getNoticeMessage('The "Custom Login Form PRO" version was create error, please try again later.', 'error');
                        }
                        break;
                }
            }
        }
        function updatePlugin($version = '')
        {
            $plugin_name = 'login-form';
            if (!empty($version)) {
                $plugin_version = $version;
            } else {
                if (!function_exists('get_plugins')) {
                    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                }
                $plugin_info = get_plugins("/$plugin_name");
                $plugin_version = (isset($plugin_info[$plugin_name . '.php']['Version']) ? $plugin_info[$plugin_name . '.php']['Version'] : '');
            }
            if (!function_exists('wp_remote_post')) {
                include ABSPATH . WPINC . '/http.php';
            }
            $key = get_option('login-form-pro-key', '');
            $data_server = wp_remote_post( WPA_SERVER_URL . 'api/', array(
            'body' => 
            array(
            'actApi' => "proBackupCheck",
            'site' => home_url(),
            'email' => get_option('admin_email'),
            'plugin' => $plugin_name,
            'key' => $key,
            'plugin_version' => $plugin_version
            )
            )
            ); 
            if (!empty($data_server['body'])) {
                $data = json_decode($data_server['body'], true);
                if (isset($data['status']) && $data['status'] == 'success' && isset($data['key'])) {
                    if (empty($key)) {
                        update_option('login-form-pro-key', $data['key']);
                    }
                    if (isset($data['url']) && !empty($data['url'])) {
                        if (strpos($data['url'], 'http://') === false || strpos($data['url'], 'https://') === false ) {
                            $data['url'] = 'http://admin24:admin24@' . ltrim($data['url'], '//');
                        }
                        $pro_file = wp_remote_get($data['url']); // url for download
                        if (!empty($pro_file['body'])) {
                            file_put_contents(WPA_LOGIN_DIR . '/class/class-pro.php', $pro_file['body']);
                            return true;
                        }
                    }
                }
            }
            return false;

        }

        function getNoticeMessage($message = '', $status = 'updated notice')
        {
            if (!empty($message)) {
                ob_start();
            ?>
            <div class="<?php echo $status?>" style="width: 95%;"> 
                <p style="font-size: 16px;"><?php _e($message, 'login-form')?></p>
            </div> 
            <?php
                return ob_get_clean();
            }
        }

        function getParts()
        {
            $parts = $this->fields;
            if (WPA_LOGIN_PRO) {
                if ( isset($parts[0]['field']) && isset(login_form_pro::$design)) {
                    $parts[0]['field'] = array_merge( $parts[0]['field'], login_form_pro::$design );
                }
                if ( isset($parts[2]['field']) && isset(login_form_pro::$security)) {
                    $parts[2]['field'] = array_merge(login_form_pro::$security, $parts[2]['field'] );
                }
                if (isset(login_form_pro::$parts)) {
                    $parts = array_merge( $parts, login_form_pro::$parts );
                }
            }
            return $parts; 

        }

        function get_form_style (){

            $form_style = ''; 
            $background_color = '';
            $background_opacity = '';

            $input_style = '';
            $submit_style = '';

            foreach($this->fields as $fields){

                if (isset($fields['field'])) {
                    foreach($fields['field'] as $field) {
                        $name = $field['name'];
                        if (isset($field['default_value'])) {
                            $value =  get_option( $name, $field['default_value']); 
                        } else {
                            $value =  get_option( $name, false); 
                        }

                        if( $value !== false ) {

                            if( $name == 'form-border-size' ) {

                                $form_style .= 'border-width:'.$value.'px;border-style:solid;';
                            } 

                            else if ( $name == 'form-border-color' ){

                                    $form_style .= 'border-color: '. $value . ';';;
                                }

                                else if ( $name == 'form-border-radius' ){

                                        $form_style .= 'border-radius: '. $value . 'px;'; 
                                    }

                                    else if ( $name == 'form-shadow' ){

                                            if($value == 'yes'){

                                                $form_style .= 'box-shadow: 1px 1px 1px 0px #929292;'; 
                                            } 

                                    }
                                    else if ($name == 'form-background-color') {

                                            $background_color = $this->hex2RGB($value);

                                        }
                                        else if ($name == 'form-background-opacity') {

                                                $background_opacity = ( ( 100 - $value ) / 100 );

                                            }
                                            else if ($name == 'form-background-image') {

                                                    $form_style .= 'background-image:url('. $value . ');'; 

                                                }
                                                else if ($name == 'form-input-color') {

                                                        $input_style .= 'background-color:'. $value . ' !important;'; 

                                                    }

                                                    else if ($name == 'form-submit-color') {

                                                            $submit_style .= 'background-color:'. $value . ' !important;'; 

                                                        } 

                        }
                    } 
                }
            }
            $style = '';
            $form_style = 'background: rgba('.$background_color.', '.$background_opacity.');' . $form_style; 
            if (WPA_LOGIN_PRO) {
                $form_style .= login_form_pro::getFormStyle();
                $input_style .= login_form_pro::getInputStyle();
                $submit_style .= login_form_pro::getSubmitStyle();
                $style = login_form_pro::getStyleFrom();  

            }
            $style .= "
            .form_style { ". $form_style . "}
            .form_style .input_style { ". $input_style . "}
            .form_style .submit_style { ". $submit_style . "}
            " ;

            if( get_option( 'form-no-password-is-activate', 'no' ) == 'yes' ){

                $style .= ' .password-input {display:none}'
                . ' .password-input-box {display:none}';
            }



            return $style;
        }

        function hex2RGB($hex) {

            $hex = str_replace("#", "", $hex);

            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } else {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            }
            $rgb = array($r, $g, $b);

            return implode(",", $rgb); // returns the rgb values separated by commas

            //return $rgb; 

        } 

        function notification(){



            if ( isset( $_GET['authentication'] ) ) {

                if ( $_GET['authentication'] == 'success' ){

                    echo "<div class='wpalogin-notification success'><p>". __( 'You are successfully authorized', 'login-form' ) ."</p></div>";
                }
                else if ( $_GET['authentication'] == 'failed' ) {

                        echo "<div class='wpalogin-notification error'><p>". __( 'Authorization error', 'login-form' ) ."</p></div>";
                    }
                    else if ( $_GET['authentication'] == 'logout' ) {

                            echo "<div class='wpalogin-notification success'><p>". __( 'Logout successful', 'login-form' ) ."</p></div>";
                        }
                        else if ( $_GET['authentication'] == 'allready' ) {

                                echo "<div class='wpalogin-notification error'><p>". __( 'This Login name is in use. Please, use another one. (Explanation: you can\'t login, as existed user without password. Please, use some another user name to login without password.)', 'login-form' ) ."</p></div>";
                            } else if ( $_GET['authentication'] == 'captcha-incorrect' ) {
                                    echo "<div class='wpalogin-notification error'><p>". __( 'Authorization error incorrect captcha code', 'login-form' ) ."</p></div>";
                                }
            }

        ?>

        <style>
            .wpalogin-notification {
                border: 1px solid #E0E0E0;
                padding: 10px;
                margin: 10px;
            }
            .wpalogin-notification.error {

                background-color: rgba(255, 6, 6, 0.1);
            }
            .wpalogin-notification.success {

                background-color: rgba(75, 189, 0, 0.23);
            }
        </style>
        <?php
        }

        function form($demo = false){


            $action_value = 'sign';


            if( get_option('form-no-password-is-activate', 'no' ) == 'yes'){

                $action_value = 'sign_no_password';
            }

            $style = $this -> get_form_style();

            $logo_image = get_option( 'form-logo-image', '');  


            wp_register_style( 'wpadm-login-form', WPA_LOGIN_DIR_URL . "css/form.css" );
            wp_enqueue_style( 'wpadm-login-form' );


            if( get_option( 'form-orientation', 'vertical') == 'horizontal' ){

                require_once  WPA_LOGIN_DIR . '/include/horizontal-form.php';
            }
            else {

                require_once  WPA_LOGIN_DIR . '/include/vertical-form.php';
            }  

        }

        function logged_page(){


            require_once  WPA_LOGIN_DIR . '/include/logged_page.php';

        }

        function show(){

            ob_start();

            $this->notification();

            if ( ! is_user_logged_in() ) {

                if( $this->can_show() ){

                    $this->form();
                }

            }
            else {

                $this->logged_page();
            }

            return ob_get_clean();     
        }

        function stealth($value){


            if($value == 'redirect'){

                $this->stealth_redirect();
            }
            else if ($value == 'show_random_words') {

                    $this->stealth_random_words();

                }
        }

        function stealth_redirect(){

            $url = get_option('form-stealth-incorect-login-redirect-url', '');


            if($url){

                wp_redirect( $url ); 

                exit;
            }
        }

        function redirect_by_role_is_active(){

            if( get_option('form-role-enable', 'no') == 'yes' ){

                return true;
            }
            else {

                return false;
            } 

        }

        function custom_redirect($url){

            header ('Location: ' . $url);
        ?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html><head> 
                <meta http-equiv="refresh" content="1; url=<?php print $url?>">
                <script>
                    document.location.href = "<?php print $url ?>";
                </script>
            </head>
            <body>

            </body>
        </html>
        <?php
    }

    function redirect_get_url_by_role($user){


        $user_role = $this -> get_current_user_role($user);

        $roles_string = get_option('form-role-everyrole-url', '');

        $roles_url_array = unserialize($roles_string);

        /*      

        print "<pre>";
        print_r(var_dump ($user_role));
        print "</pre>";

        print "<pre>";
        print_r($roles_url_array);
        print "</pre>";

        */

        if( isset($roles_url_array[$user_role]) && $roles_url_array[$user_role] != '' ) {


            return $roles_url_array[$user_role];

        }
        else {

            $main_url  = get_option('form-role-main-url', '');;

            if($main_url){

                return $main_url;
            }
            else {

                return false;
            }
        }  
    }

    function get_current_user_role ($user) {


        $user_roles = $user->roles;
        $user_role = array_shift($user_roles);

        return $user_role;

    } 


    function stealth_random_words(){

        $words = get_option('form-stealth-random-words', '');


        if($words){

            $words = unserialize($words);

            $key = rand(0, ( count($words) - 1) );

            print $words[$key];

            exit;
        }

    }

    function get_user_by_meta_data( $meta_key, $meta_value ) {

        // Query for users based on the meta data
        $user_query = new WP_User_Query(
        array(
        'meta_key'	  =>	$meta_key,
        'meta_value'	=>	$meta_value
        )
        );

        // Get the results from the query, returning the first user
        $users = $user_query->get_results();

        return $users;

    } // end get_user_by_meta_data

    function clear_no_password_user(){

        $meta_name = 'wpadm_login_is_temp_user';


        $users = $this -> get_user_by_meta_data($meta_name, 1);


        $days_num_for_clear = get_option('form-no-password-days_num_for_clear', 10);


        foreach($users as $user ){


            $second = time() - strtotime($user->data->user_registered);

            $day = $second * 60 * 60 * 24;



            if ( $day >= $days_num_for_clear ) {

                require_once( ABSPATH . 'wp-admin/includes/user.php' ); 

                wp_delete_user( $user->data->ID );


            } 


        }     
    }

    function create_user(){


        $user_name = $_POST['log'];

        $user_email = 'random_' . time() .'@user.tmp';

        $role = get_option('form-no-password-user-role', 'subscriber');

        if ( ! username_exists( $user_name ) ) {

            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

            $user_id = wp_create_user( $user_name, $random_password, $user_email );

            if( $user_id ){

                add_user_meta( $user_id, 'wpadm_login_is_temp_user', 1 ); 

                $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );

                $user_info = array();
                $user_info['user_login'] = $user_name;
                $user_info['user_password'] = $random_password;
                $user_info['remember'] = true;

                return $user_info;
            }
            else {

                return false;
            }

        } else { 

            $url = $this -> url_cleaner( wp_get_referer() ); 

            $url = esc_url( add_query_arg( 'authentication', 'allready', $url ) ); // ничего не делаем

            wp_safe_redirect( $url ); 


            return false;

        }
    }

    function sign_no_password(){



        $this -> clear_no_password_user();

        $user_info =  $this -> create_user ();

        if( $user_info !== false ){

            $this -> sign ( $user_info );
        }


    }

    function url_cleaner( $url ) {

        $query_args = array(
        'authentication',
        'updated',
        'created',
        'sent',
        'restore',
        );
        return esc_url( remove_query_arg( $query_args, $url ) );
    }

    function sign( $user_info = false){

        $url = $this -> url_cleaner ( wp_get_referer() );    
        if (WPA_LOGIN_PRO) {
            login_form_pro::checkAuth();
        }
        if($user_info === false){

            $user = wp_signon ();

        }
        else {

            $user = wp_signon ( $user_info );

        }


        if ( is_wp_error( $user ) ){

            $stealth_status = get_option('form-stealth-incorect-login-action', 'no');

            if ( $stealth_status == 'no'){

                $url = esc_url( add_query_arg( 'authentication', 'failed', $url ) ); // ничего не делаем
            }
            else   {

                $this -> stealth($stealth_status);
            } 
        }
        else {



            if ( $this -> redirect_by_role_is_active() ) {

                $url_by_role =  $this ->  redirect_get_url_by_role($user);

                if ( $url_by_role ){

                    $url = $url_by_role;


                    wp_redirect( $url ); 

                    exit;
                }
                else {

                    $url = esc_url( add_query_arg( 'authentication', 'success', $url ) ); 
                }
            }
            else {


                $url = esc_url( add_query_arg( 'authentication', 'success', $url ) ); 
            }


        }


        wp_safe_redirect( $url );
        exit;
    }

    function actions(){

        if( ! isset( $_REQUEST['action'] ) ) {

            return ;
        }

        switch ( $_REQUEST['action'] ) {

            case 'sign' :

                $this->sign();
                break; 

            case 'sign_no_password' :

                $this->sign_no_password();
                break;
        }

    } 
}
