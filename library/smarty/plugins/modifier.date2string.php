<?php

/**
 * Smarty date2string modifier plugin
 * 
 *
 * Type:     modifier<br>
 * Name:     date2string<br>
 * Purpose:  format datestamps to a l10n(Chinese Simple) string<br>
 * Input:<br>
 *         - string: input date string
 *         - default_date: default date if $string is empty
 * @link
 * @author shadom <shadom@foxmail.com>
 * @param string
 * @param string
 * @package Smarty
 * @subpackage plugins
 * @return string|void
 * @uses smarty_make_timestamp()
 */

/** 继承 */
require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');

function smarty_modifier_date2string($string, $default_date = '')
{
    static $thisTime, $thisTimeArr;
    if (!$thisTime)
    {
        $thisTime = time();
        $thisTimeArr = getdate($thisTime);
    }

    if ($string != '')
    {
        $timestamp = smarty_make_timestamp($string);
    }
    elseif ($default_date != '')
    {
        $timestamp = smarty_make_timestamp($default_date);
    }
    else
    {
        return;
    }

    $timeArr = getdate($timestamp);
    if ($timeArr['year'] == $thisTimeArr['year'])
    {
        if ($timeArr['mon'] == $thisTimeArr['mon'] && $timeArr['mday'] == $thisTimeArr['mday'])
        {
            $offset = $thisTime - $timestamp;
            if ($offset > 3600)
                return floor($offset / 3600) . '小时前';
            elseif ($offset > 60)
                return floor($offset / 60) . '分钟前';
            elseif ($offset > 0)
                return "{$offset}秒前";
            else
                return "刚刚";
        }
        else
            return "{$timeArr['mon']}月{$timeArr['mday']}日 " . (strlen($timeArr['hours']) == 1 ? "0" . $timeArr['hours'] : $timeArr['hours']) . ":" . (strlen($timeArr['minutes']) == 1 ? "0" . $timeArr['minutes'] : $timeArr['minutes']);
    }
    else
    {

        return "{$timeArr['year']}年{$timeArr['mon']}月{$timeArr['mday']}日";
    }
}

