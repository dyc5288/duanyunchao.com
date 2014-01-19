<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to lowercase
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_str_pad($input, $len = 2, $sp = ' ', $type = STR_PAD_LEFT)
{
    return str_pad($input, $len, $sp, $type);
}

