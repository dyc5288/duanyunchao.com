<?php

/**
 * 常量配置
 * 
 * @author duanyunchao
 * @version $Id$
 */
/* 访问控制 */
!defined('IN_INIT') && exit('Access Denied');

/* 调试模式，外网关闭 */
define('DEBUG_LEVEL', true);

/* URL常量 */
define('URL_SUFFIX', 'duanyunchao.cn');

/* 当前域名地址 */
define('URL', 'http://duanyunchao.cn');

/* 域 */
define('DOMAIN', 'duanyunchao.cn');

/* 类库文件目录常量 */
define('PATH_LIBRARY', PATH_ROOT . '/library');

/* 数据库文件目录常量 */
define('PATH_DBCACHE', PATH_ROOT . '/dbcache');

/* 帮助文件目录常量 */
define('PATH_HELPER', PATH_ROOT . '/helper');

/* 配置文件目录常量 */
define('PATH_CONFIG', PATH_ROOT . '/config');

/* 数据文件目录常量 */
define('PATH_DATA', PATH_ROOT . '/data');

/* API文件目录常量 */
define('PATH_API', PATH_ROOT . '/api');

/* 公共业务模型层 */
define('PATH_PUB_MODEL', PATH_ROOT . '/model');

/* 入口当前目录 */
define('PATH_DIR', getcwd());

/* 请求时间 */
define('TIME', $_SERVER['REQUEST_TIME']);

/* 应用 */
$app = substr(PATH_DIR, strripos(PATH_DIR, DIRECTORY_SEPARATOR) + 1);

/* 当前目录 */
switch ($app)
{
    default :
        define('CUR_DIR', '/');
        define('CUR_MODEL', '/');
        define('LANG', 'zh_CN');
        break;
}

/* 控制层和业务层目录 */
define('PATH_CONTROL', PATH_ROOT . CUR_DIR . '/control');
define('PATH_MODEL', PATH_ROOT . CUR_MODEL . '/model');
