<?php

/**
 * Smarty explode modifier plugin
 * 
 *
 * Type:     modifier<br>
 * Name:     explode<br>
 * @link
 * @author shadom <shadom@foxmail.com>
 * @package Smarty
 * @subpackage plugins
 */

/**
 * ::�ָ�
 *
 * @param string $source
 * @param string $key
 * @return string
 */
function smarty_modifier_explode($source,$key)
{
   $temp = explode("::",$source);
   return empty($temp[$key]) ? '' : $temp[$key];
}

