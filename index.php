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
header('Content-Type: text/html; charset=utf-8');
define('WP_USE_THEMES', true);

$GLOBALS['ip'] = '';
/**
 * 获取当前IP
 *
 * @return string|null 
 */
function get_client_ip()
{
    if ($GLOBALS['ip'] !== '')
    {
        return $GLOBALS['ip'];
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR2']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR2']);

            /* 取X-Forwarded-For2中第?个非unknown的有效IP字符? */
            foreach ($arr as $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第?个非unknown的有效IP字符? */
            foreach ($arr as $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR2'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR2');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    $onlineip                = '';
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $result                  = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    $GLOBALS['ip'] = $result;
    return $result;
}

$GLOBALS['ip'] = get_client_ip();
require('./wp/cls_iplocation.php');
$cls_iplocation = new cls_iplocation($GLOBALS['ip']);
$countrycode = $cls_iplocation->get_countrycode();

if ($countrycode != 'CN') {
	//exit('not allow');
}

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
require('./wp/wp-blog-header.php');
