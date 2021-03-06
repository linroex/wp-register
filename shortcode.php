<?php

    class shortcode
    {
        public static function html_input_func($attrs)
        {
            extract(shortcode_atts(array('type'=>'text','name'=>'','value'=>'','title'=>'','placeorder'=>''),$attrs));

            return '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" class="' . $name . '" placeholder="'.  $placeorder .'" />';

        }

        public static function html_form_func($attrs,$content=NULL)
        {
            extract(shortcode_atts(array('method'=>'post','action'=>''),$attrs));

            return '<form class="register_form" action="' . get_site_url() . '/index.php" method="' . $method .  '">' . do_shortcode($content) . '</form>';

        }
        public static function html_key_func()
        {
            return wp_nonce_field('odie_register','key-field');
        }

        public static function html_select_func($attrs,$content=NULL)
        {
            extract(shortcode_atts(array('name'=>'','title'=>''),$attrs));

            return '<select name="' . $name . '">' . do_shortcode($content) . '</select>';
        }
        public static function html_submit($attrs)
        {
            extract(shortcode_atts(array('value'=>''),$attrs));

            return '<script type="text/javascript">function submit_register(){jQuery.post("index.php",jQuery(".register_form").serialize()).success(function(data){jQuery(".register_form").html(data)})}</script><input type="button" value="' . $value . '" onclick="submit_register();"/>';
        }
        public static function html_option_func($attrs)
        {
            extract(shortcode_atts(array('text'=>'','value'=>''),$attrs));

            return '<option value="' . $value . '">' . $text . '</option>';
        }

        public static function html_textarea_func($attrs)
        {
            extract(shortcode_atts(array('name'=>'','title'=>'','cols'=>'30','rows'=>'10','placeorder'=>''),$attrs));

            return '<textarea name="' . $name . '" cols="' . $cols . '" rows="' . $rows . '" placeholder="' . $placeorder . '"></textarea>';
        }
        public static function html_rangeoption_func($attrs)
        {
            extract(shortcode_atts(array('start'=>'','end'=>''),$attrs));
            $result="";
            for ($i=$start;$i<=$end;$i++) {
                $result.='<option value="' . $i . '">' . $i . '</option>';
            }

            return $result;
        }
        public static function html_select_address()
        {
            $jsPath = get_site_url() . '/wp-content/plugins/wp-register/aj-address.js';

            return <<<html
<script type="text/javascript" src="$jsPath"></script>
<script type="text/javascript">
    $(window).load(function(){init_address()})
</script>
<input id="zipcode" name="zipcode" type="text" style="display:none;"/>
<select id="zone1" name="zone1"></select>
<select id="zone2" name="zone2"></select><br />
html;
        }
    }

    $shortcodes=array('code'=>array(
        'input',
        'form',
        'select',
        'option',
        'textarea',
        'option_r',
        'submit',
        'key',
        'address'
    ),'func'=>array(
        'html_input_func',
        'html_form_func',
        'html_select_func',
        'html_option_func',
        'html_textarea_func',
        'html_rangeoption_func',
        'html_submit',
        'html_key_func',
        'html_select_address'
    ));

    for ($i=0;$i<=count($shortcodes['code']);$i++) {
        add_shortcode($shortcodes['code'][$i],'shortcode::' . $shortcodes['func'][$i]);
    }
