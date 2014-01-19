<?php

/**
 * 基本配置
 * 
 * @author duanyunchao
 * @version $Id$
 */
/* 初始化全局配置变量 */
$GLOBALS['CONFIG'] = array();

/* 调试IP */
$GLOBALS['CONFIG']['debug_ip'] = array();

/* 当前IP */
$GLOBALS['CONFIG']['ip'] = '';

/* Gearman */
$GLOBALS['CONFIG']['gearman'] = "127.0.0.1:4730";

/* Memcached */
$GLOBALS['CONFIG']['memcached'] = array();
$GLOBALS['CONFIG']['memcached'][] = array('host'    => '127.0.0.1', 'port'    => 11211, 'weight'  => 0.1, 'timeout' => 0.5);

/* 需要加载类的文件路径 */
$GLOBALS["CONFIG"]["LIBRARY"] = array();
$GLOBALS["CONFIG"]["LIBRARY"]["lib_template"]  = PATH_LIBRARY . "/lib_template.php";
$GLOBALS["CONFIG"]["LIBRARY"]["lib_database"]  = PATH_LIBRARY . "/lib_database.php";
$GLOBALS["CONFIG"]["LIBRARY"]["lib_func"]      = PATH_LIBRARY . "/lib_func.php";
$GLOBALS["CONFIG"]["LIBRARY"]["lic_memcached"] = PATH_LIBRARY . "/lib_memcached.php";
$GLOBALS["CONFIG"]["LIBRARY"]["lib_gearman"]   = PATH_LIBRARY . "/lib_gearman.php";
