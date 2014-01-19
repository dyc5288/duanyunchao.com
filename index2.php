<?php

/**
 * 入口
 * 
 * @author duanyunchao
 * @version $Id$
 */
header('Content-Type: text/html; charset=utf-8');
require 'init.php';

/* 防止页面cache */
if (!headers_sent())
{
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
}

/* 控制器 */
$GLOBALS['CT'] = get_params('ct', 1, 'request', 'index');
$GLOBALS['AC'] = get_params('ac', 1, 'request', 'index');

/* 当前网址 */
$cururl = get_cururl();
define('URL_CURRENT', $cururl);

/* 控制器路由 */
execute_ctl('ctl_' . $GLOBALS['CT'], $GLOBALS['AC']);
