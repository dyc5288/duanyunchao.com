<?php

/**
 * 初始化
 * 
 * @author duanyunchao
 * @version $Id$
 */
/* 严格开发模式 */
error_reporting(E_ALL);
define('IN_INIT', true);

/* 定义关键常量 */
define('PATH_ROOT', strtr(__FILE__, array('\\'        => '/', '/init.php' => '', '\init.php' => '')));

/* 加载常量 */
require PATH_ROOT . '/config/inc_constants.php';

/* 加载全局配置文件 */
require PATH_ROOT . '/config/inc_config.php';

/* 数据库配置 */
require PATH_ROOT . '/config/inc_database.php';

/* 设置时区 */
date_default_timezone_set('Asia/Shanghai');

/* 加载函数库 */
require PATH_LIBRARY . '/lib_func.php';

/* 错误日志 */
ini_set('error_log', PATH_DATA . '/notsync/log/php_error.log');
ini_set('log_errors', '1');

/* 当前IP */
$GLOBALS['CONFIG']['ip'] = get_client_ip();

/* 错误控制 */
if (DEBUG_LEVEL)
{
    ini_set('display_errors', 'On');
    set_error_handler('debug_error_handler', E_ALL);
}
else
{
    if (in_array($GLOBALS['CONFIG']['ip'], $GLOBALS['CONFIG']['debug_ip']))
    {
        ini_set('display_errors', 'On');
        set_error_handler('debug_error_handler', E_ALL);
    }
    else
    {
        ini_set('display_errors', 'Off');
    }
}

/* 自动转义 */
if (!get_magic_quotes_gpc())
{
    auto_addslashes($_POST);
    auto_addslashes($_GET);
    auto_addslashes($_COOKIE);
    auto_addslashes($_FILES);
    auto_addslashes($_REQUEST);
}

/* session存储 */
ini_set('session.save_handler', 'memcached');
$tcp = array();

foreach ($GLOBALS['CONFIG']['memcached'] as $val)
{
    $tcp[] = $val['host'] . ":" . $val['port'];
}

ini_set('session.save_path', implode($tcp, ','));
ini_set('session.cookie_domain', "." . DOMAIN);
