<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty default modifier plugin
 *
 * Type:     modifier<br>
 * Name:     default<br>
 * Purpose:  designate default value for empty variables
 * @link http://smarty.php.net/manual/en/language.modifier.default.php
 *          default (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_date_equal($time1, $time2)
{
    $diff = abs($time1 - strtotime($time2));
    
    if($diff < 86400)
    {
        return '1';
    }
    
    return '0';
}

/* vim: set expandtab: */

