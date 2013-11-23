<?php

/**
 * 常量配置
 * 
 * @author duanyunchao
 * @version $Id: inc_constants.php 500 2013-05-26 05:30:47Z dyc5288 $ 
 */
// url前缀
define('BASE_URL', "http://duanyunchao.cn");
// url前缀
define('M_URL', "http://wap.duanyunchao.cn");
// 配置
$GLOBALS['U_CONFIG'] = array();
// 类文件目录常量
define('PATH_LIBRARY', PATH_ROOT . '/library');
// 数据层目录常量
define('PATH_DBCACHE', PATH_ROOT . '/dbcache');
// 帮助目录常量
define('PATH_HELPER', PATH_ROOT . '/helper');
// 类文件目录常量
define('PATH_CONFIG', PATH_ROOT . '/config');
// 数据目录常量
define('PATH_DATA', PATH_ROOT . '/data');
// 当前目录常量
define('PATH_DIR', getcwd());
// 日志目录常量
define('PATH_LOG', PATH_ROOT . '/data/log');
// 控制器
define('PATH_CONTROL', PATH_ROOT . PATH_APP . '/control');
// 模型层
define('PATH_MODEL', PATH_ROOT . PATH_APP . '/model');
?>
