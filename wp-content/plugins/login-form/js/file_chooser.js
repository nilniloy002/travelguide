
jQuery(document).ready(function() {


    // image_chooser

    jQuery(".image_chooser").click(function () {

        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        wp.media.editor.send.attachment = function(props, attachment) { 

            console.log(props)
            console.log(attachment)


            jQuery(button).prev().val(attachment.url);
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);

    });


    // java preview

    function change_form_background_color(color){

        var opacity = jQuery("select[name='form-background-opacity']").val();

        opacity = ( ( 100 - opacity ) / 100 );

        console.log(color);
        if (color == 'undefined') {
            color = jQuery("input[name='form-background-color']").val();
        }

        color = Hex2RGB(color)

        jQuery(".form_style")[0].style.setProperty('background-color', "rgba(" + color + ", " + opacity + ")", 'important');
    
    }


    jQuery("select[name='form-background-opacity']").change(function(){ 

        change_form_background_color(); 

    });


    jQuery("input[name='form-border-size']").change(function(){

        var value = jQuery(this).val();

        jQuery(".form_style").css('border-width', value);
    });


    jQuery("input[name='form-shadow']").change(function(){

        var value = jQuery(this).val();

        if(value === 'yes'){

            jQuery(".form_style").css('box-shadow', '1px 1px 1px 0px #929292');
        }
        else {
            jQuery(".form_style").css('box-shadow', 'none');
        }

    });



    Hex2RGB = function(hex) {
        if (hex.lastIndexOf('#') > -1) {
            hex = hex.replace(/#/, '0x');
        } else {
            hex = '0x' + hex;
        }
        var r = hex >> 16;
        var g = (hex & 0x00FF00) >> 8;
        var b = hex & 0x0000FF;
        return r + ',' + g + ',' + b;
    };

    // отключить кнопки в превью


    jQuery(".form_style input").click(function (e){ 
        e.preventDefault();

        return false;
    })



    // color picker

    var myOptions = { 

        // функция обратного вызова, срабатывающая каждый раз 
        // при выборе цвета (когда водите мышкой по палитре)

        change: function(event, ui){ 

            var color =  jQuery(this).val();
            var object =  jQuery(this).attr('object');
            var style_name  =  jQuery(this).attr('style_name');

            console.log(object)
            //console.log(style_name) 
            //console.log(ui.color.toString())
            if( jQuery(this).attr('name') == 'form-background-color'){
                change_form_background_color(ui.color.toString());
            } else {
                obj = jQuery(object)
                n = obj.length;
                for(i = 0; i < n; i++) {
                    obj[i].style.setProperty(style_name, ui.color.toString(), 'important');//('style', style_name + ':' + ui.color.toString() + ' !important');
                }
            }
        },  
    }


    jQuery('input[class="colorpicker"]').wpColorPicker(myOptions);


    // кнопка сбросить для файл пикера

    jQuery('.prev_reset').click(function() { 
        jQuery(this).prev('input').prev('input').val('');

        return false;

    });

    var formfield;

    /* user clicks button on custom field, runs below code that opens new window */
    jQuery('.onetarek-upload-button').click(function() { 
        formfield = jQuery(this).prev('input'); //The input field that will hold the uploaded file url
        tb_show('','media-upload.php?type=image&TB_iframe=true');

        return false;

    });

    //adding my custom function with Thick box close function tb_close() .
    window.old_tb_remove = window.tb_remove;
    window.tb_remove = function() {
        window.old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
        formfield=null;
    };

    // user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data

    window.original_send_to_editor = window.send_to_editor;

    window.send_to_editor = function(html){
        if (formfield) {
            fileurl = jQuery('img',html).attr('src');
            jQuery(formfield).val(fileurl);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    };

    jQuery('.wpadm-login-parts-title').click(function() {
        var box = jQuery(this).parent('.wpadm-login-parts');
        var body = jQuery(box).find('.wpadm-login-parts-body');
        if (jQuery(body).css('display') == 'block') {
            jQuery(body).hide('slow');
            jQuery(this).find('.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
        } else {
            jQuery(body).show('slow');
            jQuery(this).find('.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
        }
        jQuery('.wrap').find('.wpadm-login-parts-body').each(function() {
            b = jQuery(body).parent('.wpadm-login-parts')
            if (jQuery(this).parent('.wpadm-login-parts').attr('part-id') != jQuery(b).attr('part-id')) {
                jQuery(this).hide('slow');
                jQuery(b).find('.wpadm-login-parts-title').find('.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
            }
        })
    })

});