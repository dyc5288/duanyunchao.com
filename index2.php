<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/**
 * 判断是否手机访问
 * 
 * @return boolean
 */
function is_wap()
{
    $ua     = strtolower($_SERVER['HTTP_USER_AGENT']);
    $agent = "/(wap|iphone|ios|android)/i";
    if (($ua == '' || preg_match($agent, $ua)) && !strpos(strtolower($_SERVER['REQUEST_URI']), 'wap'))
    {
        return true;
    }
    else
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || (isset($_SERVER['HTTP_VIA']) && stripos($_SERVER['HTTP_VIA'], 'wap') !== FALSE))
        {
	
            return true; //from other mobile devices
        }

        return false;
    }
}

if(is_wap())
{
    header("Location:http://wap.duanyunchao.com");
}

/** Loads the WordPress Environment and Template */
require('./wp-blog-header.php');
