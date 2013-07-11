<?php
    /*
        Plugin Name: Register
        Plugin URI: http://plugins.coder.tw/register
        Description: 報名系統
        Version: 0.1.0
        Author: linroex
        Author URI: http://tfeng.org
    */

    function load($name)
    {
        spl_autoload($name);
    }
    spl_autoload_register('load');
    spl_autoload_call('shortcode');
    //spl_autoload_call('taxonomy');
    spl_autoload_call('engine');
    spl_autoload_call('setting');

    function load_js()
    {
        echo '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>';
    }
    add_action('wp_head','load_js');
