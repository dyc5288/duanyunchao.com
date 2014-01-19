<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty device_name modifier plugin
 *
 * Type:     modifier<br>
 * Name:     device_name<br>
 * Purpose:  designate device_name value for empty variables
 * @link http://smarty.php.net/manual/en/language.modifier.device_name.php
 *          device_name (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_device_name($ssoent)
{
    if (isset($GLOBALS['CONFIG']['SSOENT'][$ssoent]))
    {
        return $GLOBALS['CONFIG']['SSOENT'][$ssoent];
    }
    
    return "未知设备";
}

/* vim: set expandtab: */

