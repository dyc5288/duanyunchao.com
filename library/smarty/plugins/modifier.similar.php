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
function smarty_modifier_similar($string1, $string2)
{
    $len1 = utf8_mb_strlen($string1);
    $len2 = utf8_mb_strlen($string2);
    $scount = 0;
    
    for($i = 0; $i < $len1; $i++)
    {
        for($j = 0; $j < $len2; $j++)
        {
            if($string1{$i} == $string2{$j})
            {
                $scount++;
                break;
            }
        }
    }
    
    if($scount > $len1 / 2)
    {
        return '1';
    }
    
    return '0';
}

/* vim: set expandtab: */

