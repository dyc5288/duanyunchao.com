<?php
/**
 * 初始化
 * 
 * @author duanyunchao
 * @version $Id: init.php 499 2013-05-26 05:30:15Z dyc5288 $ 
 */

define('IN_INIT', true);
// 定义关键常量
define('PATH_ROOT', strtr(__FILE__, array('\\'        => '/', '/init.php' => '', '\init.php' => '')));
// 加载常量
require PATH_ROOT . '/config/inc_constants.php';
// 加载全局配置文件
require PATH_ROOT . '/config/inc_config.php';
// 数据库配置
require PATH_ROOT . '/config/inc_database.php';
// 加载函数库
require PATH_LIBRARY . '/lib_common.php';

// 访问IP
$GLOBALS['U_CONFIG']['ip'] = get_client_ip();

ini_set('error_log', PATH_DATA . '/notsync/php_error.log');
ini_set('log_errors', '1');

if (in_array($GLOBALS['U_CONFIG']['ip'], $GLOBALS['U_CONFIG']['debug_ip']))
{
    // 是否调试
    define('DEBUG', true);

    // 严格开发模式
    error_reporting(E_ALL);

    ini_set('display_errors', 'On');
}
else
{
    // 是否调试
    define('DEBUG', false);

    ini_set('display_errors', 'Off');
}

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 自动转义
if (!get_magic_quotes_gpc())
{
    auto_addslashes($_POST);
    auto_addslashes($_GET);
    auto_addslashes($_COOKIE);
    auto_addslashes($_FILES);
}

// 性能监测
if (PHP_SAPI !== 'cli' && function_exists('xhprof_enable'))
{
    xhprof_enable(XHPROF_FLAGS_MEMORY);
    $GLOBALS['XHPROF_ENABLE'] = 'on';
    register_shutdown_function('handle_xhprof');
}
?>