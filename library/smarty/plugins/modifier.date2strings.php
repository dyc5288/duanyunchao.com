<?php

/**
 * Smarty date2date modifier plugin
 * 
 *
 * Type:     modifier<br>
 * Name:     date2strings<br>
 * Purpose:  format datestamps to a l10n(Chinese Simple) string<br>
 * Input:<br>
 *         - string: input date string
 *         - string: input date string
 * @link
 * @author shadom <shadom@foxmail.com>
 * @package Smarty
 * @subpackage plugins
 *
 * @version $Id: modifier.date2strings.php 19463 2012-07-31 03:10:58Z duanyunchao $
 */

/**
 * 时间字符串转换
 *
 * @staticvar int $thisTime
 * @staticvar array $thisTimeArr
 * @param string $string
 * @param string $default_date
 * @return string
 */
function smarty_modifier_date2strings($string, $default_date = '')
{
    static $thisTime, $thisTimeArr;

    if (!$thisTime)
    {
        $thisTime    = time();
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
    /* 年份相同 */
    if ($timeArr['year'] == $thisTimeArr['year'])
    {
        /* 月份相同 */
        if ($timeArr['mon'] == $thisTimeArr['mon'])
        {
            /* 天相同 */
            if ($timeArr['mday'] == $thisTimeArr['mday'])
            {
                return '今天';
            }

            /* 昨天 */
            if (($thisTimeArr['mday'] - $timeArr['mday']) == 1)
            {
                return '昨天';
            }
            /* 前天 */
            if (($thisTimeArr['mday'] - $timeArr['mday']) == 2)
            {
                return '前天';
            }

            return date('Y-m-d', $timestamp);
        }
        else
            return "{$timeArr['mon']}月{$timeArr['mday']}日";
    }
    else
    {

        return "{$timeArr['year']}年{$timeArr['mon']}月{$timeArr['mday']}日";
    }
}

