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
function smarty_modifier_question($string)
{
    if(empty($string))
    {
        return '';
    }
    
    $question = @unserialize($string);
    
    if(empty($question))
    {
        return '';
    }
    
    $res = '';
    $question_conf = L(10001);
    
    foreach($question as $row)
    {
        $key = $row[0];

        if(isset($question_conf[$key]))
        {
            $row[0] = $question_conf[$key];
        }
        
        $res .= "问题：{$row[0]}，<br>答案：{$row[1]}；<br>";
    }
    
    return $res;
}

/* vim: set expandtab: */

